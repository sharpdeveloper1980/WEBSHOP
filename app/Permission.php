<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'user_id', 'is_webshop_admin', 'is_store_admin', 'stores_id',
    ];

    protected $casts = [
        'is_webshop_admin' => 'boolean',
        'is_store_admin' => 'boolean',
        'stores_id' => 'array',
    ];
}
