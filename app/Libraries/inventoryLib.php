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
use Illuminate\Support\Str;
//use Illuminate\Support\DateFactory;
use phpDocumentor\Reflection\Types\Object_;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use App\Libraries\dbLib;
use App\Libraries\appLib;

class inventoryLib
{

    public static  function getInvNo($month)
    {
        $companyId = session('companyId');
        $data = DB::table('inv_inventory')
            ->where('month', '=', $month)
            ->where('company_id', '=', $companyId)->max('number');
        if ($data)
            return $data + 1;
        else
            return "0001";
    }

    public static function getItemDetail($id)
    {
        $itemDetail = DB::table('inv_products')->where('id', '=', $id)->first();
        return $itemDetail;
    }

    public static function addInventory($data)
    {
        $data['id'] = str::uuid()->toString();
        $data['month'] = dbLib::getMonth($data['date']);
        $data['number'] = dbLib::getNumber('inv_inventories');
        $data['company_id'] = session('companyId');
        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();
        DB::table('inv_inventories')->insert($data);
    }

    public static function issueInventory($data)
    {

        // update inventory function set values in inv_inventories and calculate item issue rate
        // and send Item rate and amount .
        //dd($data);
        list($rate, $amount) = inventoryLib::updateInventory($data['prod_id'], $data['warehouse_id'], $data['qty_out'], $data['source_detail_id']);
        // Add new items rate & amount in array recieved in function
        $data['rate'] = $rate;
        $data['amount'] = $amount;



        // Array pass to addInventory function to insert values in inventory table.
        inventoryLib::addInventory($data);
        //

        return $data;
    }

    public static function getStock($prod_id, $warehouse_id = null)
    {

        if ($warehouse_id) {
            $companyId = session('companyId');
            //$month = config('app_session.month');
            $stock = DB::table('inv_inventories')
                ->select(DB::raw("sum(balance) as qty"))
                ->where([
                    ['company_id', '=', $companyId],
                    //  ['status', '=', 'Completed'],
                    // ['approvel', '=', 'Approved'],
                    // ['month', '=',$month],
                    ['warehouse_id', '=', $warehouse_id],
                    ['prod_id', '=', $prod_id],
                ])->first();
            return $stock;
        } else {
            $companyId = session('companyId');
            $stock = DB::table('inv_inventories')
                ->select(DB::raw("sum(balance) as qty"))
                ->where([
                    ['company_id', '=', $companyId],
                    //  ['status', '=', 'Completed'],
                    // ['approvel', '=', 'Approved'],
                    // ['month', '=',$month],
                    ['prod_id', '=', $prod_id],
                ])->first();
            return $stock;
        }
    }

    public static function getPurchaseStock()
    {
    }

    public static function updateInventory($product, $warehouse, $reqst_qty, $doDetailId)
    {

        $companyId = session('companyId');
        // dd('company'.$companyId.'prod'.$product.'warehouse'.$warehouse.'qty'.$reqst_qty.'do_detail'.$doDetailId);
        //dd($product.'and company is'.$companyId.'and warehouse is '.$warehouse);
        $totalQty = 0;
        $totalAmount = 0;
        $inventory = DB::table('inv_inventories')
            ->select('id', 'balance', 'rate', 'qty_in', 'source_id', 'batch_no', 'date')
            ->where([
                ['company_id', '=', $companyId],
                ['warehouse_id', '=', $warehouse],
                ['prod_id', '=', $product],
                ['balance', '>', 0],
            ])
            ->orderBy('number')
            ->get();
        $inventory_arr = array();
        $inventory_arr_final = array();
        foreach ($inventory as $inven) {

            // Seting source id. \
            // If Qty_in = balance then it set source id to update grn_edit mode = 0.
            $source_id = ($inven->qty_in == $inven->balance) ? $inven->source_id : '';

            // Add row values to array
            $inventory_arr['id'] = $inven->id;
            $inventory_arr['balance'] = $inven->balance;
            $inventory_arr['rate'] = $inven->rate;
            $inventory_arr['source_id'] = $source_id;
            $inventory_arr['batch_no'] = $inven->batch_no;
            $inventory_arr['date'] = $inven->date;

            $totalQty += $inven->balance;

            //if balnce qty is less then/equal to requested qty.
            if ($totalQty <= $reqst_qty) {
                $totalAmount += ($inven->rate * $inven->balance);
                $LineQty = $inven->balance;
            } elseif ($totalQty > $reqst_qty)   // If Balance Qty is greater then row Requsted qty.
            {
                // Find qty which will update balance qty of row.
                // This qty also add to total_qty.
                $LineQty = $inven->balance - ($totalQty - $reqst_qty);
                $inventory_arr['balance'] = $LineQty;     // updating balance qty with new qty to update row in table.
                $totalAmount += $LineQty * $inven->rate;    // Caluting total amount of row and add to Total amount.
                // Calulating total Qty which must be equal to request qty after runing loops.
                $totalQty = $LineQty + ($totalQty - $inven->balance);
            }
            array_push($inventory_arr_final, $inventory_arr);       // push update data to array to further update in database.

            // if Do_detail_id is available
            // This function will call when stock is issuing use Delivery Order (DO)
            if (isset($inven->batch_no)) {
                $expirationDate = $inven->date;
                $batch = $inven->batch_no;
                inventoryLib::setBatchExpDate($doDetailId, $product, $batch, $expirationDate, $LineQty);
            }
            if ($totalQty >= $reqst_qty)
                break;
        }

        $productIssueRate = $totalAmount / $totalQty;     // Calculating unit rate to use while stock issuance.

        $grn_source_ids = array();
        // update inventory table to update qty_issue and balance values.
        foreach ($inventory_arr_final as $update_inv) {
            $bl = $update_inv['balance'];
            DB::table('inv_inventories')
                ->where('id', $update_inv['id'])
                ->update([
                    'qty_issue' => DB::raw('qty_issue+' . $bl),
                    'balance' => DB::raw('qty_in - qty_issue'),
                ]);
            array_push($grn_source_ids, $update_inv['source_id']);
        }

        //grn edit status updated
        inventoryLib::setGrnEditStatus($grn_source_ids);

        // return total amount and issue rate of prodcuct after calculating
        return array($productIssueRate, $totalAmount);
    }

    public static function setGrnEditStatus($source_arr)
    {

        DB::table('inv_grns')
            ->whereIn('id', $source_arr)
            ->update(
                ['editable' => 0]
            );
    }

    /*data will contain following
    1.Prod_id
    2.Batch
    3.Expiration date
    4.Qty
    */
    public static function setBatchExpDate($doDetailId, $product, $batch, $expirationDate, $qty)
    {
        $id = str::uuid()->toString();
        $batch_data = array(
            "id" => $id,
            "do_detail_id" => $doDetailId,
            "prod_id" => $product,
            "qty" => $qty,
            "batch" => $batch,
            "expire_date" => date('Y-m-d', strtotime('+1 year', strtotime($expirationDate))),
        );
        DB::table('do_batch_data')->insert($batch_data);
    }

    public static function getDeliveryOrders()
    {
        $companyId = session('companyId');
        $deliveryOrders = DB::table('inv_delivery_order')->select('id', 'month', 'number')
            ->where("company_id", session('companyId'))
            ->whereNull('inv_id')
            ->orderBy('month')
            ->orderBy('number')
            ->get();

        //dd($deliveryOrders );
        return $deliveryOrders;
    }
}
