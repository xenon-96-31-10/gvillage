<?php
namespace App\Traits;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Car;
use App\Models\Profile;
use App\Models\Mechanizm;
use App\Models\Avatar;
use App\Models\Document;
use App\Models\Pass;
use App\Models\PassLog;
use App\Models\PersonalAccount;
use App\Models\PersonalAccountLog;
use App\Models\PassRatePlan;
use App\Models\PassRate;
use App\Models\Project;
use App\Models\ProjectGroup;
use App\Models\PassComment;
use App\Models\TemporaryPass;
use App\Models\PermanentPass;
use Illuminate\Support\Str;

use App\Notifications\NewPassRequest;
use Storage;
use Carbon\Carbon;

trait CreateItems
{
  public function createProfile($data){

    $profile = new Profile();

    $surname = $data['surname'];
    $name = $data['name'];
    if(isset($data['lastname'])){
      $lastname = $data['lastname'];
      $fio = $surname.' '.$name.' '.$lastname;
    }else{
      $lastname = 'Не указано';
      $fio = $surname.' '.$name;
    }

    if(isset($data['phone'])){
      $phone = $data['phone'];
    }else{
      $phone = NULL;
    }

    if(isset($data['email'])){
      $email = $data['email'];
    }else{
      $email = NULL;
    }

    if(isset($data['dateofbirth'])){
      $profile->dateofbirth = $data['dateofbirth'];
    }
    if(isset($data['sex'])){
      $sex = $data['sex'];
    }else{
      $sex = 'Не указано';
    }
    if(isset($data['sex'])){
      $sex = $data['sex'];
    }else{
      $sex = 'Не указано';
    }
    if(isset($data['address'])){
      $address = $data['address'];
    }else{
      $address = 'Не указано';
    }
    if(isset($data['role'])){
      $role = $data['role'];
    }else{
      $role = 'По умолчанию';
    }
    $profile->fio = $fio;
    $profile->sex = $sex;
    $profile->phone = $phone;
    $profile->email = $email;
    $profile->role = $role;
    $profile->address = $address;
    $profile->save();

    return $profile;
  }

