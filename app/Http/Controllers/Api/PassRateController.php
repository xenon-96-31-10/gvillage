<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PassRate;
use App\Models\PassRatePlan;
use App\Models\Project;
use App\Models\Car;
use App\Models\Profile;
use App\Models\Mechanizm;
use App\Models\Role;

class PassRateController extends Controller
{
    public function getPlans(){
      $organization = auth()->user()->get_organization();
      $plans = $organization->rate_plans;
      $data = array();
      foreach ($plans as $plan) {
        $data[] =[
          'id' => $plan->id,
          'name' => $plan->name,
          'create' => $plan->created_at->format('d.m.Y'),
          'default' => $plan->default ? 'По умолчанию' : 'Обычный',
        ];
      }
      return response()->json($data);
    }

    public function getPlan(Request $request){
      $plan = PassRatePlan::find($request->id);
      $data = [
        'id' => $plan->id,
        'name' => $plan->name,
        'default' => $plan->default,
        'temporary' => $plan->pass_rates()->where('pass', 'temporary')->get(),
        'permanent' => $plan->pass_rates()->where('pass', 'permanent')->get(),
        'selectedGroups' => $plan->project_groups,
        'selectedProjects' => $plan->projects,
      ];

      return response()->json($data);
    }

    public function getRate(Request $request){
      $data = $request->all();
      $project = Project::find($data['project']);
      $pass = $data['type'];
      $v_type = $data['visitor']['type'];
      $time = $data['permanent']['exit'];
      if($v_type == 'cars'){
        $visitor = Car::find($data['visitor']['id']);
      }elseif($v_type == 'profiles'){
        $visitor = Profile::find($data['visitor']['id']);
      }else{
        $visitor = Mechanizm::find($data['visitor']['id']);
      }

      if($project->pass_rate_plan != null){
        $rates = $project->pass_rate_plan->pass_rates;
      }elseif($project->project_group != null && $project->project_group->pass_rate_plan != null){
        $rates = $project->project_group->pass_rate_plan->pass_rates;
      }else{
        $organization = $project->organization;
        $rates = $organization->default_rate_plan()->first()->pass_rates;
      }
      $rate = $this->findRate($rates, $pass, $v_type, $visitor, $time);
      $rate->role != 'По умолчанию' ? $role = Role::where('name', $rate->role)->first()->name : $role = 'По умолчанию';
      $rate->visitor_type == 'cars' ? $name = 'Тариф для авто ('.$rate->visitor.')' : ($rate->visitor_type == 'mechanizms' ? $name = 'Тариф для техники ('.$rate->visitor.')' : $name = 'Тариф для человека (пеший)');
      $data = [
        'id' => $rate->id,
        'name' => $name,
        'role' => $role,
        'description' => $rate->description,
        'price' => $rate->price,
        'time' => $rate->time,
      ];
      return response()->json($data);
    }

    private function findRate($rates, $pass, $v_type, $visitor, $time){
      $rates = $rates->where('pass', $pass)->where('visitor_type', $v_type);
      $pass == 'permanent' ? $rates = $rates->where('time', $time) : $rates = $rates;
      if($v_type == 'cars'){
        $rates = $rates->where('visitor', $visitor->body);

        if($rates->count() > 1){
          if($visitor->owner != null && $visitor->owner->user != null){
            $role = $visitor->owner->user->roles()->first()->name;
            $rates->where('role', $role)->count() == 0 ? $role = "По умолчанию" : $role = $role;
          }else{
            $role = "По умолчанию";
          }
          $rate = $rates->where('role', $role)->first();
        }else{
          $rate = $rates->first();
        }

      }elseif($v_type == 'profiles'){
        if($visitor->user != null){
          $role = $visitor->user->roles()->first()->name;
          $rates->where('role', $role)->count() == 0 ? $role = "По умолчанию" : $role = $role;
        }else{
          $role = "По умолчанию";
        }
        $rate = $rates->where('role', $role)->first();
      }else{
        $rate = $rates->where('visitor', $visitor->type)->first();
      }
      return $rate;
    }
}
