<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounterValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'counter_id',
    ];

    /**
     * @return mixed
     */
    public function counter()
    {
      return $this->belongsTo(Counter::class);
    }
}
