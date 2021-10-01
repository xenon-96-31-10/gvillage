<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassRatePlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'default',
        'organization_id',
    ];

    /**
     * @return mixed
     */
    public function pass_rates()
    {
        return $this->hasMany(PassRate::class);
    }

    /**
     * @return mixed
     */
    public function organization()
    {
      return $this->belongsTo(Organization::class);
    }

    public function projects()
    {
      return $this->hasMany(Project::class);
    }

    public function project_groups()
    {
      return $this->hasMany(ProjectGroup::class);
    }
}
