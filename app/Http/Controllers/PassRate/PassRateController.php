<?php

namespace App\Http\Controllers\PassRate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PassRatePlan;
use App\Models\PassRate;
use App\Traits\CreateItems;
use App\Traits\UpdateItems;
use Illuminate\Http\JsonResponse;

class PassRateController extends Controller
{
    use CreateItems,UpdateItems;

    public function controlPassRatePlans(){
      return view('passRatePlan.index');
    }

    public function Create(Request $request){
      $plan = $this->createPassRatePlan($request->all());
      return $request->wantsJson()
                  ? new JsonResponse($plan->id, 200)
                  : $plan;
    }

    public function DeletePlan(Request $request){
      $plan = PassRatePlan::find($request->id);
      foreach ($plan->projects as $project) {
        $project->pass_rate_plan()->dissociate()->save();
      }
      foreach ($plan->project_groups as $group) {
        $group->pass_rate_plan()->dissociate()->save();
      }
      $res = $plan->delete();
      return $request->wantsJson()
                  ? new JsonResponse($res, 200)
                  : $res;
    }

    public function DeleteRate(Request $request){
      $rate = PassRate::find($request->id);
      $rates = $rate->pass_rate_plan->pass_rates;
      $default = $rates->where('pass', $rate->pass)->where('visitor_type', $rate->visitor_type)->where('visitor', $rate->visitor)->where('time', $rate->time)->first();

      foreach ($rate->passes as $pass) {
        $pass->rate()->dissociate();
        $pass->rate()->associate($default)->save();
      }
      $res = $rate->delete();
      return $request->wantsJson()
                  ? new JsonResponse($res, 200)
                  : $res;
    }

    public function UpdateDefault(Request $request){
      $plan = $this->updateDefaultPassRatePlan($request->id);
      return $request->wantsJson()
            ? new JsonResponse($plan->id, 200)
            : $plan;
    }

    public function UpdatePlan(Request $request){
      $plan = $this->updatePassRatePlan($request->all());

      return $request->wantsJson()
            ? new JsonResponse($plan->id, 200)
            : $plan;
    }
}
