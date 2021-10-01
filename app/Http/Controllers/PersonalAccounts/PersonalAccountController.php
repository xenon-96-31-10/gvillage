<?php

namespace App\Http\Controllers\PersonalAccounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\CreateItems;
use App\Traits\UpdateItems;
use App\Models\PersonalAccount;
use Illuminate\Http\JsonResponse;

class PersonalAccountController extends Controller
{
  use CreateItems, UpdateItems;

  public function showReport(){
    return view('account.report');
  }

  public function refill(Request $request){
    $account = $this->updateAccount($request->all());
    $total =  $request->all();
    $log = $this->createPersonalAccountLog(1, $total['amount'], $account);
    return $request->wantsJson()
                ? new JsonResponse($account->id, 200)
                : $account;
  }

  public function create(Request $request){
    $account = $this->createPersonalAccount($request->all());
    if($request->amount > 0){
      $total =  $request->amount;
      $log = $this->createPersonalAccountLog(1, $total, $account);
    }
    return $request->wantsJson()
                ? new JsonResponse($account->id, 200)
                : $account;
  }

}
