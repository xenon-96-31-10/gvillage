<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pass extends Model
{
    use HasFactory;


    protected $fillable = [
        'type',
        'status_pay',
        'price',
        'pass_id',
        'pass_type',
        'project_id',
        'author_id',
        'applicant_id',
        'rate_id',
    ];


    /**
     * @return mixed
     */
    public function log()
    {
      return $this->hasMany(PassLog::class);
    }

    /**
     * @return mixed
     */
    public function status()
    {
      return $this->hasMany(PassLog::class)->orderBy('id', 'desc')->first()->status;
    }

    /**
     * @return mixed
     */
    public function log_description()
    {
      return $this->hasMany(PassLog::class)->latest()->first()->description;
    }

    /**
     * @return mixed
     */
    public function project()
    {
      return $this->belongsTo(Project::class);
    }

    /**
     * @return mixed
     */
    public function author()
    {
      return $this->belongsTo(User::class);
    }

    /**
     * @return mixed
     */
    public function rate()
    {
      return $this->belongsTo(PassRate::class);
    }

    /**
     * @return mixed
     */
    public function applicant()
    {
      return $this->belongsTo(Profile::class);
    }

    /**
     * @return mixed
     */
    public function type()
    {
      return $this->morphTo('pass');
    }

    /**
     * @return mixed
     */
    public function comments()
    {
      return $this->hasMany(PassComment::class)->orderBy('created_at', 'desc');
    }
}
