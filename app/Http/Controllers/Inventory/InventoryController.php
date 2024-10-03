<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Object_;
use App\inventory\Inventory;
use App\Libraries\dbLib;
use App\Libraries\inventoryLib;
use Illuminate\Support\Facades\Auth;


class InventoryController extends Controller
{
    // -------------- product Module start  -----------------//
    public function attach_remove(Request $request)
    {
        DB::table('inv_products_attachments')->where('file', $request->img)->delete();
    }


    public function product_index()
    {
        $products_list = array();
        $products = DB::table('inv_products')
            ->leftJoin('fs_coas', 'fs_coas.id', '=', 'inv_products.coa_id')
            ->leftJoin('sys_units as u1', 'u1.id', '=', 'inv_products.primary_unit')
            ->leftJoin('sys_units as u2', 'u2.id', '=', 'inv_products.purchase_unit')
            ->leftJoin('sys_units as u3', 'u3.id', '=', 'inv_products.sale_unit')
            ->select('inv_products.*', 'fs_coas.name as account_name', 'u1.name as primary_unit', 'u2.name as purchase_unit', 'u3.name as sale_unit')
            ->where('prod_services', '=', 'product')
            ->where('inv_products.company_id', session('companyId'))
            ->orderBy('name')
            ->get();

        foreach ($products as $product) {
            $result = array(
                "id" => $product->id,
                "name" => $product->name,
                "qty_in_stock" => inventoryLib::getStock($product->id)->qty,
                "code" => $product->code,
                "sku" => $product->sku,
                "primary_unit" => $product->primary_unit,
                "purchase_unit" => $product->purchase_unit,
                "sale_unit" => $product->sale_unit,
                "account_name" => $product->account_name,
                "sku" => $product->sku,
                "category" => $product->category,
                "type" => $product->type,

            );
            array_push($products_list, $result);
        }
        return view('inventory.products_list', compact('products_list'));
    }


    public function product_view($id)
    {
        $categories  = DB::table('inv_categories')->select()->orderBy('category')->get();
        $types  = DB::table('inv_type')->select()->orderBy('type')->get();
        $coaAccount = DB::table('fs_coas')->select(array('id', 'name'))->where('coa_id', '=', '19')->orderBy('name')->get();
        $units = DB::table('sys_units')->orderBy('name')->get();

        if ($id) {
            $product = DB::table('inv_products')
                ->leftjoin('sys_units as pri_unit', 'pri_unit.id', '=', 'inv_products.primary_unit')
                ->leftjoin('sys_units as pur_unit', 'pur_unit.id', '=', 'inv_products.purchase_unit')
                ->leftjoin('sys_units as sale_unit', 'sale_unit.id', '=', 'inv_products.sale_unit')
                ->select('inv_products.*', 'pri_unit.name as pri_unit', 'pur_unit.name as pur_unit', 'sale_unit.name as sal_unit')
                ->where('inv_products.id', '=', $id)
                ->first();
            $attachmentRecord = dbLib::getAttachment($id);
            return view('inventory.product_view', compact('product', 'attachmentRecord', 'categories', 'types', 'coaAccount', 'units'));
            die();
        }
        $this->product_index();
    }
    public function product_form($id = null)
    {
        $categories  = DB::table('inv_categories')->select()->orderBy('category')->get();
        $types  = DB::table('inv_type')->select()->orderBy('type')->get();
        $coaAccount = DB::table('fs_coas')->select(array('id', 'name'))->where('coa_id', '=', '19')->orderBy('name')->get();
        $units = DB::table('sys_units')->select('id', 'name')->orderBy('name')->get();
        $taxables = DB::table('sys_taxes')->select('id', 'name')->distinct('name')->get();
        $packaging_details = DB::table('sys_options')->select('description')->where('type', 'product_packaging_detail')->where('status', 1)->get();
        $brands = DB::table('inv_brands')->select('name')->get();
        if ($id) {
            $product = DB::table('inv_products')->select()->where('id', '=', $id)->first();

            $attachmentRecord = dbLib::getAttachment($id);
            return view('inventory.product', compact('product', 'attachmentRecord', 'categories', 'types', 'coaAccount', 'units', 'taxables', 'packaging_details', 'brands'));
            die();
        }
        return view('inventory.product', compact('categories', 'types', 'coaAccount', 'units', 'taxables', 'packaging_details', 'brands'));
    }

