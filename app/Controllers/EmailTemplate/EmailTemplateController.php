<?php

namespace App\Http\Controllers\EmailTemplate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmailTemplateController extends Controller
{

    public function  templateList()

    {
        $templateList = DB::table('crm_email_templates')
            ->select('crm_email_templates.*')
            ->get();

        return view('email_template.email_templates_list', compact('templateList'));
    }

    public function  templateform()

    {

        return view('email_template.create-email_templates');
    }

    public function createTemplate(Request $request)
    {
        // return $request->all();

        $id = Str::uuid()->toString();
        DB::table('crm_email_templates')->insert([
            'id' => $id,
            'template_name' => $request->temp_name,
            'subject' => $request->subject,
            'body_text' => $request->bodytext,


        ]);
        return redirect('Crm/EmailTemplates');
    }
    public function editTemplate($id)
    {
        $templateList = DB::table('crm_email_templates')->where('id', $id)->first();
        return view('email_template.edit-email_templates', compact('templateList'));
    }

    public function updateTemplate(Request $request)
    {
        DB::table('crm_email_templates')->where('id', $request->edit_email_template_id)->update([
            'subject' => $request->subject,
            'body_text' => $request->bodytext,
            'template_name' => $request->temp_name,
        ]);

        return redirect('Crm/EmailTemplates');
    }

    public function deleteTemplate($id)
    {
        DB::table('crm_email_templates')->where('id', $id)->delete();
        return redirect('Crm/EmailTemplates');
    }
}
