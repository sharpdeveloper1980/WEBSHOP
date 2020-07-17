<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'server_id', 'company_id', 'name', 'technical_name', 'company_visible', 'company_description', 'company_contact_info', 
        'company_coordinates', 'company_business_hours', 'company_website_url', 'company_email', 'localization', 
        'territory', 'language', 'timezone', 'allow_guest_spectate', 'allow_client_registration', 'allow_client_login', 
        'allow_client_reservation', 'allow_client_product_marketing', 'product_pricing_enabled', 'sales_view_enabled',
        'allow_webshop_product_pricing', 'use_the_term_product_feed_instead_of_webshop', 'product_pricing_1_by_1',
        'mobile_app_show_product_recognition_button', 'logo_url', 'coordinates',
    ];

    protected $casts = [
        'coordinates' => 'array',
        'company_visible' => 'boolean',
        'allow_guest_spectate' => 'boolean',
        'allow_client_registration' => 'boolean',
        'allow_client_login' => 'boolean',
        'allow_client_reservation' => 'boolean',
        'allow_client_product_marketing' => 'boolean',
        'product_pricing_enabled' => 'boolean',
        'sales_view_enabled' => 'boolean',
        'allow_webshop_product_pricing' => 'boolean',
        'use_the_term_product_feed_instead_of_webshop' => 'boolean',
        'product_pricing_1_by_1' => 'boolean',
        'mobile_app_show_product_recognition_button' => 'boolean',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'webshop_store_id', 'id');
    }
}
