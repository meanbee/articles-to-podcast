<?php namespace App\Console\Commands;

use App\Items;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ConvertToSpeech extends Command {


    const TTS_URI = "https://stream.watsonplatform.net/text-to-speech-beta/api/";

    const FILENAME_FORMAT_OGG = "%s.ogg";
    const FILENAME_FORMAT_MP3 = "%s.mp3";

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

                    $this->info(sprintf("Converting content of '%s' to speech...", $item->url));

                    try {
                        $response = $this->getHttpClient()->post("v1/synthesize", array(
                            "auth" => array(
                                env('WATSON_TTS_USERNAME'),
                                env('WATSON_TTS_PASSWORD')
                            ),
                            "headers" => array(
                                'Content-Type'  => 'application/json',
                                'Accept'        => 'audio/ogg; codecs=opus'
                            ),
                            "body" => json_encode(
                                array(
                                    "text" => $item->content
                                )
                            )
                        ));

                    } catch (ClientException $e) {
                        $item->status = Items::STATUS_CONVERSION_FAILED;
                        $item->save();
                        $this->error($e->getMessage());
                        continue;
                    }


                    if (substr($response->getStatusCode(), 0, 1) != "2") {
                        $item->status = Items::STATUS_CONVERSION_FAILED;
                        $item->save();
                        $this->error('Invalid response from watson TTS API!');
                        continue;
                    }


                    // Save successful response
                    $filenameOgg = sprintf(static::FILENAME_FORMAT_OGG, $item->id);
                    $filenameMp3 = sprintf(static::FILENAME_FORMAT_MP3, $item->id);

                    $filesystem->put(
                        $filenameOgg,
                        $response->getBody()
                    );

                    $this->info('Downloaded, converting ogg to mp3...');

                    $output = '';
                    $returnCode = -1;
                    $origPath = storage_path() . "/app/$filenameOgg";
                    $finalPath = storage_path() . "/app/$filenameMp3";

                    exec("avconv -y -i $origPath -acodec libmp3lame $finalPath 2> /dev/null", $output, $returnCode);

                    if ($returnCode !== 0) {
                        $item->status = Items::STATUS_CONVERSION_FAILED;
                        $item->save();
                        $this->error("Error converting $filenameOgg to $filenameMp3");
                        continue;
                    }

                    // Remove original ogg file
                    $filesystem->delete($filenameOgg);

                    $item->byte_length = filesize($finalPath);
                    $item->status = Items::STATUS_CONVERTED;
                    $item->save();

                    $item->status = Items::STATUS_BEING_UPLOADED;
                    $item->save();

                    printf("Uploading '%s'..", $filenameMp3);

                    $s3 = \Storage::disk('s3');
                    $local = \Storage::disk('local');

                    $s3->put($filenameMp3, $local->get($filenameMp3));

                    printf("Done.\n");

                    $item->status = Items::STATUS_UPLOADED;
                    $item->save();

                    $local->delete($filenameMp3);

                    $this->info(sprintf("Done (%s).", $filenameMp3));

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
