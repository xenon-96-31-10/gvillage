<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Equipment;

class EquipmentController extends Controller
{
  public function getEquipments(){
    $warehouses = auth()->user()->get_organization()->warehouses;

    $data = array();
    foreach ($warehouses as $warehouse) {
      foreach ($warehouse->equipments as $equipment) {
        if($equipment->value > 0){
          $data[] = [
            'value' => $equipment->id,
            'label' => $equipment->name,
          ];
        }
      }
    }

    return response()->json($data);
  }
}
