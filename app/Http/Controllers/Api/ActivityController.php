<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Activity;

class ActivityController extends Controller
{
  public function getActivities(){
    $activities = Activity::all();
    $data = array();
    foreach ($activities as $activity) {
      $data[] = [
        'value' => $activity->id,
        'label' => $activity->name,
      ];
    }
    return response()->json($data);
  }
}
