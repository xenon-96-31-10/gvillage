<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'balance',
        'profile_id',
    ];

    /**
     * @return mixed
     */
    public function profile()
    {
      return $this->belongsTo(Profile::class);
    }

    public function log(){
      return $this->hasMany(PersonalAccountLog::class, 'account_id')->orderBy('id','desc');
    }
}
