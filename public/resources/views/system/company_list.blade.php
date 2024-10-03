@extends('layout.master')
@section('title', 'Company List')
@section('parentPageTitle', 'Admin')
@section('page-style')
<link rel="stylesheet" href="{{asset('public/assets/plugins/footable-bootstrap/css/footable.bootstrap.min.css')}}" />
<link rel="stylesheet" href="{{asset('public/assets/plugins/footable-bootstrap/css/footable.standalone.min.css')}}" />
<style>
    .dataTables_filter{
        float: inline-end;
    }
</style>
@stop
@section('content')
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
        <a href="{{url('Admin/Company/Create')}}" class="btn btn-primary" style="align:right" >
            <b><i class="zmdi zmdi-plus"></i>Add Company</b>
        </a>
            <div class="table-responsive contact">
                <table class="table table-hover mb-0 c_list c_table" id="company">
                    <thead>
                        <tr>
                            <th>Edit</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Address</th>
                        </tr>
                    </thead>
                    <tbody >
                        @foreach($companies as $company)
                        <tr>
                            <td>
                                <a class="btn btn-primary btn-sm"  href="{{url('Admin/Company/Create/'.$company->id)}}">
                                    <i class="zmdi zmdi-edit"></i>
                                </a>
                            </td>
                            <td>

                                <img src="{{url('display/'.$company->id)}}" class="avatar w30" alt="">
                                <p class="c_name">{{ $company->name}}</p>
                            </td>
                            <td>
                                <span class="email"><a href="javascript:void(0);" title="">{{$company->phone}}</a></span>
                            </td>
                            <td>{{$company->email}}</td>
                            <td>{{$company->address}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal for delete confirmation -->
<div class="modal fade" id="modalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Are You Sure to Delete The Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer">
                <a class="btn btn-secondary" data-dismiss="modal">No</a>
                <a class="btn btn-primary model-delete" href="">Yes</a>
            </div>
        </div>
    </div>
</div>
@stop
@section('page-script')
<script src="{{asset('public/assets/bundles/footable.bundle.js')}}"></script>
<script src="{{asset('public/assets/js/pages/tables/footable.js')}}"></script>
<script>
$(document).ready(function(){
$(document).on('click','.del',function(){
var id=$(this).find('#userId').val();
$(".model-delete").attr("href", "removeUser/"+id);
});
});
</script>

@include('datatable-list');
<script>
$('#company').DataTable( {
    dom: 'Bfrtip',
    buttons: [
        {
            extend: 'pageLength',
            className: 'btn cl mr-2 px-3 rounded'
        },
        {
            extend: 'copy',
            className: 'btn bg-dark mr-2 px-3 rounded',
            title: 'Company list',

        },
        {
            extend: 'csv',
            className: 'btn btn-info mr-2 px-3 rounded',
            title: 'Company list',

        },
        {
            extend: 'pdf',
            className: 'btn btn-danger mr-2 px-3 rounded',
            title: 'Company list',

        },
        {
            extend: 'excel',
            className: 'btn btn-warning mr-2 px-3 rounded',
            title: 'Company list',

        },
        {
            extend: 'print',
            className: 'btn btn-success mr-2 px-3 rounded',
            title: 'Company list',

        },
        { extend: 'colvis', className: 'visible btn rounded' }
    ],
    "bDestroy": true,
    "lengthMenu": [[100, 200, 500, -1], [100, 200, 500, "All"]],

});


</script>
@stop
