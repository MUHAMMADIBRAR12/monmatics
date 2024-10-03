@extends('layout.master')
@section('title', 'Blog Category List')
@section('parentPageTitle', 'Blog')
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
                    <button class="btn btn-primary" style="align:right" onclick="window.location.href = '{{ url('blog/category/create') }}';" >Add Blog Category</button>
                    @if (Session::has('msg'))
	                  <div class="alert alert-danger">{{ Session::get('msg') }}</div>
                    @endif
                        
                        <table class="table table-bordered table-striped table-hover" id="blog_categories">
                        <thead>
                            <tr>
                                <th class="px-1 py-0 text-center">Actions</th>
                                <th>Category</th>   
                            </tr>
                        </thead>
                            <tbody>
                             @foreach($categories as $category)
                             <tr>
                                <td class="action">
                                    <a class="btn btn-success btn-sm p-0 m-0"  href="{{url('blog/category/create/'.$category->id)}}">
                                        <i class="zmdi zmdi-edit px-2 py-1"></i>
                                    </a>
                                    <a class="btn btn-danger btn-sm del p-0 m-0" data-toggle="modal" data-target="#modalCenter">
                                           <input type="hidden" id="userId" value="{{$category->id}}">
                                           <i class="zmdi zmdi-delete text-white px-2 py-1"></i>
                                    </a>          
                                </td>
                                <td class="column_size">{{$category->category}}</td>
                             </tr>
                             @endforeach
                             
                        </tbody>
                    </table>
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
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('page-script')
@include('datatable-list');
<script>
$('#blog_categories').DataTable( {
    dom: 'Bfrtip',
    buttons: [
        {
            extend: 'pageLength',
            className: 'btn cl mr-2 px-3 rounded'
        },
        {
            extend: 'copy',
            className: 'btn bg-dark mr-2 px-3 rounded',
            title: 'Blog Category List',
            exportOptions: {
                columns: [1] // Exclude columns with the class 'actions'
            }
        },
        {
            extend: 'csv',
            className: 'btn btn-info mr-2 px-3 rounded',
            title: 'Blog Category List',
            exportOptions: {
                columns: [1] // Exclude columns with the class 'actions'
            }
        },
        {
            extend: 'pdf',
            className: 'btn btn-danger mr-2 px-3 rounded',
            title: 'Blog Category List',
            exportOptions: {
                columns: [1] // Exclude columns with the class 'actions'
            }
        },
        {
            extend: 'excel',
            className: 'btn btn-warning mr-2 px-3 rounded',
            title: 'Blog Category List',
            exportOptions: {
                columns: [1] // Exclude columns with the class 'actions'
            }
        },
        {
            extend: 'print',
            className: 'btn btn-success mr-2 px-3 rounded',
            title: 'Blog Category List',
            exportOptions: {
                columns: [1] // Exclude columns with the class 'actions'
            }
        },
        { extend: 'colvis', className: 'visible btn rounded' }
    ],
    "bDestroy": true,
    "lengthMenu": [[100, 200, 500, -1], [100, 200, 500, "All"]],
        
}); 

$(document).ready(function(){
$(document).on('click','.del',function(){
var id=$(this).find('#userId').val();
$(".model-delete").attr("href", "removeCategory/"+id);
});
});
</script>
@stop

