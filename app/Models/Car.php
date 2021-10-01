<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'model',
        'number',
        'type',
        'body',
        'color',
        'owner_id',
    ];

    /**
     * @return mixed
     */
    public function owner()
    {
      return $this->belongsTo(Profile::class);
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
      return $this->belongsToMany(Organization::class,'organization_car');
    }
}
