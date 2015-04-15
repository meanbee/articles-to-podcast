<?php namespace App\Http\Controllers;

class CmsController extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}

	/**
	 * Show Home Screen
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('cms.home');
	}

}
