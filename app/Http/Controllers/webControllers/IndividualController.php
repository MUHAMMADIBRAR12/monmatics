<?php

namespace App\Http\Controllers\webControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndividualController extends Controller
{

    public function individualReg(){
        return view('web.Auth.individuallogin');
    }
    public function individualCreate(Request $request)
    {
        // return view('web.Auth.individuallogin');
        // dd($request->all());
        // exit();
        // $request->validate([
        //     'first_name' => 'required|max:50',
        //     'last_name' => 'required|max:50',
        //     'phone' => 'nullable|numeric',
        //     'email' => 'required|email|max:50',
        //     'country' => 'required|max:50',
        //     'state' => 'required|max:50',
        //     'zip' => 'required|max:50',
        // ]);

        // Insert the data into the database using query builder
        $data = DB::connection('mysql1')->table('reg_individuals')->insert([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'country' => $request->input('country'),
            // 'state' => $request->input('state'),
            'zip' => $request->input('zip'),
        ]);

        // Check if the insertion was successful
        if ($data) {
            // Optionally, you can redirect to a success page or return a response
            return redirect()->back()->with('success', 'Individual registration created successfully.');
        } else {
            // Handle the case where the insertion failed
            return redirect()->back()->with('error', 'Failed to create individual registration.');
        }
    }
}
