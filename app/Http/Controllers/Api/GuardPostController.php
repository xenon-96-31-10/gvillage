<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Models\GuardPost;

class GuardPostController extends Controller
{
  public function getGuardPosts(){
    $guard_posts = auth()->user()->get_organization()->guard_posts;

    $data = array();
    foreach ($guard_posts as $guard_post) {
      $data[] = [
        'value' => $guard_post->id,
        'label' => $guard_post->name,
      ];
    }

    return response()->json($data);
  }

  public function getGuardPostsForList(){
    $user = auth()->user();
    if($user->hasRole('admin')){
      $posts = GuardPost::orderBy('id', 'desc')->get();
    }elseif($user->hasRole('manager')){
      $posts = auth()->user()->get_organization()->guard_posts;
    }else{
      $posts = new Collection();
      $projects = auth()->user()->profile->projects;
      foreach ($projects as $project) {
        if($project->project_group != null && $project->project_group->guard_post != null){
          $posts->push($project->project_group->guard_post);
        }
      }
      $posts = $posts->unique('id');
    }

    $data = array();
    foreach ($posts as $post) {
      $data[] = [
        'id' => $post->id,
        'title' => $post->name,
        'description' => $post->phone,
        'data' => $post->chief,
        'selected' => false,
      ];
    }
    return response()->json($data);
  }

  public function getGuardPost(Request $request){
    $post = GuardPost::find($request->id);
    $guards = array();
    foreach ($post->guards as $guard) {
      $guards[] = [
        'id' => $guard->id,
        'fio' => $guard->profile->fio,
        'phone' => $guard->profile->phone,
      ];
    }


    $data = [
      'id' => $post->id,
      'name' => $post->name,
      'phone' => $post->phone,
      'organization' => $post->organization,
      'chief' => $post->chief,
      'enters' => $post->enters,
      'projectGroup' => $post->project_group,
      'guards' => $guards,
      'date' =>$post->created_at->format('d.m.Y')
    ];
    return response()->json($data);
  }

}
