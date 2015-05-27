<?php namespace App\Console\Commands;

use App\Services\Pocket;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PocketSynchronise extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'pocket:synchronise';

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
        User::chunk(100, function ($users) {
            foreach ($users as $user) {
                $pocket = new Pocket($user);
                $pocket->synchronisePocketItems();
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
