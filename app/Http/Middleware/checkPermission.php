<?php

namespace App\Http\Middleware;

use Illuminate\Contracts\Auth\Factory as AuthMiddleware;
use Closure;
use Auth;

class checkPermission
{
    /**
     * The authentication factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    public function __construct(AuthMiddleware $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next, $permission_name)
    {
        $this->auth->authenticate();

        if(!Auth::user()->hasPermission($permission_name)) {
            return redirect()->home();
        }

        return $next($request);
    }
}
