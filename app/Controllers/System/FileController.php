<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\dbLib;
use Illuminate\Support\Facades\DB;

class FileController extends Controller
{
    public function displayFile($id)
    {
        $result = DB::table('sys_attachments')->select()->where('source_id', '=', $id)->first();

        if (!$result)
            $result = DB::table('sys_attachments')->select()->where('source_id', '=', $id)->first();

        header('Content-type: ' . $result->type);
        print($result->content);
    }

    public function downloadFile($id)
    {
        dbLib::downloadAttachment($id);
    }

    public function deleteFile(Request $request)
    {
        dbLib::deleteAttachment($request->id);
    }
}
