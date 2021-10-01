<?php

namespace App\Http\Controllers\Cars;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Car;
use App\Models\Profile;
use App\Models\Project;
use Illuminate\Support\Facades\Validator;
use App\Traits\CreateItems;
use App\Traits\UpdateItems;

class CarController extends Controller
{
    use CreateItems, UpdateItems;

    public function showControlPage(){
      return view('cars.index');
    }

    public function create(Request $request){

      $car = Car::where('number', $request->number)->first();
      if($request->passType === 'permanent'){
        if($car != null && $car->type == 'Разовое'){
          $message = $this->errorCarType();
          return new JsonResponse($message, 422);
        }
      }
      if($car != null && $car->owner == null && isset($request->owner)){
        $profile = Profile::find($request->owner);
        $car->owner()->associate($profile)->save();
        return $request->wantsJson()
                    ? new JsonResponse($car->id, 200)
                    : $car;
      }

      $this->validator($request->all())->validate();
      $car = $this->createCar($request->all());
      if(isset($request->owner)){
        $profile = Profile::find($request->owner);
        $car->owner()->associate($profile)->save();
      }
      if(isset($request->project)){
       $project = Project::find($request->project);
       $organization = $project->organization;
      }else{
        $organization = auth()->user()->get_organization();
      }
      $car->organization()->attach($organization);

      return $request->wantsJson()
                  ? new JsonResponse($car->id, 200)
                  : $car;
    }

    public function update(Request $request){
      $car = Car::find($request->id);
      if($car->number != $request->number){
        $this->validator($request->all())->validate();
      }
      $car = $this->updateCar($car, $request->all());

      if(isset($request->newOwner)){
        $profile = Profile::find($request->newOwner);
        if($car->owner != null){$car->owner()->dissociate()->save();}
        $car->owner()->associate($profile)->save();
      }

      return $request->wantsJson()
                  ? new JsonResponse($car->id, 200)
                  : $car;
    }

    public function delete(Request $request){
      $car = Car::find($request->id);
      $car->owner()->dissociate()->save();

      return $request->wantsJson()
                  ? new JsonResponse($car->id, 200)
                  : $car;
    }

    public function addCarToOrganization(Request $request){
      $car = Car::where('number', $request->number)->first();
      if(isset($request->project)){
       $project = Project::find($request->project);
       $organization = $project->organization;
      }else{
        $organization = auth()->user()->get_organization();
      }
      $car->organization()->attach($organization);
      return $request->wantsJson()
                  ? new JsonResponse($car->id, 200)
                  : $car;
    }

    public function changeCarType(Request $request){
      $car = Car::where('number', $request->number)->first();
      $car->type = 'Постоянное';
      $car->save();
      if(isset($request->project)){
       $project = Project::find($request->project);
       $organization = $project->organization;
      }else{
        $organization = auth()->user()->get_organization();
      }
      if($car->organization()->where('id', $organization->id)->get()->count() == 0){
        $car->organization()->attach($organization);
      }
      return $request->wantsJson()
                  ? new JsonResponse($car->id, 200)
                  : $car;
    }

    private function errorCarType(){
      $message = [
        'status' => 'error',
        'msg' => 'error',
        'errors' => [
                      'type' => 'Это автомобиль уже зарегестрирован в системе. Но для оформления постоянного пропуска на автомобиль - он должен быть зарегестрирован в системе как постоянный транспорт.'
                    ]
      ];

      return $message;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'number' => ['unique:cars'],
        ],
        [
          'number.unique' => 'Этот номер авто уже в системе.'
        ]);
    }
}
