<?php namespace App\Console\Commands;

use App\Items;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ConvertToSpeech extends Command {


    const TTS_URI = "http://tts-api.com/tts.mp3";

    const FILENAME_FORMAT = "%s.mp3";

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'articles:convert';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Convert article text to ogg files using IBM Watson.';

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
        $items_to_convert = Items::where('status', '=', Items::STATUS_FETCHED)
            ->chunk(100, function ($items) {
                $filesystem = Storage::disk("local");

                foreach ($items as $item) {
                    $item->status = Items::STATUS_BEING_CONVERTED;
                    $item->save();

                    printf("Converting content of '%s'...", $item->url);

                    try {
                        $response = $this->getHttpClient()->post("", array(
                            "body" => array(
                                "q" => $item->content
                            )
                        ));
                    } catch (ClientException $e) {
                        $item->status = Items::STATUS_CONVERSION_FAILED;
                        $item->save();
                        continue;
                    }


                    if (substr($response->getStatusCode(), 0, 1) == "2") {
                        $filename = sprintf(static::FILENAME_FORMAT, $item->id);

                        $filesystem->put(
                            $filename,
                            $response->getBody()
                        );

                        $item->status = Items::STATUS_CONVERTED;
                        $item->save();

                        printf("Done (%s).\n", $filename);

                    } else {
                        printf("Failed!\n");
                        
                        $item->status = tems::STATUS_CONVERSION_FAILED;
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

    /**
     * Get the http client.
     *
     * @return \GuzzleHttp\Client
     */
    protected function getHttpClient()
    {
        if (!$this->httpClient) {
            $this->httpClient = new \GuzzleHttp\Client(array("base_url" => static::TTS_URI));
        }

        return $this->httpClient;
    }

}
