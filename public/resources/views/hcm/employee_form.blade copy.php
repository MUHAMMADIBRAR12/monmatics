@extends('layout.master')
@section('title', 'Employee')
@section('parentPageTitle', 'Crm')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')
    <?php use App\Libraries\appLib; ?>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/morrisjs/morris.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/jquery-spinner/css/bootstrap-spinner.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/bootstrap-select/css/bootstrap-select.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/nouislider/nouislider.min.css') }}" />
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/dropify/css/dropify.min.css') }}" />
    <script src="https://cdn.jsdelivr.net/parsleyjs/2.9.2/parsley.min.js"></script>

    <style>
        .input-group-text {
            padding: 0 .75rem;
        }

        .amount {
            width: 150px;
            text-align: right;
        }

        .table td {
            padding: 0.10rem;
        }

        .dropify {
            width: 200px;
            height: 200px;
        }

        .mainbuttons {
            margin-right: 10px;
            width: 140px;
            text-align: center;
            border-radius: 5px;
        }

        .labels,
        .inputs {
            margin-left: 5px;
        }

        .parsley-errors-list {
            color: red;
            margin-top: 5px;
            list-style-type: none;
            margin-left: -40px;
            font-size: 12px;
        }

        .parsley-error {
            border: 1px solid red;
        }
    </style>
    <script lang="javascript/text">
        var contactURL = "{{ url('contactsSearch') }}";
        var userURL = "{{ url('userSearch') }}";
        var token = "{{ csrf_token() }}";
    </script>
