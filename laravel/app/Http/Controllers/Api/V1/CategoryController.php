<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryDescription;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Components\Translit;
use Illuminate\Support\Facades\Config;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('description')->get();
        $data = [];
        foreach ($categories as $category) {
            $data[] = [
                'id' => $category->category_id,
                'parent_id' => $category->parent_id,
                'name' => $category->description->name,
                'date_added' => $category->date_added,
                'date_modified' => $category->date_modified,
            ];
        }

        return response()->json([
            'status' => 'success',
            'count' => count($data),
            'data' => $data,
        ], 200);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'parent_id' => 'nullable|integer|exists:oc_category,category_id',
            'name' => 'required|max:255',
            'description' => 'nullable',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable|max:255',
        ];
        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'info' => $validator->errors(),
            ], 400);
        }

        $category_id = Category::max('category_id') + 1;
        $category_alias = Translit::makeTranslit($request->name);

        $category = new Category;
        $category->category_id = $category_id;
        $category->parent_id = $request->parent_id;

        if(!$category->save()) {
            return response()->json([
                'status' => 'error',
                'info' => ['Unrecognized error. Cannot save category.'],
            ], 500);
        }

        $info = [
            'category' => [
                'id' => $category_id,
            ]
        ];

        $category_description = new CategoryDescription;
        $category_description->category_id = $category_id;
        $category_description->language_id = Config::get('app.ukrainian_language_id');
        $category_description->name = $request->name;
        $category_description->description = $request->description ?? '';
        $category_description->meta_title = $request->meta_title ?? $request->name;
        $category_description->meta_description = $request->meta_description ?? '';
        $category_description->save();

        $category->saveAlias();

        return response()->json([
            'status' => 'success',
            'info' => $info,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        if(!empty($category)) {
            $data =  [
                'id' => $category->category_id,
                'parent_id' => $category->parent_id,
                'name' => $category->description->name,
                'description' => $category->description->description,
                'meta_title' => $category->description->meta_title,
                'meta_description' => $category->description->meta_description,
                'date_added' => $category->date_added,
                'date_modified' => $category->date_modified,
            ];

            return response()->json([
                'status' => 'success',
                'category' => $data,
            ], 201);
        }

        return response()->json([
            'status' => 'error',
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $rules = [
            'parent_id' => 'nullable|integer|exists:oc_category,category_id',
            'name' => 'nullable|max:255',
            'description' => 'nullable',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable|max:255',
        ];
        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'info' => $validator->errors(),
            ], 400);
        }

        $category->update($request->all());

        if(isset($request->name)) $category->description->name = $request->name;
        if(isset($request->description)) $category->description->description = $request->description;
        if(isset($request->name)) $category->description->meta_title = $request->meta_title;
        if(isset($request->name)) $category->description->meta_description = $request->meta_description;
        $category->description->save();

        $info = [
            'category' => [
                'id' => $category->category_id,
            ],
        ];

        return response()->json([
            'status' => 'success',
            'info' => $info,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        return response()->json([
            'status' => 'error',
            'info' => 'Only administrators can delete categories.'
        ], 403);
    }
}


