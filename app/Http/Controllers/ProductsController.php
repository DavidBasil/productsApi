<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    
    public function index(){
        $products = auth()->user()->products;

        return response()->json(['data' => $products, 'success' => true]);
    }

    public function show($id){
        $product = auth()->user()->products()->find($id);
        if (!$product){
            return response()->json([
                'message' => 'Product not found',
                'success' => false
            ], 400);
        }

        return response()->json(['data' => $product->toArray(), 'success' => true]);
    }

    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required|integer'
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->price = $request->price;

        if(auth()->user()->products()->save($product)){
            return response()->json([
                'data' => $product->toArray(),
                'success' => true
            ]);
        } else {
            return response()->json([
                'message' => 'Product could not be added',
                'success' => false
            ], 500);
        }
    }

    public function update(Request $request, $id){
        $product = auth()->user()->products()->find($id);

        if(!$product){
            return response()->json([
                'message' => 'Product not found',
                'success' => false
            ], 400);
        }

        $updated = $product->fill($request->all())->save();

        if($updated){
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'message' => 'Product could not be updated',
                'success' => false
            ], 500);
        }
    }

    public function destroy($id){
        $product = auth()->user()->products()->find($id);

        if(!$product){
            return response()->json([
                'message' => 'Product not found',
                'success' => false
            ], 400);
        } 

        if($product->delete()){
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'message' => 'Product could not be deleted',
                'success' => false
            ], 500);
        }


    }

}
