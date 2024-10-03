@extends('layout.master')
@section('title', 'Units List')
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
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="body">
                <div class="table-responsive">
                    <button class="btn btn-primary" style="align:right" onclick="window.location.href = '{{ url('Inventory/Unit/Create') }}';" >Add Unit</button>
                    <table class="table table-bordered table-striped table-hove" id="taxes" style="width:100%">
                        <thead>
                            <tr>
                                <th class="px-1 py-0 text-center">Action</th>
                                <th style="text-align:center">Code</th>
                                <th style="text-align:center">Name</th>
                                <th style="text-align:center">Base Unit</th>
                                <th style="text-align:center">Operator</th>
                                <th style="text-align:center">Operation value</th>
                            </tr>
                        </thead>
                        <tbody>
                          @foreach($units as $unit)
                            <tr>
                              <td class="action">
                                <button class="btn btn-success btn-sm p-0 m-0" onclick="window.location.href = '{{ url('Inventory/Unit/Create/'.$unit->id) }}';"><i class="zmdi zmdi-edit px-2 py-1"></i></button></td>
                              <td class="column_size">{{$unit->code}}</td>
                              <td class="column_size">{{$unit->name}}</td>
                              <td class="column_size">{{$unit->base_unit}}</td>
                              <td class="column_size">{{$unit->operator}}</td>
                              <td class="column_size">{{$unit->operator_value}}</td>
                            </tr>
                          @endforeach                
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
@stop
@section('page-script')
@include('datatable-list');
<script>
$('#taxes').DataTable( {
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
      { className: "dt-right", "targets": [3,4,5] },
    ],
    "bDestroy": true,
    "lengthMenu": [[100, 200, 500, -1], [100, 200, 500, "All"]],
        
}); 
</script>
@stop