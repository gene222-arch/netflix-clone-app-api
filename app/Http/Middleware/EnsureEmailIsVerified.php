<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Traits\Api\ApiResponser;
use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class EnsureEmailIsVerified
{
    use ApiResponser;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|null
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {   
        if (! $request->email) {
            return $this->error([
                'email' => 'Email is required'
            ]);
        }

        $user = $request->user('api');

        if (! $user) {
            $user = User::where('email', $request->email)->firstOrFail();
        }

        $isUserVerified = $user instanceof MustVerifyEmail && $user->hasVerifiedEmail();

        if (! $user || ! $isUserVerified) {
            return $request->expectsJson()
                    ? $this->error([
                        'email' => $request->email . ' is not verified'
                    ], 403)
                    : $this->success('Your email address is verified.');
        }

        return $next($request);
    }
}
