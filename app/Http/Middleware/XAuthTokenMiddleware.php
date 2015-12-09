<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Auth;

class XAuthTokenMiddleware
{
    /**
     * @var User
     */
    protected $user;

    /**
     * Instance of middleware.
     *
     * @param user $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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
        $xauthtoken = $request->header('X-AUTH-TOKEN');

        if ($xauthtoken)
        {
            $user = $this->user->whereXAuthToken($xauthtoken)->first();

            if ($user) 
            {
                Auth::login($user);
                return $next($request);
            }

            return ['error' => true, 'message' => 'Invalid X-AUTH-TOKEN'];
        }

        return ['error' => true, 'message' => 'X-AUTH-TOKEN is required'];
    }
}
