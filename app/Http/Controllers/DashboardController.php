<?php namespace App\Http\Controllers;

class DashboardController extends BaseController {

	public function __construct()
	{
		$this->middleware('auth');

        parent::__construct();
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('dashboard', array(
			'user' => $this->auth->getUser()
		));
	}

}
