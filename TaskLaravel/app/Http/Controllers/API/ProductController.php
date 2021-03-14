<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use Validator;
use DB;
class ProductController extends Controller
{
    public function addProduct(Request $request){
    	$validator=validator::make($request->all(),[
    		'name'=>'required',
    		'description'=>'required',
    		'qty'=>'required',
    		'price'=>'required',
    		'discount'=>'required',
    		'category'=>'required',
    		'brand'=>'required'
    		]);
    	$product = new Product;
    	$token=$request->token;
    	 $id=DB::table('session')->where('token','=',$token)->select('Admin_id')->get();
    	 foreach($id as $idd){
         $id_Admin=$idd->Admin_id;}
        $product->Admin_id=$id_Admin;
    	$product->name=$request->name;
    	$product->description=$request->description;
    	$product->qty=$request->qty;
    	$product->price=$request->price;
    	$product->discount=$request->discount;
    	$product->category=$request->category;
    	$product->brand=$request->brand;

    	$product->save();
    	return response()->json([
              'message' => 'add product',
              'code' => 200,
            ]);
    }

    public function deleteProduct($id){
    	if(Product::where('id', $id)->exists()) {
        $product = Product::find($id);
        $product->delete();

        return response()->json([
          "message" => "Product deleted"
        ], 202);
      } else {
        return response()->json([
          "message" => "Product not found"
        ], 404);
      }
    }

    public function getProduct(){
    	 $product = Product::get()->toJson(JSON_PRETTY_PRINT);
   		 return response($product, 200);
    }

    public function updateProduct(Request $request,$id){
    	if(Product::where('id', $id)->exists()) {
    		$product=Product::Find($id);
    		$product->name = is_null($request->name) ? $product->name : $request->name;
    		$product->Admin_id=$product->Admin_Id;
    		$product->description=is_null($request->description) ? $product->description : $request->description;
    		$product->qty=is_null($request->qty) ? $product->qty : $request->qty;
    		$product->price=is_null($request->price) ? $product->price : $request->price;
    		$product->discount=is_null($request->discount) ? $product->discount : $request->discount;
    		$product->category=is_null($request->category) ? $product->category : $request->category;
    		$product->brand=is_null($request->brand) ? $product->brand : $request->brand;

    		$product->save();
    		return response()->json([
            "message" => "product updated successfully"
        ], 200);


    		  } else {
        return response()->json([
          "message" => "Product not found"
        ], 404);
      }
    }

    public function SearchBYBrand($brand){
    	if (DB::table('products')->where('brand',$brand)->exists()) {
        $products = DB::table('products')->where('brand','=',$brand)->get()->toJson(JSON_PRETTY_PRINT);
        return response($products, 200);
      } else {
        return response()->json([
          "message" => "product not found"
        ], 404);
      }
    }
}
