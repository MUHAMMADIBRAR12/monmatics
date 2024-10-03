@extends('layout.master')
@section('title', 'Inward Gate Pass List')
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
              <button class="btn btn-primary" style="align:right" onclick="window.location.href = '{{ url('Inventory/IGP/Create') }}';" >New Inward Gate Pass</button>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover " id="igp" style="width:100%">
                        <thead>
                            <tr>
                                <th class="px-1 py-0">Actions</th>
                                <th>Date</th>
                                <th>I.G.P No.</th>
                                <th>P.O No.</th>
                                <th style="text-align:center">warehouse</th>
                                <th style="text-align:center">Vendor</th>
                                <th style="text-align:center">Delivery Man</th>
                                <th style="text-align:center">description</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($igps as $igp)
                            <tr>
                                <td class="action">
                                    @if($igp->editable==1)
                                    <button class="btn btn-success btn-sm p-0 m-0" onclick="window.location.href = '{{ url('Inventory/IGP/Create/'.$igp->id)}}';" ><i class="zmdi zmdi-edit px-2 py-1"></i></button>
                                    @endif
                                    <button class="btn btn-primary btn-sm p-0 m-0" onclick="window.location.href = '{{ url('Inventory/IGP/Views/'.$igp->id) }}';" ><i class="zmdi zmdi-view-day px-2 py-1"></i></button>
                                </td>
                                <td class="column_size">{{date(appLib::showDateFormat(),strtotime($igp->date))}}</td>
                                <td class="column_size">{{$igp->month ?? ''}}-{{appLib::padingZero($igp->number  ?? '')}}</td>
                                <td class="column_size">{{$igp->po_num}}</td>
                                <td class="column_size">{{$igp->warehouse_name}}</td>
                                <td class="column_size">{{$igp->vendor}}</td>
                                <td class="column_size">{{$igp->delivery_man}}</td>
                                <td class="column_size">{{$igp->note}}</td>
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
      { className: "dt-center", "targets": [0,1,2,3] },
    ],

});
</script>
@stop
