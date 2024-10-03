@extends('layout.master')
@section('title', 'Form Options List')
@section('parentPageTitle', 'Admin')
@section('page-style')
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
              <button class="btn btn-primary" style="align:right" onclick="window.location.href = '{{ url('Admin/FormOptions/Create') }}';" >Add New Options</button>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="form_options">
                        <thead>
                            <tr>
                                <th class="px-1 py-0 text-center">Actions</th>
                                <th>Type</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($options as $option)  
                            <tr>
                                <td class="action">
                                    <a class="btn btn-primary btn-sm p-0 m-0"  href="{{url('Admin/FormOptions/Create/'.$option->id)}}">
                                        <i class="zmdi zmdi-edit px-2 py-1"></i>
                                    </a>
                                    <a class="btn btn-danger btn-sm del p-0 m-0" data-toggle="modal" data-target="#modalCenter">
                                           <input type="hidden" id="userId" value="{{$option->id}}">
                                           <i class="zmdi zmdi-delete text-white px-2 py-1"></i>
                                    </a>          
                                </td>
                                <td class="column_size">{{$option->type}}</td>
                                <td class="column_size">{{$option->description}}</td>
                            </tr>
                            @endforeach  
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- model pop -->
<div class="modal fade" id="modalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Do you want to delete option? </h5>
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
<!-- model pop -->
@stop
@section('page-script')
@include('datatable-list');
<script>
$('#form_options').DataTable( {
    dom: 'Bfrtip',
    buttons: [{
                        extend: 'pageLength',
                        className: 'btn cl mr-2 px-3 rounded'
                    },
                    {
                        extend: 'copy',
                        className: 'btn bg-dark mr-2 px-3 rounded',
                        title: 'Form Options List',
                        exportOptions: {
                        columns: [1,2] // Exclude columns with the class 'actions'
            }
                    },
                    {
                        extend: 'csv',
                        className: 'btn btn-info mr-2 px-3 rounded',
                        title: 'Form Options List',
                        exportOptions: {
                        columns: [1,2] // Exclude columns with the class 'actions'
            }
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-danger mr-2 px-3 rounded',
                        title: 'Form Options List',
                        exportOptions: {
                        columns: [1,2] // Exclude columns with the class 'actions'
            }
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-warning mr-2 px-3 rounded',
                        title: 'Form Options List',
                        exportOptions: {
                        columns: [1,2] // Exclude columns with the class 'actions'
            }
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-success mr-2 px-3 rounded',
                        title: 'Form Options List',
                        exportOptions: {
                        columns: [1,2] // Exclude columns with the class 'actions'
            }
                    },
                    {
                        extend: 'colvis',
                        className: 'visible btn rounded'
                    },
                ],
    "bDestroy": true,
    "lengthMenu": [[100, 200, 500, -1], [100, 200, 500, "All"]],
        
}); 

$(document).ready(function(){
$(document).on('click','.del',function(){
var id=$(this).find('#userId').val();
$(".model-delete").attr("href", "removeOption/"+id);
});
});
</script>
@stop







