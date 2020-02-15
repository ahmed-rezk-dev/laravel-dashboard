<?php

namespace App\Http\Middleware;

use App;
use Closure;

class LanguageSwitcherApi {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next) {
		if ($request->header('lang')) {
			App::setlocale($request->header('lang'));
		} else {
			App::setlocale(config('app.locale'));
		}
		return $next($request);
	}
}