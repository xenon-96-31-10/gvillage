<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mechanizm;
use App\Models\Project;
use App\Models\Profile;

class MechanizmController extends Controller
{
  public function getProfileMechanizms(Request $request){
    $mechanizms = Profile::find($request->id)->mechanizms;
    return response()->json($mechanizms);
  }

  public function getMechanizms(Request $request){


    if($request->id != null){
      $project = Project::find($request->id);
      $organization = $project->organization;
      $mechanizms = $organization->mechanizms()->orderBy('id', 'desc')->get();
    }else{
      $user = auth()->user();
      if($user->hasRole('admin')){
        $mechanizms = Mechanizm::orderBy('id', 'desc')->get();
      }else{
        $mechanizms = $user->get_organization()->mechanizms()->orderBy('id', 'desc')->get();
      }
    }

    $data = array();
    foreach ($mechanizms as $mechanizm) {
      if(isset($mechanizm->owner)){
        $owner = $mechanizm->owner->fio;
      }else{
        $owner = "Без владельца";
      }

      $data[] = [
        'id' => $mechanizm->id,
        'title' => $mechanizm->number,
        'description' => 'Марка: '.$mechanizm->model.' | Название: '. $mechanizm->name.' | Тип: '. $mechanizm->type,
        'data' => $owner,
      ];
    }
    return response()->json($data);
  }

  public function getMechanizm(Request $request){
    $mechanizm = Mechanizm::find($request->id);
    $mechanizm->owner != null ? $owner = $mechanizm->owner->fio : $owner = 'Без владельца';
    $data = [
      'id' => $mechanizm->id,
      'number' => $mechanizm->number,
      'model' => $mechanizm->model,
      'type' => $mechanizm->type,
      'name' => $mechanizm->name,
      'color' => $mechanizm->color,
      'owner' => $owner,
    ];

    return response()->json($data);
  }
}
