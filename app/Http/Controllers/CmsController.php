<?php namespace App\Http\Controllers;

class CmsController extends BaseController {

	/**
	 * Show Home Screen
	 *
	 * @return Response
	 */
	public function index()
	{
        if ($this->auth->check()) {
            return redirect('/dashboard');
        }
        
		return view('cms.home');
	}

    /**
     * About Page
     *
     * @return \Illuminate\View\View
     */
    public function about()
    {
        return view('cms.about');
    }

}
