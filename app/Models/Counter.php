<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'project_id',
    ];

    public function project()
    {
      return $this->belongsTo(Project::class);
    }

    public function values()
    {
      return $this->hasMany(CounterValue::class);
    }
}
