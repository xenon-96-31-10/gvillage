<?php
namespace App\Traits;

use App\Models\User;
use App\Models\GuardPost;
use App\Models\GuardPostEnter;
use App\Models\GuardPostLog;

trait AuthGuardPost
{
    public function authEnter($id){
      $user = auth()->user();
      $post = $user->get_guard_post();
      $enter = $post->enters()->where('id', $id)->first();
      $log = new GuardPostLog();
      $log->status = 'Авторизован';
      $log->guard_post()->associate($post);
      $log->guarder()->associate($user);
      $log->enter()->associate($enter)->save();

      $enter->status = 'Под наблюдением';
      $enter->save();
    }

    public function logoutEnter(){
      $user = auth()->user();
      if($user->hasRole('guard-post') && $user->get_guard_status() !=null && $user->get_guard_status()->status != 'Вышел'){
        $post = $user->get_guard_post();
        $enter = $user->get_guard_status()->enter;

        $log = new GuardPostLog();
        $log->status = 'Вышел';
        $log->guard_post()->associate($post);
        $log->guarder()->associate($user);
        $log->enter()->associate($enter)->save();

        $enter->status = 'Активный';
        $enter->save();
      }
    }
}
