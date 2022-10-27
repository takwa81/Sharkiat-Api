<?php

namespace App\Http\Controllers;

use App\Models\User ;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = Category::latest()->get();
            return response()->json(CategoryResource::collection($data));
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
        $this->validate($request, [
            'name' =>'required' ,
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        // $image_path = $request->file('image')->store(public_path() . '/images/categories/');

        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension(); // you can also use file name
        $fileName = time() . '.' . $extension;
        $path = public_path() . '/images/categories/';
        $uplaod = $file->move($path, $fileName);
        $data = Category::create([
            'name' => $request->name,
            'image'=>$fileName ,
        ]);

        $data->image = URL::to('/') . '/images/categories/' . $data->image ;
        return response($data, Response::HTTP_CREATED);
    }
    else{
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
        return response()->json(new CategoryResource(Category::findOrFail($id)));
    }
    public function edit($id)
    {
        return response()->json(new CategoryResource(Category::findOrFail($id)));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCategory(Request $request, $id)
    {
        $usertype = Auth::user()->role;
        if($usertype == "1"){
        $category=Category::findOrFail($id);
        if(!$category){
            return response()->json(['message'=> 'Category not found !!!']);
        }

        if ($request->file('image') != null) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension(); // you can also use file name
            $fileName = time() . '.' . $extension;
            $path = public_path() . '/images/categories/';
            $uplaod = $file->move($path, $fileName);
            if (File::exists(public_path('images/categories/' . $category->image))) {
                File::delete(public_path('images/categories/' . $category->image));
            } else {
                $category->image  = $category->image;
            }
            $category->image  = $fileName;
        }
        $category->name = $request->name ?? $category->name;
        $category->save();

        $category->image = URL::to('/') . '/images/categories/' . $category->image ;
        return response()->json($category);
    }
    else{
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
        $category = Category::find($id);
        if (File::exists(public_path('images/categories/' . $category->image))) {
            File::delete(public_path('images/categories/' . $category->image));
        }
        Category::destroy($id);

        return response()->json(['message'=>'Category deleted Successfully ...']);
    }else{
            return response()->json(['message'=> 'you are not admin']);
        }
    }

    ///Search Category 
    public function search($name){
        return Category::where('name','like','%'.$name.'%')->get();
    }
}
