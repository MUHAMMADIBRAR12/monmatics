<?php

/*
 * The app core rights are with Solutions Wave.
 * For further help you can contact with info@solutionswave.com
 * All content of project are copyright with Solutions Wave.
 *
 * This class contains DB related functions of entire app.
 * However, logic of each module is located in their repsective models.
 */

namespace App\Libraries;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Str;
//use Illuminate\Support\DateFactory;
use phpDocumentor\Reflection\Types\Object_;
use Illuminate\Support\Facades\Auth;
use App\Libraries\appLib;
use Image;
use PDF;

class dbLib
{
    // function return array for followign info
    // current company Id
    // current user id
    public static function getInfo()
    {
        $currency = dbLib::getCurrencies(1);
        //        echo "<pre>";
        //        print_r($currency);
        //        die();


        $info = array(
            "companyId" => session('companyId'),
            "userId" => Auth::id(),
            "cur_id" => $currency->id,
            "cur_rate" => $currency->rate,
        );
        return $info;
    }

    public static function getCompanyInfo($companyId = null)
    {
        $companyId = ($companyId) ? $companyId : session('companyId');
        $companyInfo = DB::table('sys_companies')->select()->where('id', $companyId)->first();
        return $companyInfo;
    }


    public static function getCurrencies($baseCurrency = null)
    {
        if ($baseCurrency)
            $currency = DB::table('sys_currencies')->select()->where('base_currency', '=', 1)->first();
        else
            $currency = DB::table('sys_currencies')->select()->get();

        return $currency;
    }


    public static function getMonth($date)
    {
        // $month=date_format(date_create($date),"ym");
        $month = date_format(date_create($date), "Y");
        return $month;
    }

    // This function generates new number for document.
    public static function getNumber($table, $type = null)
    {
        $wahereClausees = appLib::whereData();
        if ($type) {
            $number = DB::table($table)->select('number')->where($wahereClausees)->where('voucher_type', '=', $type)->orderBy('number', 'DESC')->first();
        } else {
            $number = DB::table($table)->select('number')->where($wahereClausees)->orderBy('number', 'DESC')->first();
        }
        $number = isset($number->number) ? $number->number : 0;
        return ++$number;
    }
    // get new voucher number
    // type: voucher type, CV, CR, BR, etc
    // month: voucher number is based on month serical
    // companyid: company id for voucher number
    // return: new voucher number.
    /* public static function getVoucherNumber($type, $month, $companyId)
    {
       // echo $type ."-".$month."-".$companyId;
        $data = DB::table('fs_transmains')->select(array('voucher_number'))->where('voucher_type','=',$type)
                        ->where('month','=', $month)
                        ->where('company_id','=',$companyId)
                        ->orderByDesc('voucher_number')->first();

        if($data)
            return $data->voucher_number + 1;
        else
            return "0001";


    }
    */


    public static function getAccountCode($id)
    {
        $data = DB::table('fs_coas')->select('code')->where('coa_id', '=', $id)->orderByDesc('code')->first();
        if ($data)
            return $data->code + 1;
        else {
            return 1;
        }
    }

    public static function getDocumentNumbers($table, $additionalCheck = null)
    {
        return DB::table($table)
            ->select('id', DB::raw("concat(month,'-',LPAD(number,4,0)) as doc_number"))
            ->where('company_id', session('companyId'))
            ->where($additionalCheck)
            ->orderBy('number')
            ->orderBy('month')
            ->get();
    }
    public static function createCoaAccount($data)
    {
        $companyId = session('companyId');
        $coa_id = $data['coa_id'];
        $accountCode = dbLib::getAccountCode($coa_id);
        $data['code'] = $accountCode;
        $id = DB::table('fs_coas')->insertGetId($data);
        return $id;
    }

    public static function updateCoaAccount($data, $id)
    {
        DB::table('fs_coas')->where('id', '=', $id)->update($data);
    }

    public static function getTaxes($type = null)
    {
        $taxes = DB::table('sys_taxes')->where('status', '=', '1')->get();
        return $taxes;
    }

    public static function getDiscounts($type = null)
    {
        $discounts = DB::table('sys_discounts')->where('status', '=', '1')->get();
        return $discounts;
    }

    public static function getPaymentTerms()
    {
        $paymentTerms = DB::table('sys_payment_terms')->orderBy('terms')->get();
        return $paymentTerms;
    }


    public static function getSalesAccounts($company_id = -1)
    {
        $salesAccounts = DB::table('fs_coas')->where([['coa_id', '=', 6], ['company_id', '=', $company_id]])->orderBy('name')->get();
        return $salesAccounts;
    }

    public static function getTransactionAccounts()
    {
        $companyId = session('companyId');
        $transactionAccounts = DB::table('fs_coas')->where([['trans_group', '=', 1], ['company_id', '=', $companyId]])->orderBy('name')->get();
        return  $transactionAccounts;
    }

    public static function getProjects($status = null)
    {
        $companyId = session('companyId');
        $Projects = DB::table('prj_projects')->where([['status', '=', $status], ['company_id', '=', $companyId]])->get();
        return $Projects;
    }

