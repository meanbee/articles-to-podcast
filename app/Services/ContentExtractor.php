<?php namespace App\Services;

use Goose\Client as GooseClient;
use Readability;
use \Log;

class ContentExtractor {


    /**
     * @var \GuzzleHttp\Client $httpClient
     */
    protected $httpClient;

    /**
     * Find text from URL
     * First try Goose, then try Readability
     *
     * @param $url
     *
     * @return string
     */
    public function fetchContent($url)
    {
        $goose = new GooseClient();

        try {
            $article = $goose->extractContent($url);
            $articleText = $article->getCleanedArticleText();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return '';
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

        $articleText = $this->replace4byte(trim(strip_tags($articleText)));

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
