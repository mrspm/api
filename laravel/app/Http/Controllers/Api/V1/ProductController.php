<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductDescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Category;
use App\Http\Components\Translit;
use Illuminate\Support\Facades\Config;

class ProductController extends Controller
{
    private const PRODUCTS_PER_PAGE = 50;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::LeftJoin('oc_product_description', 'oc_product_description.product_id', '=', 'oc_product.product_id')
            ->select('oc_product.product_id as id', 'oc_product_description.name as name', 'quantity', 'price', 'date_added', 'date_modified')
            ->where('language_id', Config::get('app.ukrainian_language_id'))
            ->paginate(self::PRODUCTS_PER_PAGE);
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
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable|max:255',
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
        $product_description->meta_title = $request->meta_title ?? $request->name;
        $product_description->meta_description = $request->meta_description ?? '';
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
                'url' => $product->getUrl(),
                'category' => [
                    'id' => $product->getCategory()->category_id,
                    'name' => $product->getCategory()->description->name ?? null,
                ],
                'date_added' => $product->date_added,
                'date_modified' => $product->date_modified,
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
        $rules = [
            'sku' => ['nullable', 'max:255', Rule::unique('oc_product')->ignore($product->product_id, "product_id")],
            'quantity' => 'nullable|integer',
            'price'  => 'nullable|numeric',
            'name' => 'nullable|max:255',
            'description' => 'nullable',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable|max:255',
            'category_id' => 'nullable|integer',
        ];
        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'info' => $validator->errors(),
            ], 400);
        }

        $product->update($request->all());

        if(isset($request->name)) $product->description->name = $request->name;
        if(isset($request->description)) $product->description->description = $request->description;
        if(isset($request->name)) $product->description->meta_title = $request->meta_title;
        if(isset($request->name)) $product->description->meta_description = $request->meta_description;

        if(!empty($request->category_id)) {
            $category = Category::where('category_id', $request->category_id)->first();
            if(empty($category)) {
                $info[] = 'Category with ID ' . $request->category_id . ' not found. Product category not updated.';
            } else {
                $product->addCategory($category);
            }
        }

        $info = [
            'product' => [
                'id' => $product->product_id,
                'url' => $product->getUrl(),
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
    public function destroy(Product $product)
    {
        return response()->json([
            'status' => 'error',
            'info' => 'Only administrators can delete products.'
        ], 403);
    }
}

