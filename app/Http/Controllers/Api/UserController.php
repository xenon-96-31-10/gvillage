<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;

class UserController extends Controller
{
    public function getNotifications(){
      $user = auth()->user();
      $unReadNotifications = $user->unReadNotifications;
      $data = [
        'unReadNotifications' => $user->unReadNotifications,
        'unRead' => $user->unReadNotifications->count(),
        'Notifications' => $user->notifications,
      ];
      $user->unreadNotifications()->update(['read_at' => now()]);
      return response()->json($data);
    }

    public function getUser(){
      $login = auth()->user()->login;
      return response()->json($login);
    }

    public function getUsers(){
      if(auth()->user()->hasRole('admin')){
        $users = User::latest()->get();
      }elseif(auth()->user()->hasRole('dispatcher')){
        $users = new Collection();
        $projects = auth()->user()->profile->projects;
        foreach ($projects as $project) {
          $profiles = $project->profiles->where('user_id', '!=', null);
          foreach ($profiles as $profile) {
            $users->push($profile->user);
          }
        }
        $users = $users->unique('id');
      }else{
        $users = auth()->user()->get_organization()->users()->latest()->get();
      }
      $data = array();
      foreach ($users as $user) {
        $user->profile->account != null ? $account = $user->profile->account->number : $account = "Без ЛС";
        $data[] = [
          'id' => $user->id,
          'pId' => $user->profile->id,
          'account' => $account,
          'organization' => $user->get_organization()->name,
          'role' => $user->roles()->first()->name,
          'fio' => $user->profile->fio,
          'status' => $user->status,
        ];
      }
      return response()->json($data);
    }

    public function getProfiles(){
      if(auth()->user()->hasRole('admin')){
        $users = User::latest()->get();
      }else{
        $users = auth()->user()->get_organization()->users()->latest()->get();
      }

       $data = array();
       foreach ($users as $user) {

         $user->profile->account != null ? $account = $user->profile->account->number : $account = "Без ЛС";

         $data[] = [
           'id' => $user->profile->id,
           'title' => $user->profile->fio,
           'description' => $user->roles()->first()->name,
           'data' => $account,
         ];
       }
       return response()->json($data);
    }
}
