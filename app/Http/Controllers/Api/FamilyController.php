<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Family;

class FamilyController extends Controller
{
  public function getFamilies(){
    $families = auth()->user()->get_organization()->families;
    $data = array();


    foreach ($families as $family) {
      $users = $family->users;
      foreach($users as $user){
        if($user->hasRole('owner')){
          $owner = $user->profile->fio;
        }
      }
      $data[] = [
        'value' => $family->id,
        'label' => $family->name.' | '.$owner,
      ];
    }
    return response()->json($data);
  }
}
