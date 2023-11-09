<?php

namespace App\Http\Middleware;

use App\UserTracker;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TrackUser
{
    protected const EXEMPT_ROUTES = ['login', 'dashboard'];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->isExemptRoute($request)) {
            return $next($request);
        }

        $this->storeParams($request);
        $this->storeFirstPage($request);
        $this->storeUserAgent($request);
        $this->storeIpAddress($request);
        $this->storeReferer($request);

        return $next($request);
    }

    private function storeParams(Request $request): void
    {
        $params = array_intersect_key($request->query(), array_flip(config('tracking.params')));
        $current = Session::get(UserTracker::KEY_PARAMS, []);
        Session::put('tracking_params', array_merge($current, $params));
    }

    private function storeFirstPage(Request $request): void
    {
        if (!Session::has(UserTracker::KEY_FIRST_PAGE)) {
            Session::put(UserTracker::KEY_FIRST_PAGE, $request->fullUrl());
        }
    }

    private function storeUserAgent(Request $request): void
    {
        if (!Session::has(UserTracker::KEY_USER_AGENT)) {
            Session::put(UserTracker::KEY_USER_AGENT, $request->header('user-agent'));
        }
    }

    private function storeIpAddress(Request $request): void
    {
        if (!Session::has(UserTracker::KEY_IP_ADDRESS)) {
            Session::put(UserTracker::KEY_IP_ADDRESS, $request->ip());
        }
    }

    private function storeReferer(Request $request): void
    {
        if (!Session::has(UserTracker::KEY_REFERER)) {
            Session::put(UserTracker::KEY_REFERER, $request->headers->get('referer'));
        }
    }

    private function isExemptRoute(Request $request): bool
    {
        return in_array($request->segment(1), self::EXEMPT_ROUTES);
    }
}
