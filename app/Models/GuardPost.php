<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuardPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'project_group_id',
        'organization_id',
        'chief_id',
    ];

    public function enters()
    {
      return $this->hasMany(GuardPostEnter::class);
    }

    public function project_group()
    {
      return $this->belongsTo(ProjectGroup::class);
    }

    public function guards()
    {
      return $this->belongsToMany(User::class,'guard_post_user');
    }

    public function chief()
    {
      return $this->belongsTo(Profile::class);
    }


    /**
     * @return mixed
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }


}
