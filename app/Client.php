<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'server_id', 'client_number', 'is_active', 'user_id', 'webshop_store_id', 'fullname', 'firstname', 'lastname',
        'is_anonymous', 'nickname', 'website_url', 'street', 'zip', 'city', 'country', 'is_business_client',
        'business_name', 'business_id', 'seller_picture_url', 'seller_description', 'webshop_user_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'seller_description' => 'array',
    ];

    protected $with = ['store', 'user'];

    public function store()
    {
        return $this->belongsTo(Store::class, 'webshop_store_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'webshop_user_id', 'id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'client_product');
    }

//    public function products()
//    {
//        return $this->belongsToMany(Product::class, 'client_product', 'client_id', 'product_id');
//    }
}
