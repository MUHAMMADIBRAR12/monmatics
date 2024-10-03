<?php

namespace App\Http\Controllers\Hcm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\dbLib;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeFormController extends Controller
{
    public function index()
    {
        $companyId = session('companyId');
        $employee_list = DB::table('hcm_employee')->where('company_id', $companyId)->orderBy('created_at')->get();
        return view('hcm.employee_form_list', compact('employee_list'));
    }

    public function form($id = null)
    {
        $User = null;
        $roles = dbLib::getRoles();
        // $designations = DB::table('sys_options')->select('description')->where('type', 'employee_designation')->where('status', 1)->get();
        $Departments = DB::table('sys_departments')->where('company_id', session('companyId'))->get();
        $Designations = DB::table('hcm_designation')->where('company_id', session('companyId'))->get();
        // dd($Designations);
        if ($id) {
            $employee = DB::table('hcm_employee')->where('id', $id)->first();
            $education = DB::table('hcm_education')->where('source_id', $id)->get();
            $employment = DB::table('hcm_employment')->where('source_id', $id)->get();
            $Emg_Contact = DB::table('hcm_emg_contact')->where('source_id', $id)->get();
            $Documents = DB::table('hcm_documents')->where('source_id', $id)->get();
            $BankDetail = DB::table('hcm_bank_details')->where('source_id', $id)->get();
            $Govt_Doc = DB::table('hcm_govt_documents')->where('source_id', $id)->get();
            $work_info = DB::table('hcm_work_info')->where('source_id', $id)->first();
            $User = DB::table('users')->where('parent_id', $id)->first();
            $Departments = DB::table('sys_departments')->where('company_id', session('companyId'))->get();
            $Designations = DB::table('hcm_designation')->where('company_id', session('companyId'))->get();
            // dd($Designations);


            // $Documents_Attachments = DB::table('sys_attachments')->where('source_id', $Documents->id)->first();
            // $Govt_Doc_Attachments = DB::table('sys_attachments')->where('source_id', $Govt_Doc->id)->first();


            return view('hcm.employee_form', compact('roles', 'employee', 'Designations', 'education', 'employment', 'Emg_Contact', 'Documents', 'BankDetail', 'Govt_Doc', 'User', 'Departments','work_info'));
        } else {
            return view('hcm.employee_form', compact('roles', 'Designations', 'User', 'Departments'));
        }
    }

