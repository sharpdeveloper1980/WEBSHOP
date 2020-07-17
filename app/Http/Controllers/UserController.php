<?php

namespace App\Http\Controllers;

use App\User;

class UserController extends Controller
{
    public function getUserById($id)
    {
        $seller = User::whereId($id)->with('clientsWithProducts')->first();
        if(empty($seller)) {
            return response()->json(['error' => 'Not Found', 'message' => 'Seller not found'], 404);
        }
        
        return $seller->toArray(); 
    }
    
    public function getUserByIdRendered($id)
    {
        $seller = User::whereId($id)->with('clientsWithProducts')->first();
        if(empty($seller)) {
            return response()->json(['error' => 'Not Found', 'message' => 'Seller not found'], 404);
        }

        return response()->view('seller', ['seller' => $seller->toArray()], 200);
    }
}
