<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
  public function getRoles(){
    if(auth()->user()->hasRole('admin')){
      $roles = Role::all();
    }else{
      $roles = Role::whereNotIn('id', [1,2])->get();
    }

    return response()->json($roles);
  }

  public function getRolesAndPermissions(){
    $roles = Role::all();
    $data = array();
    foreach ($roles as $role) {
      $data[] = [
        'role' => $role->name,
        'permissions' => $role->permissions,
      ];
    }

    return response()->json($data);
  }

  public function getCurrentRole(){
    $role = auth()->user()->roles()->first()->slug;
    return response()->json($role);
  }
}
