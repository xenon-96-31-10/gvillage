<?php

namespace App\Http\Controllers\Projects;

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

class ProjectController extends Controller
{
  use CreateItems, UpdateItems;

  public function controlProjects(){
    return view('projects.control');
  }

  public function update(Request $request){
    $project = $this->updateProject($request->all());
    if($project->owner->id != $request->owner){
      $project->owner()->dissociate();
      $profile = Profile::find($request->owner);
      $project->owner()->associate($profile)->save();
    }
    if($project->project_group != null && $project->project_group->id != $request->group){
      $project->project_group()->dissociate();
      $group = ProjectGroup::find($request->group);
      $project->project_group()->associate($group )->save();
    }elseif(isset($request->group)){      
      $group = ProjectGroup::find($request->group);
      $project->project_group()->associate($group )->save();
    }
    if($project->project_type->id != $request->type){
      $project->project_type()->dissociate();
      $type = ProjectGroup::find($request->type);
      $project->project_type()->associate($type )->save();
    }
    return response()->json($project);
  }

  public function create(Request $request){
    $project = $this->createProject($request->all());

    $profile = Profile::find($request->owner);
    $project->owner()->associate($profile);
    $project->personal_account()->associate($profile->account);

    $type = ProjectGroup::find($request->type);
    $project->project_type()->associate($type );

    $status = ProjectStatus::find(1);
    $project->project_status()->associate($status);

    $organization = Organization::find($request->organization);
    $project->organization()->associate($organization);

    if(isset($request->group)){
      $group = ProjectGroup::find($request->group);
      $project->project_group()->associate($group );
    }

    $type = ProjectType::find($request->type);
    $project->project_type()->associate($type)->save();

    $project->profiles()->attach($profile);

    return response()->json($project);
  }
}
