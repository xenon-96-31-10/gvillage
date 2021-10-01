<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pass;
use App\Traits\CreateItems;
use Storage;
use Carbon\Carbon;

class PassController extends Controller
{
    use CreateItems;

    public function getFullPassList(Request $request){
      $user = auth()->user();
      $profile = auth()->user()->profile;
      if($user->hasRole('guard-post')){
        $projects = $user->get_guard_post()->project_group->projects()->orderBy('id', 'desc')->get();
      }else{
        $projects = $profile->projects()->orderBy('id', 'desc')->get();
      }

      $expectedpasses = array();
      $arrivedpasses = array();
      $departedpasses = array();
      $stayedpasses = array();
      $unusedpasses = array();
      $permanentpasses = array();

      foreach ($projects as $project) {
        $passes = $project->passes;
        foreach ($passes as $pass) {
          if($pass->rate != null){

            if($pass->type()->first()->type == 'profiles'){
              $visitor = $pass->type()->first()->visitor->fio;
            }else{
              $visitor = $pass->type()->first()->visitor->number;
            }

            if($pass->type == 'Временный пропуск'){
              $entry = Carbon::parse($pass->type()->first()->entry);
              $now = Carbon::parse(Carbon::now()->format('d.m.Y'));
              $requestDate = Carbon::parse($request->date);

              if($user->hasRole('guard-post')){
                if($pass->status() == 'Ожидает приезда'){
                  $switch = 'Прибудет';
                }elseif($pass->status() == 'Пропущен'){
                  $switch = 'Пропущен';
                }elseif($pass->status() == 'На территории' || $pass->status() == 'На объекте' || $pass->status() == 'Отбыл с объекта'){
                  $switch = 'Прошел контроль';
                }elseif($pass->status() == 'Отбыл с территории'){
                  $switch = 'Отбыл';
                }else{
                  $switch = 'Закрыт';
                }
              }else{
                if($pass->status() == 'Ожидает приезда' || $pass->status() == 'На территории'){
                  $switch = 'Прибудет';
                }elseif($pass->status() == 'Пропущен'){
                  $switch = 'Пропущен';
                }elseif($pass->status() == 'На объекте'){
                  $switch = 'Прошел контроль';
                }elseif($pass->status() == 'Отбыл с объекта'){
                  $switch = 'Отбыл';
                }else{
                  $switch = 'Закрыт';
                }
              }

              if($switch == 'Прибудет'){
                if($entry < $now){
                  if($pass->status() != 'Пропущен'){
                    $this->createPassLog('Пропущен', $pass);
                  }
                  $unusedpasses[] = [
                    'id' => $pass->id,
                    'type' => $pass->type()->first()->type,
                    'visitor' => $visitor,
                    'date' => $entry,
                  ];
                }else{
                  if($entry == $requestDate){
                    $expectedpasses[] = [
                      'id' => $pass->id,
                      'type' => $pass->type()->first()->type,
                      'visitor' => $visitor,
                      'date' => $entry,
                    ];
                  }
                }
              }elseif($switch == 'Пропущен'){
                $unusedpasses[] = [
                  'id' => $pass->id,
                  'type' => $pass->type()->first()->type,
                  'visitor' => $visitor,
                  'date' => $entry,
                ];

              }elseif($switch == 'Прошел контроль'){

                if($entry < $now){
                  //На территории
                  $stayedpasses[] = [
                    'id' => $pass->id,
                    'type' => $pass->type()->first()->type,
                    'visitor' => $visitor,
                    'date' => $entry,
                  ];
                }else{
                  //Остались на территории
                  $arrivedpasses[] = [
                    'id' => $pass->id,
                    'type' => $pass->type()->first()->type,
                    'visitor' => $visitor,
                    'date' => $entry,
                  ];
                }
              }elseif($switch == 'Отбыл'){
                //отбыли
                $departedpasses[] = [
                  'id' => $pass->id,
                  'type' => $pass->type()->first()->type,
                  'visitor' => $visitor,
                  'date' => $pass->type()->first()->exit,
                ];

              }

            }else{
              if($pass->status() != 'Закрыт'){
                $permanentpasses[] = [
                  'id' => $pass->id,
                  'type' => $pass->type()->first()->type,
                  'visitor' => $visitor,
                  'status' => $pass->status(),
                  'timetable' => $pass->type()->first()->timetable,
                ];
              }

            }
          }


        }

      }

      $data = [
        'expectedpasses' => $expectedpasses,
        'arrivedpasses' => $arrivedpasses,
        'departedpasses' => $departedpasses,
        'unusedpasses' => $unusedpasses,
        'stayedpasses' => $stayedpasses,
        'permanentpasses' => $permanentpasses,
      ];

      return response()->json($data);
    }

