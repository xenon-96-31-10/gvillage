<?php
namespace App\Traits;

use Illuminate\Http\Request;
use App\Models\PersonalAccount;
use App\Models\Car;
use App\Models\Profile;
use App\Models\Mechanizm;
use App\Models\Avatar;
use App\Models\Document;
use App\Models\Pass;
use App\Models\PassLog;
use App\Models\PassComment;
use App\Models\TemporaryPass;
use App\Models\PermanentPass;
use App\Models\PassRatePlan;
use App\Models\ProjectGroup;
use App\Models\Project;
use App\Models\PassRate;
use App\Traits\CreateItems;
use Storage;
use Carbon\Carbon;

trait UpdateItems
{
  use CreateItems;

  public function updateProfile($data){
    $profile = Profile::find($data['id']);
    $profile->fio = $data['fio'];
    $profile->sex = $data['sex'];
    $profile->dateofbirth = $data['dateofbirth'];
    $profile->save();

    return $profile;
  }

  public function updateProject($data){
    $project = Project::find($data['id']);
    $project->address = $data['address'];
    $project->name = $data['name'];
    $project->description = $data['description'];
    $project->totalarea = $data['totalarea'];
    $project->livingarea = $data['livingarea'];
    $project->save();
    return $project;
  }

  public function updateCar($car, $data){
    $number = $data['number'];
    if(isset($data['model'])){
      $model = $data['model'];
    }else{
      $model = 'Не указано';
    }
    if(isset($data['type'])){
      $type = $data['type'];
    }else{
      $type = 'Постоянное';
    }

    if(isset($data['body'])){
      $body = $data['body'];
    }else{
      $body = 'Легковое';
    }

    if(isset($data['color'])){
      $color = $data['color'];
    }else{
      $color = 'Не указано';
    }

    $car->number = $number;
    $car->model = $model;
    $car->type = $type;
    $car->body = $body;
    $car->color = $color;
    $car->save();
    return $car;
  }

  public function updateMechanizm($mechanizm, $data){
    $number = $data['number'];
    if(isset($data['model'])){
      $model = $data['model'];
    }else{
      $model = 'Не указано';
    }
    if(isset($data['type'])){
      $type = $data['type'];
    }else{
      $type = 'Не указано';
    }
    if(isset($data['name'])){
      $name = $data['name'];
    }else{
      $name = 'Не указано';
    }
    if(isset($data['color'])){
      $color = $data['color'];
    }else{
      $color = 'Не указано';
    }

    $mechanizm->model = $model;
    $mechanizm->number = $number;
    $mechanizm->type = $type;
    $mechanizm->name = $name;
    $mechanizm->color = $color;
    $mechanizm->save();
    return $mechanizm;
  }

  public function updateAccount($data){
    $account = PersonalAccount::find($data['id']);
    $account->balance = $account->balance + $data['amount'];
    $account->save();
    return $account;
  }

  public function updateAccountFrozen($pass, $account){
    $account->frozen = $account->frozen + $pass->rate->price;
    $account->save();
    return $account;
  }

  public function debitAccountRate($account, $price){
    $account->balance = $account->balance - $price;
    $account->frozen = $account->frozen - $price;
    $log = $this->createPersonalAccountLog(0, $price, $account);
    $account->save();

    return true;
  }

  public function clearAccountFrozenRate($account, $price){
    $account->frozen = $account->frozen - $price;
    $account->save();
    return true;
  }

  public function updatePassRatePlan($data){


    $plan = PassRatePlan::find($data['id']);
    $organization = $plan->organization;
    if($data['default']){
      $def_plan = $organization->default_rate_plan()->first();
      if($def_plan->id != $plan->id){
        $def_plan->default = 0;
        $def_plan->save();
        $plan->default = $data['default'];
      }
    }


    $plan->name = !isset($data['name']) ? 'Тарифный план №'.random_int(1000, 9999) : $data['name'];

    $plan->save();

    if($data['selectedProjects'] != null){
      foreach ($plan->projects as $project) {
        $project->pass_rate_plan()->dissociate()->save();
      }
      $projects = Project::find($data['selectedProjects']);
      $plan->projects()->saveMany($projects);
    }

    if($data['selectedGroups'] != null){
      foreach ($plan->project_groups as $group) {
        $group->pass_rate_plan()->dissociate()->save();
      }
      $project_groups = ProjectGroup::find($data['selectedGroups']);
      $plan->project_groups()->saveMany($project_groups);
    }

    foreach ($data['temporary'] as $item) {
      if($item['id'] != 0){
        $rate = PassRate::find($item['id']);
        $rate->description = $item['description'];
        $rate->price = $item['price'];
        $rate->save();
      }else{
        $this->createPassRate('temporary', $item, $plan);
      }
    }

    foreach ($data['permanent'] as $item) {
      if($item['id'] != 0){
        $rate = PassRate::find($item['id']);
        $rate->description = $item['description'];
        $rate->price = $item['price'];
        $rate->save();
      }else{
        $this->createPassRate('permanent', $item, $plan);
      }
    }

    return $plan;
  }

  public function updateDefaultPassRatePlan($id){
    $organization = auth()->user()->get_organization();

    $def_plan = $organization->default_rate_plan()->first();
    $def_plan->default = 0;
    $def_plan->save();

    $plan = PassRatePlan::find($id);
    $plan->default = 1;
    $plan->save();

    return $plan;
  }
}
