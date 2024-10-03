<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class PagesController extends BaseController
{
    function blank(){
    	return view('pages.blank');
    }

    function gallery(){
    	return view('pages.gallery');
    }

    function invoices1(){
        return view('pages.invoices1');
    }

    function invoices2(){
        return view('pages.invoices2');
    }
    
    function pricing(){
        return view('pages.pricing');
    }

    function profile(){
        return view('pages.profile');
    }

    function search(){
        return view('pages.search');
    }

    function timeline(){
    	return view('pages.timeline');
    }
}
