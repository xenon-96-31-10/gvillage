<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Telegram extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'user_id',
    ];

    /**
     * @return mixed
     */
    public function user()
    {
      return $this->belongsTo(User::class);
    }
}
