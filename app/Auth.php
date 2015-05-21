<?php namespace App;

use Session;

class Auth {

    const POCKET_SESSION_USERNAME = 'pocket_username';
    const POCKET_SESSION_ACCESS_TOKEN = 'pocket_access_token';

    /**
     * Authorisation check
     */
    public function check()
    {
        return (Session::get(self::POCKET_SESSION_USERNAME) && Session::get(self::POCKET_SESSION_ACCESS_TOKEN));
    }

    /**
     * Take login information from pocket and assign to session
     *
     * @param $username
     * @param $token
     *
     * @return bool
     */
    public function login($username, $token)
    {
        Session::put(self::POCKET_SESSION_USERNAME, $username);
        Session::put(self::POCKET_SESSION_ACCESS_TOKEN, $token);

        // Add to database if does not exist or update access token if user does
        if ($user = $this->getUser()) {
            $user->access_token = $this->getAccessToken();
        } else {
            $user = new User();
            $user->username = $this->getUsername();
            $user->access_token = $this->getAccessToken();
        }

        $user->save();

        return $this->check();
    }

    /**
     * Get pocket username
     *
     * @return mixed
     */
    public function getUsername()
    {
        return Session::get(self::POCKET_SESSION_USERNAME);
    }

    /**
     * Get access token from session
     *
     * @return mixed
     */
    public function getAccessToken()
    {
        return Session::get(self::POCKET_SESSION_ACCESS_TOKEN);
    }

    /**
     * Get database user for logged in user
     */
    public function getUser()
    {
        $users = User::where('username', '=', $this->getUsername())->limit(1)->get();
        if ($users->count() <= 0) {
            return;
        }

        return $users->first();
    }

    /**
     * Logout by removing fields from session
     */
    public function logout()
    {
        Session::forget(self::POCKET_SESSION_USERNAME);
        Session::forget(self::POCKET_SESSION_ACCESS_TOKEN);
    }
}
