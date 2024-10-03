@extends('layout.master')
@section('title', 'Employees')
@section('parentPageTitle', 'Hcm')

@section('page-style')
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/datatables.min.css"/>
    <link rel="stylesheet" href="{{asset('public/assets/css/sw.css')}}">
    <link rel="stylesheet" href="{{asset('public/assets/css/list.css')}}">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
@endsection

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session('error') }}</strong>
            <button type="button" class="btn-close text-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="d-flex justify-content-end mb-3">
                    <a href="{{ route('employee.create') }}" class="btn btn-primary">New Employee</a>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="employee">
                            <thead>
                            <tr>
                                <th class="">Action</th>
                                <th>Name</th>
                                <th>National ID</th>
                                <th>Email</th>
                                <th>Phone</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($employees as $employee)

                                <tr>
                                    <th class="">Action</th>
                                    <th>
                                        <a href="{{ route('employee.detail' , $employee->id) }}">
                                            {{ $employee->f_name }}
                                        </a>
                                    </th>
                                    <th>{{ $employee->national_id }}</th>
                                    <th>{{ $employee->email }}</th>
                                    <th>{{ $employee->phone }}</th>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('page-script')
    @include('datatable-list');
    <script>
        $('#employee').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                { extend: 'pageLength', className:'btn cl mr-2 px-3 rounded'},
                { extend: 'copy', className: 'btn bg-dark mr-2 px-3 rounded', title:'employees'},
                { extend: 'csv', className: 'btn btn-info mr-2 px-3 rounded', title:'employees'},
                { extend: 'pdf', className: 'btn btn-danger mr-2 px-3 rounded', title:'employees'},
                { extend: 'excel', className: 'btn btn-warning mr-2 px-3 rounded',title:'employees'},
                { extend: 'print', className: 'btn btn-success mr-2 px-3 rounded',title:'employees'},
                { extend: 'colvis', className:'visible btn rounded'},
            ],
            "bDestroy": true,
            "lengthMenu": [[100, 200, 500, -1], [100, 200, 500, "All"]],

        });
    </script>
@endsection

