<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class ProfileController extends BaseController
{
    function myProfile(){
    	return view('profile.my-profile');
    }
}