@extends('layout.master')
@section('title', 'Material Issue Request List')
@section('parentPageTitle','Production')
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
              <button class="btn btn-primary" style="align:right" onclick="window.location.href = '{{ url('Production/MIR/Create') }}';" >New M.I.R</button>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover " id="mir" style="width:100%">
                        <thead>
                            <tr>
                                <th class="px-1 py-0">Actions</th>
                                <th>Date</th>
                                <th>M.I.R No.</th>
                                <th style="text-align:center">Person</th>
                                <th style="text-align:center">Department</th>
                                <th style="text-align:center">warehouse</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mirs as $mir)
                            <tr>
                                <td class="action">
                                    @if($mir->editable==1)
                                    <button class="btn btn-success btn-sm p-0 m-0" onclick="window.location.href = '{{ url('Production/MIR/Create/'.$mir->id) }}';"><i class="zmdi zmdi-edit px-2 py-1"></i></button>
                                    @endif
                                    <button class="btn btn-primary btn-sm p-0 m-0" onclick="window.location.href = '{{ url('Production/MIR/Views/'.$mir->id) }}';" ><i class="zmdi zmdi-view-day px-2 py-1"></i></button>
                                </td>
                                <td class="column_size">{{date(appLib::showDateFormat(), strtotime($mir->date))}}</td>
                                <td class="column_size">{{$mir->month ?? ''}}-{{appLib::padingZero($mir->number  ?? '')}}</td>
                                <td class="column_size">{{$mir->person}}</td>
                                <td class="column_size">{{$mir->department}}</td>
                                <td class="column_size">{{$mir->warehouse}}</td>
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
var title ="{{$title}}";
var img="{{url(asset('public/assets/attachments/1611822923.images1.png'))}}";
$('#mir').DataTable( {
    scrollY: '50vh',
    "scrollX": true,
    scrollCollapse: true,
    dom: 'Bfrtip',
    buttons: [
        @php
           $listHeader =  appLib::listHeader();
           echo $listHeader ;
        @endphp
        ],
    "bDestroy": true,
    "lengthMenu": [[100, 200, 500, -1], [100, 200, 500, "All"]],
    "columnDefs": [
      { className: "dt-center", "targets": [0,1,2] },
    ],

});
</script>
@stop
