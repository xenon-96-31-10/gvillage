<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Project;
use App\Models\Profile;

class CarController extends Controller
{
    public function getProfileCars(Request $request){
      $cars = Profile::find($request->id)->cars;
      return response()->json($cars);
    }

    public function getCars(Request $request){
      if($request->id != null){
        $project = Project::find($request->id);
        $organization = $project->organization;
        if($request->type == 'permanent'){
          $cars = $organization->cars()->where('type','Постоянное')->orderBy('id', 'desc')->get();
        }else{
          $cars = $organization->cars()->orderBy('id', 'desc')->get();
        }
      }else{
        $user = auth()->user();
        if($user->hasRole('admin')){
          $cars = Car::orderBy('id', 'desc')->get();
        }else{
          $cars = $user->get_organization()->cars()->orderBy('id', 'desc')->get();;
        }
      }


      $data = array();
      foreach ($cars as $car) {
        if(isset($car->owner)){
          $owner = $car->owner->fio;
        }else{
          $owner = "Без владельца";
        }

        $data[] = [
          'id' => $car->id,
          'title' => $car->number,
          'description' => ' Марка: '.$car->model.'| Тип: '. $car->body.'| Цвет: '. $car->color,
          'data' => $owner,
        ];
      }
      return response()->json($data);
    }

    public function getCar(Request $request){
      $car = Car::find($request->id);
      $car->owner != null ? $owner = $car->owner->fio : $owner = 'Без владельца';

      $data = [
        'id' => $car->id,
        'number' => $car->number,
        'model' => $car->model,
        'type' => $car->type,
        'body' => $car->body,
        'color' => $car->color,
        'owner' => $owner,
      ];

      return response()->json($data);
    }
}
