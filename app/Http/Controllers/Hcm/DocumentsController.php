<?php

namespace App\Http\Controllers\Hcm;

use App\Http\Controllers\Controller;
use App\Libraries\dbLib;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DocumentsController extends Controller
{
    public function store(Request $request)
    {
        $companyId = session('companyId');

        $validation = $request->validate([
            'document_type' => 'required',
            'document_type_name' => $request->input('document_type') == 'Others' ? 'required' : '',
            'document_title' => 'nullable',
            'employee_id' => [
                'required',
                'exists:hcm_employees,id',
            ]
        ]);

        if ($request->input('document_type') == 'Others') {
            $typeId = Str::uuid();
            DB::table('hcm_employees_documents')->insert([
                'id' => $typeId,
                'document_type' => $validation['document_type_name'],
                'company_id' => $companyId,

            ]);
        }


        $id = Str::uuid();

        $file = $request->file('document_file');

        if ($file) {
            dbLib::uploadDocument($id , $request->file('document_file'));
        }

        $storeData = DB::table('hcm_employees_documents')->insert([
            'id' => $id,
            'document_type' => $request->input('document_type') == 'Others' ? $validation['document_type_name'] : $validation['document_type'],
            'title' => $validation['document_title'],
            'company_id' => $companyId,
            'employee_id' => $validation['employee_id'],

        ]);

        if (!$storeData) {
            return redirect()->back()->with('error', 'Failed to add Document. Try Again!');
        }

        return redirect()->back()->with('success', 'Document added successfully.');

    }

    public function downloadDoc($id)
    {
        dbLib::downloadAttachment($id);
    }


    public function update(Request $request, $id)
    {
        $validation = $request->validate([
            'document_type' => 'required',
            'document_type_name' => $request->input('document_type') == 'Others' ? 'required' : '',
            'title' => 'nullable',
        ]);

       $findDoc = DB::table('hcm_employees_documents')->where('id', $id)->first();

       if (!$findDoc) {
           return redirect()->back()->with('error', 'Document Not Found');
       }

        $file = $request->file('document_file');

        if ($file) {
            $deleted = dbLib::deleteAttachment($id);

            if ($deleted) {
                dbLib::uploadDocument($id , $request->file('document_file'));
            }

        }

        $storeData = DB::table('hcm_employees_documents')->where('id', $id)->update([
            'document_type' => $request->input('document_type') == 'Others' ? $validation['document_type_name'] : $validation['document_type'],
            'title' => $validation['title'],

        ]);

        if (!$storeData) {
            return redirect()->back()->with('error', 'Failed to add Document. Try Again!');
        }

        return redirect()->back()->with('success', 'Document added successfully.');

    }

    public function destroy($id)
    {
        try {

            $document = DB::table('hcm_employees_documents')->where('id', $id)->first();

            if (!$document) {
                return redirect()->back()->with('error', 'Document not found');
            }

            dbLib::deleteAttachment($id);

            DB::table('hcm_employees_documents')->where('id', $id)->delete();

            return redirect()->back()->with('success', 'Document deleted successfully');

        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Something went wrong');
        }

    }
}
