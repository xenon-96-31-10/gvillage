<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\PersonalAccount;
use App\Models\Permission;
use App\Models\Project;
use App\Models\Profile;

class PersonalAccountController extends Controller
{
    public function getPersonalAccount(Request $request){
      $profile = Profile::find($request->id);
      $profile->account != null ? $account = $profile->account : $account = "Без ЛС";
      return $request->wantsJson()
                  ? new JsonResponse($account, 200)
                  : $account;
    }

    public function checkAccessToRefill(Request $request){
      $user = auth()->user();
      $permission = Permission::where('slug','refill-personal-account')->first();

      if($user->hasPermissionTo($permission)){
        return response()->json('200');
      }else{
        $data = array();
        if(isset($request->id) && $request->id != null){
          $project = Project::find($request->id);
          $organization = $project->organization;
          $profiles = $project->profiles;
          foreach ($organization->users as $user) {
            if($user->hasRole('admin') || $user->hasRole('manager') ){
              $data[] = [
                'id' => $user->profile->id,
                'fio' => $user->profile->fio,
                'role' => $user->profile->role,
                'phone' => $user->profile->phone,
              ];
            }
          }

          foreach ($profiles as $profile) {
            if($profile->user != null && $profile->user->hasRole('dispatcher') ){
              $data[] = [
                'id' => $profile->id,
                'fio' => $profile->fio,
                'role' => $profile->role,
                'phone' => $profile->phone,
              ];
            }
          }
        }else{
          $organization = $user->get_organization();
          foreach ($organization->users as $user) {
            if($user->hasRole('admin') || $user->hasRole('manager') ){
              $data[] = [
                'id' => $user->profile->id,
                'fio' => $user->profile->fio,
                'role' => $user->profile->role,
                'phone' => $user->profile->phone,
              ];
            }
          }
        }

        return response()->json($data);
      }
    }

    public function checkAccessToCreate(Request $request){
      $user = auth()->user();
      $permission = Permission::where('slug','create-personal-account')->first();
      $organization = $user->get_organization();
      if($user->hasPermissionTo($permission)){
        return response()->json('200');
      }else{
        $data = array();
        foreach ($organization->users as $user) {
          if($user->hasRole('admin') || $user->hasRole('manager') ){
            $data[] = [
              'id' => $user->profile->id,
              'fio' => $user->profile->fio,
              'role' => $user->profile->role,
              'phone' => $user->profile->phone,
            ];
          }
        }

        return response()->json($data);
      }
    }

    public function getPersonalAccountReport(Request $request){
      $account = PersonalAccount::find($request->id);
      $logs = $account->log;
      $data = array();
      foreach ($logs as $log) {
        $data[] = [
          'id' => $log->id,
          'status' => $log->status ? 'Пополнение' : 'Списание',
          'total' => $log->total,
          'date' => $log->created_at->format('d.m.Y'),
        ];
      }

      return $request->wantsJson()
                  ? new JsonResponse($data, 200)
                  : $data;
    }
}
