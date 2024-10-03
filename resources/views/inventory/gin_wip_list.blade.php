@extends('layout.master')
@section('title', 'MIR-Approvel List')
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
              <button class="btn btn-primary" style="align:right" onclick="window.location.href = '{{ url('Inventory/GIN_WIP/Create') }}';" >New GIN-WIP</button>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="purchase_order" style="width:100%">
                        <thead>
                            <tr>
                                <th class="px-1 py-0">Edit</th>
                                <th class="px-1 py-0">Date</th>
                                <th class="px-1 py-0">GIN-WIP No.</th>
                                <th class="px-1 py-0">MIR No.</th>
                                <th class="px-1 py-0 text-center">Warehouse</th>
                                <th class="px-1 py-0 text-center">Note</th>
                                <th class="px-1 py-0 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gin_wip as $gin)
                            <tr>
                                <td class="p-0 text-center">
                                    <button class="btn btn-success btn-sm p-0 m-0" onclick="window.location.href = '{{ url('Inventory/GIN_WIP/Create/'.$gin->id)}}';" ><i class="zmdi zmdi-edit px-2 py-1"></i></button>
                                </td>
                                <td class="py-0 px-1 text-nowrap">{{ date(appLib::showDateFormat(), strtotime($gin->date))}}</td>
                                <td class="py-0 px-1 text-nowrap">{{$gin->month ?? ''}}-{{appLib::padingZero($gin->number  ?? '')}}</td>
                                <td class="py-0 px-1 text-nowrap">{{$gin->mir_num}}</td>
                                <td class="py-0 px-1 text-nowrap">{{$gin->warehouse}}</td>
                                <td class="py-0 px-1 text-nowrap">{{$gin->note}}</td>
                                <td class="py-0 px-1 text-nowrap">{{$gin->status}}</td>
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
$('#purchase_order').DataTable( {
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
        "columnDefs": [
      { className: "dt-center column_size", "targets": [0,1,2,3]},
    ],
    "bDestroy": true,
    "lengthMenu": [[100, 200, 500, -1], [100, 200, 500, "All"]],

});
</script>
@stop
