<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    
    public function showAll()
    {
        $cache_key = $this->request_domain && count($this->store_ids) ? 'categories_' . $this->request_domain : 'categories';
        
        return Cache::rememberForever($cache_key, function () {
            $categories = Category::enabled()->topLevel()->with('childrenCategories')->withCount('products')->orderBy('order_no', 'asc')->get();

            foreach( $categories as $category ) {
                foreach( $category->childrenCategories as $child ) {
                    $category->products_count += $this->countChildPages( $child );
                }
            }

            return $categories;
        });
    }

    public function showAllRendered()
    {
        $categories = $this->showAll();
        
        return response()->view('categories', ['categories' => $categories->toArray(), 'display_store' => $this->display_store], 200);
    }
    
    public function getProductsByCategoryUid($category_uid)
    {
        $categories_ids[] = intval($category_uid);

        $categories_ids = [];
        $categories_ids[] = intval($category_uid);
        $cats = Category::where('category_uid', $category_uid)->with('childrenCategories')->get()->first()
            ->childrenCategories->flatten();
        foreach ($cats as $item) {
            $categories_ids[] = $item->category_uid;
            $categories_ids = array_merge($categories_ids, $item->children->flatten()->pluck('category_uid')->toArray());
        }
        
        $categories = Category::whereIn('category_uid', $categories_ids)->with('products')->get()->pluck('products')->filter(function ($value) {
            return !$value->isEmpty();
        })->flatten();

        return $this->paginateCollection($categories, 12);
    }
    
    public function getProductsByCategoryUidRendered(Request $request, $category_uid)
    {
        $lng = 'en';
        $sort_options = [];
        if($request->sort) {
            switch($request->sort) {
                case 'price:asc':
                    $sort_options['field'] = 'price';
                    $sort_options['method'] = 'asc';
                    break;

                case 'price:desc':
                    $sort_options['field'] = 'price';
                    $sort_options['method'] = 'desc';
                    break;
            }
        }

        $categories_ids = [];
        $categories_ids[] = intval($category_uid);
        $cats = Category::where('category_uid', $category_uid)->with('childrenCategories')->get()->first()
            ->childrenCategories->flatten();
        foreach ($cats as $item) {
            $categories_ids[] = $item->category_uid;
            $categories_ids = array_merge($categories_ids, $item->children->flatten()->pluck('category_uid')->toArray());
        }
        
        $products = Category::whereIn('category_uid', $categories_ids)->whereHas('products')->get()->pluck('products')->filter(function ($value) {
            return !$value->isEmpty();
        })->flatten();

        if($request->search) {
            $term = strtolower($request->search);
            
            $products = $products->filter(function ($product) use ($term) {
                return strpos(strtolower($product->product_name[$product->store->language]), $term) !== false;
            });

            if(count($sort_options)) {
                if($sort_options['method'] === 'asc') {
                    $products = $products->sortBy($sort_options['field']);
                } else {
                    $products = $products->sortByDesc($sort_options['field']);
                }
            }
        } else {
            if(count($sort_options)) {
                if($sort_options['method'] === 'asc') {
                    $products = $products->sortBy($sort_options['field']);
                } else {
                    $products = $products->sortByDesc($sort_options['field']);
                }
            }  
        }
        
        $categories = (new CategoryController)->showAll();
        $paginated_products = $this->paginateCollection($products, env('PRODUCTS_PER_PAGE', 20));

        return response()
            ->view('products', [
                'paginated_products' => $paginated_products,
                'display_store' => $this->display_store,
                'categories' => $categories
            ], 200);
    }

    public static function countChildPages( $category ) {
        foreach( $category->children as $child ) {
            $category->products_count += CategoryController::countChildPages( $child );
        }
        return $category->products_count;
    }

    public function getTopLevel( $category_uid ) {
        $current_cat = Category::where('category_uid', $category_uid)->first();
        $category_tree = $current_cat->parent()->first();
        $breadcrumbs = [];

        if(!$category_tree) {
            $breadcrumbs[] = [
                'id' => $current_cat->category_uid,
                'name' => $current_cat['name'][$this->language] ? $current_cat['name'][$this->language] : $current_cat['name']['en']
            ];
            return ['topLevelCategory' => $current_cat, 'breadcrumbs' => $breadcrumbs];
        }
        $breadcrumbs[] = [
            'id' => $current_cat->category_uid,
            'name' => $current_cat['name'][$this->language] ? $current_cat['name'][$this->language] : $current_cat['name']['en']
        ];
        
        $topLevelCategory = CategoryController::findTopLevel($category_tree, $breadcrumbs);
        
        return $topLevelCategory;
    }
    
    public function findTopLevel($category, $breadcrumbs)
    {
        if(!empty($category->parent)) {
            $breadcrumbs[] = [
                'id' => $category['category_uid'],
                'name' => $category['name'][$this->language] ? $category['name'][$this->language] : $category['name']['en']
            ];
            return $this->findTopLevel($category->parent, $breadcrumbs);
        }
        $breadcrumbs[] = [
            'id' => $category['category_uid'],
            'name' => $category['name'][$this->language] ? $category['name'][$this->language] : $category['name']['en']
        ];
        
        return ['topLevelCategory' => $category, 'breadcrumbs' => array_reverse($breadcrumbs)];
    }
}
