<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'file_name',
        'profile_id',
    ];

    public function profile()
    {
      return $this->belongsTo(Profile::class);
    }
}
