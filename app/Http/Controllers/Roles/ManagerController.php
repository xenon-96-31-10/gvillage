<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
  public function showIndex(){
    return view('dashboards.manager.index');
  }
}
