<?php namespace App\Services;

use \App\Items as ItemModel;
use \App\UserItems as UserItemsModel;
use GuzzleHttp\Exception\ClientException;
use Goose\Client as GooseClient;
use Readability;
use \Log;

class Pocket {

    const POCKET_API_URL = "https://getpocket.com/v3/";

    /**
     * User to make pocket requests on behalf of.
     * @var
     */
    protected $user;

    /**
     * @var \GuzzleHttp\Client $httpClient
     */
    protected $httpClient;

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Add latest pocket items for user
     * Remove any that have been read
     * Delete items that aren't in any user's list
     *
     * @throws \Exception
     */
    public function synchronisePocketItems()
    {

        $feed = $this->getPocketFeed();

        $urls = array();
        foreach ($feed as $feedItem) {
            $item = $this->addItem($feedItem['resolved_url'], $feedItem['resolved_title'], $feedItem['excerpt']);
            $userItem = $this->addUserItem($item, $feedItem);
            $urls[] = $feedItem['resolved_url'];
        }

        // Remove previous items which are no longer in the feed
        $readItems = UserItemsModel::whereNotIn('item_id', array_map("md5", $urls))
            ->where('user_id', '=', $this->user->id)
            ->delete();

        // Check whether items need to be deleted from items table if no users attached
        $hangingItems = \DB::table('items')
            ->whereNotExists(function($query)
            {
                $query->select()
                    ->from('user_items')
                    ->whereRaw('user_items.item_id = items.id');
            })
            ->delete();
    }



    /**
     * @return array|mixed
     */
    protected function getPocketFeed()
    {

        try {
            $response = $this->getHttpClient()->post("get", array(
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-Accept' => 'application/json'
                ],
                "body" => json_encode(
                    array(
                        "consumer_key" => env('POCKET_API'),
                        "access_token" => $this->user->access_token,
                        "contentType"  => "article",
                        "status"       => "unread",
                        "sort"         => "newest",
                        "detailType"   => "simple",
                        "count"        => 20
                    )
                )
            ));

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return array();
        }

        $statusCode = $response->getStatusCode();
        if ($statusCode != 200) {
            if ($statusCode == 400) {
                Log::error('Invalid pocket request');
            } elseif($statusCode == 401) {
                Log::error('Problem authenticating user');
            } elseif($statusCode == 403) {
                Log::error('Authenticated but lack of permission to access this resource');
            } elseif($statusCode == 500) {
                Log::error('Pocket server issue');
            }
            return array();
        }

        $feed = $response->json();

        return $feed['list'];
    }

    /**
     * Add user item row for an item if it doesn't already exist
     *
     * @param $item
     * @param $pocketItem
     */
    protected function addUserItem($item, $pocketItem) {
        $userItem = UserItemsModel::where('user_id', '=', $this->user->id)
            ->where('item_id', '=', $item->id)
            ->limit(1)
            ->get()
            ->first();

        if (is_null($userItem)) {
            $userItem = new UserItemsModel();
            $userItem->user_id = $this->user->id;
            $userItem->item_id = $item->id;
            $userItem->created_at = date('Y-m-d H:i:s', $pocketItem['time_added']);
            $userItem->updated_at = date('Y-m-d H:i:s', $pocketItem['time_updated']);
            $userItem->save();
        }
    }

    /**
     * Add new item without fetching article content yet
     *
     * @param      $url
     * @param null $title
     * @param null $excerpt
     *
     * @return ItemModel|\Illuminate\Support\Collection|null|void|static
     */
    protected function addItem($url, $title = null, $excerpt = null)
    {
        if ($item = ItemModel::find(md5($url))) {
            return $item;
        }

        $item = new ItemModel();
        $item->id = md5($url);
        $item->url = $url;
        $item->title = $title;
        $item->content = '';
        $item->excerpt = substr($excerpt, 0, 255);
        $item->status = ItemModel::STATUS_NEW;
        $item->save();

        return $item;
    }
    

    /**
     * Get the http client.
     *
     * @return \GuzzleHttp\Client
     */
    protected function getHttpClient()
    {
        if (!$this->httpClient) {
            $this->httpClient = new \GuzzleHttp\Client(array("base_url" => static::POCKET_API_URL));
        }

        return $this->httpClient;
    }
}