@stop
@section('content')
    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="header">

            </div>

            <div style="float: inline-end;"><a href="{{ url('HCM/EmployeeForm/List') }}" class="btn btn-primary">Back</a>
            </div>
            <div class="body">
                <div class="nav nav-fill my-3">
                    <label for="" class="mainbuttons nav-link shadow-sm border ml-2 step1" id="step1"
                        style="background-color: rgb(96 123 255);">Personal Info</label>
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
                    <label for="" class="mainbuttons nav-link shadow-sm border ml-2 step9" id="step9">Role &
                        Access</label>
                </div>
                <form method="post" action="{{ url('HCM/EmployeeForm/Add') }}" class="form"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $employee->id ?? '' }}">
                    <div class="form-section section1" data-parsley-validate>
                        <div class="row">
                            <div class="col-md-2">
                                <label for="">Gender</label>
                                <select name="info_gender" class="  form-control show-tick ms select2">
                                    <option value="">Select Gender</option>
                                    @php
                                        $types = appLib::$types;
                                    @endphp
                                    <option value="Male" {{ $employee->gender ?? '' == 'Male' ? 'selected' : '' }}>
                                        Male
                                    </option>
                                    <option value="Female" {{ $employee->gender ?? '' == 'Female' ? 'selected' : '' }}>
                                        Female
                                    </option>
                                    <option value="Other" {{ $employee->gender ?? '' == 'Other' ? 'selected' : '' }}>
                                        Other
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="">Title</label>
                                <select name="info_title" class="  form-control show-tick ms select2">
                                    <option value="">Select Type</option>
                                    @php
                                        $types = appLib::$types;
                                    @endphp
                                    @foreach ($types as $type)
                                        <option value="{{ $type }}"
                                            {{ $type == ($employee->title ?? '') ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>First Name</label>
                                <input type="text" name="info_first_name" class="form-control "
                                    value="{{ $employee->first_name ?? '' }}" placeholder="First Name" required>
                            </div>
                            <div class="col-md-2">
                                <label for="code">Last Name</label>
                                <input type="text" name="info_last_name" class="form-control "
                                    value="{{ $employee->last_name ?? '' }}" placeholder="Last Name" required>
                            </div>
                            <div class="col-md-3">
                                <label for="note">Date Of Birth</label>
                                <div class="form-group">
                                    <input type="date" name="info_dob" class="form-control"
                                        value="{{ $employee->dob ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="email">Email</label>
                                <div class="form-group">
                                    <input type="email" name="info_email" class="form-control"
                                        value="{{ $employee->email ?? '' }}" placeholder="Email">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="note">Phone</label>
                                <div class="form-group">
                                    <input type="text" name="info_phone" class="form-control"
                                        value="{{ $employee->phone ?? '' }}" placeholder="Phone Number">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>Address 1</label>
                                <textarea name="info_address1" class="form-control" rows="1" placeholder="Enter Address">{{ $employee->address_one ?? '' }}</textarea>
                            </div>
                            <div class="col-md-3">
                                <label>Address 2</label>
                                <textarea name="info_address2" class="form-control" rows="1" placeholder="Enter Address">{{ $employee->address_two ?? '' }}</textarea>
                            </div>
                            <div class="col-md-3">
                                <label>Country</label>
                                <select name="info_country" class=" form-control show-tick ms select2">
                                    <option value="Pakistan"
                                        {{ ($employee->country ?? '') == 'Pakistan' ? 'selected' : '' }}>
                                        Pakistan</option>
                                    <option value="Australia"
                                        {{ ($employee->country ?? '') == 'Australia' ? 'selected' : '' }}>Australia
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>City</label>
                                <input type="text" name="info_city" class="form-control"
                                    value="{{ $employee->city ?? '' }}" placeholder="City">
                            </div>
                            <div class="col-md-3">
                                <label>State</label>
                                <input type="text" name="info_state" class="form-control"
                                    value="{{ $employee->state ?? '' }}" placeholder="State">
                            </div>
                            <div class="col-md-3">
                                <label>Zip Code</label>
                                <input type="text" name="info_zipcode" class="form-control"
                                    value="{{ $employee->code ?? '' }}" placeholder="Code">
                            </div>



                        </div>
                    </div>
                    {{-- First Section End --}}


                    @if (isset($education))

                        <div class="form-section section2" id="Education" style="display:none;" data-parsley-validate>
                            <p><i class="zmdi zmdi-plus-circle-o" onclick="Education()" style="float: inline-end"></i>
                            </p>
                            @foreach ($education as $education)
                                <input type="hidden" name="edu_id" value="{{ $education->id }}">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="labels">College / Uni</label>
                                        <input type="text" name="edu_ScholName[]" class="form-control inputs "
                                            value="{{ $education->college_name ?? '' }}" placeholder="College / Uni Name"
                                            required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="labels">From Date</label>
                                        <input type="date" name="edu_from_date[]" class="form-control inputs "
                                            value="{{ $education->from_date ?? '' }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="labels">To Date</label>
                                        <input type="date" name="edu_to_date[]" class="form-control inputs "
                                            value="{{ $education->to_date ?? '' }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="labels">Level</label>
                                        <input type="text" name="edu_level[]" class="form-control inputs "
                                            value="{{ $education->level ?? '' }}" placeholder="Level">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="labels">Marks</label>
                                        <input type="text" name="edu_marks[]" class="form-control inputs "
                                            value="{{ $education->marks ?? '' }}" placeholder="Marks">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="form-section section2" id="Education" style="display:none;" data-parsley-validate>
                            <p><i class="zmdi zmdi-plus-circle-o" onclick="Education()" style="float: inline-end"></i>
                            </p>
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="labels">Institute</label>
                                    <input type="text" name="edu_ScholName[]" class="form-control inputs "
                                        value="{{ $education->college_name ?? '' }}" placeholder="Institute" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="labels">From Date</label>
                                    <input type="date" name="edu_from_date[]" class="form-control inputs "
                                        value="{{ $education->from_date ?? '' }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="labels">To Date</label>
                                    <input type="date" name="edu_to_date[]" class="form-control inputs "
                                        value="{{ $education->to_date ?? '' }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="labels">Level</label>
                                    <input type="text" name="edu_level[]" class="form-control inputs "
                                        value="{{ $education->level ?? '' }}" placeholder="Level">
                                </div>
                                <div class="col-md-2">
                                    <label class="labels">Marks</label>
                                    <input type="text" name="edu_marks[]" class="form-control inputs "
                                        value="{{ $education->marks ?? '' }}" placeholder="Marks">
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- Second Section End --}}

                    @if (isset($employment))
                        <div class="form-section section3" id="Employment" style="display:none;" data-parsley-validate>
                            <p><i class="zmdi zmdi-plus-circle-o" onclick="Employment()" style="float: inline-end"></i>
                            </p>
                            @foreach ($employment as $employment)
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="hidden" name="emp_id" value="{{ $employment->id ?? '' }}">
                                        <label class="labels">Company</label>
                                        <input type="text" name="emp_company[]" class="form-control inputs "
                                            value="{{ $employment->company ?? '' }}" placeholder="Company" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="labels">From Date</label>
                                        <input type="date" name="emp_from_date[]" class="form-control inputs "
                                            value="{{ $employment->from_date ?? '' }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="labels">To date</label>
                                        <input type="date" name="emp_to_date[]" class="form-control inputs "
                                            value="{{ $employment->to_date ?? '' }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="labels">Designation</label>
                                        <input type="text" name="emp_designation[]" class="form-control inputs "
                                            value="{{ $employment->designation ?? '' }}" placeholder="Designation">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="form-section section3" id="Employment" style="display:none;" data-parsley-validate>
                            <p><i class="zmdi zmdi-plus-circle-o" onclick="Employment()" style="float: inline-end"></i>
                            </p>
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="labels">Company</label>
                                    <input type="text" name="emp_company[]" class="form-control inputs "
                                        value="{{ $employment->company ?? '' }}" placeholder="Company" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="labels">From Date</label>
                                    <input type="date" name="emp_from_date[]" class="form-control inputs "
                                        value="{{ $employment->from_date ?? '' }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="labels">To date</label>
                                    <input type="date" name="emp_to_date[]" class="form-control inputs "
                                        value="{{ $employment->to_date ?? '' }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="labels">Designation</label>
                                    <input type="text" name="emp_designation[]" class="form-control inputs "
                                        value="{{ $employment->designation ?? '' }}" placeholder="Designation">
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- Third Section End --}}



                    @if (isset($Emg_Contact))
                        <div class="form-section section4" id="Emg-contact" style="display:none;" data-parsley-validate>
                            <p><i class="zmdi zmdi-plus-circle-o" style="float: inline-end" onclick="Emg_contact()"></i>
                            </p>
                            @foreach ($Emg_Contact as $Emg_Contact)
                                <div class="row">
                                    <input type="hidden" name="emg_id" value="{{ $Emg_Contact->id ?? '' }}">
                                    <div class="col-md-3">
                                        <label class="labels">Name</label>
                                        <input type="text" name="emg_name[]" class="form-control inputs "
                                            value="{{ $Emg_Contact->name ?? '' }}" placeholder="Name" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">Relationship</label>
                                        <select name="emg_relationship[]" class="  form-control show-tick ms select2">
                                            <option value="">Select Relation</option>
                                            <option value="parent"
                                                {{ $Emg_Contact->relationship == 'parent' ? 'selected' : '' }}>Parent
                                            </option>
                                            <option value="sibling"
                                                {{ $Emg_Contact->relationship == 'sibling' ? 'selected' : '' }}>Sibling
                                            </option>
                                            <option value="friend"
                                                {{ $Emg_Contact->relationship == 'friend' ? 'selected' : '' }}>Friend
                                            </option>
                                            <option value="colleague"
                                                {{ $Emg_Contact->relationship == 'colleague' ? 'selected' : '' }}>
                                                Colleague</option>
                                            <option value="others"
                                                {{ $Emg_Contact->relationship == 'others' ? 'selected' : '' }}>Others
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="labels">Email</label>
                                        <input type="email" name="emg_email[]" class="form-control inputs "
                                            value="{{ $Emg_Contact->email ?? '' }}" placeholder="Email">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="labels">Phone</label>
                                        <input type="text" name="emg_phone[]" class="form-control inputs "
                                            value="{{ $Emg_Contact->phone ?? '' }}" placeholder="Phone">
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    @else
                        <div class="form-section section4" id="Emg-contact" style="display:none;" data-parsley-validate>
                            <p><i class="zmdi zmdi-plus-circle-o" style="float: inline-end" onclick="Emg_contact()"></i>
                            </p>
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="labels">Name</label>
                                    <input type="text" name="emg_name[]" class="form-control inputs "
                                        value="{{ $Emg_Contact->name ?? '' }}" placeholder="Name" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Relationship</label>
                                    <select name="emg_relationship[]" class="  form-control show-tick ms select2">
                                        <option value="">Select Relation</option>
                                        <option value="parent">Parent
                                        </option>
                                        <option value="sibling">Sibling
                                        </option>
                                        <option value="friend">Friend
                                        </option>
                                        <option value="colleague">Colleague
                                        </option>
                                        <option value="others">Others
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="labels">Email</label>
                                    <input type="email" name="emg_email[]" class="form-control inputs "
                                        value="{{ $Emg_Contact->email ?? '' }}" placeholder="Email">
                                </div>
                                <div class="col-md-3">
                                    <label class="labels">Phone</label>
                                    <input type="text" name="emg_phone[]" class="form-control inputs "
                                        value="{{ $Emg_Contact->phone ?? '' }}" placeholder="Phone">
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- Fourth Section End --}}


                    @if (isset($Documents))
                        <div class="form-section section5" id="Documents" style="display:none;" data-parsley-validate>
                            <p><i class="zmdi zmdi-plus-circle-o" style="float: inline-end" onclick="Documents()"></i>
                            </p>
                            @foreach ($Documents as $document)
                                @php
                                    $documentsAttachments = DB::table('sys_attachments')
                                        ->where('source_id', $document->id)
                                        ->first();
                                @endphp
                                <input type="hidden" name="doc_id" value="{{ $document->id ?? '' }}">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="labels">Subject</label>
                                        <input type="text" name="doc_subject[]" class="form-control inputs"
                                            value="{{ $document->subject ?? '' }}" placeholder="Subject" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="labels">Expiration</label>
                                        <input type="date" name="doc_expiration[]" class="form-control inputs"
                                            value="{{ $document->expiration ?? '' }}" placeholder="Explain">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="labels">Browse</label>
                                        <input type="file" name="doc_file[]" class="form-control inputs">
                                    </div>

                                    <div class="doc" style="margin-top: 35px;">
                                        <a href="" data-toggle="modal"
                                            data-target="#exampleModal{{ $document->id }}"><i style="font-size: 25px;"
                                                class="zmdi zmdi-file"></i></a>
                                    </div>
                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModal{{ $document->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">
                                                        {{ $documentsAttachments->file ?? '' }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <img src="data:image/png;base64,{{ base64_encode($documentsAttachments->content ?? '') }}"
                                                        alt="">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Model End --}}
                                </div>
                            @endforeach

                        </div>
                    @else
                        <div class="form-section section5" id="Documents" style="display:none;" data-parsley-validate>
                            <p><i class="zmdi zmdi-plus-circle-o" style="float: inline-end" onclick="Documents()"></i>
                            </p>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="labels">Subject</label>
                                    <input type="text" name="doc_subject[]" class="form-control inputs"
                                        value="{{ $document->subject ?? '' }}" placeholder="Subject" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="labels">Expiration</label>
                                    <input type="date" name="doc_expiration[]" class="form-control inputs"
                                        value="{{ $document->expiration ?? '' }}" placeholder="Explain">
                                </div>
                                <div class="col-md-3">
                                    <label class="labels">Browse</label>
                                    <input type="file" name="doc_file[]" class="form-control inputs">
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- Fifth Section End --}}


                    @if (isset($BankDetail))
                        <div class="form-section section6" id="Bank-Details" style="display:none;" data-parsley-validate>
                            <p><i class="zmdi zmdi-plus-circle-o" style="float: inline-end" onclick="Bank_Details()"></i>
                            </p>
                            @foreach ($BankDetail as $BankDetail)
                                <input type="hidden" name="bank_account_id" value="{{ $BankDetail->id ?? '' }}">

                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="labels">Account Title</label>
                                        <input type="text" name="bank_account_title[]" class="form-control inputs "
                                            value="{{ $BankDetail->account_title ?? '' }}" placeholder="Account Title"
                                            required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="labels">Account Number</label>
                                        <input type="text" name="bank_account_number[]" class="form-control inputs "
                                            value="{{ $BankDetail->account_number ?? '' }}" placeholder="Account Number">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="labels">Bank Name</label>
                                        <input type="text" name="bank_title[]" class="form-control inputs "
                                            value="{{ $BankDetail->bank_name ?? '' }}" placeholder="Bank Name">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="labels">Branch Code</label>
                                        <input type="text" name="bank_branch_code[]" class="form-control inputs "
                                            value="{{ $BankDetail->bank_branch_code ?? '' }}" placeholder="Branch Code">
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    @else
                        <div class="form-section section6" id="Bank-Details" style="display:none;" data-parsley-validate>
                            <p><i class="zmdi zmdi-plus-circle-o" style="float: inline-end" onclick="Bank_Details()"></i>
                            </p>
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="labels">Account Title</label>
                                    <input type="text" name="bank_account_title[]" class="form-control inputs "
                                        value="{{ $BankDetail->account_title ?? '' }}" placeholder="Account Title"
                                        required>
                                </div>
                                <div class="col-md-3">
                                    <label class="labels">Account Number</label>
                                    <input type="text" name="bank_account_number[]" class="form-control inputs "
                                        value="{{ $BankDetail->account_number ?? '' }}" placeholder="Account Number">
                                </div>
                                <div class="col-md-3">
                                    <label class="labels">Bank Name</label>
                                    <input type="text" name="bank_title[]" class="form-control inputs "
                                        value="{{ $BankDetail->bank_name ?? '' }}" placeholder="Bank Name">
                                </div>
                                <div class="col-md-3">
                                    <label class="labels">Branch Code</label>
                                    <input type="text" name="bank_branch_code[]" class="form-control inputs "
                                        value="{{ $BankDetail->bank_branch_code ?? '' }}" placeholder="Branch Code">
                                </div>
                            </div>

                        </div>
                    @endif
                    {{-- Six Section End --}}


                    @if (isset($Govt_Doc))
                        <div class="form-section section7" id="Govt_Documents" style="display: none;"
                            data-parsley-validate>
                            <p><i class="zmdi zmdi-plus-circle-o" style="float: inline-end"
                                    onclick="Govt_Documents()"></i>
                            </p>
                            @foreach ($Govt_Doc as $Govt_Doc)
                                @php
                                    $Gov_Attachments = DB::table('sys_attachments')
                                        ->where('source_id', $Govt_Doc->id)
                                        ->first();
                                @endphp


                                <input type="hidden" name="govt_doc_id" value="{{ $Govt_Doc->id ?? '' }}">

                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="labels">Document Type</label>
                                        <input type="text" name="govt_doc_type[]" class="form-control inputs "
                                            value="{{ $Govt_Doc->doc_type ?? '' }}" placeholder="Document Type" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="labels">Issue Date</label>
                                        <input type="date" name="govt_doc_from_date[]" class="form-control inputs "
                                            value="{{ $Govt_Doc->issue_date ?? '' }}" placeholder="Account Number">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="labels">Expire Date</label>
                                        <input type="date" name="govt_doc_to_date[]" class="form-control inputs "
                                            value="{{ $Govt_Doc->exp_date ?? '' }}" placeholder="Bank Name">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="labels">Note</label>
                                        <input type="text" name="govt_doc_note[]" class="form-control inputs "
                                            value="{{ $Govt_Doc->note ?? '' }}" placeholder="Enter Note">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="labels">File</label>
                                        <input type="file" name="govt_doc_file[]" class="form-control inputs"
                                            id="govt_doc_file">
                                    </div>

                                    <div class="pic" style="margin-top: 35px;">
                                        <a href="" data-toggle="modal"
                                            data-target="#exampleModal{{ $Govt_Doc->id ?? '' }} "><i
                                                style="font-size: 25px;" class="zmdi zmdi-file"></i></a>
                                    </div>
                                    {{-- <div class="col-md-1">
                                        <img class="img-fluid" id="selectedImage"
                                            src="data:image/pmg;base64,{{ base64_encode($Govt_Doc_Attachments->content ?? '') }}"
                                            alt="">
                                    </div> --}}
                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModal{{ $Govt_Doc->id ?? '' }}" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">
                                                        {{ $Gov_Attachments->file ?? '' }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <img src="data:image/png;base64,{{ base64_encode($Gov_Attachments->content ?? '') }}"
                                                        alt="">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    @else
                        <div class="form-section section7" id="Govt_Documents" style="display:none;"
                            data-parsley-validate>
                            <p><i class="zmdi zmdi-plus-circle-o" style="float: inline-end"
                                    onclick="Govt_Documents()"></i>
                            </p>
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="labels">Document Type</label>
                                    <input type="text" name="govt_doc_type[]" class="form-control inputs "
                                        value="{{ $Govt_Doc->doc_type ?? '' }}" placeholder="Document Type" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="labels">Issue Date</label>
                                    <input type="date" name="govt_doc_from_date[]" class="form-control inputs "
                                        value="{{ $Govt_Doc->issue_date ?? '' }}" placeholder="Account Number">
                                </div>
                                <div class="col-md-2">
                                    <label class="labels">Expire Date</label>
                                    <input type="date" name="govt_doc_to_date[]" class="form-control inputs "
                                        value="{{ $Govt_Doc->exp_date ?? '' }}" placeholder="Bank Name">
                                </div>
                                <div class="col-md-3">
                                    <label class="labels">Note</label>
                                    <input type="text" name="govt_doc_note[]" class="form-control inputs "
                                        value="{{ $Govt_Doc->note ?? '' }}" placeholder="Enter Note">
                                </div>
                                <div class="col-md-2">
                                    <label class="labels">File</label>
                                    <input type="file" name="govt_doc_file[]" class="form-control inputs"
                                        id="govt_doc_file">
                                </div>
                                <div class="col-md-1">
                                    <img class="img-fluid" id="selectedImage"
                                        src="data:image/pmg;base64,{{ base64_encode($Govt_Doc_Attachments->content ?? '') }}"
                                        alt="">
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- Seventh Section End --}}


                    <div class="form-section section8" style="display: none;" data-parsley-validate>
                        <input type="hidden" name="work_info_id" value="{{ $work_info->id ?? '' }}">
                        <div class="row">
                            <div class="col-md-2">
                                <label>Department</label>
                                <select name="info_department" class=" form-control show-tick ms select2" required>
                                    <option value="" disabled selected>----Select----</option>
                                    @foreach ($Departments as $Department)
                                        <option value="{{ $Department->name }}"
                                            {{ $Department->name == ($work_info->department ?? '') ? 'selected' : '' }}>
                                            {{ $Department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Designation</label>
                                <select name="info_designation" class=" form-control show-tick ms select2">
                                    <option value="" disabled selected>----Select----</option>
                                    @foreach ($Designations as $Desig)
                                        <option value="{{ $Desig->designation ?? '' }}"
                                            {{ $Desig->designation == ($work_info->designation ?? '') ? 'selected' : '' }}>
                                            {{ $Desig->designation ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Reporting To</label>
                                <select name="info_reporting_to" class=" form-control show-tick ms select2">
                                    <option value="" disabled selected>----Select----</option>
                                    @foreach ($Departments as $Department)
                                        <option value="{{ $Department->name }}"
                                            {{ $Department->name == ($work_info->reporting_to ?? '') ? 'selected' : '' }}>
                                            {{ $Department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Type</label>
                                <select name="info_type" class=" form-control show-tick ms select2" required>
                                    <option value="" disabled selected>----Select----</option>
                                    <option value="Onsite" {{ ($work_info->type ?? '') == 'Onsite' ? 'selected' : '' }}>
                                        Onsite</option>
                                    <option value="Remote" {{ ($work_info->type ?? '') == 'Remote' ? 'selected' : '' }}>
                                        Remote</option>
                                    <option value="Hybrid" {{ ($work_info->type ?? '') == 'Hybrid' ? 'selected' : '' }}>
                                        Hybrid</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Category</label>
                                <select name="info_category" class=" form-control show-tick ms select2" required>
                                    <option value="" disabled selected>----Select----</option>
                                    <option
                                        value="Permanent"{{ ($work_info->category ?? '') == 'Permanent' ? 'selected' : '' }}>
                                        Permanent</option>
                                    <option
                                        value="Contractual"{{ ($work_info->category ?? '') == 'Contractual' ? 'selected' : '' }}>
                                        Contractual</option>
                                    <option
                                        value="Outsource"{{ ($work_info->category ?? '') == 'Outsource' ? 'selected' : '' }}>
                                        Outsource</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Joining Date</label>
                                <input type="date" value="{{ $work_info->joining_date ?? '' }}" name="info_joining"
                                    class="form-control " placeholder="Password" required>
                            </div>
                            <div class="col-md-3">
                                <label>Description1</label>
                                <textarea name="info_description1" id="" class="form-control" cols="30" rows="2">{{ $work_info->description1 ?? '' }}</textarea>
                            </div>
                            <div class="col-md-3">
                                <label>Description2</label>
                                <textarea name="info_description2" id="" class="form-control" cols="30" rows="2">{{ $work_info->description2 ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                    {{-- Eight Section End --}}


                    <div class="form-section section9" style="display:none;" data-parsley-validate>
                        <div class="row">
                            <div class="col-md-2">
                                <input type="hidden" name="user_id" value="{{ $User->id ?? '' }}">
                                <label>Email</label>
                                <input type="text" name="user_email" data-parsley-type="email" class="form-control "
                                    value="{{ $User->email ?? '' }}" placeholder="Email" required>
                            </div>
                            <div class="col-md-2">
                                <label>User Name</label>
                                <input type="text" name="user_name" class="form-control "
                                    value="{{ $User->name ?? '' }}" placeholder="User Name" required>
                            </div>
                            <div class="col-md-2">
                                <label>Password</label>
                                <input type="password" name="user_password" class="form-control " placeholder="Password"
                                    required>
                            </div>
                            <div class="col-md-3">
                                <label>Role</label>
                                <select name="user_role" class=" form-control show-tick ms select2" required>
                                    <option value="" disabled selected>----Select Role----</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}"
                                            {{ $User && $role->name == $User->role ? 'selected' : '' }}>
                                            {{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Status</label>
                                <select name="user_status" class=" form-control show-tick ms select2" required>
                                    <option disabled selected>
                                        Select Status</option>
                                    <option value="0" {{ $User && $User->status == '0' ? 'selected' : '' }}>
                                        On</option>
                                    <option value="1" {{ $User && $User->status == '0' ? 'selected' : '' }}>Off
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    {{-- Nine Section End --}}

                    <div>
                        <button type="button" class="btn btn-primary prevBtn" style="display: none;"><i
                                class="zmdi zmdi-arrow-left"></i>
                            Previous</button>
                        <button type="button" class="btn btn-primary skipBtn next"></i>
                            SkiP <i class="zmdi zmdi-arrow-right"></i></button>
                        <button type="button" class="btn btn-primary nextBtn">Next <i
                                class="zmdi zmdi-check"></i></button>
                        <button type="button" class="btn btn-primary submitBtn" style="display: none;">Save</button>
                    </div>

                </form>


            </div>
        </div>
    </div>
@stop
@section('page-script')
    <script src="{{ asset('public/assets/plugins/dropify/js/dropify.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/pages/forms/dropify.js') }}"></script>
    <script src="{{ asset('public/assets/js/sw.js') }}"></script>
    {{-- <script>
        $(document).ready(function(){
            var $sections = $('.form-section');

            function navigateTo(index){
                $sections.removeClass('current')
            }
        });
    </script> --}}
    {{-- <script>
        $(document).ready(function() {
            var currentSection = 1;


            $(".next").on("click", function() {
                $(".section" + currentSection).hide();
                currentSection++;
                $(".section" + currentSection).show();

                $(".nextBtn").on("click", function() {
                    var isValid = $('.section' + currentSection).parsley().validate();

                    if (isValid) {
                        $(".section" + currentSection).hide();
                        currentSection++;
                        $(".section" + currentSection).show();

                    }
                });



                if (currentSection === 2) {
                    document.getElementById("step1").style.backgroundColor = "#7fc47f";
                    document.getElementById("step2").style.backgroundColor = "rgb(96 123 255)";
                    $(".prevBtn").show();

                }


                if (currentSection === 3) {
                    document.getElementById("step1").style.backgroundColor = "#7fc47f";
                    document.getElementById("step2").style.backgroundColor = "#7fc47f";
                    document.getElementById("step3").style.backgroundColor = "rgb(96 123 255)";
                }

                if (currentSection === 4) {
                    document.getElementById("step1").style.backgroundColor = "#7fc47f";
                    document.getElementById("step2").style.backgroundColor = "#7fc47f";
                    document.getElementById("step3").style.backgroundColor = "#7fc47f";
                    document.getElementById("step4").style.backgroundColor = "rgb(96 123 255)";
                }

                if (currentSection === 5) {
                    document.getElementById("step1").style.backgroundColor = "#7fc47f";
                    document.getElementById("step2").style.backgroundColor = "#7fc47f";
                    document.getElementById("step3").style.backgroundColor = "#7fc47f";
                    document.getElementById("step4").style.backgroundColor = "#7fc47f";
                    document.getElementById("step5").style.backgroundColor = "rgb(96 123 255)";
                }

                if (currentSection === 6) {
                    document.getElementById("step1").style.backgroundColor = "#7fc47f";
                    document.getElementById("step2").style.backgroundColor = "#7fc47f";
                    document.getElementById("step3").style.backgroundColor = "#7fc47f";
                    document.getElementById("step4").style.backgroundColor = "#7fc47f";
                    document.getElementById("step5").style.backgroundColor = "#7fc47f";
                    document.getElementById("step6").style.backgroundColor = "rgb(96 123 255)";
                }

                if (currentSection === 7) {
                    document.getElementById("step1").style.backgroundColor = "#7fc47f";
                    document.getElementById("step2").style.backgroundColor = "#7fc47f";
                    document.getElementById("step3").style.backgroundColor = "#7fc47f";
                    document.getElementById("step4").style.backgroundColor = "#7fc47f";
                    document.getElementById("step5").style.backgroundColor = "#7fc47f";
                    document.getElementById("step6").style.backgroundColor = "#7fc47f";
                    document.getElementById("step7").style.backgroundColor = "rgb(96 123 255)";
                }


                if (currentSection === 8) {
                    document.getElementById("step1").style.backgroundColor = "#7fc47f";
                    document.getElementById("step2").style.backgroundColor = "#7fc47f";
                    document.getElementById("step3").style.backgroundColor = "#7fc47f";
                    document.getElementById("step4").style.backgroundColor = "#7fc47f";
                    document.getElementById("step5").style.backgroundColor = "#7fc47f";
                    document.getElementById("step6").style.backgroundColor = "#7fc47f";
                    document.getElementById("step7").style.backgroundColor = "#7fc47f";
                    document.getElementById("step8").style.backgroundColor = "rgb(96 123 255)";
                    $(".next").hide();
                    $(".submitBtn").show();

                    $(".nextBtn").hide();

                }

            });

            $(".prevBtn").on("click", function() {
                $(".section" + currentSection).hide();
                currentSection--;
                $(".section" + currentSection).show();

                if (currentSection === 1) {
                    $(".prevBtn").hide();
                }

                if (currentSection === 1) {
                    document.getElementById("step1").style.backgroundColor = "rgb(96 123 255)";
                    document.getElementById("step2").style.backgroundColor = "white";
                    document.getElementById("step3").style.backgroundColor = "white";
                    document.getElementById("step4").style.backgroundColor = "white";
                    document.getElementById("step5").style.backgroundColor = "white";
                    document.getElementById("step6").style.backgroundColor = "white";
                    document.getElementById("step7").style.backgroundColor = "white";
                    document.getElementById("step8").style.backgroundColor = "white";
                }
                if (currentSection === 2) {
                    document.getElementById("step2").style.backgroundColor = "rgb(96 123 255)";
                    document.getElementById("step3").style.backgroundColor = "white";
                    document.getElementById("step4").style.backgroundColor = "white";
                    document.getElementById("step5").style.backgroundColor = "white";
                    document.getElementById("step6").style.backgroundColor = "white";
                    document.getElementById("step7").style.backgroundColor = "white";
                    document.getElementById("step8").style.backgroundColor = "white";
                }
                if (currentSection === 3) {
                    document.getElementById("step3").style.backgroundColor = "rgb(96 123 255)";
                    document.getElementById("step4").style.backgroundColor = "white";
                    document.getElementById("step5").style.backgroundColor = "white";
                    document.getElementById("step6").style.backgroundColor = "white";
                    document.getElementById("step7").style.backgroundColor = "white";
                    document.getElementById("step8").style.backgroundColor = "white";
                }
                if (currentSection === 4) {
                    document.getElementById("step4").style.backgroundColor = "rgb(96 123 255)";
                    document.getElementById("step5").style.backgroundColor = "white";
                    document.getElementById("step6").style.backgroundColor = "white";
                    document.getElementById("step7").style.backgroundColor = "white";
                    document.getElementById("step8").style.backgroundColor = "white";
                }
                if (currentSection === 5) {
                    document.getElementById("step5").style.backgroundColor = "rgb(96 123 255)";
                    document.getElementById("step6").style.backgroundColor = "white";
                    document.getElementById("step7").style.backgroundColor = "white";
                    document.getElementById("step8").style.backgroundColor = "white";
                }
                if (currentSection === 6) {
                    document.getElementById("step6").style.backgroundColor = "rgb(96 123 255)";
                    document.getElementById("step7").style.backgroundColor = "white";
                    document.getElementById("step8").style.backgroundColor = "white";
                }
                if (currentSection === 7) {
                    document.getElementById("step7").style.backgroundColor = "rgb(96 123 255)";
                    document.getElementById("step8").style.backgroundColor = "white";
                }

                if (currentSection < 8) {

                    $(".skipBtn").show();
                    $(".nextBtn").show();
                    $(".submitBtn").hide();
                }
            });
            $(".submitBtn").on("click", function() {
                $(".form").submit();

            });
        });
    </script> --}}
    <script>
        $(document).ready(function() {
            var currentSection = 1;
            var totalSections = 9;

            function updateStepColors() {
                for (let i = 1; i <= totalSections; i++) {
                    let stepId = "step" + i;
                    let color = i === currentSection ? "rgb(96 123 255)" : "white";
                    document.getElementById(stepId).style.backgroundColor = color;
                }
            }

            function updateButtonVisibility() {

                $(".prevBtn").toggle(currentSection !== 1);
                $(".skipBtn").toggle(currentSection !== 1 && currentSection < totalSections);
                $(".nextBtn ").toggle(currentSection < totalSections);
                $(".submitBtn").toggle(currentSection === totalSections);
            }



            function showNextSection() {
                $(".section" + currentSection).hide();
                currentSection++;
                $(".section" + currentSection).show();

                updateStepColors();
                updateButtonVisibility();
            }

            $(".nextBtn").on("click", function() {
                var isValid = $('.section' + currentSection).parsley().validate();

                if (isValid) {
                    showNextSection();
                }
            });

            $(".skipBtn").on("click", function() {
                showNextSection();
            });

            $(".prevBtn").on("click", function() {
                $(".section" + currentSection).hide();
                currentSection--;
                $(".section" + currentSection).show();

                updateStepColors();
                updateButtonVisibility();
            });

            $(".submitBtn").on("click", function() {
                var issValid = $('.section' + currentSection).parsley().validate();

                if (issValid) {
                    $(".form").submit();
                }
            });

            // Initial setup
            updateStepColors();
            updateButtonVisibility();
        });
    </script>
    <script>
        function Education() {
            // Show the Education section
            $('#Education').show();

            // Append the content inside the Education section
            $('#Education').append(`
            <div class="form-section ">
                        <p><i class="zmdi zmdi-minus-circle-outline remove-table-row" style="float: inline-end"></i></p>
                        <div class="row m-1">
                            <div class="col-md-3">
                                <input type="text" name="edu_ScholName[]" class="form-control inputs "
                                     placeholder="Institute" required>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="edu_from_date[]" class="form-control inputs "
                                    >
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="edu_to_date[]" class="form-control inputs "
                                    >
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="edu_level[]" class="form-control inputs "
                                     placeholder="Level">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="edu_marks[]" class="form-control inputs "
                                     placeholder="Marks">
                            </div>
                        </div>
                    </div>
        `);

            $(document).on('click', '.remove-table-row', function() {

                $(this).closest('div').remove();
            });

        }
    </script>
    <script>
        function Employment() {

            $('#Employment').show();

            $('#Employment').append(`
            <div class="form-section">
                        <p><i class="zmdi zmdi-minus-circle-outline remove-div" style="float: inline-end"></i></p>
                        <div class="row m-1">
                            <div class="col-md-3">
                                <input type="text" name="emp_company[]" class="form-control inputs "
                                     placeholder="Company" required>
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="emp_from_date[]" class="form-control inputs "
                                    >
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="emp_to_date[]" class="form-control inputs "
                                    >
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="emp_designation[]" class="form-control inputs "
                                     placeholder="Designation">
                            </div>
                        </div>
                    </div>`);

            $(document).on('click', '.remove-div', function() {
                $(this).closest('div').remove();
            })

        }
    </script>
    <script>
        function Emg_contact() {
            $('#Emg-contact').show();

            $('#Emg-contact').append(`
            <div class="form-section " >
                        <p><i class="zmdi zmdi-minus-circle-outline remove-div" style="float: inline-end"></i></p>
                        <div class="row">
                                    <input type="hidden" name="emg_id" value="">
                                    <div class="col-md-3">
                                        <input type="text" name="emg_name[]" class="form-control inputs "
                                            value="" placeholder="Name" required>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="emg_relationship[]" class="  form-control show-tick ms select2">
                                            <option value="" >Select Relation</option>
                                            <option value="parent">Parent</option>
                                            <option value="sibling">Sibling</option>
                                            <option value="friend">Friend</option>
                                            <option value="colleague">Colleague</option>
                                            <option value="others">Others</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="email" name="emg_email[]" class="form-control inputs "
                                            value="" placeholder="Email">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="emg_phone[]" class="form-control inputs "
                                            value="" placeholder="Phone">
                                    </div>
                                </div>
                    </div>`);

        }
    </script>
    <script>
        function Documents() {
            $('#Documents').show();

            $('#Documents').append(`
            <div class="form-section" >
                        <p><i class="zmdi zmdi-minus-circle-outline remove-div" style="float: inline-end"></i></p>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="doc_subject[]" class="form-control inputs "
                                     placeholder="Subject" required>
                            </div>
                            <div class="col-md-4">
                                        <input type="date" name="doc_expiration[]" class="form-control inputs"
                                            value="" placeholder="Explain">
                                    </div>
                            <div class="col-md-3">
                                <input type="file" name="doc_file[]" class="form-control inputs">
                            </div>
                        </div>
                    </div>`);
        }
    </script>
    <script>
        function Bank_Details() {
            $('#Bank-Details').show();

            $('#Bank-Details').append(`
                     <div class="form-section ">
                        <p><i class="zmdi zmdi-minus-circle-outline remove-div" style="float: inline-end"></i></p>
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="bank_account_title[]" class="form-control inputs "
                                     placeholder="Account Title" required>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="bank_account_number[]" class="form-control inputs "
                                     placeholder="Account Number">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="bank_title[]" class="form-control inputs "
                                     placeholder="Bank Name">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="bank_branch_code[]" class="form-control inputs "
                                     placeholder="Branch Code">
                            </div>
                        </div>
                    </div>`);

        }
    </script>
    <script>
        function Govt_Documents() {

            $('#Govt_Documents').show();

            $('#Govt_Documents').append(`
            <div class="form-section " >
                        <p><i class="zmdi zmdi-minus-circle-outline remove-div" style="float: inline-end"></i>
                        </p>
                        <div class="row">
                            <div class="col-md-2">
                                <input type="text" name="govt_doc_type[]" class="form-control inputs "
                                     placeholder="Document Type" required>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="govt_doc_from_date[]" class="form-control inputs "
                                     placeholder="Account Number">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="govt_doc_to_date[]" class="form-control inputs "
                                     placeholder="Bank Name">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="govt_doc_note[]" class="form-control inputs "
                                     placeholder="Enter Note">
                            </div>
                            <div class="col-md-2">
                                <input type="File" name="govt_doc_file[]" class="form-control inputs "
                                    >
                            </div>
                        </div>
                    </div>`);

        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"
        integrity="sha512-eyHL1atYNycXNXZMDndxrDhNAegH2BDWt1TmkXJPoGf1WLlNYt08CSjkqF5lnCRmdm3IrkHid8s2jOUY4NIZVQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.js"
        integrity="sha512-Fq/wHuMI7AraoOK+juE5oYILKvSPe6GC5ZWZnvpOO/ZPdtyA29n+a5kVLP4XaLyDy9D1IBPYzdFycO33Ijd0Pg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

@stop
