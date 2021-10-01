<?php

namespace App\Http\Controllers\ProjectGroups;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Profile;
use App\Models\Project;
use App\Models\ProjectGroup;
use App\Models\ProjectStatus;
use App\Models\Organization;
use App\Models\ProjectType;

use Illuminate\Support\Facades\Validator;
use App\Traits\CreateItems;
use App\Traits\UpdateItems;

class ProjectGroupController extends Controller
{
  use CreateItems, UpdateItems;

  public function controlProjectGroups(){
    return view('projectGroups.control');
  }

  public function update(Request $request){
    $group = ProjectGroup::find($request->id);
    $group->name = $request->name;
    $group->save();
    if($request->projects != null){
      foreach ($group->projects as $project) {
        $project->project_group()->dissociate()->save();
      }
      $projects = Project::find($request->projects);
      $group->projects()->saveMany($projects);
    }else{
      if($group->projects != null){
        foreach ($group->projects as $project) {
          $project->project_group()->dissociate()->save();
        }
      }
    }
    return response()->json($group);
  }

  public function create(Request $request){
    $group = new ProjectGroup();
    $group->name = $request->name;
    $organization = Organization::find($request->organization);
    $group->organization()->associate($organization)->save();
    if($request->projects != null){
      foreach ($group->projects as $project) {
        $project->project_group()->dissociate()->save();
      }
      $projects = Project::find($request->projects);
      $group->projects()->saveMany($projects);
    }else{
      if($group->projects != null){
        foreach ($group->projects as $project) {
          $project->project_group()->dissociate()->save();
        }
      }
    }
    return response()->json($group);
  }
}
