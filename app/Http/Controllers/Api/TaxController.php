<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tax;

class TaxController extends Controller
{
  public function getTaxes(){
    $taxes = Tax::all();
    return response()->json($taxes);
  }

}
