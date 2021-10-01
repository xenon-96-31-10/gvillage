<?php
namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Profile;
use App\Models\Car;
use App\Models\Role;
use App\Models\Group;
use App\Models\Organization;
use App\Models\Family;
use App\Models\Project;
use App\Models\ProjectGroup;
use App\Models\ProjectType;
use App\Models\ProjectStatus;
use App\Models\GuardPost;
use App\Models\Activity;
use App\Models\Equipment;
use App\Notifications\NewUser;


use Illuminate\Support\Facades\Auth;

use Storage;
use Carbon\Carbon;

trait Register
{

  public function createUser($data, $name){

    $pass = random_int(100000, 999999);
    $role = Role::where('name', $name)->first();

    $user = new User();
    $user->phone = $data['phone'];
    $user->email = $data['email'];
    $user->password = bcrypt($pass);
    $user->status = 'Новый пользователь';
    $user->save();
    $this->addUserToGroup($name, $user);
    $user->roles()->attach($role);

    $user->notify(new NewUser($pass));
    return $user;

  }

  public function createUserProfile($data, $user){
    $fio = explode(" ", $data['fio']);

    for ($i=0; $i < 3; $i++) {
      if(!isset($fio[$i])){
        $fio[$i] = 'Не указано';
      }
    }

    $profile = new Profile();
    $profile->fio = $data['fio'];
    $profile->name = $fio[0];
    $profile->lastname = $fio[1];
    $profile->surname = $fio[2];

    if(isset($data['sex'])){
      $profile->sex = $data['sex'];
    }else{
      $profile->sex = "Мужской";
    }

    if(isset($data['dateofbirth'])){
      $profile->dateofbirth = $data['dateofbirth'];
    }else{
      $profile->dateofbirth = Carbon::now()->subYears(18);
    }
    $profile->user()->associate($user)->save();
  }

  public function createCar($data){
    $car = new Car();

    if(isset($data['model'])){
      $car->model = $data['model'];
    }else{
      $car->model = 'Не указано';
    }
    $car->number = $data['number'];
    if(isset($data['type'])){
      $car->type = $data['type'];
    }else{
      $car->type = 'Постоянное';
    }
    $car->body = $data['body'];
    if(isset($data['color'])){
      $car->color = $data['color'];
    }else{
      $car->color = 'Не указано';
    }
    $car->save();

    return $car;
  }

  public function addUserToGroup($slug, $user){
    $group = Group::where('slug', $slug)->first();
    $user->group()->attach($group);
  }

  public function addUserToOrganization($user, $id){
    if(isset($id)){
      if(is_numeric($id)){
        $organization = Organization::find($id);
      }else{
        $organization = $this->crateOrganization($id);
      }
    }else{
      $organization = auth()->user()->get_organization();
    }

    $user->organization()->attach($organization);
  }

  public function crateOrganization($name){
    $organization = new Organization();
    $organization->name = $name;
    $organization->save();

    return $organization;
  }

  public function addActivitiesToUser($data, $user){
    foreach ($data as $id) {
      if(is_numeric($id)){
        $activity = Activity::find($id);
      }else{
        $activity = $this->createActiviy($id);
      }
      $user->activities()->attach($activity);
    }
  }

  public function createActiviy($name){
    $activity = new Activity();
    $activity->name = $name;
    $activity->save();

    return $activity;
  }

  public function addEquipmentsToUser($data, $user){
    foreach ($data as $id) {
      $equipment = Equipment::find($id);
      $user->equipments()->attach($equipment);
    }
  }

  public function addUserToFamily($id, $user){
    $family = Family::find($id);
    $user->family()->attach($family);

    return $family;
  }

  public function createFamily($user){
    $organization = $user->get_organization();
    $latest = $organization->families()->latest()->first();
    $id = $latest->id + 1;

    $family = new Family();
    $family->name = 'Семья '.$id;
    $family->organization()->associate($organization);
    $family->save();

    $user->family()->attach($family);
  }


  public function addUserToFamilyProjects($user, $family){
    $users = $family->users;
    foreach($users as $user){
      if($user->hasRole('owner')){
        $projects = $user->projects;
      }
    }
    $user->projects()->attach($projects);
  }

  public function addUserToProject($user, $id){
    $project = Project::find($id);
    $user->projects()->attach($project);
  }

  public function addUserToGuardPost($user, $id){
    $post = GuardPost::find($id);
    $user->guard_post()->attach($post);
  }

  public function createProject($data, $user){
    $organization = auth()->user()->get_organization();
    $type = ProjectType::find($data['type']);
    $status = ProjectStatus::find(1);

    if(isset($data['description'])){
      $description = $data['description'];
    }else{
      $description = 'Без описания';
    }

    $project = new Project();
    $project->name = $data['name'];
    $project->description = $description;
    $project->address = $data['address'];
    $project->region = $data['region'];
    $project->city = $data['city'];
    $project->street = $data['street'];
    $project->house = $data['house'];
    if(isset($data['block'])){
      $project->block = $data['block'];
    }
    $project->room = $data['flat'];
    $project->totalarea = $data['totalarea'];
    $project->livingarea = $data['livingarea'];
    $project->project_status()->associate($status);
    $project->organization()->associate($organization);
    if($data['group'] != 'false'){
      if(is_numeric($data['group'])){
        $group = ProjectGroup::find($data['group']);
      }else{
        $group = $this->createGroup($data['group']);
      }
      $project->project_group()->associate($group);
    }
    $project->project_type()->associate($type)->save();

    $user->projects()->attach($project);
  }

  public function createGroup($name){
    $organization = auth()->user()->get_organization();
    $group = new ProjectGroup();
    $group->name = $name;
    $group->organization()->associate($organization);
    $group->save();

    return $group;
  }

}
