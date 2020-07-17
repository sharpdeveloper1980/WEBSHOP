<?php

namespace App\Http\Controllers;

use App\Category;
use App\Client;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\LazyCollection;

class ProductController extends Controller
{
    
    public function showAll(Request $request)
    {
        $products = self::getFilteredProducts($this->store_ids, $request->lng, $request->sort, $request->search);

        return $this->paginateCollection($products, env('PRODUCTS_PER_PAGE', 20));
    }
    
    public function showAllRendered(Request $request)
    {
        
        if($request->global_search && !empty($request->global_search)) {
            $products = self::getGlobalFilteredProducts($request->global_search);
        } else {
            $products = self::getFilteredProducts($this->store_ids, $request->lng, $request->sort, $request->search, null, $request->category);
        }
        
        $paginated_products = $this->paginateCollection($products, env('PRODUCTS_PER_PAGE', 20));

        $categories = Category::enabled()->topLevel()->with('childrenCategories')->orderBy('order_no', 'asc')->get()->toArray();

        $topLevelCategory = null;
        if($request->category) {
            $topLevelCategory = app(CategoryController::class)->getTopLevel($request->category);
        }

        $hero_img_url = '';
        $path = public_path('/storage/'.$this->hero_img_url);
        if($this->hero_img_url && File::exists($path)) {
            $hero_img_url = url('/storage/'.$this->hero_img_url);
        }
        
        return response()->view('products', [
            'paginated_products' => $paginated_products, 
            'display_store' => $this->display_store, 
            'categories' => $categories,
            'selectedCategory' => intval($request->category),
            'topLevelCategory' => $topLevelCategory['topLevelCategory'],
            'breadcrumbs' => $topLevelCategory['breadcrumbs'],
            'language' => $this->language,
            'hide_search' => true,
            'hero_img_url' => $hero_img_url,
            'hero_img_link' => $this->hero_img_link,
            'commercial_html' => $this->commercial_html
        ], 200);
    }
    
    public function getProductById($id)
    {
        return Product::whereId($id)->first()->toArray();
    }
    
    public function getProductByIdRendered($id)
    {
        $product = Product::whereId($id)->first()->toArray();

        $categories = Category::enabled()->topLevel()->with('childrenCategories')->orderBy('order_no', 'asc')->get()->toArray();
        
        return response()->view('product', [
            'product' => $product, 
            'display_store' => $this->display_store,
            'categories' => $categories,
            'language' => $this->language,
        ], 200);
    }
    
    public function renderTopBar()
    {
        $logo_img_url = '';
        $path = public_path('/storage/'.$this->logo_img_url);
        if(File::exists($path)) {
            $logo_img_url = url('/storage/'.$this->logo_img_url);
        }
        return response()->view('partials.topbar', ['logo_img_url' => $logo_img_url], 200);
    }
    
    public static function getGlobalFilteredProducts($search = null)
    {
        if($search) {
            $term = strtolower($search);
            return Product::cursor()->filter(function ($product) use ($term) {
                return $product->seller && $product->client && 
                    (strpos(strtolower($product->product_name[$product->store->language]), $term) !== false ||
                    strpos(strtolower($product->description[$product->store->language]), $term) !== false ||
                    strpos(strtolower($product->client[0]->nickname), $term) !== false || 
                    strpos(strtolower($product->store->name), $term) !== false);
            })->sortByDesc('created');
        }
        
        return collect([]);
    }
    
    public static function getFilteredProducts($store_ids = [], $lng = 'en', $sort = null, $search = null, $client = null, $category_uid = null)
    {
        if(!$lng) $lng = 'en';
        $sort_options = [];
        if($sort) {
            switch($sort) {
                case 'price:asc':
                    $sort_options['field'] = 'price';
                    $sort_options['method'] = 'asc';
                    break;

                case 'price:desc':
                    $sort_options['field'] = 'price';
                    $sort_options['method'] = 'desc';
                    break;
                case 'created:asc':
                    $sort_options['field'] = 'created';
                    $sort_options['method'] = 'asc';
                    break;

                case 'created:desc':
                    $sort_options['field'] = 'created';
                    $sort_options['method'] = 'desc';
                    break;
                case 'title:asc':
                    $sort_options['field'] = 'native_name';
                    $sort_options['method'] = 'asc';
                    break;

                case 'title:desc':
                    $sort_options['field'] = 'native_name';
                    $sort_options['method'] = 'desc';
                    break;
            }
        }

        $products = new Product;

        if($store_ids && !empty($store_ids)) {
            $products = $products->whereIn('webshop_store_id', $store_ids);
        }
        
        if($client && !empty($client)) {
            $products = $products->where(['client_number' => $client->client_number, 'server_id' => $client->server_id]);
        }
        
        if($category_uid && !empty($category_uid)) {
            $categories_ids = [];
            $categories_ids[] = intval($category_uid);
            $cats = Category::where('category_uid', $category_uid)->with('childrenCategories')->get()->first()
                ->childrenCategories->flatten();
            foreach ($cats as $item) {
                $categories_ids[] = $item->category_uid;
                $categories_ids = array_merge($categories_ids, $item->children->flatten()->pluck('category_uid')->toArray());
            }
            
            $products = $products->where(function($query) use ($categories_ids) {
                foreach ($categories_ids as $category) {
                    $query->orWhereJsonContains('categories', $category);
                }
            });
        }
        
        if($search) {
            $term = strtolower($search);
            $products = $products->cursor()->filter(function ($product) use ($term) {
                return (strpos(strtolower($product->product_name[$product->store->language]), $term) !== false ||
                    strpos(strtolower($product->description[$product->store->language]), $term) !== false) && 
                    $product->seller && $product->client;
            });
            if(count($sort_options)) {
                if($sort_options['method'] === 'asc') {
                    $products = $products->sortBy($sort_options['field']);
                } else {
                    $products = $products->sortByDesc($sort_options['field']);
                }
            } else {
                $products = $products->sortByDesc('created');
            }
        }
        
        if(!$products instanceof LazyCollection) {
            $products = $products->get();
            if(count($sort_options)) {
                if ($sort_options['method'] === 'asc') {
                    $products = $products->sortBy($sort_options['field']);
                } else {
                    $products = $products->sortByDesc($sort_options['field']);
                }
            } else {
                $products = $products->sortByDesc('created');
            }
        }
        
        return $products;
    }
}
