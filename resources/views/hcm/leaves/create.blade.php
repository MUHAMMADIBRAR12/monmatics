@extends('layout.master')
@section('title', 'Create Leave')
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
                    <form action="{{ route('leave.store') }}" method="POST">
                        @csrf
                        <div class="row col-12">
                            <div class="col-lg-6 mb-3">
                                <label class="form-check-label" for="leave_group">Leave Name</label>
                                <input
                                    class="form-control"
                                    name="leave_group"
                                    id="leave_group"
                                    value=""
                                    type="text" placeholder="Leave Name" required/>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label class="form-check-label" for="leave_for">Leave For</label>
                                <select name="leave_for" id="leave_for" class="form-control">
                                    <option>Select</option>
                                    @foreach($designations as $designation)
                                        <option value="{{ $designation->id }}">{{ $designation->designation }}</option>

                                    @endforeach
                                </select>
                            </div>
                            <div class="d-flex justify-content-start">
                                <div id="addNewLeaveType" class="p-1 px-2 rounded pe-auto bg-primary text-white" style="cursor: pointer;" title="Add New Leave Type" >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                                    </svg>
                                </div>
                            </div>
                            <div id="leaveType">

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
        $(document).ready(function() {
            // Add new leave type fields
            $('#addNewLeaveType').click(function() {
                let leaveTypeHTML = `
                <div class="row col-lg-12 leave-type">
                    <div class="col-4 mb-3">
                        <label class="form-check-label" for="leave_type[]">Leave Type</label>
                        <input class="form-control" name="leave_type[]" type="text" placeholder="Leave Type" required>
                    </div>
                    <div class="col-4 mb-3">
                        <label class="form-check-label" for="leave_count[]">Leaves Count</label>
                        <input class="form-control" name="leave_count[]" type="text" placeholder="Leave Count" required>
                    </div>
                    <div class="col-4 mb-3">
                        <label class="form-check-label" for="leave_category[]">Leave Category</label>
                        <select class="form-control" name="leave_category[]" required>
                            <option value="Accural">Accural</option>
                            <option value="Earned">Earned</option>
                            <option value="Carry_Forward">Carry Forward</option>
                        </select>
                    </div>
                </div>
            `;
                $('#leaveType').append(leaveTypeHTML);
            });

        });
    </script>


@endsection

