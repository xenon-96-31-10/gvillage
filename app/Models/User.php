<?php

namespace App\Models;

use App\Traits\HasRolesAndPermissions;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPassword;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRolesAndPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'login',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendPasswordResetNotification($token)
    {
       $this->notify(new ResetPassword($token));
    }

    /**
     * @return mixed
     */
    public function profile()
    {
       return $this->hasOne(Profile::class);
    }

    /**
     * @return mixed
     */
    public function telegram()
    {
       return $this->hasOne(Telegram::class);
    }

    /**
     * @return mixed
     */
    public function profileAvatar()
    {
       return $this->hasOneThrough(Avatar::class, Profile::class);
    }

    /**
     * @return mixed
     */
    public function profileAccount()
    {
       return $this->hasOneThrough(PersonalAccount::class, Profile::class);
    }

    /**
     * @return mixed
     */
    public function log()
    {
      return $this->hasMany(Log::class);
    }

    /**
     * @return mixed
     */
    public function organization()
    {
        return $this->belongsToMany(Organization::class,'organization_user');
    }

    /**
     * @return mixed
     */
    public function get_organization()
    {
        return $this->belongsToMany(Organization::class,'organization_user')->first();
    }

    /**
     * @return mixed
     */
    public function activities()
    {
        return $this->belongsToMany(Activity::class,'activity_user');
    }

    /**
     * @return mixed
     */
    public function equipments()
    {
        return $this->belongsToMany(Equipment::class,'equipment_user');
    }

    public function guard_post()
    {
      return $this->belongsToMany(GuardPost::class,'guard_post_user');
    }

    public function get_guard_post()
    {
      return $this->belongsToMany(GuardPost::class,'guard_post_user')->first();
    }

    public function get_guard_status()
    {
      return $this->hasMany(GuardPostLog::class, 'guard_id')->latest()->first();
    }
}
