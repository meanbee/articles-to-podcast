<?php namespace App\Console\Commands;

use App\Items;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

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

                    $this->info(sprintf("Converting content of '%s'...", $item->url));

                    try {
                        $response = $this->getHttpClient()->post("v1/synthesize", array(
                            "headers" => array(
                                'Authorization' => 'Basic ' . base64_encode(env('WATSON_TTS_USERNAME') . ':' . env('WATSON_TTS_PASSWORD')),
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
                        $this->error($e->getMessage());
                        $item->status = Items::STATUS_CONVERSION_FAILED;
                        $item->save();
                        continue;
                    }


                    if (substr($response->getStatusCode(), 0, 1) == "2") {
                        $filename = sprintf(static::FILENAME_FORMAT_OGG, $item->id);

                        $filesystem->put(
                            $filename,
                            $response->getBody()
                        );

                        // Convert ogg file to mp3.
                        $output = '';
                        $returnCode = -1;
                        $origPath = storage_path() . "/app/$filename";
                        $finalPath = storage_path() . "/app/" . sprintf(static::FILENAME_FORMAT_MP3, $item->id);

                        exec("ffmpeg -i $origPath -acodec libmp3lame $finalPath 2> /dev/null", $output, $returnCode);

                        if ($returnCode !== 0) {
                            $this->error('Error converting .ogg to .mp3');
                            $item->status = Items::STATUS_CONVERSION_FAILED;
                            $item->save();
                        }

                        // Remove original ogg file
                        $filesystem->delete($filename);

                        $item->status = Items::STATUS_CONVERTED;
                        $item->save();

                        $this->info(sprintf("Done (%s).", $filename));

                    }

                    $item->status = Items::STATUS_CONVERSION_FAILED;
                    $item->save();
                    $this->error('Failed!');
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
