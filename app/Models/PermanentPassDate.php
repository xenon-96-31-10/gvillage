<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PermanentPassDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'enry',
        'exit',
        'pass_id'
    ];

    protected $dates = ['entry', 'exit'];

    public function setEntryAttribute ($date){
      $this->attributes['entry'] = Carbon::parse($date);
    }

    /**
     * @return mixed
     */
    public function pass()
    {
      return $this->belongsTo(PermanentPass::class, 'pass_id');
    }
}
