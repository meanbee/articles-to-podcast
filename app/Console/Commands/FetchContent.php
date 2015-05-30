<?php namespace App\Console\Commands;

use App\Items;
use App\Services\ContentExtractor;
use Illuminate\Console\Command;
use \Log;

class FetchContent extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'articles:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sychronise pocket feed for all users';

    /**
     * @var \GuzzleHttp\Client $httpClient
     */
    protected $httpClient;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $contentExtractor = new ContentExtractor();

        Items::where('status', '=', Items::STATUS_NEW)
            ->chunk(100, function ($items) use ($contentExtractor) {
            foreach ($items as $item) {
                $item->status = Items::STATUS_BEING_FETCHED;
                $item->save();

                $content = $contentExtractor->fetchContent($item->url);
                if ($content) {
                    $item->content = $content;
                    $item->status = Items::STATUS_FETCHED;
                    $item->save();
                } else {
                    Log::error('Unable to find content for ' . $item->$url);
                    $item->status = Items::STATUS_FETCH_FAILED;
                    $item->save();
                }
            }
        });
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }
}
