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
        $schedule->command('pocket:synchronise')->daily()->withoutOverlapping();
        $schedule->command('article:run-all')->everyTenMinutes()->withoutOverlapping();
	}
}
