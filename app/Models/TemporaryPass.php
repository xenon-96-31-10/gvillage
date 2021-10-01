<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class TemporaryPass extends Model
{
    use HasFactory;

    protected $fillable = [
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

    public function pass()
    {
      return $this->morphMany(Pass::class, 'pass');
    }

    public function visitor(){
      return $this->morphTo();
    }

}
