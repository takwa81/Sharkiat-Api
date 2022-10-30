<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductDetailsResource;
use App\Models\Product ;
use App\Models\Category ;
use App\Models\ProductImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\File;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $data = Product::latest()->get();
            return response()->json(ProductResource::collection($data));
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        $usertype = Auth::user()->role;
        if($usertype == "1"){
        $request->validate(
            [
                "name" => "required",
                "images" => "required",
                "price" => "required",
            ]
        );
       
        $product = Product::create([
            "name" => $request->name,
            "price" => $request->price,
            "discount" => $request->discount,
            "sale_price" =>  $request->price - ($request->price * ($request->discount / 100)),
            "description" => $request->description,
            "quantity" => $request->quantity,
            "is_appear_home" => $request->is_appear_home,
            "category_id" => $request->category_id,
            "expire_date" => $request->expire_date,
        ]);

        $images = $request->file('images');
        $imageName = "" ;
        foreach ($images as $image) {
            $new_name = rand().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('/images/products/'),$new_name);
            $imageName=$imageName.$new_name.',';
            ProductImages::create([
                "image" => $new_name,
                "product_id" => $product->id,
            ]);
        }
        return response()->json(new ProductResource(Product::findOrFail($product->id)));
    }else{
        return response()->json(['message'=> 'you are not admin']);
    }
    }

   

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(new ProductResource(Product::findOrFail($id)));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateProduct(Request $request, $id)
    {
        $usertype = Auth::user()->role;
        if($usertype == "1"){

        $product=Product::find($id);
        if(!$product){
            return response()->json(['message'=> 'Category not found !!!']);
        }
        $images = $request->file('images');
        $imageName = "" ;
       
        if ($request->file('images') != null) {
            foreach ($product->images as $file) {
                if (File::exists(public_path('images/products/' . $file->image))) {
                    File::delete(public_path('images/products/' . $file->image));
                }
                $file->delete();
            }
            foreach ($images as $image) {
                // $fileName = "" ;
                // $extension = $file->getClientOriginalExtension(); // you can also use file name
                // $fileName = $file->getClientOriginalName() . time() . '.' . $extension;
                // $path = public_path() . '/images/products/';
                // $uplaod = $file->move($path, $fileName);
                $new_name = rand().'.'.$image->getClientOriginalExtension();
                $image->move(public_path('/images/products/'),$new_name);
                $imageName=$imageName.$new_name.',';
                ProductImages::create([
                    "image" => $new_name,
                    "product_id" => $product->id,
                ]);
                ProductImages::create([
                    "image" => $new_name,
                    "product_id" => $product->id,
                ]);
                
            }
        }
        $product->name = $request->name ?? $product->name;
        $product->price = $request->price ?? $product->price;
        $product->quantity = $request->quantity?? $product->quantity;
        $product->discount = $request->discount ?? $product->discount;
        $product->category_id = $request->category_id ?? $product->category_id;
        $product->sale_price = $request->discount != null ? $request->price - ($request->price * ($request->discount / 100)) : null;
        $product->is_appear_home = $request->is_appear_home ?? $product->is_appear_home;
        $product->save();
        return response()->json(['message'=>'Product Updated Successfully','data'=>new ProductResource(Product::findOrFail($product->id))]);
    }else{
        return response()->json(['message'=> 'you are not admin']);
    }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $usertype = Auth::user()->role;
        if($usertype == "1"){
        $product = Product::find($id);
        foreach ($product->images as $file) {
            if (File::exists(public_path('images/products/' . $file->image))) {
                File::delete(public_path('images/products/' . $file->image));
            }
        }
        Product::destroy($id);

        return response()->json(['message'=>'Product deleted Successfully ...']);
    }else{
        return response()->json(['message'=> 'you are not admin']);
    }
    }

    ///Search Product 
    public function search($name){
        return Product::where('name','like','%'.$name.'%')->get();
    }
}