    public function getReportPassList(Request $request){
      $user = auth()->user();
      $profile = auth()->user()->profile;
      if($user->hasRole('guard-post')){
        $projects = $user->get_guard_post()->project_group->projects()->orderBy('id', 'desc')->get();
      }else{
        $projects = $profile->projects()->orderBy('id', 'desc')->get();
      }

      $temporarypasses = array();
      $permanentpasses = array();
      $humanpasses = array();
      $passData = [
        'temporary' => [
          'title' => 'Временные пропуски',
          'cars' => 0,
          'profiles' => 0,
          'mechanizms' => 0,
          'counter' => 0,
          'unpay' => [
            'count' => 0,
            'price' => 0,
          ],
          'pay' => [
            'count' => 0,
            'price' => 0,
            'free' => 0,
          ],
        ],
        'permanent' => [
          'title' => 'Постоянные пропуски',
          'cars' => 0,
          'profiles' => 0,
          'mechanizms' => '-',
        ],
        'expectedpasses' => [
          'title' => 'Ожидают приезда',
          'cars' => 0,
          'profiles' => 0,
          'mechanizms' => 0,
        ], // ожидают приезда
        'stayedpasses' => [
          'title' => 'На территории / объекте',
          'cars' => 0,
          'profiles' => 0,
          'mechanizms' => 0,
        ],//на территории
        'departedpasses' => [
          'title' => 'Отбыли с территории / объекта',
          'cars' => 0,
          'profiles' => 0,
          'mechanizms' => 0,
        ], // отбыли
        'unusedpasses' => [
          'title' => 'Пропущены / Не явка',
          'cars' => 0,
          'profiles' => 0,
          'mechanizms' => 0,
        ],//пропущен
      ];


      foreach ($projects as $project) {
        if($request->own == 'true'){
          $passes = $project->passes->where('author_id', $user->id);
        }else{
          $passes = $project->passes;
        }
        foreach ($passes as $pass) {

          $type = $pass->type()->first()->type;
          $entry = $pass->type()->first()->entry;

          if($entry == $request->date || $pass->type == 'Постоянный пропуск'){
            $passData['temporary']['counter'] += 1;
            if($pass->status_pay == "Не оплачен"){
              $passData['temporary']['unpay']['count'] += 1;
              $passData['temporary']['unpay']['price'] += $pass->rate->price;
            }else{
              $passData['temporary']['pay']['count'] += 1;
              $passData['temporary']['pay']['price'] += $pass->rate->price;
              if($pass->rate->price == 0){
                $passData['temporary']['pay']['free'] += 1;
              }
            }
            if($pass->status() == 'Ожидает приезда'){
              $passData['expectedpasses'][''.$type] +=1;
            }elseif($pass->status() == 'На территории' || $pass->status() == 'На объекте'){
              $passData['stayedpasses'][''.$type] +=1;
            }elseif($pass->status() == 'Отбыл с территории' || $pass->status() == 'Отбыл с объекта'){
              $passData['departedpasses'][''.$type] +=1;
            }elseif($pass->status() == 'Пропущен'){
              $passData['unusedpasses'][''.$type] +=1;
            }
          }

          if($pass->type == 'Временный пропуск'){

            if($entry == $request->date){
              if($type == 'profiles'){
                $humanpasses[] = [
                  'id' => $pass->id,
                  'type' => $pass->type()->first()->type,
                  'status' => $pass->status(),
                  'visitor' => $pass->type()->first()->visitor,
                ];
                $passData['temporary']['profiles'] += 1;
              }else{
                if($type == 'cars'){
                  $passData['temporary']['cars'] += 1;
                }else{
                  $passData['temporary']['mechanizms'] += 1;
                }
                $temporarypasses[] = [
                  'id' => $pass->id,
                  'type' => $pass->type()->first()->type,
                  'status' => $pass->status(),
                  'visitor' => $pass->type()->first()->visitor,
                ];
              }
            }


          }else{
            if($type == 'cars'){
              $passData['permanent']['cars'] += 1;
            }else{
              $passData['permanent']['profiles'] += 1;
            }

            $permanentpasses[] = [
              'id' => $pass->id,
              'type' => $pass->type()->first()->type,
              'status' => $pass->status(),
              'visitor' => $pass->type()->first()->visitor,
            ];
          }

        }
      }

      $data = [
        'temporarypasses' => $temporarypasses,
        'humanpasses' => $humanpasses,
        'permanentpasses' => $permanentpasses,
        'passData' => $passData,
      ];

      return response()->json($data);

    }

