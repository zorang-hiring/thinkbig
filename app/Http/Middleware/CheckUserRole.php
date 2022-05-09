<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $role
     * @return mixed
     * @throws AuthorizationException
     */
    public function handle($request, Closure $next, $role)
    {
        /** @var User $user */
        $user = Auth::guard()->user();

        $message = 'You do not have permission to view this page';

        if (!$user) {
            throw new AuthorizationException($message, 401);
        } else if (!$user->hasRole($role)) {
            throw new AuthorizationException($message, 403);
        }

        return $next($request);
    }
}
