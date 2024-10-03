<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Libraries\appLib;

class OpportunitiesController extends Controller
{
    public function index()
    {
        $opportunities = DB::table('crm_opportunities')
            ->leftjoin('crm_customers', 'crm_customers.id', '=', 'crm_opportunities.cust_id')
            ->leftjoin('users', 'users.id', '=', 'crm_opportunities.assigned_to')
            ->select('crm_opportunities.*', 'crm_customers.name as cust_name', DB::raw("concat(users.firstName,' ',users.lastName) as user_name"))
            // ->where('crm_opportunities.assigned_to', '=', Auth::user()->id)
            ->get();
        return view('crm.opportunities_list', compact('opportunities'));
    }

    public function form($id = null)
    {
        $currencies = DB::table('sys_currencies')->select('code', 'name')->get();
        $types = appLib::getOptions('opportunities_type'); //DB::table('sys_options')->select('description')->where('type','opportunities_type')->where('status',1)->get();
        $sale_stages = appLib::getOptions('opportunities_sale_stage'); //DB::table('sys_options')->select('description')->where('type','opportunities_sale_stage')->where('status',1)->get();
        $lead_sources = appLib::getOptions('opportunities_lead_source'); //DB::table('sys_options')->select('description')->where('type','opportunities_lead_source')->where('status',1)->orderBy('description')->get();
        if ($id) {
            $opportunity = DB::table('crm_opportunities')
                ->leftjoin('crm_customers', 'crm_customers.id', '=', 'crm_opportunities.cust_id')
                ->leftjoin('users', 'users.id', '=', 'crm_opportunities.assigned_to')
                ->select('crm_opportunities.*', 'crm_customers.name as cust_name', DB::raw("concat(users.firstName,' ',users.lastName) as user"))
                ->where('crm_opportunities.id', $id)
                ->get()
                ->first();
            return view('crm.opportunities', compact('currencies', 'types', 'sale_stages', 'lead_sources', 'opportunity'));
        } else {
            return view('crm.opportunities', compact('currencies', 'types', 'sale_stages', 'lead_sources'));
        }
    }

    public function save(Request $request)
    {
        //dd($request->input());
        if ($request->id) {
            $opportunityData = array(
                "id" => $request->id,
                "name" => $request->opportunity,
                "cust_id" => $request->lead_ID,
                "cur_name" => $request->currency,
                "close_date" => $request->close_date,
                "amount" => $request->amount,
                "lead_type" => $request->lead_type,
                "sale_stage" => $request->sale_stage,
                "lead_source" => $request->lead_source,
                "compaign_id" => $request->compaign,
                "next_step" => $request->next_step,
                "assigned_to" => $request->assign_ID ? $request->assign_ID : Auth::user()->id,
                "description" => $request->description,
                "updated_at" => Carbon::now(),
            );
            DB::table('crm_opportunities')->where('id', $request->id)->update($opportunityData);
        } else {
            $id = str::uuid()->toString();
            $companyId = config('app_session.companyId');
            $userId = Auth::id();
            $opportunityData = array(
                "id" => $id,
                "name" => $request->opportunity,
                "cust_id" => $request->lead_ID,
                "cur_name" => $request->currency,
                "close_date" => $request->close_date,
                "amount" => $request->amount,
                "lead_type" => $request->lead_type,
                "sale_stage" => $request->sale_stage,
                "lead_source" => $request->lead_source,
                "compaign_id" => $request->compaign,
                "next_step" => $request->next_step,
                "assigned_to" => $request->assign_ID ? $request->assign_ID : Auth::user()->id,
                "description" => $request->description,
                "company_id" => $companyId,
                "created_by" => $userId,
                "created_at" => Carbon::now(),
            );
            DB::table('crm_opportunities')->insert($opportunityData);
        }

        return redirect('Crm/Opportunities/List');
    }

    public function remove($id)
    {
        DB::table('crm_opportunities')->where('id', $id)->delete();
        return redirect('Crm/Opportunities/List')->with('delete-msg', 'Opportunity Is Removed');
    }

    public function searchOpportunities(Request $request)
    {

        $whereCluse = array();
        if ($request->lead_id) {
            array_push($whereCluse, array('crm_opportunities.cust_id', '=', $request->lead_id));
        }
        if ($request->assign_id) {
            array_push($whereCluse, array('crm_opportunities.assigned_to', '=', $request->assign_id));
        }
        if ($request->sale_stage) {
            array_push($whereCluse, array('crm_opportunities.sale_stage', '=', $request->sale_stage));
        }
        $opportunities = DB::table('crm_opportunities')
            ->leftjoin('crm_customers', 'crm_customers.id', '=', 'crm_opportunities.cust_id')
            ->leftjoin('users', 'users.id', '=', 'crm_opportunities.assigned_to')
            ->select('crm_opportunities.*', 'crm_customers.name as cust_name', DB::raw("concat(users.firstName,' ',users.lastName) as project_manager"))
            ->where($whereCluse)
            ->orderBy('crm_opportunities.close_date')
            ->get();
        return  $opportunities;
    }
}
