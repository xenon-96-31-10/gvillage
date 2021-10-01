<?php

namespace App\Http\Controllers\GuardPosts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\GuardPost;
use App\Models\GuardPostEnter;
use App\Models\Profile;
use App\Models\User;
use App\Models\Organization;
use App\Models\ProjectGroup;

use Illuminate\Support\Facades\Validator;
use App\Traits\CreateItems;
use App\Traits\UpdateItems;

class GuardPostController extends Controller
{
  use CreateItems, UpdateItems;

  public function controlGuardPosts(){
    return view('guardPosts.control');
  }

  public function viewGuardPosts(){
    return view('guardPosts.view');
  }

  public function update(Request $request){
    $post = GuardPost::find($request->id);
    $post->name = $request->name;
    $post->save();
    $guards = User::find($request->guards);
    $post->guards()->sync($guards);

    foreach($request->enters as $item) {
      if($item['id'] != 'new'){
        $enter = GuardPostEnter::find($item['id']);
      }else{
        $enter = new GuardPostEnter();
        $enter->guard_post()->associate($post);
      }
      $item['name'] == '' || $item['name'] == null ? $name = 'КПП'.random_int(10000, 99999) : $name = $item['name'];
      $enter->name = $name;
      $enter->phone = $item['phone'] || "";
      $enter->status = $item['status'];
      $enter->save();
    }
    return response()->json($post);
  }

  public function create(Request $request){
    $post = new GuardPost();
    $post->name = $request->name;
    $post->phone = $request->phone;
    $chief = Profile::find($request->chief);
    $post->chief()->associate($chief );
    $organization = Organization::find($request->organization);
    $post->organization()->associate($organization);
    if(is_numeric($request->group)){
      $group = ProjectGroup::find($request->group);
    }else{
      $group = new ProjectGroup();
      $group->name = $request->name;
      $group->organization()->associate($organization)->save();
    }
    $post->project_group()->associate($group)->save();

    $guards = User::find($request->guards);
    $post->guards()->attach($guards);

    foreach($request->enters as $item) {
      $enter = new GuardPostEnter();
      $enter->guard_post()->associate($post);
      $item['name'] == '' || $item['name'] == null ? $name = 'КПП'.random_int(10000, 99999) : $name = $item['name'];
      $enter->name = $name;
      $enter->phone = $item['phone'] || "";
      $enter->status = $item['status'];
      $enter->save();
    }
    return response()->json($post);
  }
}
