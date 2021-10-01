<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'value',
        'totalvalue',
        'warehouse_id',
    ];

    /**
     * @return mixed
     */
    public function users()
    {
      return $this->belongsToMany(User::class,'equipment_user');
    }

    /**
     * @return mixed
     */
    public function warehouse()
    {
      return $this->belongsTo(Warehouse::class);
    }
}
