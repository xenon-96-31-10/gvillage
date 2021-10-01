<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'fio',
        'phone',
        'email',
        'role',
        'code',
        'sex',
        'address',
        'dateofbirth',
        'user_id',
    ];

    protected $dates = ['dateofbirth'];

    public function setDateofbirthAttribute ($date){
      $this->attributes['dateofbirth'] = Carbon::parse($date);
    }

    public function getDateofbirthAttribute ($date){
      return Carbon::parse($date)->format('d.m.Y');
    }

    public function setEmailAttribute($value) {
      if ( empty($value) ) { // will check for empty string
      $this->attributes['email'] = NULL;
      } else {
          $this->attributes['email'] = $value;
      }
    }

    public function setPhoneAttribute($value) {
      if ( empty($value) ) { // will check for empty string
      $this->attributes['phone'] = NULL;
      } else {
          $this->attributes['phone'] = $value;
      }
    }

    /**
     * @return mixed
     */
    public function user()
    {
      return $this->belongsTo(User::class);
    }

    /**
     * @return mixed
     */
    public function cars()
    {
      return $this->hasMany(Car::class,'owner_id');
    }

    /**
     * @return mixed
     */
    public function mechanizms()
    {
      return $this->hasMany(Mechanizm::class,'owner_id');
    }

    /**
     * @return mixed
     */
    public function own_projects()
    {
      return $this->hasMany(Project::class, 'owner_id');
    }

    public function projects()
    {
      return $this->belongsToMany(Project::class, 'project_profile');
    }

    /**
     * @return mixed
     */
    public function account()
    {
       return $this->hasOne(PersonalAccount::class);
    }

    /**
     * @return mixed
     */
    public function avatar()
    {
       return $this->hasOne(Avatar::class);
    }

    /**
     * @return mixed
     */
    public function documents()
    {
      return $this->hasMany(Document::class);
    }

    public function passport()
    {
      return $this->hasMany(Document::class)->where('name', 'Паспорт')->first();
    }

    public function temporary_passes()
    {
      return $this->morphMany(TemporaryPass::class, 'visitor');
    }

    public function permanent_passes()
    {
      return $this->morphMany(PermanentPass::class, 'visitor');
    }

    public function organization()
    {
      return $this->belongsToMany(Organization::class,'organization_profile');
    }

    /**
     * @return mixed
     */
    public function get_organization()
    {
        return $this->belongsToMany(Organization::class,'organization_profile')->first();
    }

    public function group()
    {
        return $this->belongsToMany(Group::class,'group_profile');
    }

    public function family()
    {
        return $this->belongsToMany(Family::class,'family_profile');
    }
}
