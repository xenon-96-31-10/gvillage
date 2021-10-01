<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Profile;
use App\Models\GuardPost;

use App\Traits\Register;
use Carbon\Carbon;


class UserController extends Controller
{
  use Register;

  public function showIndex(){
    return view('users.index');
  }

  public function showRegisterForm(){
    return view('users.register');
  }

  public function showUpdateForm(){
    return view('users.update');
  }

  public function updateUser(Request $request){
    $user = auth()->user();
    if($request->password != '' && isset($request->password)){
      $this->validatorPassword($request->all())->validate();
      $user->password = Hash::make($request->password);
    }
    if($user->login != $request->login && isset($request->login)){
      $this->validatorLogin($request->all())->validate();
      $user->login = $request->login;
    }
    $user->save();
  }



  public function Register(Request $request){
    $data = $request->all();

    //return response()->json($data['projectid']);
    $this->validator($data['general'])->validate();
    if(isset($data['car']['number'])){
      $this->validatorCar($data['car'])->validate();
    }

    $user = $this->createUser($data['general'], $data['role']);

    $this->createUserProfile($data['general'], $user);

    if(isset($data['car']['number'])){
      $car = $this->createCar($data['car']);
      $car->owner()->associate($user->profile)->save();
    }
    $this->addUserToOrganization($user, $data['organization']);

    if($data['role'] == 'Собственник'){
      $this->createFamily($user);
      $this->addUserToGroup('owners', $user);
      $this->createProject($data['project'], $user);
    }elseif(($data['role'] == 'Охранник территории/дома')||($data['role'] == 'Член семьи')||($data['role'] == 'Представитель собственника')){
      $family = $this->addUserToFamily($data['family'], $user);
      if($data['role'] == 'Охранник территории/дома'){
        $this->addUserToProject($user, $data['projectid']);
      }else{
        $this->addUserToFamilyProjects($user, $family);
      }
    }elseif($data['role'] == 'Охранник на посту'){
      $this->addUserToGroup('workers', $user);
      $this->addUserToGuardPost($user, $data['guardpost']);
    }elseif($data['role'] == 'Диспетчер'){
      $this->addUserToGroup('workers', $user);
      $this->addUserToProject($user, $data['projectid']);
    }elseif($data['role'] == 'Работник'){
      $this->addUserToGroup('workers', $user);
      if(isset($data['activities'])){ $this->addActivitiesToUser($data['activities'], $user);}
      if(isset($data['equipments'])){ $this->addEquipmentsToUser($data['equipments'], $user);}
    }elseif(($data['role'] == 'Администратор')||($data['role'] == 'Менеджер')){
      $this->addUserToGroup('managers', $user);
    }else{
      $this->addUserToGroup('workers', $user);
    }

    return response()->json('200');
  }

  /**
   * Get a validator for an incoming registration request.
   *
   * @param  array  $data
   * @return \Illuminate\Contracts\Validation\Validator
   */
   protected function validatorLogin(array $data)
   {
       return Validator::make($data, [
           'login' => ['required', 'string', 'max:255', 'unique:users,login'],
       ],
       [
         'login.unique'=>'Этот логин уже используется другим пользователем. Придумайте новый, пожалуйста.'
       ]);
   }
  protected function validatorPassword(array $data)
  {
      return Validator::make($data, [
          'password' => ['required', 'string', 'min:6', 'confirmed'],
      ]);
  }
}
