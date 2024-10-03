@extends('layout.master')
@section('title', 'Create Salary')
@section('parentPageTitle', 'Hcm')

@section('page-style')
    <link rel="stylesheet" href="{{asset('public/assets/css/sw.css')}}">
    <link rel="stylesheet" href="{{asset('public/assets/css/list.css')}}">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
@endsection

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body body">
                    <form action="{{ route('salary.store') }}" method="POST">
                        @csrf
                        <div class="row col-12">
                            <div class="col-lg-3 mb-3">
                                <label class="form-check-label" for="salary_name">Salary Name</label>
                                <input
                                    class="form-control"
                                    name="salary_name"
                                    id="salary_name"
                                    value=""
                                    type="text" placeholder="Salary Name" />
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-check-label" for="salary_for">Salary For</label>
                                <select name="salary_for" id="salary_for" class="form-control">
                                    <option>Select</option>
                                    @foreach($designations as $designation)
                                        <option value="{{ $designation->id }}">{{ $designation->designation }}</option>

                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <label class="form-check-label" for="basic_salary">Basic Salary</label>
                                <input
                                    class="form-control"
                                    name="basic_salary"
                                    id="basic_salary"
                                    value=""
                                    type="text" placeholder="Basic Salary" />
                            </div>

                            <div class="col-lg-6 mb-3">
                                <div class="rounded text-white p-1 mb-1" style="background-color: #0c7ce6;">
                                    Allowances
                                </div>
                                <div class="d-flex justify-content-end px-1">
                                    <div id="addNewAllowanceAmount" class="p-1 px-2 rounded pe-auto bg-primary text-white" style="cursor: pointer;" title="Add Allowance" >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                                            <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                                        </svg>
                                    </div>
                                </div>
                                <div id="addAllowance">
                                    <div class="row col-lg-12 allowance-name">
                                        <div class="col-5 mb-3">
                                            <label class="form-check-label" for="allowance_name">Allowance Name</label>
                                            <input
                                                class="form-control"
                                                name="allowance_name[]"
                                                id="allowance_name"
                                                value=""
                                                type="text" placeholder="Allowance Name" />
                                        </div>
                                        <div class="col-3 mb-3">
                                            <label class="form-check-label" for="allowance_amount">Amount</label>
                                            <input
                                                class="form-control"
                                                name="allowance_amount[]"
                                                id="allowance_amount"
                                                value=""
                                                type="number" placeholder="Amount" />
                                        </div>
                                        <div class="col-3 mb-3">
                                            <label class="form-check-label" for="allowance_percentage">Percentage</label>
                                            <input
                                                class="form-control"
                                                name="allowance_percentage[]"
                                                id="allowance_percentage"
                                                value=""
                                                type="number" placeholder="Percentage" />
                                        </div>
                                        <div class="col-1 mt-4">
                                            <div id="deleteNewAllowanceAmount" class="p-1 px-2 rounded pe-auto bg-danger text-white" style="cursor: pointer;" title="Add Allowance" >
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <div class="col-lg-6 mb-3">
                                <div class="rounded text-white p-1 mb-1" style="background-color: #0c7ce6;">
                                    Deductions
                                </div>
                                <div class="d-flex justify-content-end">
                                    <div id="addNewDeduction" class="p-1 px-2 rounded pe-auto bg-primary text-white" style="cursor: pointer;" title="Add Allowance" >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                                            <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="deductionDetail" id="addDeduction">
                                    <div class="row col-lg-12 deduction-name">

                                        <div class="col-5 mb-3">
                                            <label class="form-check-label" for="deduction_name">Deduction Name</label>
                                            <input
                                                class="form-control"
                                                name="deduction_name[]"
                                                id="deduction_name"
                                                value=""
                                                type="text" placeholder="Deduction Name" />
                                        </div>
                                        <div class="col-3 mb-3">
                                            <label class="form-check-label" for="deduction_amount">Amount</label>
                                            <input
                                                class="form-control"
                                                name="deduction_amount[]"
                                                id="deduction_amount"
                                                value=""
                                                type="number" placeholder="Amount" />
                                        </div>
                                        <div class="col-3 mb-3">
                                            <label class="form-check-label" for="deduction_percentage">Percentage</label>
                                            <input
                                                class="form-control"
                                                name="deduction_percentage[]"
                                                id="deduction_percentage"
                                                value=""
                                                type="number" placeholder="Percentage" />
                                        </div>
                                        <div class="col-1 mt-4">
                                            <div id="deleteNewDeduction" class="p-1 px-2 rounded pe-auto bg-danger text-white" style="cursor: pointer;" title="Add Allowance" >
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>



                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('page-script')
    <script>
        function addNewAllowanceAmount() {
            let allowance = document.querySelector('#addAllowance');
            let newAllowance = allowance.querySelector('.allowance-name').cloneNode(true);
            allowance.appendChild(newAllowance);

            let deleteButton = newAllowance.querySelector('#deleteNewAllowanceAmount');
            deleteButton.addEventListener('click', function() {
                allowance.removeChild(newAllowance); // Remove the newly created allowance
            })
        }

        // Event listener for the button
        document.getElementById('addNewAllowanceAmount').addEventListener('click', addNewAllowanceAmount);

        function addNewDeduction() {
            let deduction = document.querySelector('#addDeduction');
            let newDeduction = deduction.querySelector('.deduction-name').cloneNode(true);
            deduction.appendChild(newDeduction);


            let deleteButton = newDeduction.querySelector('#deleteNewDeduction');
            deleteButton.addEventListener('click', function() {
                deduction.removeChild(newDeduction); // Remove the newly created deduction
            })
        }

        // Event listener for the button
        document.getElementById('addNewDeduction').addEventListener('click', addNewDeduction);

    </script>
@endsection

