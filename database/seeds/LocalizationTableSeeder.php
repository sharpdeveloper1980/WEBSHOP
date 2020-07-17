<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocalizationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = [
            'ar' => 'Arabic',
            'bg' => 'Bulgarian',
            'da' => 'Danish',
            'de' => 'German',
            'en' => 'English',
            'es' => 'Spanish',
            'fi' => 'Finnish',
            'fr' => 'French',
            'he' => 'Hebrew',
            'it' => 'Italian',
            'nl' => 'Dutch',
            'no' => 'Norwegian',
            'ru' => 'Russian',
            'sv' => 'Swedish'
        ];
        
        $basic_translations = [
            'home' => 'Home',
            'global_search_placeholder' => 'Search for products, stores and people...',
            'store_search' => 'Search for products and people...',
            'seller_search' => 'Search for products...',
            'categories' => 'Categories',
            'clear' => 'Clear',
            'browse_categories' => 'Browse Categories',
            'product_description' => 'Product description',
            'seller' => 'Seller',
            'store' => 'Store',
            'table' => 'Table',
            'all_products' => 'All products',
            'there_are_no_products' => 'There are no products',
            'products' => 'products',
            'page' => 'page',
            'of' => 'of',
            'latest' => 'Latest',
            'earliest' => 'Earliest',
            'cheapest_first' => 'Cheapest first',
            'expensive_first' => 'Expensive first',
            'title_asc' => 'Title (asc)',
            'title_desc' => 'Title (desc)',
            'about' => 'About',
            'business_name' => 'Business name',
            'not_yet_chosen' => 'Not yet chosen',
            'opening_hours' => 'Opening Hours',
            'contact' => 'Contact',
            'website' => 'Website',
            'add_to_cart' => 'Add To Cart',
        ];
        $insert_arr = [];
        
        foreach ($languages as $language => $name) {
            foreach ($basic_translations as $key => $value) {
                $insert_arr[] = [
                    'key' => $key,
                    'value' => $value,
                    'language' => $language
                ];
            }
        }
        
        DB::table('localization')->insert($insert_arr);
    }
}
