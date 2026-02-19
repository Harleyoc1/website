<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class PermissionLevelAuthorization
{
    protected abstract function getRequiredLevel(): int;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
        } else {
            // Fallback to basic Authorization if no standard auth
            $user = BasicAuthorization::getAuthenticatedUser($request);
        }
        if (!isset($user) or !$user->hasPermission($this->getRequiredLevel())) {
            abort(Response::HTTP_FORBIDDEN);
        }
        return $next($request);
    }
}
