<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class BasicAuthorization
{

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!self::hasHeader($request) || !self::getAuthenticatedUser($request)) {
            abort(Response::HTTP_UNAUTHORIZED);
        }
        return $next($request);
    }

    public static function hasHeader(Request $request): bool
    {
        return $request->hasHeader('Authorization');
    }

    public static function getAuthenticatedUser(Request $request): User|false
    {
        $credentials = base64_decode(substr($request->header('Authorization'), 6));
        list($username, $password) = explode(':', $credentials);
        $user = User::where('email', $username)->first();
        if (isset($user) and Hash::check($password, $user->password)) {
            return $user;
        }
        return false;
    }

}
