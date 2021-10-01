<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'address',
        'owner_id',
        'personal_account_id',
        'totalarea',
        'livingarea',
        'project_type_id',
        'organization_id',
        'status_id',
        'project_group_id',
    ];

    public function project_status()
    {
      return $this->belongsTo(ProjectStatus::class);
    }

    public function profiles()
    {
      return $this->belongsToMany(Profile::class,'project_profile');
    }

    public function reg_profiles()
    {
      return $this->belongsToMany(Profile::class,'project_profile')->where('user_id', '!=', null);
    }

    public function owner()
    {
      return $this->belongsTo(Profile::class);
    }

    public function personal_account()
    {
      return $this->belongsTo(PersonalAccount::class);
    }

    public function counters()
    {
      return $this->hasMany(Counter::class);
    }

    public function passes()
    {
      return $this->hasMany(Pass::class);
    }

    public function project_type()
    {
      return $this->belongsTo(ProjectType::class);
    }

    /**
     * @return mixed
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function project_group()
    {
      return $this->belongsTo(ProjectGroup::class);
    }

    public function pass_rate_plan()
    {
      return $this->belongsTo(PassRatePlan::class);
    }
}
