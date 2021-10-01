<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'organization_id',
    ];

    public function guard_post()
    {
      return $this->hasOne(GuardPost::class);
    }

    public function projects()
    {
      return $this->hasMany(Project::class);
    }

    public function pass_rate_plan()
    {
      return $this->belongsTo(PassRatePlan::class);
    }

    /**
     * @return mixed
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
