<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'author_id',
        'pass_id',
    ];

    public function pass()
    {
      return $this->belongsTo(Pass::class);
    }

    public function author()
    {
      return $this->belongsTo(User::class);
    }
}
