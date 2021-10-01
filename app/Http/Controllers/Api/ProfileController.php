<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Organization;
use Storage;

class ProfileController extends Controller
{
  public function checkCode(Request $request){
    $profile = Profile::where('code', $request->code)->first();
    if($profile === null){
      $message = $this->errorCheckCode();
      return new JsonResponse($message, 422);
    }else{
      return $request->wantsJson()
                  ? new JsonResponse($profile->id, 200)
                  : $profile;
    }
  }

  public function getProfiles(Request $request){
    isset($request->id) ? $organization = Project::find($request->id)->organization : $organization = auth()->user()->get_organization();

     $profiles = $organization->profiles()->orderBy('id', 'desc')->get();

     $data = array();
     foreach ($profiles as $profile) {
       $profile->user != null ?  $role = $profile->user->roles()->first()->name : $role = $profile->role;

       $profile->account != null ? $account = $profile->account->number : $account = "Без ЛС";

       $data[] = [
         'id' => $profile->id,
         'title' => $profile->fio,
         'description' => $role,
         'data' => $account,
       ];
     }
     return response()->json($data);
  }

  public function getAvailableProfiles(Request $request){
      if(auth()->user()->hasRole('admin')){
        $profiles = Profile::latest()->get();
      }elseif(auth()->user()->hasRole('owner')){
        $profiles = new Collection();
        $projects = auth()->user()->profile->own_projects;
        foreach ($projects as $project) {
          $profiles = $profiles->merge($project->profiles);
        }
        $profiles = $profiles->unique('id');
      }elseif(auth()->user()->hasRole('dispatcher') || auth()->user()->hasRole('ownersguard') || auth()->user()->hasRole('owners-rep') || auth()->user()->hasRole('renter')){
        $profiles = new Collection();
        $projects = auth()->user()->profile->projects;
        foreach ($projects as $project) {
          $profiles = $profiles->merge($project->profiles);
        }
        $profiles = $profiles->unique('id');
      }else{
        $profiles = auth()->user()->get_organization()->profiles()->latest()->get();
      }

     $data = array();
     foreach ($profiles as $profile) {
       $profile->user != null ?  $role = $profile->user->roles()->first()->name : $role = $profile->role;
       $profile->user != null ?  $status = 'Зарегестрирован' : $status = 'Не зарегестрирован';
       $profile->account != null ? $account = $profile->account->number : $account = "Без ЛС";

       $data[] = [
         'id' => $profile->id,
         'account' => $account,
         'organization' => $profile->get_organization()->name,
         'role' => $role,
         'fio' => $profile->fio,
         'status' => $status,
       ];
     }
     return response()->json($data);
  }

  public function getApplicants(Request $request){
     $project = Project::find($request->id);
     $profiles = $project->profiles;
     $data = array();
     foreach ($profiles as $profile) {
       $role = $profile->user->roles()->first()->name;

       $profile->account != null ? $account = $profile->account->number : $account = "Без ЛС";

       $data[] = [
         'id' => $profile->id,
         'title' => $profile->fio,
         'description' => $role,
         'data' => $account,
       ];
     }
     return response()->json($data);
  }

  public function getCurrentApplicantId(){
    $id = auth()->user()->profile->id;
    return response()->json($id);
  }

  public function findHuman(Request $request){
    $keywords = $request->human;
    $profiles = Profile::where('fio', 'like', '%' . $keywords . '%')->limit(2)->get();
    return response()->json($profiles);
  }

  public function getHuman(Request $request){
    $profile = Profile::where('fio', $request->human)->first();
    if($profile !== null){
      $status = '200';
      $text = 'Пользователь найден!';
      if($profile->avatar !== null){
        $avatar = 'Фото добавлено';
      }else{
        $avatar = 'Фото отсутствует';
      }
      if($profile->passport() !== null){
        $passport = 'Паспорт добавлен';
      }else{
        $passport = 'Паспорт отсутствует';
      }
    }else{
      $status = '404';
      $passport = 'Паспорт отсутствует';
      $avatar = 'Фото отсутствует';
      $profile = ['id' => 'new'];
      $text = 'Пользователь не зарегестрирован в системе. Если хотите на него зарегестрировать пропуск, просто продолжайте, иначе проверьте данные.';
    }

    $data = [
      'status' =>  $status,
      'text' => $text,
      'profile' => $profile,
      'avatar' => $avatar,
      'passport' => $passport,
    ];
    return response()->json($data);
  }

  public function getProfile(Request $request){
    $profile = Profile::find($request->id);

    $profile->avatar !== null ? $avatar = Storage::url($profile->avatar->path) : $avatar = 'Фото отсутствует';
    $profile->account != null ? $account = $profile->account : $account = "Без ЛС";


    $data = [
      'profile' => $profile,
      'dateofbirth' => $profile->dateofbirth,
      'account' => $account,
      'avatar' => $avatar,
      'documents' => $profile->documents,
      'cars' => $profile->cars,
      'mechanizms' => $profile->mechanizms,
    ];
    return response()->json($data);
  }

  private function errorCheckCode(){
    $message = [
      'status' => 'error',
      'msg' => 'error',
      'errors' => [
                    'code' => 'Регистрационный код не верен. Проверьте введенные данные.',
                  ]
    ];

    return $message;
  }
}
