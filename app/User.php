<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'server_id', 'password', 'user_id', 'username', 'fullname', 'firstname', 'lastname',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token', 'password'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
//    public function setPasswordAttribute($password)
//    {
//        if ( !empty($password) ) {
//            $this->attributes['password'] = bcrypt($password);
//        }
//    }

    public function setUserIdAttribute($value)
    {
        $this->attributes['user_id'] = intval($value);
    }

    public function clients()
    {
        return $this->hasMany(Client::class, 'webshop_user_id', 'id');
    }

    public function clientsWithProducts()
    {
        return $this->hasMany(Client::class, 'webshop_user_id', 'id')->with('products');
    }

    public function permission()
    {
        return $this->hasOne(Permission::class);
    }

//    public function stores()
//    {
//        return $this->hasManyThrough(
//            Store::class,  // Posts table
//            Client::class,  // Users table
//            'webshop_user_id', // Foreign key on users table...
//            'user_id', // Foreign key on posts table...
//            'id', // Local key on countries table...
//            'id' // Local key on users table...
//        );
//    }

//    public function store()
//    {
//        return $this->belongsTo(Store::class);
//    }
}
