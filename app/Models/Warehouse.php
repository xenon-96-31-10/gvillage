<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'organization_id',
    ];

    /**
     * @return mixed
     */
    public function equipments()
    {
      return $this->hasMany(Equipment::class);
    }
    
    /**
     * @return mixed
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
