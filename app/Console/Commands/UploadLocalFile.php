<?php namespace App\Console\Commands;

use App\Items;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class UploadLocalFile extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'articles:upload';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Upload local files to a remote service.';

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
		$items_to_upload = Items::where('status', '=', Items::STATUS_CONVERTED)
			->chunk(100, function ($items) {
				foreach ($items as $item) {
					$local_file = $item->id . '.mp3';

					$item->status = Items::STATUS_BEING_UPLOADED;
					$item->save();

					printf("Uploading '%s'..", $local_file);

					$s3 = \Storage::disk('s3');
					$local = \Storage::disk('local');

					$s3->put($local_file, $local->get($local_file));

					printf("Done.\n");

					$item->status = Items::STATUS_UPLOADED;
					$item->save();

					$local->delete($local_file);
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
