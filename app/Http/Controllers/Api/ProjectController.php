<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectGroup;
use App\Models\ProjectType;
use App\Models\Family;

class ProjectController extends Controller
{
    /**
     * [getProjects for list of projects from API]
     * @return $projects
     */
    public function getProjects(){
      $user = auth()->user();
      $profile = auth()->user()->profile;
      if($user->hasRole('guard-post')){
        $projects = $user->get_guard_post()->project_group->projects()->orderBy('id', 'desc')->get();
      }else{
        $projects = $profile->projects()->orderBy('id', 'desc')->get();
      }

      $data = array();
      foreach ($projects as $project) {
        $data[] = [
          'id' => $project->id,
          'title' => $project->name,
          'description' => $project->address,
          'data' => $project->owner->fio,
          'selected' => false,
        ];
      }
      return response()->json($data);
    }

    public function getProjectsForRate(){
      $user = auth()->user();
      $projects = $user->get_organization()->projects()->orderBy('id', 'desc')->get();

      $data = array();
      foreach ($projects as $project) {
        $data[] = [
          'id' => $project->id,
          'title' => $project->name,
          'description' => $project->address,
          'data' => $project->owner->fio,
          'selected' => false,
        ];
      }
      return response()->json($data);
    }

    public function getProjectGroupsForRate(){
      $user = auth()->user();
      $groups = $user->get_organization()->project_groups()->orderBy('id', 'desc')->get();

      $data = array();
      foreach ($groups as $group) {
        $data[] = [
          'id' => $group->id,
          'title' => $group->name,
          'description' => '',
          'data' => '',
          'selected' => false,
        ];
      }
      return response()->json($data);
    }

    public function getProjectsForList(){
      $user = auth()->user();
      if($user->hasRole('admin')){
        $projects = Project::orderBy('id', 'desc')->get();
      }elseif($user->hasRole('owner')){
        $projects = $user->profile->own_projects()->orderBy('id', 'desc')->get();
      }elseif($user->hasRole('dispatcher')){
        $projects = $user->profile->projects()->orderBy('id', 'desc')->get();
      }else{
        $projects = $user->get_organization()->projects()->orderBy('id', 'desc')->get();
      }

      $data = array();
      foreach ($projects as $project) {
        $project->project_group != null ? $group = $project->project_group->name : $group = 'Вне группы';
        $data[] = [
          'id' => $project->id,
          'title' => $project->address,
          'description' => $group,
          'data' => $project->owner->fio,
          'selected' => false,
        ];
      }
      return response()->json($data);
    }

    public function getProjectGroupsForList(){
      $user = auth()->user();
      if($user->hasRole('admin')){
        $groups = ProjectGroup::orderBy('id', 'desc')->get();
      }elseif($user->hasRole('manager')){
        $groups = $user->get_organization()->project_groups()->orderBy('id', 'desc')->get();
      }


      $data = array();
      foreach ($groups as $group) {
        $data[] = [
          'id' => $group->id,
          'title' => $group->name,
          'description' => 'Проектов: '.$group->projects->count(),
          'data' => 'Дата создания: '.$group->created_at->format('d.m.Y'),
          'selected' => false,
        ];
      }
      return response()->json($data);
    }

    public function getProject(Request $request){
      $project = Project::find($request->id);
      $project->project_group != null ? $group = $project->project_group : $group = 'Вне группы';
      $data = [
        'id' => $project->id,
        'name' => $project->name,
        'description' => $project->description,
        'address' => $project->address,
        'totalarea' => $project->totalarea,
        'livingarea' => $project->livingarea,
        'owner' => $project->owner,
        'group' => $group,
        'profiles' => $project->profiles,
        'type' => $project->project_type,
        'date' => $project->created_at->format('d.m.Y'),
      ];
      return response()->json($data);
    }

    public function getProjectGroup(Request $request){
      $group = ProjectGroup::find($request->id);
      $group->pass_rate_plan != null ? $plan = $group->pass_rate_plan : $plan = 'По умолчанию';
      $group->guard_post != null ? $post = $group->guard_post : $post = 'Без поста охраны';
      $data = [
        'id' => $group->id,
        'name' => $group->name,
        'organization' => $group->organization,
        'plan' => $plan,
        'post' => $post,
        'projects' => $group->projects,
        'date' => $group->created_at->format('d.m.Y'),
      ];
      return response()->json($data);
    }

    public function getOptionProjects(){
      $projects = auth()->user()->get_organization()->projects;
      $data = array();
      foreach ($projects as $project) {
        $data[] = [
          'value' => $project->id,
          'label' => $project->name.' ('. $project->address.')',
        ];
      }
      return response()->json($data);
    }

    public function getProjectGroups(Request $request){
      if(auth()->user()->hasRole('admin')){
        $groups = ProjectGroup::all();
      }else{
        $groups = auth()->user()->get_organization()->project_groups;
      }
      $data = array();
      foreach ($groups as $group) {
        if(isset($request->post)){
          if($group->guard_post == null){
            $data[] = [
              'value' => $group->id,
              'label' => $group->name,
            ];
          }
        }else{
          $data[] = [
            'value' => $group->id,
            'label' => $group->name,
          ];
        }

      }
      return response()->json($data);
    }

    public function getFamilyProjects(Request $request){
      $family = Family::find($request->id);
      $data = array();
      $users = $family->users;
      foreach($users as $user){
        if($user->hasRole('owner')){
          $projects = $user->projects;
        }
      }
      foreach ($projects as $project) {
        $data[] = [
          'value' => $project->id,
          'label' => $project->name.' ('. $project->address.')',
        ];
      }
      return response()->json($data);
    }

    public function getProjectTypes(){
      $types = ProjectType::all();
      $data = array();
      foreach ($types as $type) {
        $data[] = [
          'value' => $type->id,
          'label' => $type->name,
        ];
      }
      return response()->json($data);
    }


}
