@extends('layout.master')
@section('title', 'Employee Form')
@section('parentPageTitle','HCM')
@section('page-style')
<?php  use App\Libraries\appLib; ?>
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/datatables.min.css"/>
<link rel="stylesheet" href="{{asset('public/assets/css/sw.css')}}">
<link rel="stylesheet" href="{{asset('public/assets/css/list.css')}}">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
@stop
@section('content')
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
              <button class="btn btn-primary" style="align:right" onclick="window.location.href = '{{ url('HCM/EmployeeForm/Create') }}';" >New Employee Form</button>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover " id="igp">
                        <thead>
                            <tr>
                                <th class="px-1 py-0">Actions</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Gender</th>
                                <th>Date of Birth</th>
                                <th>Phone</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employee_list as $employee)
                            <tr>
                                <td class="action">
                                    <button class="btn btn-success btn-sm p-0 m-0" onclick="window.location.href = '{{ url('HCM/EmployeeForm/Create/'.$employee->id)}}';" ><i class="zmdi zmdi-edit px-2 py-1"></i></button>
                                   <a href="{{ url('HCM/EmployeeData/View/'.$employee->id)}}" class="btn btn-danger btn-sm p-0 m-0">   <i class="zmdi zmdi-receipt px-2 py-1"></i></a>

                                </td>
                                <td class="column_size">{{$employee->first_name ?? ''}}</td>
                                <td class="column_size">{{$employee->last_name ?? ''}}</td>
                                <td class="column_size">{{$employee->gender ?? ''}}</td>
                                <td class="column_size">{{ date(appLib::showDateFormat(), strtotime($employee->dob ?? ''))}}</td>
                                <td class="column_size">{{$employee->phone?? ''}}</td>
                                <td class="column_size">{{$employee->email ?? ''}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('page-script')
@include('datatable-list');
<script>
$('#igp').DataTable( {
    scrollY: '50vh',
    scrollCollapse: true,
    dom: 'Bfrtip',
    buttons: [
        { extend: 'pageLength', className:'btn cl mr-2 px-3 rounded'},
        { extend: 'copy', className: 'btn bg-dark mr-2 px-3 rounded', title:'Products'},
        { extend: 'csv', className: 'btn btn-info mr-2 px-3 rounded', title:'Products'},
        { extend: 'pdf', className: 'btn btn-danger mr-2 px-3 rounded', title:'Products'},
        { extend: 'excel', className: 'btn btn-warning mr-2 px-3 rounded',title:'Products'},
        { extend: 'print', className: 'btn btn-success mr-2 px-3 rounded',title:'Products'},
        { extend: 'colvis', className:'visible btn rounded'},

    ],
    "bDestroy": true,
    "lengthMenu": [[100, 200, 500, -1], [100, 200, 500, "All"]],
    "columnDefs": [
      { className: "dt-right", "targets": [1,2,3] },
    ],

});
</script>
@stop
