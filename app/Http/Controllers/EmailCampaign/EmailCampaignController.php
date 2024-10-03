<?php

namespace App\Http\Controllers\EmailCampaign;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\Artisan;

class EmailCampaignController extends Controller
{
    public function  campList()

    {
        $campaginList = DB::table('crm_campaign')
            ->select('crm_campaign.*')
            ->get();

        return view('email_campaign.campagin_list', compact('campaginList'));
    }
    public function  campaignform()
    {
        return view('email_campaign.create-email_campaign');
    }

    public function  categoryData(Request $request)
    {
        if ($request->category == 'Customer') {
            $data = DB::table('crm_customers')->select('id', 'name', 'email')->get();
            return response()->json($data);
        } else if ($request->category == 'Vendor') {
            $data = DB::table('pa_vendor_details')->select('id', 'name', 'email')->get();
            return response()->json($data);
        } else if ($request->category == 'Contacts') {
            $data = DB::table('crm_contacts')->select('id', 'first_name as name', 'email')->get();
            return response()->json($data);
        } else if ($request->category == 'Users') {
            $data = DB::table('users')->select('id', 'name', 'email')->get();
            return response()->json($data);
        }
    }



    public function createCampaign(Request $request)
    {
        //  return $request->all();


        $id = Str::uuid()->toString();
        $result = DB::table('crm_campaign')->insert([
            'id' => $id,
            'campaign_name' => $request->camp_name,
            'category' => $request->category,
            // 'name' => json_encode($request->bulk_check),
            // 'email' => $request->email,
            'start_date' => $request->camp_date,
            'category' => $request->bulk_selection,
        ]);




        $bulkCheck = $request->input('bulk_check');
        $i = 0;

        if (is_array($bulkCheck)) {
            $i = 0;
        foreach ($bulkCheck as $check) {

            if ($check) {
                $name = $request->name[$i];
                $email = $request->email[$i];


                DB::table('crm_campaign_email_list')->insert([

                    'camp_id' => $id,
                    'name' => $name,
                    'email' => $email,
                ]);
            }
            $i++;
        }
    }
        return redirect('Crm/EmailCampaign');
    }







    public function editcamp_email($id)
    {


        $campaignList = DB::table('crm_campaign')->where('id', $id)->first();


        return view('email_campaign.edit-email_campaign', compact('campaignList'));
    }
    public function updatecamp_email(Request $request)
    {
        DB::table('crm_campaign')->where('id', $request->edit_campaign_id)->update([

            'campaign_name' => $request->camp_name,
            'category' => $request->category,
            'start_date' => $request->camp_date,
            'category' => $request->bulk_selection,
        ]);

        return redirect('Crm/EmailCampaign');
    }
    public function deletecamp_email($id)
    {
        DB::table('crm_campaign')->where('id', $id)->delete();
        return redirect('Crm/EmailCampaign');
    }

    public function Emailsend(Request $request)
    {
        $campaginList = DB::table('crm_campaign')
            ->select('crm_campaign.*')
            ->get();


        $templateList = DB::table('crm_email_templates')
            ->select('crm_email_templates.*')
            ->get();



        return view('email_campaign.send_email', compact('campaginList', 'templateList'));
    }



    public function refreshInfo(Request $request)
    {
        // dd($request->all());
        $List = DB::table('crm_campaign_email_list')
            ->leftJoin('crm_campaign', 'crm_campaign_email_list.camp_id', '=', 'crm_campaign.id')
            ->select('crm_campaign_email_list.name', 'crm_campaign_email_list.email', 'crm_campaign.campaign_name')
            ->where('crm_campaign_email_list.camp_id', '=', $request->campaign)
            ->get();


        return response()->json($List);
    }

    public function preViewTemplate(Request $request)
    {

        $templateList = DB::table('crm_email_templates')

            ->select('crm_email_templates.*')
            ->where('crm_email_templates.id', '=', $request->template)
            ->get();



        return response()->json($templateList);
    }

    public function sendEmailsNow(Request $request)
    {
        // return $request->all();
        $templateName = $request->input('temp_selection');

        // Call the SendEmails command with the selected email template
        Artisan::call('send:email', [
            '--template_name' => $templateName,

        ]);

        return redirect()->back()->with('success', 'Emails sent successfully!');
    }
}
