<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	private $openRoutes = ['/mobile-app'];
	 
	public function handle($request, Closure $next)
	{
        $rootUrl = url('/');
        foreach($this->openRoutes as $route){
            if(substr($request->url(),0,strlen($rootUrl.$route))==$rootUrl.$route) return $next($request);
        }
		return parent::handle($request, $next);
	}

}