    public static function getEmployees($status = null)
    {
        $companyId = session('companyId');
        $employees = DB::table('hcm_employee')->select('id', DB::raw("concat(hcm_employee.first_name,' ',hcm_employee.last_name) as employee_name"))->where([['status', '=', $status], ['company_id', '=', $companyId]])->get();
        return $employees;
    }

    public static function getDepartments($status = 1)
    {
        $companyId = session('companyId');
        $departments = DB::table('sys_departments')->select()->where([['status', '=', $status], ['company_id', '=', $companyId]])->get();
        return $departments;
    }
    public static function wipAccounts()
    {
        return DB::table('fs_coas')->select('id', 'name')->where('coa_id', 23)->orWhere('coa_id', 40)->get();
    }

    public static function getWarehouses($status = 1)
    {
        $companyId = session('companyId');
        $warehouse = DB::table('sys_warehouses')->where('status', $status)->where('company_id', $companyId)->get();
        return $warehouse;
    }

    public static function getUnits($status = 1)
    {
        $companyId = session('companyId');
        $warehouse = DB::table('sys_warehouses')->where('status', $status)->where('company_id', $companyId)->get();
        return $warehouse;
    }


    // The function convert quantity units.
    // Qty to be converted
    // Converting Unit
    // Type: 1=From base unit to secondary unit.
    //       2=From Secondary unit to base unit.
    public static function convertUnit($qty, $unit, $type)
    {

        if (is_numeric($unit))
            $opt_val = DB::table('sys_units')->select('operator_value')->where('id', $unit)->first();
        else
            $opt_val = DB::table('sys_units')->select('operator_value')->where('name', $unit)->first();

        if (isset($opt_val)) {
            if ($type == 1)   // 1=From base unit to secondary unit.
                return $qty / $opt_val->operator_value;
            elseif ($type == 2)    //2=From Secondary unit to base unit.
                return $qty * $opt_val->operator_value;
            else
                return 1; //$qty;       // For development we are setting as'N/A' later set as $qty
        } else
            return 1; //$qty;       // For development we are setting as'N/A' later set as $qty
    }

    public static function getRoles($status = 1)
    {
        $companyId = session('companyId');
        $roles = DB::table('sys_roles')->select('name')->where([['status', '=', $status], ['company_id', '=', $companyId]])->get()->unique('name');
        return $roles;
    }

    public static function getSpecialDocument($table, $id)
    {
        $doc = DB::table($table)
            ->select(DB::raw("concat(month,'-',LPAD(number,4,0)) as doc_number"))
            ->where('id', $id)
            ->first();
        return $doc->doc_number;
    }





    public static function uploadDocument($source_id, $file, $title = "", $order = 0)
    {
        $companyId = session('companyId');
        $id =  Str::uuid()->toString();

        $content = Response::make($file->get());
        $ext = $file->extension();
        $fileData = array(
            "id" => $id,
            "source_id" => $source_id,
            "file" => $file->getClientOriginalName(),
            "type" => $file->getClientMimeType(),
            // "size"=>$file->getClientSize(),
            //"content"=>$content->content(),
            "content" => $file->get(),
            "company_id" => $companyId,
            "title" => $title,
            "order" => $order
        );

        DB::table('sys_attachments')->insert($fileData);

    }


    public static function deleteAttachment($id)
    {
        DB::table('sys_attachments')->where('source_id', $id)->delete();
    }

    public static function getAttachment($source_id)
    {
        return DB::table('sys_attachments')->select()->where('source_id', '=', $source_id)->get();
    }

    public static function getAttachmentType($id)
    {
        $result = DB::table('sys_attachments')->select()->where('id', '=', $id)->first();
        if (!$result)
            $result = DB::table('sys_attachments')->select()->where('source_id', '=', $id)->first();

        if ($result)
            return $result->type;
        else
            return null;
    }

    public  static function downloadAttachment($id)
    {
        $result = DB::table('sys_attachments')->select()->where('id', '=', $id)->first();
        if (!$result)
            $result = DB::table('sys_attachments')->select()->where('source_id', '=', $id)->first();

        if ($result) {
            // echo ($result->type);
            header('Content-type: ' . $result->type);
            header("Content-Disposition: attachment; filename=$result->file");
            print($result->content);
        }
    }

    // public static function  displayImage($id)
    // {
    //     $result = DB::table('sys_attachments')->select()->where('source_id', '=', $id)->first();

    //     if (!$result)
    //         $result = DB::table('sys_attachments')->select()->where('source_id', '=', $id)->first();

    //     header('Content-type: ' . $result->type);
    //     print($result->content);
    // }

    public static function  pdfImage($id)
    {
        $result = DB::table('sys_attachments')->select()->where('id', '=', $id)->first();
        if (!$result)
            $result = DB::table('sys_attachments')->select()->where('source_id', '=', $id)->first();

        if ($result) {
            $type = Str::after($result->type, '/');
            return array(
                "type" => $type,
                "content" => $result->content
            );
        }
    }
}
