<?php

namespace App\Http\Controllers\Hcm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SocialMediaController extends Controller
{
    public function store(Request $request)
    {
//        $companyId = session('companyId');


        $validation = $request->validate([
            'social_platform' => 'required',
            'social_platform_name' => $request->input('social_platform') == 'Other' ? 'required' : '',
            'profile_url' => 'required|url',
            'employee_id' => [
                'required',
                'exists:hcm_employees,id',
            ]
        ]);

        if ($request->input('social_platform') == 'Other') {

            $socialId = Str::uuid();

            DB::table('hcm_socialmedia_detail')->insert([
                'id' => $socialId,
                'employee_id' => null,
                'platform_name' => $validation['social_platform_name'],
                'url' => null,
                'created_at' => now(),
//                'company_id' => $companyId,

            ]);
        }




        $userSocialId = Str::uuid();


        $storeData = DB::table('hcm_socialmedia_detail')->insert([
            'id' => $userSocialId,
            'employee_id' => $validation['employee_id'],
            'platform_name' => $request->input('social_platform') == 'Other' ? $validation['social_platform_name'] : $validation['social_platform'],
            'url' => $validation['profile_url'],
            'created_at' => now(),
        ]);

        if (!$storeData) {
            return redirect()->back()->with('error' , 'Faild to save social media detail');
        }

        return redirect()->back()->with('success' , 'Social media detail added successfully');
    }
}
