<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerCategoryController extends Controller
{
    //this function display the Customer Category List
    public function index()
    {
        $categories = DB::table('crm_categories')->get();
        return view('crm.customerCategoryList', compact('categories'));
    }
    public function customerCategory(Request $request)
    {
        if ($request->isMethod('get')) {

            return view('crm.customerCategory');
        } else {
            //this part update the category
            if ($request->id) {

                DB::table('crm_categories')
                    ->where('id', $request->id)
                    ->update(
                        [
                            'category' => $request->category,

                        ]
                    );
                return redirect('Crm/CutomerCategory/List');
            }
            $id = Str::uuid()->toString();
            DB::table('crm_categories')->insert([
                [
                    'id' => $id,
                    'category' => $request->category,

                ],
            ]);
            return redirect('Crm/CutomerCategory/List');
        }
    }
    //this function delete the service
    public function removeCategory($id)
    {

        DB::table('crm_categories')->where('id', $id)->delete();
        return back()->with('msg', 'Record Deleted successfully');
    }

    public function editCategory($id)
    {
        $category = DB::table('crm_categories')->where('id', $id)->get()->first();
        return view('crm.customerCategory', compact('category'));
    }
}
