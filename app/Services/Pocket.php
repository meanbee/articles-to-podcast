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
     * Add latest pock
     *
     * @throws \Exception
     */
    public function synchronisePocketItems()
    {

        $feed = $this->getPocketFeed();

        $urls = array();
        foreach ($feed as $feedItem) {
            $item = $this->addItem($feedItem['resolved_url'], $feedItem['resolved_title'], $feedItem['excerpt']);
            if ($item) {
                $this->addUserItem($item);
                $urls[] = $feedItem['resolved_url'];
            }
        }

        // Remove previous items which are no longer in the feed
        $affectedRows = UserItemsModel::whereNotIn('item_id', array_map("md5", $urls))->delete();
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
                        "detailType"   => "simple"
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
        }

        $feed = $response->json();

        return $feed['list'];
    }

    /**
     * Add user item row for an item if it doesn't already exist
     *
     * @param $item
     */
    protected function addUserItem($item) {
        $userItem = UserItemsModel::where('user_id', '=', $this->user->id)
            ->where('item_id', '=', $item->id)
            ->limit(1)
            ->get()
            ->first();

        if ($userItem == null) {
            $userItem = new UserItemsModel();
            $userItem->user_id = $this->user->id;
            $userItem->item_id = $item->id;
            $userItem->save();
        }
    }

    protected function addItem($url, $title = null, $excerpt = null)
    {
        if (ItemModel::find(md5($url))) {
            return ItemModel::find(md5($url));
        }

        if (!$articleText = $this->getArticleContent($url)) {
            Log::error('Unable to find article for URL: ' . $url);
        }

        $item = new ItemModel();
        $item->id = md5($url);
        $item->url = $url;
        $item->content = $this->replace4byte($articleText);
        $item->title = $this->replace4byte($title);
        $item->excerpt = $this->replace4byte(substr($excerpt, 0, 255));
        $item->status = ItemModel::STATUS_FETCHED;
        $item->save();

        return $item;
    }

    /**
     * Get article body text
     * First try Goose, then try Readability
     *
     * @param $url
     * @return string
     */
    protected function getArticleContent($url)
    {
        $goose = new GooseClient();

        try {
            $article = $goose->extractContent($url);
            $articleText = $article->getCleanedArticleText();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }

        if (!$articleText) {
            // If Goose failed, try Readability
            $html = $article->getRawHtml();

            if (function_exists('tidy_parse_string')) {
                $tidy = tidy_parse_string($html, array(), 'UTF8');
                $tidy->cleanRepair();
                $html = $tidy->value;
            }

            $readability = new Readability($html, $url);
            $readability->debug = false;
            $result = $readability->init();

            if ($result) {
                $articleText = $readability->getContent()->innerHTML;
                // if we've got Tidy, let's clean it up for output
                if (function_exists('tidy_parse_string')) {
                    $tidy = tidy_parse_string($articleText, array('indent' => true, 'show-body-only' => true), 'UTF8');
                    $tidy->cleanRepair();
                    $articleText = $tidy->value;
                }
            }
        }

        $articleText = trim(strip_tags($articleText));

        return $articleText;
    }

    /**
     * Replace utf8mb4 with utf8
     * @param $string
     *
     * @return mixed
     */
    protected function replace4byte($string) {
        return preg_replace('%(?:
          \xF0[\x90-\xBF][\x80-\xBF]{2}      # planes 1-3
        | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
        | \xF4[\x80-\x8F][\x80-\xBF]{2}      # plane 16
        )%xs', '', $string);
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
