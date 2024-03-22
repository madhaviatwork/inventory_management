<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        Category::factory()->count(8)->create();
        $categories = Category::all();
        return response()->json([
            'status' => 'success',
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'metatitle' => 'required|string|max:255',
            'content' => 'required|string|max:255',
        ];
        $messages = [
            'required' => 'The :attribute is required'
        ];
        $request->validate($rules, $messages);

        $category = Category::create([
            'title' => $request->title,
            'metaTitle' => $request->metatitle,
            'slug' => Str::slug($request->title),
            'content' => $request->content
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Category created successfully',
            'category' => $category,
        ]);
    }

    public function show($id)
    {
        $category = Category::find($id);
        return response()->json([
            'status' => 'success',
            'category' => $category,
        ]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'title' => 'sometimes|required|string|max:255',
            'metatitle' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string|max:255',
        ];
        $messages = [
            'required' => 'The :attribute is required'
        ];
        $request->validate($rules, $messages);

        $category = Category::findOrFail($id);
        $category->title = $request->title;
        $category->metaTitle = $request->metatitle;
        $category->slug = Str::slug($request->title);
        $category->content = $request->content;
        $category->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Category updated successfully',
            'category' => $category,
        ]);
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        $category->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Category deleted successfully',
            'category' => $category,
        ]);
    }

}