    public function save(Request $request)
    {

        if ($request->id) {
            $employee = [
                'title' => $request->info_title,
                'first_name' => $request->info_first_name,
                'last_name' => $request->info_last_name,
                'email' => $request->info_email,
                'city' => $request->info_city,
                'country' => $request->info_country,
                'state' => $request->info_state,
                'code' => $request->info_zipcode,
                'gender' => $request->info_gender,
                'dob' => $request->info_dob,
                'phone' => $request->info_phone,
                'address_one' => $request->info_address1,
                'address_two' => $request->info_address2,
                'company_id' => session('companyId'),
                'created_at' => carbon::now(),
                'created_by' => Auth::user()->id,
            ];

            DB::table('hcm_employee')->where('id', $request->id)->update($employee);
        } else {
            $id = str::uuid()->toString();
            $employee = [
                'id' => $id,
                'title' => $request->info_title,
                'first_name' => $request->info_first_name,
                'last_name' => $request->info_last_name,
                'email' => $request->info_email,
                'city' => $request->info_city,
                'country' => $request->info_country,
                'state' => $request->info_state,
                'code' => $request->info_zipcode,
                'gender' => $request->info_gender,
                'dob' => $request->info_dob,
                'phone' => $request->info_phone,
                'address_one' => $request->info_address1,
                'address_two' => $request->info_address2,
                'company_id' => session('companyId'),
                'created_at' => carbon::now(),
                'created_by' => Auth::user()->id,
            ];

            DB::table('hcm_employee')->insert($employee);
        }

        if ($request->edu_id) {
            $educationData = [];
            $eduRowCount = count($request->input('edu_ScholName'));

            for ($i = 0; $i < $eduRowCount; $i++) {
                // dd([$i]);
                $educationData[] = [
                    'college_name' => $request->edu_ScholName[$i],
                    'from_date' => $request->edu_from_date[$i],
                    'to_date' => $request->edu_to_date[$i],
                    'level' => $request->edu_level[$i],
                    'marks' => $request->edu_marks[$i],
                    'source_id' => $request->id ?? $id,
                    'created_at' => Carbon::now(),
                    'updated_at' => null,
                ];
            }
            DB::table('hcm_education')->where('id', $request->edu_id)->update($educationData[0]);
        } else {
            $educationData = [];
            $eduRowCount = count($request->input('edu_ScholName'));
            for ($i = 0; $i < $eduRowCount; $i++) {
                $edu_id = Str::uuid()->toString();

                $educationData[] = [
                    'id' => $edu_id,
                    'college_name' => $request->edu_ScholName[$i],
                    'from_date' => $request->edu_from_date[$i],
                    'to_date' => $request->edu_to_date[$i],
                    'level' => $request->edu_level[$i],
                    'marks' => $request->edu_marks[$i],
                    'source_id' => $id,
                    'created_at' => Carbon::now(),
                    'updated_at' => null,
                ];
            }
            DB::table('hcm_education')->insert($educationData);
        }

        if ($request->emp_id) {
            $EmploymentData = [];
            $empRowCount = count($request->input('emp_company'));
            // dd($empRowCount);
            for ($i = 0; $i < $empRowCount; $i++) {
                $EmploymentData[] = [
                    'company' => $request->emp_company[$i],
                    'from_date' => $request->emp_from_date[$i],
                    'to_date' => $request->emp_to_date[$i],
                    'designation' => $request->emp_designation[$i],
                    'source_id' => $request->id ?? $id,
                    'created_at' => carbon::now(),
                ];
            }
            DB::table('hcm_employment')->where('id', $request->emp_id)->update($EmploymentData[0]);
        } else {
            $EmploymentData = [];
            $empRowCount = count($request->input('emp_company'));
            // dd($empRowCount);
            for ($i = 0; $i < $empRowCount; $i++) {
                $emp_id = str::uuid()->toString();
                $EmploymentData[] = [
                    'id' => $emp_id,
                    'company' => $request->emp_company[$i],
                    'from_date' => $request->emp_from_date[$i],
                    'to_date' => $request->emp_to_date[$i],
                    'designation' => $request->emp_designation[$i],
                    'source_id' => $id,
                    'created_at' => carbon::now(),
                ];
            }
            DB::table('hcm_employment')->insert($EmploymentData);
        }

        if ($request->emg_id) {
            $Emg_Data = [];
            $Emg_Inputs = count($request->input('emg_name'));

            for ($i = 0; $i < $Emg_Inputs; $i++) {
                $Emg_Data[] = [
                    'name' => $request->emg_name[$i],
                    'relationship' => $request->emg_relationship[$i],
                    'email' => $request->emg_email[$i],
                    'phone' => $request->emg_phone[$i],
                    'source_id' => $request->id ?? $id,
                    'created_at' => carbon::now(),
                    'updated_at' => null,
                ];
            }
            DB::table('hcm_emg_contact')->where('id', $request->emg_id)->update($Emg_Data[0]);
        } else {
            $Emg_Data = [];
            $Emg_Inputs = count($request->input('emg_name'));

            for ($i = 0; $i < $Emg_Inputs; $i++) {
                $emg_id = str::uuid()->toString();
                $Emg_Data[] = [
                    'id' => $emg_id,
                    'name' => $request->emg_name[$i],
                    'relationship' => $request->emg_relationship[$i],
                    'email' => $request->emg_email[$i],
                    'phone' => $request->emg_phone[$i],
                    'source_id' => $id,
                    'created_at' => carbon::now(),
                    'updated_at' => null,
                ];
            }
            DB::table('hcm_emg_contact')->insert($Emg_Data);
        }



        if ($request->doc_id) {

            $Documents = [];
            $documentRowCount = count($request->input('doc_subject'));

            for ($i = 0; $i < $documentRowCount; $i++) {

                $Documents[] = [
                    'subject' => $request->doc_subject[$i],
                    'expiration' => $request->doc_expiration[$i],
                    'source_id' => $request->id ?? $id,
                    'created_at' => Carbon::now(),
                    'updated_at' => null,
                ];

                // Handle file upload inside the loop
                $uploadedFile = $request->file('doc_file')[$i] ?? null;

                if ($uploadedFile && $uploadedFile->isValid()) {
                    dbLib::uploadDocument($request->doc_id, $uploadedFile);
                }
            }
            DB::table('hcm_documents')->where('id', $request->doc_id)->update($Documents[0]);
        } else {
            $Documents = [];
            $documentRowCount = count($request->input('doc_subject'));

            for ($i = 0; $i < $documentRowCount; $i++) {
                $doc_id = Str::uuid()->toString();

                $Documents[] = [
                    'id' => $doc_id,
                    'subject' => $request->doc_subject[$i],
                    'expiration' => $request->doc_expiration[$i],
                    'source_id' => $id,
                    'created_at' => Carbon::now(),
                    'updated_at' => null,
                ];

                $uploadedFile = $request->file('doc_file')[$i] ?? null;

                if ($uploadedFile && $uploadedFile->isValid()) {
                    dbLib::uploadDocument($doc_id, $uploadedFile);
                }
            }
            DB::table('hcm_documents')->insert($Documents);
        }

        if ($request->bank_account_id) {

            $BankDetail = [];

            $BankInput = count($request->bank_account_title);

            for ($i = 0; $i < $BankInput; $i++) {
                $BankDetail[] = [
                    'account_title' => $request->bank_account_title[$i],
                    'account_number' => $request->bank_account_number[$i],
                    'bank_name' => $request->bank_title[$i],
                    'bank_branch_code' => $request->bank_branch_code[$i],
                    'source_id' => $request->id ?? $id,
                    'created_at' => carbon::now(),
                ];
            }

            DB::table('hcm_bank_details')->where('id', $request->bank_account_id)->update($BankDetail[0]);
        } else {
            $BankDetail = [];

            $BankInput = count($request->bank_account_title);

            for ($i = 0; $i < $BankInput; $i++) {
                $bank_id = str::uuid()->toString();
                $BankDetail[] = [
                    'id' => $bank_id,
                    'account_title' => $request->bank_account_title[$i],
                    'account_number' => $request->bank_account_number[$i],
                    'bank_name' => $request->bank_title[$i],
                    'bank_branch_code' => $request->bank_branch_code[$i],
                    'source_id' => $id,
                    'created_at' => carbon::now(),
                ];
            }

            DB::table('hcm_bank_details')->insert($BankDetail);
        }

        if ($request->govt_doc_id) {

            $Govt_Doc = [];
            $Govt_Doc_Inputs = count($request->govt_doc_type);
            for ($i = 0; $i < $Govt_Doc_Inputs; $i++) {
                $Govt_Doc[] = [
                    'doc_type' => $request->govt_doc_type[$i],
                    'issue_date' => $request->govt_doc_from_date[$i],
                    'exp_date' => $request->govt_doc_to_date[$i],
                    'note' => $request->govt_doc_note[$i],
                    'source_id' => $request->id ?? $id,
                    'created_at' => carbon::now(),
                    'updated_at' => null,
                ];
                if ($request->hasFile('govt_doc_file')) {
                    dbLib::uploadDocument($request->govt_doc_id, $request->file('govt_doc_file')[$i]);
                }
            }
            DB::table('hcm_govt_documents')->where('id', $request->govt_doc_id)->update($Govt_Doc[0]);
        } else {
            $Govt_Doc = [];
            $Govt_Doc_Inputs = count($request->govt_doc_type);
            for ($i = 0; $i < $Govt_Doc_Inputs; $i++) {
                $govt_doc_id = str::uuid()->toString();
                $Govt_Doc[] = [
                    'id' => $govt_doc_id,
                    'doc_type' => $request->govt_doc_type[$i],
                    'issue_date' => $request->govt_doc_from_date[$i],
                    'exp_date' => $request->govt_doc_to_date[$i],
                    'note' => $request->govt_doc_note[$i],
                    'source_id' => $id,
                    'created_at' => carbon::now(),
                    'updated_at' => null,
                ];
                if ($request->hasFile('govt_doc_file')) {
                    dbLib::uploadDocument($govt_doc_id, $request->file('govt_doc_file')[$i]);
                }
            }
            DB::table('hcm_govt_documents')->insert($Govt_Doc);
        }

        if ($request->user_id) {
            $User = [
                'email' => $request->user_email,
                'name' => $request->user_name,
                'role' => $request->user_role,
                'status' => $request->user_status,
                'parent_id' => $request->id,
                'created_at' => carbon::now(),
                'updated_at' => null,
            ];

            if ($request->user_password) {
                Hash::make($request->user_password);
            }

            // dd($request->doc_file);
            DB::table('users')->where('id', $request->user_id)->update($User);
        } else {
            $User = [
                'email' => $request->user_email,
                'password' => Hash::make($request->user_password),
                'name' => $request->user_name,
                'role' => $request->user_role,
                'status' => $request->user_status,
                'parent_id' => $id,
                'created_at' => carbon::now(),
                'updated_at' => null,
            ];

            // dd($request->doc_file);
            DB::table('users')->insert($User);
        }

        if ($request->work_info_id) {
            $work_info = [
                'department' => $request->info_department,
                'designation' => $request->info_designation,
                'reporting_to' => $request->info_reporting_to,
                'type' => $request->info_type,
                'category' => $request->info_category,
                'joining_date' => $request->info_joining,
                'description1' => $request->info_description1,
                'description2' => $request->info_description2,
                'created_at' => carbon::now(),
                'updated_at' => null,
            ];
            DB::table('hcm_work_info')->where('id', $request->work_info_id)->update($work_info);
        } else {
            $info_id = str::uuid()->toString();
            $work_info = [
                'id' => $info_id,
                'department' => $request->info_department,
                'designation' => $request->info_designation,
                'reporting_to' => $request->info_reporting_to,
                'type' => $request->info_type,
                'category' => $request->info_category,
                'joining_date' => $request->info_joining,
                'description1' => $request->info_description1,
                'description2' => $request->info_description2,
                'created_at' => carbon::now(),
                'updated_at' => null,
            ];

            // dd($request->doc_file);
            DB::table('hcm_work_info')->insert($work_info);
        }




        return redirect('HCM/EmployeeForm/List');
    }

