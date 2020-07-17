<?php

namespace App\Jobs;

use App\Category;
use App\Domain;
use App\Http\Controllers\CategoryController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class RefreshCategories implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $domains = Domain::all();
        if($domains) {
            foreach ($domains as $domain) {
                $this->processCategories($domain->domain, $domain->store_ids);
            }
        }
        
        $this->processCategories(null, []);
    }
    
    public function processCategories($domain, $store_ids)
    {
        config(['store_ids' => $store_ids]);
        $categories = Category::enabled()->topLevel()->with('childrenCategories')->withCount('products')->orderBy('order_no', 'asc')->get();

        foreach( $categories as $category ) {
            foreach( $category->childrenCategories as $child ) {
                $category->products_count += CategoryController::countChildPages( $child );
            }
        }

        $cache_key = !empty($domain) ? 'categories_' . $domain : 'categories';

        Cache::forget($cache_key);
        Cache::forever($cache_key, $categories);
    }
}
