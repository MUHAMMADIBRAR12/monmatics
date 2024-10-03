@extends('layout.master')
@section('title', 'GRN-WIP List')
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
            <button class="btn btn-primary" style="align:right" onclick="window.location.href = '{{ url('Inventory/GRN_WIP/Create') }}';" >New GRN-WIP</button>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover js-exportable" id="grn" style="width:100%">
                        <thead>
                            <tr>
                                <th class="px-1 py-0">Edit</th>
                                <th class="px-1 py-0">Date</th>
                                <th class="px-1 py-0">GRN NO</th>
                                <th class="px-1 py-0">Batch NO</th>
                                <th class="px-1 py-0 text-center">WareHouse</th>
                                <th class="px-1 py-0 text-center">Product</th>
                                <th class="px-1 py-0 text-center">Qty Received</th>
                                <th class="px-1 py-0 text-center">Unit</th>
                                <th class="px-1 py-0 text-center">Note</th>


                            </tr>
                        </thead>
                        <tbody>
                            @foreach($grn_wip_list as $grn_wip)
                            <tr>
                                <td class="action text-nowrap">
                                    @if($grn_wip->editable==1)
                                    <button class="btn btn-success btn-sm p-0 m-0" onclick="window.location.href = '{{ url('Inventory/GRN_WIP/Create/'.$grn_wip->id) }}';"><i class="zmdi zmdi-edit px-2 py-1"></i></button>
                                    @endif
                                    <button class="btn btn-primary btn-sm p-0 m-0" onclick="window.location.href = '{{ url('Inventory/GRN_WIP/Views/'.$grn_wip->id) }}';" ><i class="zmdi zmdi-eye px-2 py-1"></i></button>
                                    <button class="btn btn-danger btn-sm p-0 m-0"  onclick="window.open('{{ url('Inventory/GRN_WIP/Print/'.$grn_wip->id) }}');"><i class="zmdi zmdi-receipt px-2 py-1"></i></button>
                                </td>
                                <td class="py-0 px-1 text-nowrap">{{date(appLib::showDateFormat(),strtotime($grn_wip->date))}}</td>
                                <td class="py-0 px-1 text-nowrap">{{($grn_wip->month ?? '').'-'.appLib::padingZero($grn_wip->number  ?? '')}}</td>
                                <td class="py-0 px-1 text-nowrap">{{$grn_wip->batch}}</td>
                                <td class="py-0 px-1 text-nowrap">{{$grn_wip->warehouse_name}}</td>
                                <td class="py-0 px-1 text-nowrap">{{$grn_wip->product}}</td>
                                <td class="py-0 px-1 text-nowrap">{{$grn_wip->qty}}</td>
                                <td class="py-0 px-1 text-nowrap">{{$grn_wip->unit}}</td>
                                <td class="py-0 px-1 text-nowrap">{{$grn_wip->note}}</td>
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
    scrollY: '50vh',
    scrollX: true,
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
      { className: "dt-right", "targets": [6] },
    ],

});
</script>
@stop
