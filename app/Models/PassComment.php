<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassComment extends Model
{
    use HasFactory;


    protected $fillable = [
        'text',
        'pass_id',
        'author_id'
    ];

    /**
     * @return mixed
     */
    public function pass()
    {
      return $this->belongsTo(Pass::class);
    }

    /**
     * @return mixed
     */
    public function author()
    {
      return $this->belongsTo(User::class);
    }
}
