<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Auth\AuthService;
use Closure;
use Illuminate\Http\Request;

class AuthenticateIfNot
{
    public function __construct(private AuthService $authService)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() === null && $request->bearerToken()) {
            $user = $this->authService->getUserFromToken($request->bearerToken());
            $request->setUserResolver(fn () => $user);
        }

        return $next($request);
    }
}
