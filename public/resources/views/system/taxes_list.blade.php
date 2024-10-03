@extends('layout.master')
@section('title', 'Taxes Listing')
@section('parentPageTitle', 'System')
@section('page-style')
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
                    <button class="btn btn-primary" style="align:right" onclick="window.location.href = '{{ url('Admin/Taxes/Create') }}';" >New Tax</button>
                    <table class="table table-bordered table-striped table-hover" id="taxes">
                        <thead>
                            <tr>
                                <th class="px-1 py-0 text-center">Action</th>
                                <th>Title</th>
                                <th>Rate</th>
                                <th>Withheld</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                            <tbody>
                                @foreach($taxList as $tax)
                                <tr >
                                    <td class="action">
                                        <button class="btn btn-success btn-sm p-0 m-0" onclick="window.location.href = '{{ url('Admin/Taxes/Create/'.$tax->id) }}';"><i class="zmdi zmdi-edit px-2 py-1"></i></button>                                        
                                    </td>
                                    <td class="column_size">{{ $tax->name }}</td>
                                    <td class="column_size" style="text-align: right;">{{ $tax->rate }}</td>
                                    <td class="column_size">{{  ($tax->withheld==1)?'Yes':'No' }}</td>
                                    <td class="column_size">{{ ($tax->status==1)?'Active':'Disable' }}</td>
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
    $('#taxes').DataTable( {
    dom: 'Bfrtip',
    buttons: [
        {
            extend: 'pageLength',
            className: 'btn cl mr-2 px-3 rounded'
        },
        {
            extend: 'copy',
            className: 'btn bg-dark mr-2 px-3 rounded',
            title: 'Taxes Listing',
            exportOptions: {
                columns: [1,2,3,4] // Exclude columns with the class 'actions'
            }
        },
        {
            extend: 'csv',
            className: 'btn btn-info mr-2 px-3 rounded',
            title: 'Taxes Listing',
            exportOptions: {
                columns: [1,2,3,4] // Exclude columns with the class 'actions'
            }
        },
        {
            extend: 'pdf',
            className: 'btn btn-danger mr-2 px-3 rounded',
            title: 'Taxes Listing',
            exportOptions: {
                columns: [1,2,3,4] // Exclude columns with the class 'actions'
            }
        },
        {
            extend: 'excel',
            className: 'btn btn-warning mr-2 px-3 rounded',
            title: 'Taxes Listing',
            exportOptions: {
                columns: [1,2,3,4] // Exclude columns with the class 'actions'
            }
        },
        {
            extend: 'print',
            className: 'btn btn-success mr-2 px-3 rounded',
            title: 'Taxes Listing',
            exportOptions: {
                columns: [1] // Exclude columns with the class 'actions'
            }
        },
        { extend: 'colvis', className: 'visible btn rounded' }
    ],
    "bDestroy": true,
    "lengthMenu": [[100, 200, 500, -1], [100, 200, 500, "All"]],
        
}); 
</script>
@stop