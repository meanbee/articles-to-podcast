<?php namespace App\Http\Controllers;

use \App\Auth;

class BaseController extends Controller {

    protected $auth;

    public function __construct()
    {
        $this->auth = new Auth();
        view()->share('auth', $this->auth);
    }


}
