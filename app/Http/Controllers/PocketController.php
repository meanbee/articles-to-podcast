<?php namespace App\Http\Controllers;

use Session;
use GuzzleHttp\Client;
use GuzzleHttp\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;

class PocketController extends BaseController {

    const POCKET_REQUEST   = 'https://getpocket.com/v3/oauth/request';
    const POCKET_AUTHORIZE = 'https://getpocket.com/auth/authorize';
    const POCKET_OAUTH_AUTHORIZE = 'https://getpocket.com/v3/oauth/authorize';

    const POCKET_SESSION_USERNAME = 'pocket_username';
    const POCKET_SESSION_ACCESS_TOKEN = 'pocket_access_token';


    public function __construct()
    {
        parent::__construct();
    }

    public function login()
    {
        $client = new Client;

        try {
            /** @var \GuzzleHttp\Message\ResponseInterface $response */
            $response = $client->post(self::POCKET_REQUEST, [
                'headers' => ['X-Accept' => 'application/json'],
                'body' => [
                    'consumer_key' => env('POCKET_API'),
                    'redirect_uri' => action('PocketController@loginResponse')
                ]
            ]);
        } catch (RequestException $e) {
            echo $e->getRequest();
            if ($e->hasResponse()) {
                echo $e->getResponse();
            }
        }

        $statusCode = $response->getStatusCode();
        if ($statusCode != 200) {
            if ($statusCode == 400) {
                Log::error('Invalid pocket parameters');

            } elseif($statusCode == 403) {
                Log::error('Pocket invalid consumer key');

            } elseif($statusCode == 500) {
                Log::error('Pocket server issue');
            }
        }

        $json = $response->json();

        if (!isset($json['code'])) {
            Log::error('Pocket should have responded with an error');
        }

        Session::put('pocket_code', $json['code']);

        $params = http_build_query(
            array(
                'request_token' => $json['code'],
                'redirect_uri' => action('PocketController@loginResponse')
            )
        );

        return redirect(self::POCKET_AUTHORIZE . '?' . $params);
    }

    public function loginResponse() {

        $client = new Client;

        try {
            /** @var \GuzzleHttp\Message\ResponseInterface $response */
            $response = $client->post(self::POCKET_OAUTH_AUTHORIZE, [
                'headers' => ['X-Accept' => 'application/json'],
                'body'    => [
                    'consumer_key' => env('POCKET_API'),
                    'code'         => Session::get('pocket_code')
                ]
            ]);
        } catch (RequestException $e) {
            echo $e->getRequest();
            if ($e->hasResponse()) {
                echo $e->getResponse();
            }
        }

        $statusCode = $response->getStatusCode();
        if ($statusCode != 200) {
            if ($statusCode == 400) {
                Log::error('Missing consumer key');

            } elseif ($statusCode == 403) {
                Log::error('Invalid consumer key');

            } elseif ($statusCode == 500) {
                Log::error('Pocket server issue');
            }
        }

        $json = $response->json();

        $this->auth->login($json['username'], $json['access_token']);

        return redirect('/dashboard');
    }

    /**
     * Logout by resetting session and redirecting to home
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout() {
        $this->auth->logout();
        return redirect('/');
    }
}
