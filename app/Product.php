<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'server_id', 'product_id', 'product_number', 'client_number', 'user_id', 'product_name', 'description',
        'price', 'discount', 'created', 'changed', 'quantity', 'webshop_store_id',
        'currency', 'table_name', 'status_code', 'vat_percentage', 'categories', 'tags',
        'photos', 'channels',
    ];

    protected $casts = [
        'created' => 'datetime',
        'changed' => 'datetime',
        'product_name' => 'array',
        'description' => 'array',
        'categories' => 'array',
        'tags' => 'array',
        'photos' => 'array',
        'channels' => 'array',
    ];

    protected $with = ['seller:id,user_id,name', 'store:id,company_id,name,technical_name,language', 'client:id,user_id,nickname'];

    protected $appends = ['display_store', 'native_name'];

    public function setCreatedAttribute($value)
    {
        $this->attributes['created'] = Carbon::parse($value);
    }

    public function setChangedAttribute($value)
    {
        $this->attributes['changed'] = Carbon::parse($value);
    }

    public function getNameAttribute($value)
    {
        return $this->product_name['en'];
    }

    public function getNativeNameAttribute()
    {
        return $this->product_name[$this->store->language];
    }

    public function getPhotoAttribute($value)
    {
        return $this->photos[0];
    }

    public function getDisplayStoreAttribute()
    {
        return config('display_store');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function client()
    {
        return $this->belongsToMany(Client::class, 'client_product');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'webshop_store_id', 'id');
    }

    public function productCategories()
    {
        return $this->belongsToMany(Category::class, 'product_category', 'product_id', 'category_id', 'id', 'category_uid');
    }

}
