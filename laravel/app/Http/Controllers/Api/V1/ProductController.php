<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductDescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Http\Components\Translit;
use Illuminate\Support\Facades\Config;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = Product::first();
        dd($product);
        die;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'sku' => 'required|unique:oc_product|max:255',
            'quantity' => 'required|integer',
            'price'  => 'required|numeric',
            'image' => 'required|extensions:jpg,png',
            'name' => 'required|max:255',
            'description' => 'nullable',
            'meta_title' => 'nullable',
            'meta_description' => 'nullable',
            'category_id' => 'nullable|integer',
        ];
        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'info' => $validator->errors(),
            ], 400);
        }

        $product_id = Product::max('product_id') + 1;
        $product_alias = Translit::makeTranslit($request->name);

        $product = new Product;
        $product->product_id = $product_id;
        $product->sku = $request->sku;
        $product->quantity = $request->quantity;
        $product->price = $request->price;
        $image_name = $product_id  . '-' . $product_alias . '.' . $request->file('image')->extension();
        $request->file('image')->move(env('IMAGE_DIR'), $image_name);
        $product->image = 'catalog/' . $image_name;

        if(!$product->save()) {
            return response()->json([
                'status' => 'error',
                'info' => ['Unrecognized error. Cannot save product.'],
            ], 500);
        }

        $info = [
            'product' => [
                'id' => $product_id,
            ]
        ];

        $product_description = new ProductDescription;
        $product_description->product_id = $product_id;
        $product_description->language_id = Config::get('app.ukrainian_language_id');
        $product_description->name = $request->name;
        $product_description->description = $request->description ?? '';
        $product_description->meta_title = $request->description ?? $request->name;
        $product_description->meta_description = $request->description ?? '';
        $product_description->save();

        if(!empty($request->category_id)) {
            $category = Category::where('category_id', $request->category_id)->first();
            if(empty($category)) {
                $info[] = 'Category with ID ' . $request->category_id . ' not found. No category defined for product. Product will not be accessable on site.';
            } else {
                $product->addCategory($category);
                $info['product']['url'] = $product->getUrl();
            }
        } else {
            $info[] = 'No category defined for product. Product will not be accessable on site.';
        }

        return response()->json([
            'status' => 'success',
            'info' => $info,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        if(!empty($product)) {
            return [
                'id' => $product->product_id,
                'sku' => $product->sku,
                'quantity' => $product->quantity,
                'price' => $product->price,
                'image' => Config::get('app.url') . '/image/' . $product->image,
                'name' => $product->description->name,
                'description' => $product->description->description,
                'meta_title' => $product->description->meta_title,
                'meta_description' => $product->description->meta_description,
            ];
        }

        return response()->json([
            'status' => 'error',
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
