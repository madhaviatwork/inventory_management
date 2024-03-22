<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        // Product::factory()->count(80)->create();
        $query = Product::query();
        if(request()->all()){
            if ($s = request()->input('search')) {
                $query->whereRaw("title LIKE '%" . $s . "%'")
                    ->orWhereRaw("content LIKE '%" . $s . "%'")
                    ->with(['category' => function($query) use ($s){
                        $query->whereRaw("title LIKE '%" . $s . "%'");
                    }]);
            }

            if ($range = [request()->input('price_from'),request()->input('price_to')]) {
                $query->whereBetween('price', $range);
            }

            if ($sort = request()->input('sort')) {
                $query->orderBy('price', $sort);
            }
        }

        $perPage = 10;
        $page = request()->input('page', 1);
        $total = $query->count();

        $result = $query->offset(($page - 1) * $perPage)->limit($perPage)->get();

        return response()->json([
            'status' => 'success',
            'data' => $result,
            'total' => $total,
            'page' => $page,
            'last_page' => ceil($total / $perPage)
        ]);
    }

    public function store(Request $request)
    {   
        $rules = [
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'price' => 'required|regex:/^\d+(\.\d{1,2})?/',
        ];
        $messages = [
            'required' => 'The :attribute is required'
        ];
        $request->validate($rules, $messages);

        $product = Product::create([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'image' => $request->image
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Product created successfully',
            'product' => $product,
        ]);
    }

    public function show($id)
    {
        $product = Product::find($id);
        return response()->json([
            'status' => 'success',
            'product' => $product,
        ]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string|max:255',
            'category_id' => 'sometimes|required|integer',
        ];
        $messages = [
            'required' => 'The :attribute is required'
        ];
        $validateddata = $request->validate($rules, $messages);

        if($validateddata){
            $product = Product::find($id);
            $product->title = $request->title ? $request->title : $product->title;
            $product->content = $request->content ? $request->content : $product->content;
            $product->category_id = $request->category_id ? $request->category_id : $product->category_id;
            $product->price = $request->price ? $request->price : $product->price;
            $product->image = $request->image ? $request->image : $product->image;
            $product->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Product updated successfully',
                'product' => $product,
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => $errors->all(),
                'product' => $product,
            ]);

        }
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Product deleted successfully',
            'product' => $product,
        ]);
    }

}
