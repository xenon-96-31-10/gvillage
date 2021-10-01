<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'pass',
        'visitor_type',
        'visitor',
        'role',
        'time',
        'price',
        'description',
        'pass_rate_plan_id',
    ];

    /**
     * @return mixed
     */
    public function pass_rate_plan()
    {
      return $this->belongsTo(PassRatePlan::class);
    }

    public function passes()
    {
      return $this->hasMany(Pass::class, 'rate_id');
    }

}
