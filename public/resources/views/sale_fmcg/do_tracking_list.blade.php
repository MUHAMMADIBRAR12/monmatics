@extends('layout.master')
@section('title', 'D.O Tracking List')
@section('parentPageTitle', 'Sales')
@section('page-style')
<?php  use App\Libraries\appLib; ?>
<link rel="stylesheet" href="{{asset('public/assets/plugins/jquery-datatable/dataTables.bootstrap4.min.css')}}"/>
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/datatables.min.css"/>
<link rel="stylesheet" href="{{asset('public/assets/css/sw.css')}}">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
@stop
@section('content')
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
            <button class="btn btn-primary" style="align:right" onclick="window.location.href = '{{ url('Sales_Fmcg/DoTracking/Create') }}';" >New D.O Tracking</button>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover js-exportable" id="grn">
                        <thead>
                            <tr>
                                <th>Edit</th>
                                <th>D.O Tracking No.</th>
                                <th>D.O No.</th>
                                <th>S.O No.</th>
                                <th>Customer Name</th>
                                <th>Date</th>
                                <th>Veh No</th>
                                <th>Delivery Name</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($do_tracking as $tracking)
                            <tr>
                                <td class="p-0 text-center">
                                @if($tracking->do_tracking_edit==1)
                                <button class="btn btn-success btn-sm p-0 m-0" onclick="window.location.href = '{{ url('Sales_Fmcg/DoTracking/Create/'.$tracking->id) }}';" > <i class="zmdi zmdi-edit px-2 py-1"></i></button>
                                @endif
                                <button class="btn btn-primary btn-sm p-0 m-0" onclick="window.location.href = '{{ url('Sales_Fmcg/DoTracking/View/'.$tracking->id) }}';" > <i class="zmdi zmdi-view-day px-2 py-1"></i></button>
                                </td>
                                <td class="p-0">{{$tracking->month ?? '' }}-{{appLib::padingZero($tracking->number  ?? '')}}</td>
                                <td class="p-0">{{$tracking->month ?? '' }}-{{appLib::padingZero($tracking->number  ?? '')}}</td>
                                <td class="p-0">{{$tracking->so_num}}</td>
                                <td class="p-0">{{$tracking->cust_name}}</td>
                                <td class="p-0">{{ date(appLib::showDateFormat(), strtotime($tracking->date))}}</td>
                                <td class="p-0">{{$tracking->veh_no}}</td>
                                <td class="p-0">{{$tracking->delivery_name}}</td>
                                <td class="p-0">{{$tracking->remarks}}</td>
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
<script src="{{asset('public/assets/bundles/datatablescripts.bundle.js')}}"></script>
<script src="{{asset('public/assets/plugins/jquery-datatable/buttons/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('public/assets/plugins/jquery-datatable/buttons/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('public/assets/plugins/jquery-datatable/buttons/buttons.html5.min.js')}}"></script>
<script src="{{asset('public/assets/plugins/jquery-datatable/buttons/buttons.print.min.js')}}"></script>
<script src="{{asset('public/assets/plugins/jquery-datatable/buttons/buttons.colVis.min.js')}}"></script>
<script src="{{asset('public/assets/plugins/jquery-datatable/buttons/buttons.pdfMake.min.js')}}"></script>
<script src="{{asset('public/assets/plugins/jquery-datatable/buttons/buttons.pdfMakeVfs.min.js')}}"></script>
<script src="{{asset('public/assets/plugins/jquery-datatable/buttons/buttons.excel.min.js')}}"></script>
<script>
$('#grn').DataTable( {
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
