<?php

namespace App\Http\Controllers\Profiles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Profile;
use App\Models\Organization;
use App\Models\Document;
use App\Models\Project;
use Illuminate\Support\Facades\Validator;
use App\Traits\CreateItems;
use App\Traits\UpdateItems;
use Storage;
use File;
use ZipArchive;

class ProfileController extends Controller
{
    use CreateItems, UpdateItems;

    public function controlProfiles(){
      return view('profiles.control');
    }

    public function viewProfiles(){
      return view('profiles.view');
    }

    public function showCreateForm(){
      return view('profiles.create');
    }


    public function fastCreate(Request $request){
      $profile = $this->createProfile($request->all());
      if($request->project != 'null'){
       $project = Project::find($request->project);
       $organization = $project->organization;
      }else{
        $organization = auth()->user()->get_organization();
      }

      $profile->organization()->attach($organization);

      if($request->avatar != 'null'){$this->createAvatar($profile, $request->avatar);}
      if($request->passport != 'null'){$this->createDocument($profile, $request->passport, 'Паспорт');}

      return $request->wantsJson()
                  ? new JsonResponse($profile->id, 200)
                  : $profile;
    }

    public function regProfile(Request $request){
      $profile = $this->createProfile($request->all());

      $organization = Organization::find($request->organization);
      $profile->organization()->attach($organization);

      $profile->code = $organization->code.'_'.random_int(10000, 99999);
      $profile->save();

      if($request->avatar != 'null'){$this->createAvatar($profile, $request->avatar);}
      if($request->passport != 'null'){$this->createDocument($profile, $request->passport, 'Паспорт');}

      return $request->wantsJson()
                  ? new JsonResponse($profile->code, 200)
                  : $profile;
    }

    public function showProfile(){
      return view('profiles.profile');
    }

    public function uploadAvatar(Request $request){
      $request->validate([
            'avatar' => 'required|mimes:png,jpg,jpeg|max:2048'
          ],
          [
            'avatar.mimes' => 'Файл должен быть формата: png, jpg, jpeg.',
            'avatar.max' => 'Файл не должен превышать :max Кбайт.'
          ]);
      $profile = Profile::find($request->id);
      $this->createAvatar($profile, $request->avatar);
      return response()->json('200');
    }

    public function deleteAvatar(Request $request){
      $avatar = Profile::find($request->id)->avatar;
      $path = $avatar->path;
      $avatar->delete();
      return response()->json('200');
    }

    public function update(Request $request){
      $profile = $this->updateProfile($request->all());

      return $request->wantsJson()
                  ? new JsonResponse($profile->id, 200)
                  : $profile;
    }

    public function updateRole(Request $request){
      $profile = Profile::find($request->id);
      $profile->role = $request->role;
      if($profile->code == null){
        $profile->code = $profile->get_organization()->code.'_'.random_int(10000, 99999);
      }
      $profile->save();
      return $request->wantsJson()
                  ? new JsonResponse($profile->code, 200)
                  : $profile;
    }

    public function updateContacts(Request $request){
      $profile = Profile::find($request->id);
      if(isset($request->email) && $profile->email != $request->email){
        $this->validatorEmail($request->all())->validate();
      }
      if(isset($request->phone) && $profile->phone != $request->phone){
        $this->validatorPhone($request->all())->validate();
      }
      isset($request->address) ? $profile->address = $request->address : $profile->address = 'Без уточнения';
      if(isset($request->phone)){
        $profile->phone = $request->phone;
      }
      if(isset($request->email)){
        $profile->email = $request->email;
      }
      $profile->save();

      return $request->wantsJson()
                  ? new JsonResponse($profile->id, 200)
                  : $profile;
    }



    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validatorEmail(array $data)
    {
        return Validator::make($data, [
            'email' => ['unique:profiles,email'],
        ],
        [
          'email.unique' => 'Эта почта уже используется',
        ]);
    }

    protected function validatorPhone(array $data)
    {
        return Validator::make($data, [
            'phone' => ['unique:profiles,phone'],
        ],
        [
          'phone.unique' => 'Этот телефон уже используется',
        ]);
    }

    protected function validator(array $data)
    {
      return Validator::make($data, [
          'phone' => ['required', 'string', 'size:10', 'unique:users,phone'],
          'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email']
      ],
      [
          'phone.required' => 'Поле Телефон должно быть заполнено',
          'phone.string' => 'Поле Телефон должно быть строчного типа',
          'phone.unique' => 'Пользователь с таким телефоном уже есть',
          'phone.size' => 'Введите 10 цифр',
          'email.required' => 'Поле Email должно быть заполнено',
          'email.string' => 'Поле Email должно быть строчного типа',
          'email.unique' => 'Пользователь с таким email уже есть',
      ],);
    }
}
