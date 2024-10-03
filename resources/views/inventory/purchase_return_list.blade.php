@extends('layout.master')
@section('title', 'Purchase Return List')
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
              <button class="btn btn-primary" style="align:right" onclick="window.location.href = '{{ url('Inventory/PurchaseReturn/Create') }}';" >Add Purchase Return</button>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover " id="igp">
                        <thead>
                            <tr>
                                <th>Edit</th>
                                <th>Date</th>
                                <th>Purchase Return NO#</th>
                                <th style="text-align:center">warehouse</th>
                                <th style="text-align:center">description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $purchaseReturns as  $purchaseReturn)
                                <tr>
                                    <td class="action"> <button class="btn btn-primary btn-sm p-0 m-0" onclick="window.location.href = '{{ url('Inventory/PurchaseReturn/Create/'.$purchaseReturn->id) }}';"><i class="zmdi zmdi-edit px-2 py-1"></i></button></td>
                                    <td class="column_size">{{  date(appLib::showDateFormat(), strtotime($purchaseReturn->date))}}</td>
                                    <td class="column_size">{{$purchaseReturn->month  }}-{{appLib::padingZero($purchaseReturn->number  ?? '')}}</td>
                                    <td class="column_size">{{$purchaseReturn->name}}</td>
                                    <td class="column_size">{{$purchaseReturn->note}}</td>
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
      { className: "dt-center", "targets": [0,1,2] },
    ],

});
</script>
@stop
