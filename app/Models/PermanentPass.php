<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
class PermanentPass extends Model
{
    use HasFactory;


    protected $fillable = [
        'timetable',
        'entry',
        'exit',
        'type',
        'visitor_type',
        'visitor_id',
    ];

    protected $dates = ['entry', 'exit'];

    public function setEntryAttribute ($date){
      $this->attributes['entry'] = Carbon::parse($date);
    }

    public function setExitAttribute ($date){
      $this->attributes['exit'] = Carbon::parse($date);
    }

    public function getEntryAttribute ($date){
      return Carbon::parse($date)->format('d.m.Y');
    }

    public function getExitAttribute ($date){
      if($date != null){
        return Carbon::parse($date)->format('d.m.Y');
      }
    }

    /**
     * @return mixed
     */
    public function pass()
    {
      return $this->morphMany(Pass::class, 'pass');
    }

    /**
     * @return mixed
     */
    public function visitor(){
      return $this->morphTo();
    }

    /**
     * @return mixed
     */
    public function dates()
    {
      return $this->hasMany(PermanentPassDate::class, 'pass_id');
    }
}
