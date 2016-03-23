<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('front.homepage');
    }

    public function show()
    {
    	return view('front.product.show');
    }
}