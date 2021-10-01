<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\AuthGuardPost;

use App\Models\GuardPost;
use App\Models\GuardPostEnter;
use App\Models\GuardPostLog;

class GuardPostController extends Controller
{
    use AuthGuardPost;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showAuth(){
      return view('dashboards.guardpost.auth');
    }

    public function Auth(Request $request){
      $this->authEnter($request->enter);
      $enter = GuardPostEnter::where('id', $request->enter)->first();
      return redirect()->route('guardpost')->with('success', 'Вы авторизовались на '.$enter->name.' !');
    }

    public function Logout(){
      $this->logoutEnter();
      $enter = auth()->user()->get_guard_status()->enter;
      return redirect()->route('guardpost.auth')->with('success', 'Вы вышли с '.$enter->name.' !');
    }

    public function showIndex(){
      return view('dashboards.guardpost.index');
    }
}
