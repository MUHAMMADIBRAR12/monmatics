<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Libraries\dbLib;
use App\Libraries\accountsLib;
class OpeningBalanceController extends Controller
{
    public $account=array();
    public function form()
    {
        /*
        $accounts=DB::table('fs_coas')
                    ->leftjoin('fs_transdetails','fs_transdetails.coa_id','=','fs_coas.id')
                    ->join('fs_transmains',function($join){
                        $join->on('fs_transmains.id','=','fs_transdetails.trm_id');
                        $join->on('fs_transmains.voucher_type','=',DB::raw("'99'"));
                    })
                    ->select('fs_coas.id','fs_coas.name','fs_transdetails.debit','fs_transdetails.credit')
                    ->where('fs_coas.trans_group',1)
                    ->where('fs_coas.company_id',$companyId)
                    ->get();
        */
        //$companyId = session('companyId');
        /*
        $accounts=DB::table('fs_coas')
                    ->leftjoin('fs_transdetails','fs_transdetails.coa_id','=','fs_coas.id')
                    ->leftjoin('fs_transmains','fs_transmains.id','=','fs_transdetails.trm_id') 
                    ->select('fs_coas.id','fs_coas.name','fs_transdetails.debit','fs_transdetails.credit','fs_transmains.date')
                    ->where('fs_coas.trans_group',1)
                    ->orderBy('fs_coas.name')
                    ->where('fs_transmains.voucher_type','=',99)
                    ->where('fs_coas.company_id',$companyId)
                    ->get();
                    */
       // if($accounts->isEmpty())
       // {
            //$accounts=DB::table('fs_coas')
                      //  ->select('fs_coas.id','fs_coas.name')
                      //  ->where('fs_coas.trans_group',1)
                      //  ->where('fs_coas.company_id',$companyId)
                     //   ->orderBy('fs_coas.name')
                    //    ->get();
      //  }
       // dd($accounts);
        $accounts=DB::table('fs_coas')->select('id','name')->where('trans_group',0)->orderBy('name')->get();
        $date = DB::table('');
        $currencies=dbLib::getCurrencies();
        return view('accounts.opening_balance',compact('accounts','currencies','accounts'));
    }

    //riz
    public function openingAccount($coaId=-1)
    {
         $coas = DB::table('fs_coas')->select()->where('coa_id','=', $coaId)->get();
         
         foreach ($coas as $coa)
         {
             if($coa->trans_group==0)
                 $this->openingAccount($coa->id);
             else
                 $this->getOpeningBalance($coa->coa_id, $coa->name);
         }
    }
    //riz
    public function getOpeningBalance($coa_id, $name)
    {
        $coas = DB::table('fs_transdetails')->select('debit', 'credit')           
                    ->join('fs_transmains',function($join){
                        $join->on('fs_transmains.id','=','fs_transdetails.trm_id');
                        $join->on('fs_transmains.voucher_type',DB::raw('99'));
                    })
                    ->where('coa_id','=',$coa_id)->first();
        
            $this->account[]=array ("coa_id"=>$coa_id, "name"=>$name, "debit"=>$coas ? $coas->debit : 0, "credit"=>$coas ?$coas->credit : 0); 
    }

    public function getOpeningAccounts(Request $request)
    {
        $this->openingAccount($request->coa_id);
        //print_r($this->account);
        return response()->json($this->account);
        
    }

    // public function getOpeningBalance(Request $request)
    // {
    //     $coas=OpeningBalanceController::openingBalance($request->id);
    //     return $coas;
    // }

    // public static function openingBalance($coaId)
    // {
    //     $mainData = array ();
    //     if($coaId == -1)
    //     {
    //         $coas = DB::table('fs_coas')
    //         ->leftJoin('fs_transdetails','fs_transdetails.coa_id','=','fs_coas.id')
    //         ->select('fs_coas.id','fs_coas.name','fs_transdetails.debit','fs_transdetails.credit')
    //         ->orderBy('fs_coas.name')
    //         ->orderby('fs_coas.id')->get();
    //     }
    //     else
    //     {
    //         // $coas = DB::table('fs_coas')->select(array('id','coa_id', 'name','editable','trans_group'))->where('coa_id','=', $coaId)->orderby('id')->get();
    //         $coas = DB::table('fs_coass')
    //         ->leftJoin('fs_transdetails','fs_transdetails.coa_id','=','fs_coas.id')
    //         ->join('fs_transmains',function($join){
    //             $join->on('fs_transmains.id','=','fs_transdetails.trm_id');
    //             $join->on('fs_transmains.voucher_type',DB::raw('99'));
    //         })
    //         ->select('fs_coas.id','fs_coas.name','fs_transdetails.debit','fs_transdetails.credit')
    //         ->where('fs_coas.coa_id','=', $coaId)
    //         ->orderBy('fs_coas.name')
    //         ->orderby('fs_coas.id')->get();
    //     }
        
    //     foreach ($coas as $coaId)
    //     {
          
    //         $mainData[]= $coaId;
    //        $coaIdData = OpeningBalanceController::openingBalance($coaId->id);
    //        $mainData[]= $coaIdData;
    //     }     
       
    //     return  $mainData;
    // }

    public function save(Request $request)
    {
        
        $date = Carbon::now();

        //dd($request->input());
        DB::table('fs_transmains')->where('note','Opening Balance')->delete();
        DB::table('fs_transdetails')->where('description','Opening Balance')->delete();
        //dd($request->input());
        $transmain=array(
            'id'=>$request->trm_id,
            'date'=>$date,
            'voucher_type'=>99,
            'note'=>'Opening Balance'
        );
        $trm_id=accountsLib::saveTransMain($transmain);
        
        $count = count($request->coa_id);
        for($i=0;$i<$count;$i++)
        {
            $data=array(
                'trm_id'=>$trm_id,
                'coa_id'=>$request->coa_id[$i],
                'description'=>'Opening Balance',
                'debit'=>$request->debit[$i],
                'credit'=>$request->credit[$i]
            );
            accountsLib::saveTransDetail($data);
        }

        return back();

    }
}
