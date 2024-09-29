<?php namespace Mcamara\LaravelLocalization\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cookie;
use Mcamara\LaravelLocalization\LanguageNegotiator;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LocaleCookieRedirect extends LaravelLocalizationMiddlewareBase
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
	{
		// If the URL of the request is in exceptions.
		if ($this->shouldIgnore($request)) {
			return $next($request);
		}

		$params = explode('/', $request->path());
		$locale = $request->cookie('locale', false);

		$response = $next($request);

		if ($response instanceof BinaryFileResponse) {
			return $response;
		}

		if (\count($params) > 0 && app('laravellocalization')->checkLocaleInSupportedLocales($params[0])) {
			return $response->withCookie(cookie()->forever('locale', $params[0]));
		}

		if (empty($locale) && app('laravellocalization')->hideUrlAndAcceptHeader()) {
			$negotiator = new LanguageNegotiator(
				app('laravellocalization')->getDefaultLocale(),
				app('laravellocalization')->getSupportedLocales(),
				$request
			);
			$locale = $negotiator->negotiateLanguage();
			Cookie::queue(Cookie::forever('locale', $locale));
		}

		if ($locale === false) {
			$locale = app('laravellocalization')->getCurrentLocale();
		}

		if (
			$locale &&
			app('laravellocalization')->checkLocaleInSupportedLocales($locale) &&
			!(app('laravellocalization')->isHiddenDefault($locale))
		) {
			$redirection = app('laravellocalization')->getLocalizedURL($locale);
			$redirectResponse = new RedirectResponse($redirection, 302, ['Vary' => 'Accept-Language']);

			return $redirectResponse->withCookie(cookie()->forever('locale', $params[0]));
		}

		return $response;
	}

}
