@extends('layout.master')
@section('title', 'Brands List')
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
                    <button class="btn btn-primary" id="new_brand" data-toggle="modal" data-target="#addBrand">New Brand</button>
                    @if (Session::has('update_msg'))
	                  <div class="alert alert-warning">{{ Session::get('update_msg') }}</div>
                    @endif
                    @if (Session::has('insert_msg'))
	                  <div class="alert alert-success">{{ Session::get('insert_msg') }}</div>
                    @endif
                    @if (Session::has('delete_msg'))
	                  <div class="alert alert-danger">{{ Session::get('delete_msg') }}</div>
                    @endif
                        <table class="table table-bordered table-striped table-hover" id="brands">
                        <thead>
                            <tr>
                                <th class="px-1 py-0 text-center">Actions</th>
                                 <th>Brand</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($brands as $brand)
                            <tr>
                                <td class="action">
                                    <a class="btn btn-success btn-sm brand_edit p-0 m-0" id="{{$brand->id}}" data-toggle="modal" data-target="#addBrand" >
                                        <i class="zmdi zmdi-edit text-white px-2 py-1"></i>
                                    </a>
                                    <a class="btn btn-danger btn-sm del p-0 m-0" data-toggle="modal" data-target="#modalCenter">
                                           <input type="hidden" id="brandId" value="{{$brand->id}}">
                                           <i class="zmdi zmdi-delete text-white px-2 py-1"></i>
                                    </a>          
                                </td>
                                <td class="column_size">{{$brand->name}}</td>
                            </tr>
                            @endforeach 
                        </tbody>
                    </table>
                <!-- Model For Add Brand -->
                <div class="modal fade" id="addBrand" data-keyboard="false" data-backdrop="static" aria-labelledby="exampleModalLabel" >
                    <div class="modal-dialog">
                        <div class="modal-content ">
                            <form action="{{url('Inventory/Brands/Add')}}" method="post">
                                @csrf
                                <div class="modal-header d-block ">
                                    <h5 class="modal-title text-center  text-primary" id="exampleModalLabel">Brand</h5>
                                </div>
                                <div class="modal-body d-block mx-5">
                                    <input type="text" name="brand" id="brand" class="form-control text-center" required>
                                    <input type="hidden" name="id" id="id">
                                </div>
                                <div class="modal-footer d-block">
                                    <div class="text-center">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                         <button type="submit" class="btn btn-success">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Model For Add Brand -->
                <!-- Model For Delete -->
                <div class="modal fade" id="modalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header d-block">
                                <h5 class="modal-title text-center" id="exampleModalLongTitle">Are You Want to Remove This Brand?</h5>
                            </div>
                            <div class="modal-footer d-block">
                                <div class="text-center text-light">
                                    <a class="btn btn-primary" data-dismiss="modal">No</a>
                                    <a class="btn btn-success model-delete" href="">Yes</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
                <!-- Model For Delete -->
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('page-script')
@include('datatable-list');
<script>
$('#brands').DataTable( {
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
        
}); 
</script>
<script>
var brandURL="{{ url('Inventory/Brands/Edit') }}";
var token =  "{{ csrf_token()}}";
$(document).ready(function(){

//new brand
$('#new_brand').click(function(){
    $('#id').val('');
    $('#brand').val('');
});

// brand edit
$(document).on('click','.brand_edit',function(){
    var id=$(this).attr('id');
    $.post(brandURL,{id:id, _token:token},function(data){
        data.map(function(val,i){
            $('#id').val(val.id);
            $('#brand').val(val.name);
        });
    });
});

//brand delete
$(document).on('click','.del',function(){
var id=$(this).find('#brandId').val();
$(".model-delete").attr("href", "brandRemove/"+id);
});

});

</script>
@stop