    public function getPass(Request $request){
      $pass = Pass::find($request->id);
      $type = $pass->type;

      $visitor_type = $pass->type()->first()->type;
      $owner = null;
      $applicant = $pass->applicant;
      $avatar = 'Фото отсутствует';
      $passport = 'Паспорт отсутствует';
      $visitor = $pass->type()->first()->visitor;
      $project = $pass->project;
      $rate = $pass->rate;
      $comments = [];
      $logs = [];
      $dates = [];

      if($type == 'Временный пропуск'){
        $dates = [
          'entry' => $pass->type()->first()->entry,
          'exit' => $pass->type()->first()->exit,
        ];
      }else{
        $dates = [
          'timetable' => $pass->type()->first()->timetable,
        ];
      }
      if($visitor_type == 'profiles'){
        if($visitor->avatar != null){
          $avatar = Storage::url($visitor->avatar->path);
        }
        if($visitor->passport() != null){
          $passport = Storage::url($visitor->passport()->path);
        }

      }else{
        if($visitor->owner != null){
          $owner = $visitor->owner;
          if($owner->avatar != null){
            $avatar = Storage::url($owner->avatar->path);
          }
          if($owner->passport() != null){
            $passport = Storage::url($owner->passport()->path);
          }
        }
      }

      foreach ($pass->comments as $comment) {
        $comments[] = [
          'text' => $comment->text,
          'author' => $comment->author->profile,
        ];
      }

      foreach ($pass->log as $log) {

        $logs[] = [
          'id' => $log->id,
          'status' => $log->status,
          'description' => $log->description,
          'author' => $log->author->profile->fio,
          'role' => $log->author->roles()->first()->name,
          'created_at' => $log->created_at->format('d.m.Y'),
        ];
      }



      $data = [
        'id' => $request->id,
        'type' => $type,
        'dates' => $dates,
        'visitorType' => $visitor_type,
        'visitor' => $visitor,
        'plan' => $rate->pass_rate_plan->name,
        'rate' => $rate,
        'owner' => $owner,
        'avatar' => $avatar,
        'passport' => $passport,
        'status' => $pass->status(),
        'status_pay' => $pass->status_pay,
        'author' => $pass->author->profile->fio,
        'created_at' => $pass->created_at->format('d.m.Y'),
        'applicant' => $applicant,
        'project' => $project,
        'comments' => $pass->comments,
        'logs' => $logs,
      ];

      return response()->json($data);

    }
}
