<?php

namespace App\Http\Controllers;

use App\Category;
use App\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function getClientById($id)
    {
        $seller = Client::whereId($id)->with('products')->first();
        if(empty($seller)) {
            return response()->json(['error' => 'Not Found', 'message' => 'Seller not found'], 404);
        }

        return $seller->toArray();
    }

    public function getClientByIdRendered(Request $request, $id)
    {
        $seller = Client::whereId($id)->first();
        if(empty($seller)) {
            return response()->json(['error' => 'Not Found', 'message' => 'Seller not found'], 404);
        }

        $products = ProductController::getFilteredProducts([], $request->lng, $request->sort, $request->search, $seller, $request->category);
        $paginated_products = $this->paginateCollection($products, env('PRODUCTS_PER_PAGE', 20));

        $categories = Category::enabled()->topLevel()->with('childrenCategories')->orderBy('order_no', 'asc')->get()->toArray();
        
        return response()->view('seller', [
            'seller' => $seller->toArray(), 
            'paginated_products' => $paginated_products, 
            'display_store' => $this->display_store,
            'categories' => $categories,
            'language' => $this->language
        ], 200);
    }
}
