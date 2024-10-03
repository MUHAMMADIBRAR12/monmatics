@extends('layout.master')
@section('title', 'Create Department')
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

                    <form action="{{ route('hcm.department.store') }}" method="POST" class="">
                            @csrf
                            <div class="col-12 row">
                                <div class="col-2 mb-3">
                                    <label class="form-check-label" for="dept_code">Department Code</label>
                                    <input
                                        class="form-control"
                                        name="dept_code"
                                        id="dept_code"
                                        value="{{ old('dept_code') }}"
                                        type="text" placeholder="Enter Dept Code" />
                                    @error('dept_code')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-3 mb-3">
                                    <label class="form-check-label" for="name">Department Name</label>
                                    <input
                                        class="form-control"
                                        name="name"
                                        id="name"
                                        value="{{ old('name') }}"
                                        type="text" placeholder="Enter Department Name" />
                                    @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-3 mb-3">
                                    <label class="form-check-label" for="email">Email</label>
                                    <input
                                        class="form-control"
                                        name="email"
                                        id="email"
                                        value="{{ old('email') }}"
                                        type="text" placeholder="Enter Department Email" />
                                    @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-4 mb-3">
                                    <label class="form-check-label" for="phone">Phone</label>
                                    <input
                                        class="form-control"
                                        name="phone"
                                        id="phone"
                                        value="{{ old('phone') }}"
                                        type="text" placeholder="Enter Department Phone No" />
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-check-label" for="parent_dept">Parent Department</label>
                                    <select class="form-control" name="parent_dept_id" id="parent_dept">
                                        <option value="">Select....</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-6 mb-3">
                                    <label class="form-check-label" for="location">Location</label>
                                    <input
                                        class="form-control"
                                        name="location"
                                        id="location"
                                        value="{{ old('location') }}"
                                        type="text" placeholder="Enter Department Location" />
                                    @error('location')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button class="btn btn-dark">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save New Department</button>
                            </div>

                        </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('page-script')

@endsection

