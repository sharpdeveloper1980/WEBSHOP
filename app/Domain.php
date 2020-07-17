<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'domain', 'store_ids', 'language', 'logo', 'hero_img_url', 'hero_img_link', 'logo_img_url', 'css', 'commercial_html',
    ];
    
    protected $casts = [
        'store_ids' => 'array',
    ];
}
