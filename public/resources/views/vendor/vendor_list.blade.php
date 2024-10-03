@extends('layout.master')
@section('title', 'Vendor List')
@section('parentPageTitle', 'Vendor')
@section('page-style')
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/datatables.min.css"/>
<link rel="stylesheet" href="{{asset('public/assets/css/sw.css')}}">
<link rel="stylesheet" href="{{asset('public/assets/css/list.css')}}">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
@stop
@section('content')
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="body">
                <div class="table-responsive">
                    <button class="btn btn-primary" style="align:right" onclick="window.location.href = '{{ url('Vendor/VendorManagement/Create') }}';" >New Vendor</button>
                    <table class="table table-bordered table-striped table-hover" id="vendors">
                        <thead>
                            <tr>
                                <th class="px-1 py-0">Action</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Note</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>status</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($vendorList as $vendor) 
                        <tr>
                        <td class="action">
                            <button class="btn btn-primary btn-sm p-0 m-0" onclick="window.location.href = '{{ url('Vendor/VendorManagement/Views/'.$vendor->id) }}';"><i class="zmdi zmdi-view-day px-2 py-1"></i></button>                        
                        </td>
                        <td class="column_size">{{$vendor->name}}</td>
                        <td class="column_size">{{$vendor->category}}</td>
                        <td class="column_size">{{$vendor->note}}</td>
                        <td class="column_size text-right">{{$vendor->phone}}</td>
                        <td class="column_size">{{$vendor->email}}</td>
                        <td class="column_size">
                          <span  class="{{($vendor->status==1)?'text-success':'text-danger'}}">{{($vendor->status==1)?'Active':'InActive'}} </span> 
                        </td>
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
$('#vendors').DataTable( {
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
        
}); 
</script>
@stop