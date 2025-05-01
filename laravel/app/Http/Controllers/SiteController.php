<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index()
    {
        $product = Product::where('product_id', 17297)->first();
        dd($product->getUrl());

        /*
        $host = 'https://api.loc/api';
        $url = $host . '/v1/products';

        $method = 'post';

        $body = [
            'name' => 'Test product',
            'description' => 'Test product description',
            'sku' => '344567',
            'quantity' => 0,
            'price' => 27,
            'category_id' => 3753872,
            'image' => new \CurlFile('D:/t.jpg', 'image/jpg', 't.jpg'),
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if (strtoupper($method) == 'POST') curl_setopt($ch, CURLOPT_POST, true);
        //if(!empty(self::$token)) curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if (!empty($body)) curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);

        curl_close($ch);

        return json_decode($result);
        */
    }

    /*
    public function index()
    {
        return view('site.index');
    }
    */
}
