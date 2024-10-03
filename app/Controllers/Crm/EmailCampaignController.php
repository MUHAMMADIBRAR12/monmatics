<?php

namespace App\Http\Controllers\Crm;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\Artisan;
use App\Mail\UserMail;
use Exception;
use Illuminate\Support\Facades\Mail;

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

        $campaginList = DB::table('crm_campaign')
            ->select('crm_campaign.*')
            ->get();


        $templateList = DB::table('crm_email_templates')
            ->select('crm_email_templates.*')
            ->get();

        return view('email_campaign.create-email_campaign',compact('campaginList','templateList'));
    }

    public function  categoryData(Request $request)
    {
        if ($request->category == 'Customer') {
            $data = DB::table('crm_customers')
                ->join('crm_customers_address', 'crm_customers.id', '=', 'crm_customers_address.cust_id')
                ->select('crm_customers.id', 'crm_customers.name', 'crm_customers_address.email')
                ->get();
        
            return response()->json($data);
        }
         else if ($request->category == 'Vendor') {
            $data = DB::table('pa_vendors')
                ->join('pa_vendor_details', 'pa_vendors.id', '=', 'pa_vendor_details.pa_ven_id')
                ->select('pa_vendors.id', 'pa_vendors.name', 'pa_vendor_details.email')->get();
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
    // Insert data into 'crm_campaign' table
    // print_r ($request->all());
    // die;
    $id = Str::uuid()->toString();
    $result = DB::table('crm_campaign')->insert([
        'id' => $id,
        'campaign_name' => $request->campaign_name,
        'start_date' => $request->camp_date,
        'category' => $request->bulk_selection,
    ]);

    if ($result) {
        // Get the selected checkboxes from the form
        $bulkCheck = $request->input('bulk_check');
        dd($bulkCheck);

        if (($bulkCheck)) {
            foreach ($bulkCheck as $check) {
                if (is_array($check) && isset($check['name']) && isset($check['email'])) {
                    $name = $check['name']; // Access 'name' property
                    $email = $check['email']; // Access 'email' property
        
                    // Debugging: Output the entire object to check
                    // dd($check);
        
                    DB::table('crm_campaign_email_list')->insert([
                        'camp_id' => $id,
                        'name' => $name,
                        'email' => $email,
                    ]);
                } else {
                    // Debugging: Output the unexpected structure
                    dd($check);
                }
            }
        }
        
        
        // Send emails now
        try {
            $templateId = $request->input('temp_selection');
            $users = DB::table('users')->get();
            $record = DB::table('crm_email_templates')->where('id', $templateId)->first();

            foreach ($users as $user) {
                Mail::to($user->email)->send(new UserMail('rizwannaqvi@gmail.com', $record->subject, $record->body_text));
                return redirect()->route('crm.campList')->with('success', 'Emails sent successfully!');
            }

        } catch (Exception $e) {
            // Handle the exception here
            return redirect()->back()->with('error', 'An error occurred while sending emails: ' . $e->getMessage());
        }
    } else {
        return redirect()->back()->with('error', 'Failed to create the campaign. Please try again.');
    }
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
                {try {
                    $id = $request->input('temp_selection');

                    $users = DB::table('users')->get();

                    $record = DB::table('crm_email_templates')->where('id', $id)->first();

                    foreach ($users as $user) {
                        Mail::to($user->email)->send(new UserMail('rizwannaqvi@gmail.com', $record->subject, $record->body_text));
                    }

                    return redirect()->route('crm.campList')->with('success', 'Emails sent successfully!');
                } catch (Exception $e) {
                    // Handle the exception here
                    return redirect()->back()->with('error', 'An error occurred while sending emails: ' . $e->getMessage());
                }
        }
}
