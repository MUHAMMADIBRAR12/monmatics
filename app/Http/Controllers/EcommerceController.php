<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class EcommerceController extends BaseController
{
    function dashboard(){
    	return view('ecommerce.dashboard');
    }

    function product(){
    	return view('ecommerce.product');
    }

    function productList(){
        return view('ecommerce.product-list');
    }

    function productDetail(){
    	return view('ecommerce.product-detail');
    }
}