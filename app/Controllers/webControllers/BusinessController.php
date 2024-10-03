<?php

namespace App\Http\Controllers\webControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class BusinessController extends Controller
{

    public function index()
    {
        return view('web.Auth.business');
    }
    public function createBusiness(Request $request)
    {
        // Validate the input data from the first form

        // Store the validated data in the session
        $request->session()->put('business_data', $request->all());

        // Redirect to the second form
        return redirect()->route('Business-Registration-Detail');
    }

    public function createBusinessDetail(Request $request)
    {
        // Retrieve the stored data from the session
        $businessData = $request->session()->get('business_data');

        // Merge the data from the first form with the second form data
        $mergedData = array_merge($businessData, $request->all());

        // Insert the merged data into the database using query builder
        $affected = DB::table('reg_businesses')->insert([
            'business_name' => $mergedData['business_name'],
            'business_type' => $mergedData['business_type'],
            'number_of_users' => $mergedData['number_of_users'],
            'country' => $mergedData['country'],
            'state' => $mergedData['state'],
            'zip' => $mergedData['zip'],
            'sir_name' => $mergedData['sir_name'],
            'first_name' => $mergedData['first_name'],
            'last_name' => $mergedData['last_name'],
            'designation' => $mergedData['designation'],
            'email' => $mergedData['email'],
        ]);

        // Clear the stored data from the session
        $request->session()->forget('business_data');

        if ($affected) {
            // Redirect to a success page or return a response
            return redirect()->back()->with('success', 'Business registration created successfully.');
        } else {
            // Handle the case where the insertion failed
            return redirect()->back()->with('error', 'Failed to create business registration.');
        }
    }
}
