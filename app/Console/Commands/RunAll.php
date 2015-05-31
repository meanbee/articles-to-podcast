<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use \Log;
use \Artisan;

class RunAll extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'articles:run-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run through full process for an article: fetch content, convert to speech and upload to S3 processes';

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
        $this->info('Fetching article contents');
        Artisan::call('articles:fetch');

        $this->info('Converting article text to speech');
        Artisan::call('articles:convert');

        $this->info('Uploading sound files to S3');
        Artisan::call('articles:upload');
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
