@extends('layout.master')
@section('title', 'Sales Return')
@section('parentPageTitle', 'Inventory')
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
              <button class="btn btn-primary" style="align:right" onclick="window.location.href = '{{ url('Inventory/SalesReturn/Create') }}';" >Sale Return</button>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover " id="igp" style="width:100%">
                        <thead>
                            <tr>
                                <th class="px-1 py-0">Actions</th>
                                <th>Document No.</th>
                                <th>Date</th>
                                <th style="text-align:center">Customer</th>
                                <th style="text-align:center">warehouse</th>
                                <th style="text-align:center">description</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($salesReturnList as $salesReturn)
                            <tr>
                                <td class="action">
                                <button class="btn btn-success btn-sm p-0 m-0" onclick="window.location.href = '{{ url('Inventory/SalesReturn/Create/'.$salesReturn->id)}}';" ><i class="zmdi zmdi-edit px-2 py-1"></i></button>
                                </td>
                                <td class="column_size">{{$salesReturn->month ?? ''}}-{{appLib::padingZero($salesReturn->number  ?? '')}}</td>
                                <td class="column_size">{{  date(appLib::showDateFormat(), strtotime($salesReturn->date))}}</td>
                                <td class="column_size">{{$salesReturn->cust_name}}</td>
                                <td class="column_size">{{$salesReturn->warehouse_name}}</td>
                                <td class="column_size">{{$salesReturn->note}}</td>
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
    "scrollX": true,
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
      { className: "dt-center", "targets": [0,1,2] },
    ],

});
</script>
@stop
