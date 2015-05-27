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

        var_dump($this->auth->getUser()->items()); exit;
        $items = $this->auth->getUser()->items()->get();

		return view('dashboard', array(
			'user' => $this->auth->getUser(),
            'items' => $items
		));
	}

}
