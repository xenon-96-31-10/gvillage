<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalAccountLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'total',
        'account_id',
    ];

    /**
     * @return mixed
     */
    public function account()
    {
      return $this->belongsTo(PersonalAccount::class);
    }
}
