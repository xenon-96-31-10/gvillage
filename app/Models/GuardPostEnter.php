<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuardPostEnter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'phone',
        'guard_post_id'
    ];

    public function guard_post()
    {
      return $this->belongsTo(GuardPost::class);
    }

    /**
     * @return mixed
     */
    public function status()
    {
      return $this->hasMany(GuardPostLog::class)->latest()->first();
    }
}