  public function createProject($data){
    $project = new Project();
    $project->address = $data['address'];
    $project->name = !isset($data['name']) ? 'Объект '.random_int(1000, 9999) : $data['name'];
    $project->description = !isset($data['description']) ? 'Без описания' : $data['description'];
    $project->totalarea = $data['totalarea'];
    $project->livingarea = $data['livingarea'];

    return $project;
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
    $avatar->file_name = $filename;
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

  public function createDocument($profile, $doc, $name){
    Storage::makeDirectory('public/profiles/'.$profile->id.'/');
    $name == "Паспорт" ? $filename = "passport." : $filename = "document".random_int(10000, 99999).".";
    $filename .= $doc->getClientOriginalExtension();
    $path = Storage::putFileAs('public/profiles/'.$profile->id.'/documents' , $doc, $filename);
    $doc = new Document();
    $doc->path = $path;
    $doc->file_name = $filename;
    $doc->name = $name;
    $doc->profile()->associate($profile)->save();
  }

  public function createCar($data){
    $number = $data['number'];
    $car = new Car();
    if(isset($data['model'])){
      $model = $data['model'];
    }else{
      $model = 'Не указано';
    }
    if(isset($data['type'])){
      $type = $data['type'];
    }else{
      $type = 'Постоянное';
    }

    if(isset($data['body'])){
      $body = $data['body'];
    }else{
      $body = 'Легковое';
    }

    if(isset($data['color'])){
      $color = $data['color'];
    }else{
      $color = 'Не указано';
    }

    $car->model = $model;
    $car->number = $number;
    $car->type = $type;
    $car->body = $body;
    $car->color = $color;
    $car->save();
    return $car;
  }

  public function createMechanizm($data){
    $number = $data['number'];
    $mechanizm = new Mechanizm();
    if(isset($data['model'])){
      $model = $data['model'];
    }else{
      $model = 'Не указано';
    }
    if(isset($data['type'])){
      $type = $data['type'];
    }else{
      $type = 'Не указано';
    }
    if(isset($data['name'])){
      $name = $data['name'];
    }else{
      $name = 'Не указано';
    }
    if(isset($data['color'])){
      $color = $data['color'];
    }else{
      $color = 'Не указано';
    }

    $mechanizm->model = $model;
    $mechanizm->number = $number;
    $mechanizm->type = $type;
    $mechanizm->name = $name;
    $mechanizm->color = $color;
    $mechanizm->save();
    return $mechanizm;
  }

  public function createTemporaryPass($data){
    $visitor = $this->takeVisitor($data['visitor']);
    $temporary = new TemporaryPass();
    $temporary->entry = $data['temporary']['entry'];

    if($visitor->temporary_passes->where('entry', $temporary->entry)->count() > 0){
      foreach ($visitor->temporary_passes->where('entry', $temporary->entry) as $ticket) {
        if($ticket->pass->first()->project->id == $data['project'] && $ticket->exit == null){
          return 'error';
        }
      }
    }
    $temporary->type = $data["visitor"]['type'];
    $temporary->visitor()->associate($visitor)->save();

    return $temporary;
  }

  public function createPermanentPass($data){
    $permanent = new PermanentPass();

    $visitor = $this->takeVisitor($data['visitor']);
    if(isset($data['permanent']['timetable'])){
      $permanent->timetable = $data['permanent']['timetable'][0];
    }else{
      $permanent->timetable = 'Ежедневно';
    }

    $permanent->entry = $data['permanent']['entry'];

    if($visitor->permanent_passes->count() > 0){
      foreach ($visitor->permanent_passes as $ticket) {
        if($ticket->pass->first()->project->id == $data['project'] && $ticket->exit >= Carbon::now()->format('d.m.Y')){
          return 'error';
        }
      }
    }

    if($data['permanent']['exit'] == 'полгода'){
      $permanent->exit = Carbon::now()->addMonths(6);
    }else{
      $permanent->exit = Carbon::now()->addMonth();
    }

    $permanent->type = $data["visitor"]['type'];

    $permanent->visitor()->associate($visitor)->save();

    return $permanent;
  }

  public function addComentToPass($text, $pass){
    $author = auth()->user();
    $comment = new PassComment();
    $comment->text = $text;
    $comment->author()->associate($author);
    $comment->pass()->associate($pass)->save();
  }

  public function createPassLog($status, $pass){
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

    if($log->pass()->associate($pass)->save()){
      return true;
    }
  }

  public function createPersonalAccount($data){
    $profile = Profile::find($data['id']);
    $acc = new PersonalAccount();
    $acc->number = $data['number'];
    $acc->balance = $data['amount'];
    $acc->frozen = 0;
    $acc->profile()->associate($profile)->save();
    return $acc;
  }

  public function createPersonalAccountLog($status, $total, $account){;
    $log = new PersonalAccountLog();
    $log->status = $status;
    $log->total = $total;
    $log->account()->associate($account)->save();
  }

  public function createNotificationsForRequestPass($project, $applicant){
    $users = $project->users;
    foreach ($users as $user) {
      if($user->hasRole('guardpost') || $user->hasRole('dispatcher') ){
        $user->notify(new NewPassRequest($applicant));
      }
    }
  }

  private function takeVisitor($data){
    if($data['type'] == 'cars'){
      $visitor = Car::find($data['id']);
    }elseif($data['type'] == 'profiles'){
      $visitor = Profile::find($data['id']);
    }else{
      $visitor = Mechanizm::find($data['id']);
    }
    return $visitor;
  }

  public function createPassRatePlan($data){
    $organization = auth()->user()->get_organization();

    if($data['default']){
      $def_plan = $organization->default_rate_plan()->first();
      $def_plan->default = 0;
      $def_plan->save();
    }

    $plan = new PassRatePlan();
    $plan->name = !isset($data['name']) ? 'Тарифный план №'.random_int(1000, 9999) : $data['name'];
    $plan->default = $data['default'];
    $plan->organization()->associate($organization)->save();

    if($data['selectedProjects'] != null){
      $projects = Project::find($data['selectedProjects']);
      $plan->projects()->saveMany($projects);
    }

    if($data['selectedGroups'] != null){
      $project_groups = ProjectGroup::find($data['selectedGroups']);
      $plan->project_groups()->saveMany($project_groups);
    }

    foreach ($data['temporary'] as $rate) {
      $this->createPassRate('temporary', $rate, $plan);
    }

    foreach ($data['permanent'] as $rate) {
      $this->createPassRate('permanent', $rate, $plan);
    }

    return $plan;
  }

  private function createPassRate($pass, $data, $plan){
    $rate = new PassRate();
    $rate->pass = $pass;
    $rate->visitor_type = $data['visitor_type'];
    $rate->visitor = $data['visitor'];
    $rate->time = $pass == 'temporary' ? "1 въезд" : $data['time'];
    $rate->role = $data['role'];
    $rate->description = isset($data['description']) ? $data['description'] : 'Без заметок';
    $rate->price =  $data['price'];
    $rate->pass_rate_plan()->associate($plan)->save();
  }

}
