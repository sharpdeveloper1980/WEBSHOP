<?php

namespace App\Http\Controllers;

use App\Category;
use App\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    
    public function getStoreBySlug($slug)
    {
        $store = Store::whereTechnicalName($slug)->first();
        if(empty($store)) {
            return response()->json(['error' => 'Not Found', 'message' => 'There is no such store'], 404);
        }
        
        return $store;
    }
    
    public function getStoreBySlugRendered(Request $request, $slug)
    {
        $store = Store::whereTechnicalName($slug)->first();
        if(empty($store)) {
            return response()->json(['error' => 'Not Found', 'message' => 'There is no such store'], 404);
        }

        $products = ProductController::getFilteredProducts([$store->id], $request->lng, $request->sort, $request->search, null, $request->category);

        $paginated_products = $this->paginateCollection($products, env('PRODUCTS_PER_PAGE', 20));

        $categories = Category::enabled()->topLevel()->with('childrenCategories')->orderBy('order_no', 'asc')->get()->toArray();

        return response()->view('store', [
            'store' => $store, 
            'paginated_products' => $paginated_products, 
            'display_store' => $this->display_store,
            'categories' => $categories,
            'language' => $this->language
        ], 200);
    }
    
    public function getStoreProducts(Request $request, $slug)
    {
        $store = Store::whereTechnicalName($slug)->first();
        $products = ProductController::getFilteredProducts([$store->id], $request->lng, $request->sort, $request->search);
        
        return $this->paginateCollection($products, env('PRODUCTS_PER_PAGE', 20));
    }
}
