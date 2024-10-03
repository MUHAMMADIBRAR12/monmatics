@extends('layout.master')
@section('title', 'Role Management')
@section('parentPageTitle', 'Admin')
@section('page-style')
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/datatables.min.css"/>
<link rel="stylesheet" href="{{asset('public/assets/css/sw.css')}}">
<link rel="stylesheet" href="{{asset('public/assets/css/list.css')}}">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
@stop
@section('content')
<!-- list table start -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="body">
                <div class="table-responsive">
                    <a href="{{url('Admin/RoleManagement/Create')}}" class="btn btn-primary" style="align:right" >
                    <b><i class="zmdi zmdi-plus"></i>Add Role</b>
                    </a>
                    @if (Session::has('message'))
	                  <div class="alert alert-danger">{{ Session::get('message') }}</div>
                    @endif
                    <table class="table table-bordered table-striped table-hover" id="roles">
                        <thead>
                            <tr>
                                <th class="px-1 py-0 text-center">Actions</th>
                                <th>Role</th>
                                {{-- <th>Description</th>                       --}}
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($roleList as $role)
                        <tr>
                            <td class="action">
                                <a class="btn btn-success btn-sm p-0 m-0"  href="{{ route('Admin/RoleManagement/RoleEdit',['id' => $role->id])}}"><i class="zmdi zmdi-edit px-2 py-1"></i></a>
                                <a class="btn btn-danger btn-sm del text-white p-0 m-0" data-toggle="modal" data-target="#exampleModalCenter">
                                    <input type="hidden" id="roleId" value="{{$role->id}}">
                                    <i class="zmdi zmdi-delete px-2 py-1"></i>
                                </a> 
                            </td>
                            <td class="column_size">{{$role->name}}</td>
                            {{-- <td class="column_size"></td> --}}
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <!-- Modal for delete confirmation -->
                    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                                        <a class="btn btn-primary role-delete" href="">Yes</a>
                                    </div>
                                </div>
                            </div>
                        </div>             
                    </div>
            </div>
        </div>
    </div>
</div>
<!-- list table end -->
@stop
@section('page-script')
@include('datatable-list');
<script>
$('#roles').DataTable( {
    dom: 'Bfrtip',
    buttons: [{
                    extend: 'pageLength',
                    className: 'btn cl mr-2 px-3 rounded'
                },
                {
                    extend: 'copy',
                    className: 'btn bg-dark mr-2 px-3 rounded',
                    title: 'Role Management',
                    exportOptions: {
                columns: [1] // Exclude columns with the class 'actions'
            }
                },
                {
                    extend: 'csv',
                    className: 'btn btn-info mr-2 px-3 rounded',
                    title: 'Role Management',
                    exportOptions: {
                columns: [1] // Exclude columns with the class 'actions'
            }
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-danger mr-2 px-3 rounded',
                    title: 'Role Management',
                    exportOptions: {
                columns: [1] // Exclude columns with the class 'actions'
            }
                },
                {
                    extend: 'excel',
                    className: 'btn btn-warning mr-2 px-3 rounded',
                    title: 'Role Management',
                    exportOptions: {
                columns: [1] // Exclude columns with the class 'actions'
            }
                },
                {
                    extend: 'print',
                    className: 'btn btn-success mr-2 px-3 rounded',
                    title: 'Role Management',
                    exportOptions: {
                columns: [1] // Exclude columns with the class 'actions'
            }
                },
                {
                    extend: 'colvis',
                    className: 'visible btn rounded',
                    exportOptions: {
                columns: [1] // Exclude columns with the class 'actions'
            }
                },
            ],
    "bDestroy": true,
    "lengthMenu": [[100, 200, 500, -1], [100, 200, 500, "All"]],
        
});
//brand delete
$(document).on('click','.del',function(){
var id=$(this).find('#roleId').val();

$(".role-delete").attr("href", "roleRemove/"+id);
});
</script>
@stop