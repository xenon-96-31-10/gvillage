<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
      $guards = empty($guards) ? [null] : $guards;

      foreach ($guards as $guard) {
          if (Auth::guard($guard)->check()) {
              $role = auth()->user()->roles()->first();
              $for = [
                  'admin' => 'admin',
                  'manager' => 'manager',
                  'owner'  => 'owner',
                  'guard-post' => 'guardpost',
                  'dispatcher' => 'dispatcher',
              ];
              return redirect()->route($for[$role->slug]);
          }
      }

      return $next($request);
    }
}
