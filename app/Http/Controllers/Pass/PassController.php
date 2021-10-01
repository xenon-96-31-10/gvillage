<?php

namespace App\Http\Controllers\Pass;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\CreateItems;
use App\Traits\UpdateItems;
use Illuminate\Http\JsonResponse;
use App\Models\Project;
use App\Models\Pass;
use App\Models\PassRate;
use App\Models\Profile;
use App\Models\PermanentPassDate;

use Carbon\Carbon;

class PassController extends Controller
{
  use CreateItems,UpdateItems;

  public function __construct()
  {
    $this->middleware(['guardpost']);
  }

  /**
   * [showIndex to show list of the passes]
   * @return view with passes
   */
  public function showIndex(){
    return view('passes.index');
  }

  /**
   * [showIndex to show list of the passes]
   * @return view with passes
   */
  public function showReport(){
    return view('passes.report');
  }

  public function showDraggableIndex(){
    $passes = Pass::orderBy('id', 'desc')->get();
    return view('passes.draggableindex', compact('passes'));
  }

  /**
   * [showTemporaryCreateForm to show create form of the temporary passes]
   * @return view
   */
  public function showTemporaryCreateForm(){
    return view('passes.temporary.create');
  }

  /**
   * [showPermanentCreateForm to show create form of the Permanent passes]
   * @return view
   */
  public function showPermanentCreateForm(){
    return view('passes.permanent.create');
  }

  /**
   * [create ]
   * @param  Request $request [data from createform]
   * @return [response]           [api from vue component]
   */
  public function Create(Request $request){
    $author = auth()->user();
    $project = Project::find($request->project);
    $applicant = Profile::find($request->applicant);

    $pass = new Pass();
    $rate = PassRate::find($request->rate);
    $project->personal_account != null ? $account = $project->personal_account : $account = $project->owner->account;
    $balance = $account->balance - $account->frozen;
    if($balance < $rate->price){
      $message = $this->errorAccountBalance($account->id, $project->owner->fio, $balance, $rate->price, $project->id);
      return new JsonResponse($message, 422);
    }

    $pass->rate()->associate($rate);
    $status_log = 'Создан';
    $status_log2 = 'Ожидает приезда';
    if($request->type == 'temporary'){
      $type = $this->createTemporaryPass($request->all());
      if($type == 'error'){
        $message = $this->errorEqualEntry();
        return new JsonResponse($message, 422);
      }
      $pass->type = 'Временный пропуск';
    }else{
      $type = $this->createPermanentPass($request->all());
      if($type == 'error'){
        $message = $this->errorEqualPermanentPass();
        return new JsonResponse($message, 422);
      }
      $pass->type = 'Постоянный пропуск';
    }
    $pass->price = $rate->price;

    if($pass->rate != null && $pass->rate->price == 0){
      $pass->status_pay = 'Бесплатный';
    }else{
      $pass->status_pay = 'Не оплачен';
    }

    $pass->author()->associate($author);
    $pass->type()->associate($type);
    $pass->applicant()->associate($applicant);
    $pass->project()->associate($project)->save();
    if($this->createPassLog($status_log, $pass)){
      $this->createPassLog($status_log2, $pass);
    }
    $this->updateAccountFrozen($pass, $account);
    if(isset($request->comment)){ $this->addComentToPass($request->comment, $pass); }

    return $request->wantsJson()
                ? new JsonResponse($pass->id, 200)
                : $pass;
  }

  public function checkIn(Request $request){
    $pass = Pass::find($request->id);
    $type = $pass->type()->first();
    if($pass->type == 'Временный пропуск'){
      $type->entry = Carbon::now()->format('d.m.Y');
      $type->save();
    }else{
      $date = new PermanentPassDate();
      $date->entry = Carbon::now()->format('d.m.Y');
      $date->pass()->associate($type)->save();
    }
    $pass->project->personal_account != null ? $account = $pass->project->personal_account : $account = $pass->project->owner->account;

    if($pass->status_pay === 'Не оплачен'){
      if($this->debitAccountRate($account, $pass->price)){
          $this->createPassLog('Оплачен', $pass);
          $pass->status_pay = 'Оплачен';
          $pass->save();
      }
    }

    if(auth()->user()->hasRole('guard-post')){
      $this->createPassLog('Прошел контроль', $pass);
      $this->createPassLog('На территории', $pass);
    }else{
      $this->createPassLog('Прошел на объект', $pass);
      $this->createPassLog('На объекте', $pass);
    }

    return response()->json('200');
  }

