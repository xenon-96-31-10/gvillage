<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuardPostAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
          if(!Auth::check()){
            return redirect('login');
          }
          $user = auth()->user();
          if($user->hasRole('guard-post')){
            if($user->get_guard_status() != null){
              $status = $user->get_guard_status()->status;
            }else{
              $status = 'Первый вход';
            }
            if($status != 'Авторизован') {
                return redirect()->route('guardpost.auth')->with('danger', 'Сначала авторизуйтесь на посту!');
            }
          }
        return $next($request);
    }
}
