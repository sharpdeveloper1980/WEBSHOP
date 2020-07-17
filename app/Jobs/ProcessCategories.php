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

class ProcessCategories implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    private $domain;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($domain)
    {
        $this->domain = $domain;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $domain = Domain::whereDomain($this->domain)->first();
        if($domain) {
            config(['store_ids' => $domain->store_ids]);
            $categories = Category::enabled()->topLevel()->with('childrenCategories')->withCount('products')->orderBy('order_no', 'asc')->get();

            foreach ($categories as $category) {
                foreach ($category->childrenCategories as $child) {
                    $category->products_count += CategoryController::countChildPages($child);
                }
            }

            $cache_key = $this->domain ? 'categories_' . $this->domain : 'categories';

            Cache::forget($cache_key);
            Cache::forever($cache_key, $categories);
        }
    }
}
