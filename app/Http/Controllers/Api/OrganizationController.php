<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Organization;

class OrganizationController extends Controller
{
  public function getOrganizations(Request $request){
    if(auth()->user()->hasRole('admin')){
      $organizations = Organization::all();
    }else{
      $organizations = auth()->user()->organization;
    }
    $data = array();
    foreach ($organizations as $organization) {
      $data[] = [
        'value' => $organization->id,
        'label' => $organization->name,
      ];
    }
    return response()->json($data);
  }
}
