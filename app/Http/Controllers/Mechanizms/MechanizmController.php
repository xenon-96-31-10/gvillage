<?php

namespace App\Http\Controllers\Mechanizms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Mechanizm;
use App\Models\Profile;
use App\Models\Project;
use Illuminate\Support\Facades\Validator;
use App\Traits\CreateItems;
use App\Traits\UpdateItems;

class MechanizmController extends Controller
{
  use CreateItems, UpdateItems;

  public function showControlPage(){
    return view('mechanizms.index');
  }

  public function create(Request $request){
    $mechanizm = Mechanizm::where('number', $request->number)->first();
    if($mechanizm != null && $mechanizm->owner == null && isset($request->owner)){
      $profile = Profile::find($request->owner);
      $mechanizm->owner()->associate($profile)->save();
      return $request->wantsJson()
                  ? new JsonResponse($mechanizm->id, 200)
                  : $mechanizm;
    }

    $this->validator($request->all())->validate();
    $mechanizm = $this->createMechanizm($request->all());
    if(isset($request->owner)){
      $profile = Profile::find($request->owner);
      $mechanizm->owner()->associate($profile)->save();
    }
    if(isset($request->project)){
     $project = Project::find($request->project);
     $organization = $project->organization;
    }else{
      $organization = auth()->user()->get_organization();
    }
    $mechanizm->organization()->attach($organization);
    return $request->wantsJson()
                ? new JsonResponse($mechanizm->id, 200)
                : $car;
  }

  public function update(Request $request){
    $mechanizm = Mechanizm::find($request->id);
    if($mechanizm->number != $request->number){
      $this->validator($request->all())->validate();
    }
    $mechanizm = $this->updateMechanizm($mechanizm, $request->all());

    if(isset($request->newOwner)){
      $profile = Profile::find($request->newOwner);
      if($mechanizm->owner != null){$mechanizm->owner()->dissociate()->save();}
      $mechanizm->owner()->associate($profile)->save();
    }

    return $request->wantsJson()
                ? new JsonResponse($mechanizm->id, 200)
                : $mechanizm;
  }

  public function delete(Request $request){
    $mechanizm = Mechanizm::find($request->id);
    $mechanizm->owner()->dissociate()->save();

    return $request->wantsJson()
                ? new JsonResponse($mechanizm->id, 200)
                : $mechanizm;
  }

  public function addMechanizmToOrganization(Request $request){
    $mechanizm = Mechanizm::where('number', $request->number)->first();
    if(isset($request->project)){
     $project = Project::find($request->project);
     $organization = $project->organization;
    }else{
      $organization = auth()->user()->get_organization();
    }
    $mechanizm->organization()->attach($organization);
    return $request->wantsJson()
                ? new JsonResponse($mechanizm->id, 200)
                : $mechanizm;
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
          'number' => ['required', 'unique:mechanizms'],
      ],
      [
        'number.unique' => 'Этот номер техники уже в системе',
      ]);
  }
}
