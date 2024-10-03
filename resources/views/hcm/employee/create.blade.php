@extends('layout.master')
@section('title', 'Create Employee')
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
                    <form action="{{ route('employee.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="row col-lg-12">
                                <div class="mb-2 col-lg-3">
                                    <label for="fname" class="form-label mb-0">First Name</label>
                                    <input type="text" class="form-control" id="fname" name="fname"/>
                                    @error('fname')
                                    <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-2 col-lg-3">
                                    <label for="lname" class="form-label mb-0">Last Name</label>
                                    <input type="text" class="form-control" id="lname" name="lname"/>
                                </div>
                                <div class="mb-2 col-lg-3">
                                    <label for="nationalID" class="form-label mb-0">National ID:</label>
                                    <input type="text" class="form-control" id="nationalID" name="nationalID"/>
                                    @error('nationalID')
                                    <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-2 col-lg-3">
                                    <label for="phone" class="form-label mb-0">Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone"/>
                                    @error('phone')
                                    <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-2 col-lg-3">
                                    <label for="email" class="form-label mb-0">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"/>
                                </div>
                                <div class="mb-2 col-lg-3">
                                    <label for="dob" class="form-label mb-0">DOB</label>
                                    <input type="date" class="form-control" id="dob" name="dob"/>
                                </div>
                                <div class="mb-2 col-lg-3">
                                    <label for="gender" class="form-label mb-0">Gender:</label>
                                    <select class="form-control" name="gender" id="gender">
                                        <option value="">Select</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="perfer_not_to_say">Prefer Not To Say</option>
                                    </select>
                                </div>
                                <div class="mb-2 col-lg-3">
                                    <label for="marital_status" class="form-label mb-0">Marital Status:</label>
                                    <select class="form-select" id="marital_status" name="marital_status">
                                        <option value="">   Select</option>
                                        <option value="single">Single</option>
                                        <option value="married">Married</option>
                                        <option value="divorced">Divorced</option>
                                        <option value="widowed">Widowed</option>
                                    </select>
                                </div>
                                <h6 class="text-primary font-bold mb-0 mt-3">Current Address</h6>
                                <div class="mb-2 col-lg-6">
                                    <label for="current_address" class="form-label mb-0">Adress:</label>
                                    <input type="text" class="form-control" id="current_address" name="current_address"/>
                                </div>
                                <div class="mb-2 col-lg-2">
                                    <label for="current_city" class="form-label mb-0">City:</label>
                                    <input type="text" class="form-control" id="current_city" name="current_city"/>
                                </div>
                                <div class="mb-2 col-lg-2">
                                    <label for="current_state" class="form-label mb-0">State:</label>
                                    <input type="text" class="form-control" id="current_state" name="current_state"/>
                                </div>
                                <div class="mb-2 col-lg-2">
                                    <label for="current_country" class="form-label mb-0">Country:</label>
                                    <input type="text" class="form-control" id="current_country" name="current_country"/>
                                </div>

                                <h6 class="text-primary font-bold mb-0 mt-3">Permanent Address</h6>
                                <div class="mb-2 col-lg-6">
                                    <label for="permanent_address" class="form-label mb-0">Adress:</label>
                                    <input type="text" class="form-control" id="permanent_address" name="permanent_address"/>
                                </div>
                                <div class="mb-2 col-lg-2">
                                    <label for="permanent_city" class="form-label mb-0">City:</label>
                                    <input type="text" class="form-control" id="permanent_city" name="permanent_city"/>
                                </div>
                                <div class="mb-2 col-lg-2">
                                    <label for="permanent_state" class="form-label mb-0">State:</label>
                                    <input type="text" class="form-control" id="permanent_state" name="permanent_state"/>
                                </div>
                                <div class="mb-2 col-lg-2">
                                    <label for="permanent_country" class="form-label mb-0">Country:</label>
                                    <input type="text" class="form-control" id="permanent_country" name="permanent_country"/>
                                </div>
                            </div>

                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('page-script')



@endsection

