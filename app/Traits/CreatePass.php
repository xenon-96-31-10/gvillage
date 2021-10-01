<?php
namespace App\Traits;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Pass;
use App\Models\TemporaryPass;
use App\Models\PermanentPass;
use App\Models\PassLog;
use App\Models\PassComment;
use App\Models\Car;
use App\Models\Profile;
use App\Models\Avatar;
use App\Models\Document;
use App\Models\Mechanizm;
use App\Models\Tax;


use Storage;
use Carbon\Carbon;

trait CreatePass
{

  public function createLog($status, $pass){
    $author = auth()->user();
    $log = new PassLog();
    $log->status = $status;
    if($author->hasRole('guard-post')){
      $post_log = $author->get_guard_status();
      $guard_post = $post_log->guard_post;
      $guard_post_enter = $post_log->enter;
      $log->description = 'Пост: '.$guard_post->name.' Вход: '. $guard_post_enter->name;
    }elseif($author->hasRole('dispatcher')){
      $log->description = 'Диспетчер';
    }else{
      $log->description = 'Охрана объекта';
    }
    $log->author()->associate($author);
    $log->pass()->associate($pass)->save();
  }

  public function addComent($text, $pass){
    $author = auth()->user();
    $comment = new PassComment();
    $comment->text = $text;
    $comment->author()->associate($author);
    $comment->pass()->associate($pass)->save();
  }

  public function createTemporaryPass($data){
    if(auth()->user()->hasRole('owner')){
      $applicant = auth()->user()->profile;
    }else{
      $applicant = $this->createHuman($data['applicantid'], $data['applicantfio']);
    }

    $temporary = new TemporaryPass();

    $temporary->entry = $data["date"];
    $temporary->type = $data["visitor"];
    if($data["visitor"] == "car"){
      $visitor = $this->createCar($data);
    }elseif($data["visitor"] == "human"){
      $visitor = $this->createHuman($data['humanid'], $data['humanfio']);

      if($data['avatar'] != 'null'){$this->createAvatar($visitor, $data['avatar']);}
      if($data['passport'] != 'null'){$this->createPassport($visitor, $data['passport']);}
    }elseif($data["visitor"] == "mechanizm"){
      $visitor = $this->createMechanizm($data);
    }

    $temporary->applicant()->associate($applicant);

    $temporary->visitor()->associate($visitor)->save();

    return $temporary;
  }

  public function repeatTemporary(Request $request){
    $pass = Pass::find($request->id);
    $temporary = $pass->type()->first();
    $temporary->entry = Carbon::now();
    $temporary->exit = null;
    $temporary->save();
    $this->createLog('Повтор пропуска', $pass);
    $this->createLog('Ожидает приезда', $pass);
    return response()->json('200');
  }

  public function createPermanentPass($data){
    $permanent = new PermanentPass();

    if(isset($data['timetable'])){
      $permanent->timetable = $data['timetable'];
    }else{
      $permanent->timetable = 'Ежедневно';
    }

    $permanent->type = $data["visitor"];
    if($data["visitor"] == "car"){
      $visitor = $this->createCar($data);
    }elseif($data["visitor"] == "human"){
      $visitor = $this->createHuman($data['humanid'], $data['humanfio']);

      if($data['avatar'] != 'null'){$this->createAvatar($visitor, $data['avatar']);}
      if($data['passport'] != 'null'){$this->createPassport($visitor, $data['passport']);}
    }elseif($data["visitor"] == "mechanizm"){
      $visitor = $this->createMechanizm($data);
    }

    $permanent->visitor()->associate($visitor)->save();

    return $permanent;
  }

  public function createCar($data){
    $id = $data['carid'];
    $number = $data['carnumber'];
    $owner = $data['owner'];

    if($id != 'new'){
      $car = Car::find($id);
    }else{
      $car = new Car();
      if($data['type'] == 'permanent'){
        $model = $data['carmodel'];
        $type = 'Постоянное';
        $body = $data['carbody'];
        if(isset($data['carcolor'])){
          $color = $data['carcolor'];
        }else{
          $color = 'Не указано';
        }
      }else{
        $model = 'Не указано';
        $type = 'Временное';
        $body = 'Легковое';
        $color = 'Не указано';
      }

      $car->model = $model;
      $car->number = $number;
      $car->type = $type;
      $car->body = $body;
      $car->color = $color;
      $car->save();
    }
    if($owner == 'true'){
      $ownerid = $data['ownerid'];
      $ownerfio = $data['ownerfio'];
      $profile = $this->createHuman($ownerid, $ownerfio);
      $car->owner()->associate($profile)->save();
    }
    return $car;
  }

  public function createMechanizm($data){

    $id = $data['mechanizmid'];
    $number = $data['mechanizmnumber'];
    $owner = $data['owner'];

    if($id != 'new'){
      $mechanizm = Mechanizm::find($id);
    }else{
      $mechanizm = new Mechanizm();
      $mechanizm->model = $data['mechanizmmodel'];
      $mechanizm->number = $number;
      $tax = Tax::find($data['mechanizmtype']);
      if($tax->type == "Спецтехника"){
        $mechanizm->name = $data['mechanizmname'];
      }else{
        $mechanizm->name = $tax->type;
      }
      if(isset($data['mechanizmcolor'])){
        $mechanizm->color = $data['mechanizmcolor'];
      }else{
        $mechanizm->color = 'Не указано';
      }
      $mechanizm->tax()->associate($tax)->save();;
    }
    if($owner == 'true'){
      $ownerid = $data['ownerid'];
      $ownerfio = $data['ownerfio'];
      $profile = $this->createHuman($ownerid, $ownerfio);
      $mechanizm->owner()->associate($profile)->save();
    }
    return $mechanizm;

  }

  public function createHuman($id, $fiop){
    if($id != 'new'){
      $profile = Profile::find($id);
    }else{
      $profile = new Profile();
      $profile->fio = $fiop;
      $fio = explode(" ", $fiop);
      $profile->surname = $fio[0];
      $profile->name = $fio[1];
      $profile->lastname = $fio[2];
      $profile->sex = 'Не указано';
      $profile->save();
    }

    return $profile;
  }

  public function createAvatar($profile, $avatar){
    Storage::makeDirectory('public/profiles/'.$profile->id.'/');
    $filename = "avatar.";
    $filename .= $avatar->getClientOriginalExtension();
    $path = Storage::putFileAs('public/profiles/'.$profile->id.'/avatar' , $avatar, $filename);
    if($profile->avatar != null){
      $avatar = $profile->avatar;
    }else{
      $avatar = new Avatar();
      $avatar->profile()->associate($profile);
    }
    $avatar->path = $path;
    $avatar->save();

  }

  public function createPassport($profile, $passport){
    Storage::makeDirectory('public/profiles/'.$profile->id.'/');
    $filename = "passport.";
    $filename .= $passport->getClientOriginalExtension();
    $path = Storage::putFileAs('public/profiles/'.$profile->id.'/documents' , $passport, $filename);
    $passport = new Document();
    $passport->path = $path;
    $passport->name = "Паспорт";
    $passport->profile()->associate($profile)->save();
  }
}
