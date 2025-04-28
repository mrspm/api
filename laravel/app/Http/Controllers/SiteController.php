<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index()
    {
        $products = Product::first();
        dd($products);
        die;
    }

    /*
    public function index()
    {
        return view('site.index');
    }
    */
}