    public function getEvent()
    {
        $result = DB::table('crm_opportunities')
            ->join('hcm_employee', 'hcm_employee.user_id', '=', 'crm_opportunities.assigned_to')
            ->select('crm_opportunities.name as title', 'crm_opportunities.close_date as start', 'hcm_employee.color as className')
            ->get();
        $finalData = array();
        foreach ($result as $row) {
            $data = array(
                'title' => $row->title,
                'start' => $row->start,
                'className' => $row->className
            );
            array_push($finalData, $data);
        }
        return json_encode($finalData);
    }


    public function designation($id = null)
    {


        if ($id) {

            $desig = DB::table('hcm_designation')->where('id', $id)->first();

            $Designations = DB::table('hcm_designation')->where('company_id', session('companyId'))->get();
            // dd($Designations);
            return view('hcm.designation', compact('Designations', 'desig'));
        }
        $Designations = DB::table('hcm_designation')->where('company_id', session('companyId'))->get();
        // dd($Designations);
        return view('hcm.designation', compact('Designations'));
    }

    public function postdesignation(Request $request)
    {

        $id =  str::uuid()->toString();
        $data = [
            'id' => $id,
            'designation' => $request->designation,
            'code' => $request->code,
            'reporting_to' => $request->reporting_to,
            'company_id' => session('companyId'),
            'created_at' => Carbon::now(),
        ];

        if ($request->id) {
            $data = [
                'designation' => $request->designation,
                'code' => $request->code,
                'reporting_to' => $request->reporting_to,
                'company_id' => session('companyId'),
                'updated_at' => Carbon::now(),
            ];
            DB::table('hcm_designation')->where('id', $request->id)->update($data);
            return redirect()->back()->with('message', 'Designation Updated successfully');
        }

        DB::table('hcm_designation')->insert($data);
        return redirect()->back()->with('message', 'Designation Added successfully');
    }


    public function deletedesignation($id)
    {

        DB::table('hcm_designation')->where('id', $id)->delete();
        return redirect()->back()->with('error', 'Designation Deleted successfully');
    }

    public function View(){
        
        return view('hcm.viewdetails');
    }
}
