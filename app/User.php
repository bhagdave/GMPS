<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\UseUuid;

class User extends Authenticatable
{
    use Notifiable;
    use UseUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'organisation_id', 'synapse_user_id', 'synapse_access_token', 'synapse_device_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function organisation(){
        return $this->belongsTo(Organisation::class);
    }
    public function owns(){
        return $this->hasMany(Group::class, 'created_user_id');
    }

    public function groups(){
        return $this->belongsToMany(Group::class)
            ->withTimestamps()
            ->withPivot('type');
    }

}
