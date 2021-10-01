<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionThroughRoleMiddleware
{
  /**
   * Handle an incoming request.
   * @param $request
   * @param Closure $next
   * @param $role
   * @param null $permission
   * @return mixed
   */
  public function handle($request, Closure $next, $permission_through_role)
  {
      if(!Auth::check()){
        return redirect('login');
      }
      $permission = \App\Models\Permission::where('slug', $permission_through_role)->first();
      if(!auth()->user()->hasPermissionTo($permission)) {
          abort(404);
      }
      return $next($request);
  }
}
