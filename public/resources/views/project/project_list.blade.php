@extends('layout.master')
@section('title', 'Project Management')
@section('parentPageTitle', 'Project')
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
              <button class="btn btn-primary" style="align:right" onclick="window.location.href = '{{ url('Project/ProjectManagment/Create') }}'" >New Project</button>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover dataTable js-exportable" id="igp">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>View</th>
                                <th>Project</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>description</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projects as $project)
                            <tr>
                                <td class="p-0 text-center">
                                    <button class="btn btn-success btn-sm p-0 m-0" onclick="window.location.href = '{{ url('Project/ProjectManagment/Create/'.$project->id)}}';" ><i class="zmdi zmdi-edit px-2 py-1"></i></button>
                                    <!-- <button class="btn btn-primary btn-sm p-0 m-0" onclick="window.location.href = '{{ url('Project/ProjectManagment/View/') }}';" ><i class="zmdi zmdi-view-day px-2 py-1"></i></button> -->
                                </td>
                                <td class="p-0 text-center"><button class="btn btn-danger btn-sm p-0 m-0"><a  href="{{ url('Project/ProjectManagment/View/'.$project->id) }}" class="btn btn-danger print btn-sm p-0 m-0"><i class="zmdi zmdi-receipt px-2 py-1"></i></a></button></td>

                                <td class="p-0">{{$project->name}}</td>
                                <td class="p-0">{{date(appLib::showDateFormat(), strtotime($project->start_date))}}</td>
                                <td class="p-0">{{date(appLib::showDateFormat(), strtotime($project->end_date))}}</td>
                                <td class="p-0">{{$project->description}}</td>

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

});
</script>
@stop
