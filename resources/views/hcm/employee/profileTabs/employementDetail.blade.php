<!-- Employment Detail Start -->
<div class="px-1">
    <div class="d-flex justify-content-between">
        <div>

        </div>
        <div class="">
            @if($employementDetails->isEmpty())
                <div class="p-1 px-2 rounded pe-auto" style="background-color: #0C7CE6; color:white; cursor: pointer;" title="Add Employment Detail" data-toggle="modal" data-target="#addEmploymentDetail">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                    </svg>
                </div>
            @else
                <div class="p-1 px-2 rounded pe-auto" style="background-color: #0C7CE6; color:white; cursor: pointer;" title="Edit Employment Detail" data-toggle="modal" data-target="#editEmploymentDetail">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                    </svg>
                </div>
            @endif

        </div>

    </div>
    <div class="p-3 mt-2 rounded" style="background-color: #F5F5F5;">

        @if($employementDetails->isEmpty())
            <h5 class="text-center font-bold"> Please Add Employee Official Detail</h5>
        @endif

    @foreach($employementDetails as $employementDetail)
        @php
            $department = DB::table('hcm_departments')->where('id', $employementDetail->department_id)->first();
            $designation = DB::table('hcm_designation')->where('id', $employementDetail->designation_id)->first();
            $shift = DB::table('hcm_shifts')->where('id', $employementDetail->shift_id)->first();
        @endphp

            <div class="row col-12">
                <div class="col-3 gap-2 d-flex">
                    <p class="font-bold">Employee code:</p>
                    <p class="text-primary">{{ $employementDetail->emp_code }}</p>
                </div>
                <div class="col-3 gap-2 d-flex">
                    <p class="font-bold">Employement Type:</p>
                    <p class="text-primary">{{ $employementDetail->emp_type }}</p>
                </div>
                <div class="col-2 gap-2 d-flex">
                    <p class="font-bold">DOJ:</p>
                    <p class="text-primary">{{ $employementDetail->doj }}</p>
                </div>
                <div class="col-4 gap-2 d-flex">
                    <p class="font-bold">Company Email:</p>
                    <p class="text-primary">{{ $employementDetail->company_email }}</p>
                </div>
                <div class="col-3 gap-2 d-flex">
                    <p class="font-bold">Department:</p>
                    <p class="text-primary">{{ $department->name }}</p>
                </div>
                <div class="col-3 gap-2 d-flex">
                    <p class="font-bold">Designation:</p>
                    <p class="text-primary">{{ $designation->designation }}</p>
                </div>
{{--                <div class="col-2 gap-2 d-flex">--}}
{{--                    <p class="font-bold">Reporting To:</p>--}}
{{--                    <p class="text-primary">ABCD</p>--}}
{{--                </div>--}}
                <div class="col-2 gap-2 d-flex">
                    <p class="font-bold">Shift:</p>
                    <p class="text-primary">{{ $shift->shift_name }}</p>
                </div>
                <div class="col-3 gap-2 d-flex">
                    <p class="font-bold">Work Location:</p>
                    <p class="text-primary">{{ $employementDetail->work_location }}</p>
                </div>
                <div class="col-2 gap-2 d-flex">
                    <p class="font-bold">Payroll Type:</p>
                    <p class="text-primary">{{ $employementDetail->payroll_type }}</p>
                </div>
            </div>

            <div class="row col-12 mt-3">
                <h6 class="text-primary col-12 font-bold">Credientials</h6>
                <div class="col-3 gap-2 d-flex">
                    <p class="font-bold">Username:</p>
                    <p class="text-primary">{{ $employementDetail->username }}</p>
                </div>
                <div class="col-3 gap-2 d-flex">
                    <p class="font-bold">Role:</p>
                    <p class="text-primary">{{ $employementDetail->role_id }}</p>
                </div>

                <div class="col-3 gap-2 d-flex">
                    <p class="font-bold">Status:</p>
                    <p class="text-primary">{{ $employementDetail->status }}</p>
                </div>
            </div>







                <div class="modal fade" id="editEmploymentDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Employment Detail</h5>
                                <p style="cursor:pointer;" class="close text-primary" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                        </svg>
                    </span>
                                </p>
                            </div>
                            <form action="{{ route('employment-detail.update', $employementDetail->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                                    <div class="row col-lg-12">
                                        <div class="mb-2 col-lg-3">
                                            <label for="emp_code" class="form-label mb-0">Emp Code</label>
                                            <input type="text" class="form-control" value="{{ $employementDetail->emp_code }}" id="emp_code" name="emp_code"/>
                                        </div>
                                        <div class="mb-2 col-lg-6">
                                            <label for="emp_type" class="form-label mb-0">Employment Type</label>
                                            <select name="emp_type" id="emp_type" class="form-control">
                                                <option value="">Select</option>
                                                <option value="Permanent" {{ $employementDetail->emp_type == 'Permanent' ? 'selected' : '' }}>Permanent</option>
                                                <option value="Part_Time" {{ $employementDetail->emp_type == 'Part_Time' ? 'selected' : '' }}>Part Time</option>
                                                <option value="Apprenticeship" {{ $employementDetail->emp_type == 'Apprenticeship' ? 'selected' : '' }}>Apprenticeship</option>
                                                <option value="Interns" {{ $employementDetail->emp_type == 'Interns' ? 'selected' : '' }}>Interns</option>
                                            </select>
                                        </div>
                                        <div class="mb-2 col-lg-3">
                                            <label for="doj" class="form-label mb-0">DOJ</label>
                                            <input type="date" class="form-control" value="{{ $employementDetail->doj }}" id="doj" name="doj"/>
                                        </div>
                                        <div class="mb-2 col-lg-6">
                                            <label for="department_id" class="form-label mb-0">Department</label>
                                            <select name="department_id" id="department_id" class="form-control">
                                                <option value="">Select</option>
                                                @foreach($departments as $depart)
                                                    <option value="{{ $depart->id }}" {{ $depart->id == $employementDetail->department_id ? 'selected' : '' }}>{{ $depart->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-2 col-lg-6">
                                            <label for="designation_id" class="form-label mb-0">Designation</label>
                                            <select name="designation_id" id="designation_id" class="form-control">
                                                <option value="">Select</option>
                                                @foreach($designations as $designation)
                                                    <option value="{{ $designation->id }}" {{ $designation->id == $employementDetail->designation_id ? 'selected' : '' }}>{{ $designation->designation }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-2 col-lg-6">
                                            <label for="shift_id" class="form-label mb-0">Shift</label>
                                            <select name="shift_id" id="shift_id" class="form-control">
                                                <option value="">Select</option>
                                                @foreach($shifts as $shift)
                                                    <option value="{{ $shift->id }}" {{ $shift->id == $employementDetail->shift_id ? 'selected' : '' }}>{{ $shift->shift_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-2 col-lg-6">
                                            <label for="payroll_type" class="form-payroll_typelabel mb-0">Payroll Type</label>
                                            <select name="payroll_type" id="payroll_type" class="form-control">
                                                <option value="">Select</option>
                                                <option value="Daily" {{ $employementDetail->payroll_type == 'Daily' ? 'selected' : '' }}>Daily</option>
                                                <option value="Monthly" {{ $employementDetail->payroll_type == 'Monthly' ? 'selected' : '' }}>Monthly</option>
                                                <option value="Yearly" {{ $employementDetail->payroll_type == 'Yearly' ? 'selected' : '' }}>Yearly</option>
                                            </select>
                                        </div>
                                        <div class="mb-2 col-lg-4">
                                            <label for="work_location" class="form-label mb-0">Work Location</label>
                                            <input type="text" class="form-control" id="work_location" value="{{ $employementDetail->work_location }}" name="work_location"/>
                                        </div>

                                        <div class="row col-lg-12 mt-3">
                                            <div class="mb-2 col-lg-4">
                                                <label for="company_email" class="form-label mb-0">Company Email</label>
                                                <input type="email" class="form-control" id="company_email" value="{{ $employementDetail->company_email }}" name="company_email"/>
                                            </div>
                                            <div class="mb-2 col-lg-4">
                                                <label for="username" class="form-label mb-0">Username</label>
                                                <input type="text" class="form-control" id="username" value="{{ $employementDetail->username }}" name="username"/>
                                            </div>
                                            <div class="mb-2 col-lg-4">
                                                <label for="password" class="form-label mb-0">Password</label>
                                                <input type="password" class="form-control" id="password" name="password"/>
                                            </div>
                                            <div class="mb-2 col-lg-6">
                                                <label for="role" class="form-label mb-0">Role</label>
                                                <select name="role" id="role" class="form-control">
                                                    <option value="">Select</option>
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->name }}" {{ $role->name == $employementDetail->role_id ? 'selected' : '' }}>{{ $role->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-2 col-lg-6">
                                                <label for="status" class="form-label mb-0">Status</label>
                                                <select name="status" id="status" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Active">Active</option>
                                                    <option value="Deactive">Deactive</option>
                                                </select>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
    </div>
</div>
<!-- Employment Detail End -->


<div class="row col-12 gap-5 mt-5 px-1">
    <div class="col-lg-5">
        <h5 class="rounded text-white p-1 leaveToggleVisibility" style="background-color: #0c7ce6;">Leaves</h5>
        <table class="table table-hover table-responsive leaveContentHideShow">
            <thead>
            <tr>
                <th>Leave</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Annual</td>
                <td>12</td>
                <td>
                    <div class="text-primary" style="cursor: pointer;" title="Edit Leave">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                        </svg>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="3" class="">
                    <div class="d-flex justify-content-center">
                        <div class="w-25 px-5">
                            <div class="p-1 rounded pe-auto text-center" style="background-color: #0C7CE6; color:white; cursor: pointer;" title="Add New Leave" data-toggle="modal" data-target="#experiencenAddModel">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                                    <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="col-lg-5">
        <h5 class="rounded text-white p-1 salaryToggleVisibility" style="background-color: #0c7ce6;">Salary</h5>

        <div class="salaryContentHideShow">
            <div class="d-flex justify-content-between align-items-center">
                <div class="">
                    <p class="text-muted">
                        Basic Salary: <span class="text-primary">3000</span>
                    </p>
                </div>
                <div>
                    <div class="d-flex justify-content-end align-items-center gap-2">
                        <label for="salary_currency" class="form-label mb-0">Currency</label>
                        <input type="text" class="form-control w-25" id="salary_currency" name="salary_currency"/>
                    </div>
                </div>

            </div>

            <table class="table table-hover table-responsive">
                <thead>
                <tr>
                    <th>Allowance</th>
                    <th>Amount</th>
                    {{--                <th>Remainings</th>--}}
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>conveyance</td>
                    <td>20000</td>
                    {{--                <td>5</td>--}}
                    <td>
                        <div class="text-primary" style="cursor: pointer;" title="Edit Leave">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                            </svg>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>conveyance</td>
                    <td>20000</td>
                    {{--                <td>5</td>--}}
                    <td>
                        <div class="text-primary" style="cursor: pointer;" title="Edit Leave">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                            </svg>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>conveyance</td>
                    <td>20000</td>
                    {{--                <td>5</td>--}}
                    <td>
                        <div class="text-primary" style="cursor: pointer;" title="Edit Leave">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                            </svg>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="">
                        <div class="d-flex justify-content-center">
                            <div class="w-25 px-5">
                                <div class="p-1 rounded pe-auto text-center" style="background-color: #0C7CE6; color:white; cursor: pointer;" title="Add New Allowance" data-toggle="modal" data-target="#experiencenAddModel">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                    </td>
                </tr>
                </tbody>
            </table>
            <table class="table table-hover table-responsive">
                <thead>
                <tr>
                    <th>Deduction</th>
                    <th>Amount</th>
                    {{--                <th>Remainings</th>--}}
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>provident </td>
                    <td>2000</td>
                    {{--                <td>5</td>--}}
                    <td>
                        <div class="text-primary" style="cursor: pointer;" title="Edit Leave">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                            </svg>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>provident </td>
                    <td>2000</td>
                    {{--                <td>5</td>--}}
                    <td>
                        <div class="text-primary" style="cursor: pointer;" title="Edit Leave">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                            </svg>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>provident </td>
                    <td>2000</td>
                    {{--                <td>5</td>--}}
                    <td>
                        <div class="text-primary" style="cursor: pointer;" title="Edit Leave">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                            </svg>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="">
                        <div class="d-flex justify-content-center">
                            <div class="w-25 px-5">
                                <div class="p-1 rounded pe-auto text-center" style="background-color: #0C7CE6; color:white; cursor: pointer;" title="Add New Allowance" data-toggle="modal" data-target="#experiencenAddModel">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                    </td>
                </tr>
                </tbody>
            </table>

            <table class="table table-primary table-responsive">
                <thead>
                <tr>
                    <th>Total Salary</th>
                    <th class="text-end">300000</th>
                </tr>
                </thead>
            </table>
        </div>

    </div>
</div>







<div class="modal fade" id="addEmploymentDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Employment Detail</h5>
                <p style="cursor:pointer;" class="close text-primary" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                        </svg>
                    </span>
                </p>
            </div>
            <form action="{{ route('employment-detail.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                    <div class="row col-lg-12">
                        <div class="mb-2 col-lg-3">
                            <label for="emp_code" class="form-label mb-0">Emp Code</label>
                            <input type="text" class="form-control"  id="emp_code" name="emp_code"/>
                        </div>
                        <div class="mb-2 col-lg-6">
                            <label for="emp_type" class="form-label mb-0">Employment Type</label>
                            <select name="emp_type" id="emp_type" class="form-control">
                                <option value="">Select</option>
                                <option value="Permanent">Permanent</option>
                                <option value="Part_Time">Part Time</option>
                                <option value="Part_Time">Apprenticeship</option>
                                <option value="Interns">Interns</option>
                            </select>
                        </div>
                        <div class="mb-2 col-lg-3">
                            <label for="doj" class="form-label mb-0">DOJ</label>
                            <input type="date" class="form-control"  id="doj" name="doj"/>
                        </div>
                        <div class="mb-2 col-lg-6">
                            <label for="department_id" class="form-label mb-0">Department</label>
                            <select name="department_id" id="department_id" class="form-control">
                                <option value="">Select</option>
                                @foreach($departments as $depart)
                                    <option value="{{ $depart->id }}">{{ $depart->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2 col-lg-6">
                            <label for="designation_id" class="form-label mb-0">Designation</label>
                            <select name="designation_id" id="designation_id" class="form-control">
                                <option value="">Select</option>
                                @foreach($designations as $designation)
                                    <option value="{{ $designation->id }}">{{ $designation->designation }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2 col-lg-6">
                            <label for="shift_id" class="form-label mb-0">Shift</label>
                            <select name="shift_id" id="shift_id" class="form-control">
                                <option value="">Select</option>
                                @foreach($shifts as $shift)
                                    <option value="{{ $shift->id }}">{{ $shift->shift_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2 col-lg-6">
                            <label for="payroll_type" class="form-payroll_typelabel mb-0">Payroll Type</label>
                            <select name="payroll_type" id="payroll_type" class="form-control">
                                <option value="">Select</option>
                                <option value="Daily">Daily</option>
                                <option value="Monthly">Monthly</option>
                                <option value="Yearly">Yearly</option>
                            </select>
                        </div>
                        <div class="mb-2 col-lg-4">
                            <label for="work_location" class="form-label mb-0">Work Location</label>
                            <input type="text" class="form-control" id="work_location" name="work_location"/>
                        </div>

                        <div class="row col-lg-12 mt-3">
                            <div class="mb-2 col-lg-4">
                                <label for="company_email" class="form-label mb-0">Company Email</label>
                                <input type="email" class="form-control" id="company_email" name="company_email"/>
                            </div>
                            <div class="mb-2 col-lg-4">
                                <label for="username" class="form-label mb-0">Username</label>
                                <input type="text" class="form-control" id="username" name="username"/>
                            </div>
                            <div class="mb-2 col-lg-4">
                                <label for="password" class="form-label mb-0">Password</label>
                                <input type="password" class="form-control" id="password" name="password"/>
                            </div>
                            <div class="mb-2 col-lg-6">
                                <label for="role" class="form-label mb-0">Role</label>
                                <select name="role" id="role" class="form-control">
                                    <option value="">Select</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-2 col-lg-6">
                                <label for="status" class="form-label mb-0">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Select</option>
                                    <option value="Active">Active</option>
                                    <option value="Deactive">Deactive</option>
                                </select>
                            </div>

                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>










