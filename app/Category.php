<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_uid', 'parent_category_uid', 'enabled', 'order_no', 'name', 'commercial_html',
    ];

    protected $casts = [
        'name' => 'array',
    ];

    public function getNameEngAttribute($value)
    {
        return $this->name['en'];
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_category_uid', 'category_uid')->withCount('products')->with('children');
    }

    public function childrenCategories()
    {
        return $this->hasMany(Category::class, 'parent_category_uid', 'category_uid')->withCount('products')->with('children');
    }
    
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_category_uid', 'category_uid')->with('parent');
    }

    public function products()
    {
        $store_ids = config('store_ids');
        if(count($store_ids)) {
            return $this->belongsToMany(Product::class, 'product_category', 'category_id', 'product_id', 'category_uid', 'id')
                ->whereIn('webshop_store_id', $store_ids);
        } else {
            return $this->belongsToMany(Product::class, 'product_category', 'category_id', 'product_id', 'category_uid', 'id');
        }
    }

    public function scopeEnabled($query)
    {
        return $query->where('enabled', 1);
    }

    public function scopeTopLevel($query)
    {
        return $query->where('parent_category_uid', 0);
    }
}
