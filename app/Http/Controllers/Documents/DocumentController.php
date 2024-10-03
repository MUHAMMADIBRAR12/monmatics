<?php

namespace App\Http\Controllers\Documents;


use App\Http\Controllers\Controller;
use App\Libraries\dbLib;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{

    public function CreateDocument($id = null)
    {

        $departments = DB::table('sys_departments')->where('company_id', session('companyId'))->get();
        $parent_documents = DB::table('documents')->select('title', 'id')->get();
        $id = $id;

        if ($id) {
            $documents = DB::table('documents')->where('id', $id)->first();
            $parent_documents = DB::table('documents')->select('title', 'id')->get();
            $attachmentRecord = dbLib::getAttachment($id);

            return view('documents.create_document', compact('departments', 'documents', 'id', 'parent_documents', 'attachmentRecord'));
        }

        // dd($parent_documents);
        return view('documents.create_document', compact('departments', 'parent_documents', 'id'));
    }


    public function AddDocument(Request $request)
    {

        $id = $request->id ?? Str::uuid()->toString();
        $data = array(

            "id" => $id,
            "title" => $request->title,
            "description" => $request->description,
            "expiration_date" => $request->expire_date,
            "volume" => $request->volume,
            "parent_title" => $request->parent_title,
            "department" => $request->department,
            "status" => $request->status,
            "company_id" => session('companyId'),
            "user_id" => Auth()->user()->id,
        );
        if ($request->file('file')) {
            dbLib::uploadDocument($id, $request->file('file'));
        }

        if ($request->id) {
            DB::table('documents')->where('id', $request->id)->update($data);
            return redirect('Document/All/Documents')->with('update_msg', 'Document Updated Successfully');
        } else {
            DB::table('documents')->insert($data);
            return redirect('Document/All/Documents')->with('insert_msg', 'Document Added Successfully');
        }


        // dd($request->all());




    }

    // public function allDocuments()
    // {
    //     $user = Auth::user();

    //     if ($user) {
    //         $documents = DB::table('documents')
    //             ->where(function ($query) use ($user) {
    //                 $query->where('status', 'public');

    //                 $query->orWhere(function ($query) use ($user) {
    //                     $query->where('status', 'private')
    //                         ->where('user_id', $user->id);
    //                 });
    //             })
    //             ->get();
    //     } else {
    //         $documents = DB::table('documents')->where('status', 'public')->get();
    //     }

    //     return view('documents.all_documents', compact('documents'));
    // }

    public function allDocuments()
    {
        $user = Auth::user();

        if ($user) {
            $publicDocuments = DB::table('documents')
                ->where('status', 'public')
                ->get();

            $userDocuments = DB::table('documents')
                ->where('status', 'private')
                ->where('user_id', $user->id)
                ->get();

            $sharedDocuments = DB::table('share_documents')
                ->join('documents', 'share_documents.doc_id', '=', 'documents.id')
                ->where(function ($query) use ($user) {
                    $query->where('share_documents.user_id', $user->id)
                        ->orWhere('documents.user_id', $user->id);
                })
                ->select('documents.*')
                ->get();

            $documents = $publicDocuments
                ->merge($userDocuments)
                ->merge($sharedDocuments)
                ->unique('id'); // Ensure uniqueness based on document ID
        } else {
            $documents = DB::table('documents')->where('status', 'public')->get();
        }

        return view('documents.all_documents', compact('documents'));
    }





    public function DeleteDocument($id)
    {
        DB::table('documents')->where('id', $id)->delete();
        DB::table('sys_attachments')->where('source_id', $id)->delete();
        return redirect()->back()->with('delete_msg', 'Document Deleted successfully');
    }


    public function ViewDocument($id)
    {

        $document = DB::table('documents')->where('id', $id)->first();

        $attachmentRecord = dbLib::getAttachment($id);
        // dd($attachmentRecord);

        return view('documents.view_documents', compact('document', 'attachmentRecord'));
    }

    public function DeleteDocumentAttachment($id)
    {

        DB::table('sys_attachments')->where('id', $id)->delete();

        return redirect()->back()->with('delete_msg', 'Attachment Deleted successfully');
    }

    public function ShareDocument($id)
    {

        // $users = DB::table('users')->get();
        $users = DB::table('users')->where('is_deleted', 0)->where('status', 1)->get();
        // dd($users);
        $document = DB::table('documents')->where('id', $id)->first();


        $checkUsers = DB::table('share_documents')->where('doc_id',$id)->pluck('user_id')->toArray();

        // dd($checkUsers);

        return view('documents.share', compact('users', 'document','checkUsers'));
    }

    public function SaveShareDocument(Request $request)
    {

        DB::table('share_documents')->where('doc_id', $request->doc_id)->delete();
        $userIds = $request->input('user_id', []);

        $Sharedata = [];

        foreach ($userIds as $user) {
            $id = str::uuid()->toString();
            $Sharedata[] = [

                'id' => $id,
                'doc_id' => $request->doc_id,
                'user_id' => $user,
                'company_id' => session('companyId'),
            ];
        }

        DB::table('share_documents')->insert($Sharedata);

        return redirect()->back()->with('insert_msg', 'Docs Share successfully');
    }


}
