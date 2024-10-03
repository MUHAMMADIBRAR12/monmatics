@extends('layout.master')
@section('title', 'Employee Detail')
@section('parentPageTitle', 'HCM')
@section('page-style')
    <?php use App\Libraries\appLib; ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/datatables.min.css" />
    <link rel="stylesheet" href="{{ asset('public/assets/css/sw.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/list.css') }}">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <style>
        .mainbuttons {
            margin-right: 10px;
            width: 140px;
            text-align: center;
            border-radius: 5px;
        }

        .nav-link {
            cursor: pointer;
        }

    </style>
@stop
@section('content')
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="header">
            <div style="float: inline-end;"><a href="{{ url('HCM/EmployeeForm/List') }}" class="btn btn-primary">Back</a></div>

                    <strong>Employee Details</strong>
                </div>
                <div class="body">

                    <div class="nav nav-fill my-3">
                        <label for="" class="mainbuttons nav-link shadow-sm border ml-2 step1" id="step1"
                            style="background-color: rgb(32 164 235);">Personal Info</label>
                        <label for="" class="mainbuttons nav-link shadow-sm border ml-2 step2"
                            id="step2">Education</label>
                        <label for="" class="mainbuttons nav-link shadow-sm border ml-2 step3"
                            id="step3">Employment</label>
                        <label for="" class="mainbuttons nav-link shadow-sm border ml-2 step4" id="step4">Emg
                            Contact</label>
                        <label for="" class="mainbuttons nav-link shadow-sm border ml-2 step5"
                            id="step5">Documents</label>
                        <label for="" class="mainbuttons nav-link shadow-sm border ml-2 step6" id="step6">Bank
                            details</label>
                        <label for="" class="mainbuttons nav-link shadow-sm border ml-2 step7" id="step7">Govt
                            Docs.</label>
                        <label for="" class="mainbuttons nav-link shadow-sm border ml-2 step8" id="step8">Work
                            Info</label>
                        <label for="" class="mainbuttons nav-link shadow-sm border ml-2 step9" id="step9">User
                            Details</label>
                    </div>


                    <div class="section1 section">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="">Gender</label>
                                <p>{{ $employee->gender ?? '' }}</p>
                            </div>
                            <div class="col-md-2">
                                <label for="">Title</label>
                                <p>{{ $employee->title ?? '' }}</p>
                            </div>
                            <div class="col-md-2">
                                <label for="">First Name</label>
                                <p>{{ $employee->first_name ?? '' }}</p>
                            </div>
                            <div class="col-md-2">
                                <label for="">Last Name</label>
                                <p>{{ $employee->last_name ?? '' }}</p>
                            </div>
                            <div class="col-md-2">
                                <label for="">Date of birth</label>
                                <p>{{ $employee->dob ?? '' }}</p>
                            </div>
                            <div class="col-md-2">
                                <label for="">Email</label>
                                <p>{{ $employee->email ?? '' }}</p>
                            </div>
                            <div class="col-md-2">
                                <label for="">Phone</label>
                                <p>{{ $employee->phone ?? '' }}</p>
                            </div>
                            <div class="col-md-2">
                                <label for="">Coutnry</label>
                                <p>{{ $employee->country ?? '' }}</p>
                            </div>
                            <div class="col-md-2">
                                <label for="">State</label>
                                <p>{{ $employee->state ?? '' }}</p>
                            </div>
                            <div class="col-md-2">
                                <label for="">City</label>
                                <p>{{ $employee->city ?? '' }}</p>
                            </div>
                            <div class="col-md-2">
                                <label for="">Zip Code</label>
                                <p>{{ $employee->code ?? '' }}</p>
                            </div>
                            <div class="col-md-2">
                                <label for="">Address 1</label>
                                <p>{{ $employee->address_one ?? '' }}</p>
                            </div>
                            <div class="col-md-2">
                                <label for="">Address 2</label>
                                <p>{{ $employee->address_two ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="section2 section" style="display: none;">
                        @foreach ($educations as $education)
                            <h6>{{ $loop->iteration }}.</h6>
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="">Institute</label>
                                    <p>{{ $education->college_name ?? '' }}</p>
                                </div>
                                <div class="col-md-2">
                                    <label for="">From date</label>
                                    <p>{{ $education->from_date ?? '' }}</p>
                                </div>
                                <div class="col-md-2">
                                    <label for="">To date</label>
                                    <p>{{ $education->to_date ?? '' }}</p>
                                </div>
                                <div class="col-md-2">
                                    <label for="">level </label>
                                    <p>{{ $education->level ?? '' }}</p>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Marks</label>
                                    <p>{{ $education->marks ?? '' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="section3 section" style="display: none;">
                        @foreach ($employments as $employment)
                            <h6>{{ $loop->iteration }}.</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="">Company</label>
                                    <p>{{ $employment->company ?? '' }}</p>
                                </div>
                                <div class="col-md-3">
                                    <label for="">From date</label>
                                    <p>{{ $employment->from_date ?? '' }}</p>
                                </div>
                                <div class="col-md-3">
                                    <label for="">To date</label>
                                    <p>{{ $employment->to_date ?? '' }}</p>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Designation</label>
                                    <p>{{ $employment->designation ?? '' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="section4 section" style="display: none;">
                        @foreach ($Emg_Contacts as $Emg_Contact)
                            <h6>{{ $loop->iteration }}.</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="">Name</label>
                                    <p>{{ $Emg_Contact->name ?? '' }}</p>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Relation Ship</label>
                                    <p>{{ $Emg_Contact->relationship ?? '' }}</p>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Email</label>
                                    <p>{{ $Emg_Contact->email ?? '' }}</p>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Phone </label>
                                    <p>{{ $Emg_Contact->phone ?? '' }}</p>
                                </div>
                            </div>
                        @endforeach

                    </div>
                    <div class="section5 section" style="display: none;">
                        @foreach ($Documents as $Documents)
                            @php
                                $docs = DB::table('sys_attachments')
                                    ->where('source_id', $Documents->id)
                                    ->first();
                            @endphp
                            <h6>{{ $loop->iteration }}.</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="">Subject</label>
                                    <p>{{ $Documents->subject ?? '' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <label for="">Expiration</label>
                                    <p>{{ $Documents->expiration ?? '' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <label for="">File</label>
                                    <p  class="fileshow" style="color: rgb(32, 164, 235); cursor: pointer;"  data-toggle="modal" data-target="#filemodel{{ $Documents->id }}"> Show File</p>
                                    {{-- Model --}}
                                    <div class="modal fade" id="filemodel{{ $Documents->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">{{ $docs->file ?? '' }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <img src="data:image/png;base64,{{ base64_encode($docs->content ?? '') }}" alt="">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- End Model --}}
                                </div>
                            </div>
                        @endforeach

                    </div>
                    <div class="section6 section" style="display: none;">
                        @foreach ($BankDetails as $BankDetail)
                            <h6>{{ $loop->iteration }}.</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="">Account Title</label>
                                    <p>{{ $BankDetail->account_title ?? '' }}</p>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Account Number</label>
                                    <p>{{ $BankDetail->account_number ?? '' }}</p>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Bank Name</label>
                                    <p>{{ $BankDetail->bank_name ?? '' }}</p>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Branch Code</label>
                                    <p>{{ $BankDetail->bank_branch_code ?? '' }}</p>
                                </div>
                            </div>
                        @endforeach

                    </div>
                    <div class="section7 section" style="display: none;">
                        @foreach ($Govt_Docs as $Govt_Doc)
                            @php
                                $gov_docs = DB::table('sys_attachments')
                                    ->where('id', $Govt_Doc->id)
                                    ->first();
                            @endphp
                            <h6>{{ $loop->iteration }}.</h6>
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="">Document Type</label>
                                    <p>{{ $Govt_Doc->doc_type ?? '' }}</p>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Issue Date</label>
                                    <p>{{ $Govt_Doc->issue_date ?? '' }}</p>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Expire Date</label>
                                    <p>{{ $Govt_Doc->exp_date ?? '' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <label for="">Note</label>
                                    <p>{{ $Govt_Doc->note ?? '' }}</p>
                                </div>
                                <div class="col-md-2">
                                    <label for="">File</label>
                                    <p class="fileshow"  style="color: rgb(32, 164, 235); cursor: pointer;"   data-toggle="modal" data-target="#govt{{ $Govt_Doc->id }}"> Show File</p>
                                </div>
                            </div>
                            {{-- Model --}}
                            <div class="modal fade" id="govt{{ $Govt_Doc->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">{{ $gov_docs->file ?? '' }}
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <img src="data:image/png;base64,{{ base64_encode($gov_docs->content ?? '') }}"
                                                class="img-fluid" alt="">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- End Model --}}
                        @endforeach
                    </div>
                    <div class="section8 section" style="display: none;">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="">Department</label>
                                <p>{{ $work_info->department ?? '' }}</p>
                            </div>
                            <div class="col-md-2">
                                <label for="">Designation</label>
                                <p>{{ $work_info->designation ?? '' }}</p>
                            </div>
                            <div class="col-md-2">
                                <label for="">Reporting To</label>
                                <p>{{ $work_info->reporting_to ?? '' }}</p>
                            </div>
                            <div class="col-md-2">
                                <label for="">Type </label>
                                <p>{{ $work_info->type ?? '' }}</p>
                            </div>
                            <div class="col-md-2">
                                <label for="">Category</label>
                                <p>{{ $work_info->category ?? '' }}</p>
                            </div>
                            <div class="col-md-2">
                                <label for="">Joining Date</label>
                                <p>{{ $work_info->joining_date ?? '' }}</p>
                            </div>
                            <div class="col-md-3">
                                <label for="">Description 1</label>
                                <p>{{ $work_info->description1 ?? '' }}</p>
                            </div>
                            <div class="col-md-3">
                                <label for="">Description 2</label>
                                <p>{{ $work_info->description2 ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="section9 section" style="display: none;">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="">User Name</label>
                                <p>{{ $User->name ?? '' }}</p>
                            </div>
                            <div class="col-md-3">
                                <label for="">Email</label>
                                <p>{{ $User->email ?? '' }}</p>
                            </div>
                            <div class="col-md-3">
                                <label for="">Role</label>
                                <p>{{ $User->role ?? '' }}</p>
                            </div>
                            <div class="col-md-3">
                                <label for="">Status </label>
                                <p>{{ ($User->status ?? '') == 0 ? 'Suspend' : 'Active' }}</p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('page-script')
    <script>
        // var currentSection = 1;
        var totalSection = 10;

        for (let i = 1; i < totalSection; i++) {

            var elemnt = document.querySelector(".step" + i);

            $('.step' + i).on('click', function() {

                $('.section').hide();
                $('.section' + i).show();
                $('.nav-link').css("backgroundColor", "white");
                $('.step' + i).css("backgroundColor", "rgb(32 164 235)");

            });
        }
    </script>
@stop
