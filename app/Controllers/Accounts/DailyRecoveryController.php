<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Libraries\accountsLib;
use App\Libraries\dbLib;
use Illuminate\Support\Str;
use Carbon\Carbon;
class DailyRecoveryController extends Controller
{
    public function index()
    {
        $daily_recovery_list=DB::table('fs_daily_recovery')
                                ->join('fs_coas as fs1','fs1.id','=','fs_daily_recovery.received_coa_id')
                                ->join('fs_coas as fs2','fs2.id','=','fs_daily_recovery.cst_coa_id')
                                ->select('fs_daily_recovery.*','fs1.name as received_in','fs2.name as received_from')
                                ->where('fs_daily_recovery.status','Pending')
                                ->where('fs_daily_recovery.company_id',session('companyId'))
                                ->orderBy('fs_daily_recovery.month')
                                ->orderBy('fs_daily_recovery.number')
                                ->get();

        return view('accounts.daily_recovery_list',compact('daily_recovery_list'));
    }

    public function form($id=null)
    {
        if($id)
        {
            $daily_recovery=DB::table('fs_daily_recovery')
                                ->join('fs_coas as fs1','fs1.id','=','fs_daily_recovery.received_coa_id')
                                ->join('fs_coas as fs2','fs2.id','=','fs_daily_recovery.cst_coa_id')
                                ->select('fs_daily_recovery.*','fs1.name as received_in','fs2.name as received_from')
                                ->where('fs_daily_recovery.id',$id)
                                ->get()
                                ->first();         
            $coa_id=($daily_recovery->type==1) ? 10 : 9 ;
            $received_in=DB::table('fs_coas')->select('id','name')->where('coa_id',$coa_id)->get();
            return view('accounts.daily_recovery',compact('daily_recovery','received_in'));
        }
        else
        {
            return view('accounts.daily_recovery');
        }
    }
    
    public function save(Request $request)
    {
        //dd($request->input());
        if($request->has('status'))
        {
             //save transmains data
            $transMains=array(
                "id"=>$request->trm_id,
                "date"=>$request->date,
                "voucher_type"=>$request->type,
                "note"=>$request->description,
            );
            $trmId=accountsLib::saveTransMain($transMains);

            accountsLib::delTransDetail($trmId);
            //save transdetails data
            $transDetails=array(
                "trm_id"=>$trmId,
                "coa_id"=>$request->received_from_ID,
                "description"=>$request->description,
                "debit"=>0,
                "credit"=>$request->amount,
            );
            accountsLib::saveTransDetail($transDetails);
        
            // save transdetails data 2nd time
            $transDetails['debit']=$request->amount;
            $transDetails['credit']=0;
            $transDetails['coa_id']=$request->received_in;
            accountsLib::saveTransDetail($transDetails);
        }

        //Daily Recovery
        $month = dbLib::getMonth($request->date);
        $daily_recovery=array(
            "date"=>$request->date,
            "month"=>$month,
            "type"=>$request->type,
            "status"=>$request->has('status') ? $request->status : 'Pending',
            "trm_id"=>isset($trmId) ? $trmId: '',
            "received_coa_id"=>$request->received_in,
            "cheque_no"=>$request->cheque_no,
            "cst_coa_id"=>$request->received_from_ID,
            "ref_no"=>$request->ref_no,
            "amount"=>$request->amount,
            "description"=>$request->description,
        );
        if($request->id)
        {
            $daily_recovery['updated_at']=Carbon::now();
            DB::table('fs_daily_recovery')->where('id',$request->id)->update($daily_recovery);
        }
        else
        {
            $daily_recovery['id']= str::uuid()->toString();
            $daily_recovery['company_id']= session('companyId');
            $daily_recovery['created_at']=Carbon::now();
            $daily_recovery['number']= dbLib::getNumber('fs_daily_recovery');
            DB::table('fs_daily_recovery')->insert($daily_recovery);
        }

        return redirect('Accounts/DailyRecorvery/List');
    }
    public function report(Request $request)
    {
        $whereCluse = array();
        $companyId = session('companyId');
        if($request->from_date)
        {
            $arrFromDate = array ('fs_daily_recovery.date', '>=',$request->from_date);
            array_push($whereCluse, $arrFromDate);
        }
        if($request->to_date)
        {
            $arrToDate = array ('fs_daily_recovery.date', '<=', $request->to_date);
            array_push($whereCluse, $arrToDate);
        }
        if($request->status)
        {
            $arrStatus = array ('fs_daily_recovery.status', '=', $request->status);
            array_push($whereCluse, $arrStatus);
        }
        if($request->cust_coa_id)
        {
            $arrStatus = array ('fs_daily_recovery.cst_coa_id', '=', $request->cust_coa_id);
            array_push($whereCluse, $arrStatus);
        }
        $daily_recovery_list=DB::table('fs_daily_recovery')
                                ->leftJoin('fs_transmains','fs_transmains.id','=','fs_daily_recovery.trm_id')
                                ->join('fs_coas as fs1','fs1.id','=','fs_daily_recovery.received_coa_id')
                                ->join('fs_coas as fs2','fs2.id','=','fs_daily_recovery.cst_coa_id')
                                ->select('fs_daily_recovery.*',DB::raw("concat(fs_daily_recovery.month,'-',LPAD(fs_daily_recovery.number,4,0)) as recovery_num"),'fs1.name as received_in','fs2.name as received_from','fs_transmains.post_status')
                                ->where('fs_daily_recovery.company_id',$companyId)
                                ->where($whereCluse)
                                ->orderBy('fs_daily_recovery.month')
                                ->orderBy('fs_daily_recovery.number')
                                ->get();
        return $daily_recovery_list;
        
    }
}
