<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
        'App\Console\Commands\ConvertToSpeech',
        'App\Console\Commands\FetchContent',
		'App\Console\Commands\PocketSynchronise',
		'App\Console\Commands\RunAll',
		'App\Console\Commands\UploadLocalFile',
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
        /**
         * This is what we would like to run but Heroku cron on the free plan only runs every 10 minutes.
         * If the minute that it runs doesn't exactly pass the regular expression for our commands (e.g on the stroke of
         * midnight and every 10 minutes past the hour), then our commands will never run.
         * For that reason these have been commented out and moved to the cron schedule.  They remain here for future
         * use and for documentation on what commands need to be regularly run.
         */
        //$schedule->command('pocket:synchronise')->daily()->withoutOverlapping();
        //$schedule->command('article:run-all')->everyTenMinutes()->withoutOverlapping();
	}
}
