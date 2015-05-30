<?php namespace App\Http\Middleware;

use Closure;
use App\Auth;
use \Session;

class Authenticate {

    /**
     * The auth instance.
     */
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if (!$this->auth->check())
		{
			if ($request->ajax())
			{
				return response('Unauthorized.', 401);
			}
			else
			{
                Session::put('error', 'Please login to reach this page.');
				return redirect('/');
			}
		}

		return $next($request);
	}

}
