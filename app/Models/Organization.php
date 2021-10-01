<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
    ];

    public function users()
    {
      return $this->belongsToMany(User::class,'organization_user');
    }

    public function profiles()
    {
      return $this->belongsToMany(Profile::class,'organization_profile');
    }

    public function cars()
    {
      return $this->belongsToMany(Car::class,'organization_car');
    }

    public function mechanizms()
    {
      return $this->belongsToMany(Mechanizm::class,'organization_mechanizm');
    }

    public function projects()
    {
      return $this->hasMany(Project::class);
    }

    public function families()
    {
      return $this->hasMany(Family::class);
    }

    public function warehouses()
    {
      return $this->hasMany(Warehouse::class);
    }

    public function guard_posts()
    {
      return $this->hasMany(GuardPost::class);
    }

    public function rate_plans(){
      return $this->hasMany(PassRatePlan::class)->orderBy('id', 'desc');
    }

    public function default_rate_plan(){
      return $this->hasMany(PassRatePlan::class)->where('default', 1);
    }

    public function project_groups()
    {
      return $this->hasMany(ProjectGroup::class);
    }
}