  public function checkOut(Request $request){
    $pass = Pass::find($request->id);
    $type = $pass->type()->first();


    if(auth()->user()->hasRole('guard-post')){
      $this->createPassLog('Отбыл с территории', $pass);
    }else{
      $this->createPassLog('Отбыл с объекта', $pass);
    }

    if($pass->type == 'Временный пропуск'){
      $type->exit = Carbon::now()->format('d.m.Y');
      $type->save();
    }else{
      $this->createPassLog('Ожидает приезда', $pass);
      $date = $type->dates()->latest()->first();
      $date->exit = Carbon::now()->format('d.m.Y');
      $date->save();
    }


    return response()->json('200');
  }

  public function repeatTemporary(Request $request){
    $pass = Pass::find($request->id);
    $rate = $pass->rate;
    $pass->project->personal_account != null ? $account = $pass->project->personal_account : $account = $pass->project->owner->account;

    if($pass->rate->price == 0){
      $pass->status_pay = 'Оплачен';
    }else{
      $pass->status_pay = 'Не оплачен';
    }
    $pass->save();

    if($pass->status_pay == 'Не оплачен' && $pass->status() == 'Пропущен'){
      $this->clearAccountFrozenRate($account, $pass->price);
    }
    $balance = $account->balance - $account->frozen;
    if($balance < $pass->rate->price){
      $message = $this->errorAccountBalance($account->id, $pass->project->owner->fio, $balance, $pass->rate->price, $pass->project->id);
      return new JsonResponse($message, 422);
    }
    $this->updateAccountFrozen($pass, $account);
    $pass->save();
    $temporary = $pass->type()->first();
    if($request->type == 'today'){
      $temporary->entry = Carbon::now()->format('d.m.Y');
    }else{
      $temporary->entry = Carbon::now()->addDay()->format('d.m.Y');
    }
    $temporary->save();
    if($this->createPassLog('Повтор пропуска', $pass)){
      $this->createPassLog('Ожидает приезда', $pass);
    }
    return $request->wantsJson()
                ? new JsonResponse($pass->id, 200)
                : $pass;
  }

  public function deleteFromList(Request $request){
    $pass = Pass::find($request->id);
    $this->createPassLog('Закрыт', $pass);

    return response()->json('200');
  }

  public function deletePass(Request $request){
    $pass = Pass::find($request->id);

    $pass->project->personal_account != null ? $account = $pass->project->personal_account : $account = $pass->project->owner->account;
    $this->clearAccountFrozenRate($account, $pass->price);

    $type = $pass->type()->first();
    $pass->delete();
    $type->delete();
    return response()->json('200');
  }

  public function createComment(Request $request){
    $pass = Pass::find($request->id);
    $this->addComentToPass($request->text, $pass);
    return response()->json('200');
  }


  private function errorAccountBalance($id, $fio,$balance, $rate, $project_id){
    $amount = $rate - $balance;
    $message = [
      'status' => 'error',
      'msg' => 'error',
      'errors' => [
                    'account' => [
                        'text' => 'На счету недостаточно средств для оформления пропуска ('.$fio.'). Доступно: '.$balance.' р. Необходимо: '.$rate.' р. Пополните минимум на: '.$amount.' р.',
                        'id' => $id,
                        'fio' => $fio,
                        'amount' => $amount,
                        'project_id' => $project_id,
                      ],
                  ]
    ];

    return $message;
  }

  private function errorEqualEntry(){
    $message = [
      'status' => 'error',
      'msg' => 'error',
      'errors' => [
                    'equal_entry' => [
                        'text' => 'На эту дату уже зарегестрирован пропуск с таким посетителем, пожалуйста смените дату!'
                      ],
                  ]
    ];

    return $message;
  }

  private function errorEqualPermanentPass(){
    $message = [
      'status' => 'error',
      'msg' => 'error',
      'errors' => [
                    'equal_entry' => [
                        'text' => 'На этот объект уже создан действующий пропуск для этого посетителя. Пожалуйста смените посетителя, чтобы продолжить!'
                      ],
                  ]
    ];

    return $message;
  }

}