    public function units(Request $request)
    {
        $pur_sal_units = DB::table('sys_units')->select('id', 'name')->where('base_unit', $request->primary_unit)->get();
        return $pur_sal_units;
    }

    public function product_save(Request $request)
    {
        // Validation
        $id = ($request->id) ? $request->id : NULL;
        $arrValidation = array(
            'name' => 'required|unique:inv_products,name,' . $id,
            'code' => 'nullable|unique:inv_products,code,' . $id,
            'sku' => 'nullable|unique:inv_products,sku,' . $id,
        );
        $Validation = $request->validate($arrValidation);
        //////////////////////

        $companyId = session('companyId');
        $userId = Auth::id();
        $data = array(
            "name" => $request->name,
            "coa_id" => $request->coa_id,
            "category" => $request->category,
            "brand" => $request->brand,
            "type" => $request->type,
            "code" => $request->code,
            "sku" => $request->sku,
            "tax" => $request->taxable,
            "primary_unit" => $request->primary_unit,
            "purchase_unit" => $request->purchase_unit,
            "sale_unit" => $request->sale_unit,
            "purchase_price" => $request->purchase_price,
            "sale_price" => $request->sale_price,
            "reorder" => $request->reorder,
            "sales_description" => $request->sales_description,
            "purchase_description" => $request->purchase_description,
            "packing_detail" => $request->packaging_detail,
            "report_order" => $request->report_order,
            "description" => $request->description,
            "company_id" => $companyId,
            "status" => $request->status,
            "prod_services" => "product",
        );
        if ($request->id) {
            $id = $request->id;
            DB::table('inv_products')->where('id', '=', $id)->update($data);
        } else {
            $id = str::uuid()->toString();
            $data['id'] = $id;
            DB::table('inv_products')->insert($data);
        }

        // attachments
        if ($request->file) {
            $prdId = $id;
            foreach ($request->file as $fileData) {
                $fileName = time() . '.' . $fileData->getClientOriginalName(); //    $fileData->extension();
                $fileData->move(public_path('assets/products'), $fileName);

                $id = str::uuid()->toString();
                $fileData = array(
                    "id" => $id,
                    "source_id" => $prdId,
                    "file" => $fileName,
                );
                DB::table('sys_attachments')->insert($fileData);
            }
        }
        return redirect()->route('Inventory/Product/List');
    }
    // -------------- product Module End  -----------------//



    // -------------- Brands Module Start ---------------- //

    public function br_list()
    {
        $brands = DB::table('inv_brands')->get();
        return view('inventory.brand_list', compact('brands'));
    }

    public function br_save(Request $request)
    {
        $brand = array(
            "name" => $request->brand,
        );
        if ($request->id) {
            $brand['id'] = $request->id;
            DB::table('inv_brands')->where('id', $request->id)->update($brand);
            return redirect('Inventory/Brands/List')->with('update_msg', 'Brand Updated');
        } else {
            $id = str::uuid()->toString();
            $brand['id'] = $id;
            DB::table('inv_brands')->insert($brand);
            return redirect('Inventory/Brands/List')->with('insert_msg', 'New Brand Added');
        }
    }

    public function br_edit(Request $request)
    {
        $id = $request->id;
        $brand = DB::table('inv_brands')->where('id', '=', $id)->get();
        return $brand;
    }

    public function br_remove($id)
    {
        DB::table('inv_brands')->where('id', $id)->delete();
        return back()->with('delete_msg', 'Brand Delete');
    }
    // -------------- Brands Module End ---------------- //

}
