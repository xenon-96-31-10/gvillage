<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mechanizm extends Model
{
    use HasFactory;

    protected $fillable = [
        'model',
        'number',
        'name',
        'color',
        'tax_id',
        'owner_id',
    ];

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
      return $this->belongsToMany(Organization::class,'organization_mechanizm');
    }
}
