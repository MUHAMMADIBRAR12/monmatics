@extends('layout.master')
@section('title', 'Rate Code List')
@section('parentPageTitle', 'Sale')
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
              <button class="btn btn-primary" style="align:right" onclick="window.location.href = '{{ url('Sales_Fmcg/RateCode/Create') }}';" >Rate Code</button>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover " id="igp">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Rate Code Number</th>
                                <th>Customer Category</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $rate_code as  $rate)
                            <tr>
                            <td class="action"><button class="btn btn-success btn-sm p-0 m-0" onclick="window.location.href = '{{ url('Sales_Fmcg/RateCode/Create/'.$rate->id)}}';" ><i class="zmdi zmdi-edit px-2 py-1"></i></button></td>
                                <td class="column_size">{{$rate->rate_code_num}}</td>
                                <td class="column_size">{{$rate->category}}</td>
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