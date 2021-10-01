<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuardPostLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'guard_post_id',
        'guard_post_enter_id',
        'guard_id'
    ];

    public function guard_post()
    {
      return $this->belongsTo(GuardPost::class);
    }

    public function guarder()
    {
      return $this->belongsTo(User::class, 'guard_id');
    }

    public function enter()
    {
      return $this->belongsTo(GuardPostEnter::class,'guard_post_enter_id');
    }


}
