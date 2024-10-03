@extends('layout.master')
@section('title', 'Role Management')
@section('parentPageTitle', 'Admin')
@section('page-style')
<link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.bootstrap.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.standalone.min.css') }}"/>
<style>
ul{
    list-style-type: none;
}
</style>
@stop
@section('content')
<div class="row clearfix">
        <div class="card col-lg-12">
            <div class="header">
             <div class="row">
                <div class="col-md-9">
                    <h2><strong>Role</strong> Assign</h2>
                </div>
                <div class="col-md-3">
                  
                    <a href="{{ url('role_management')}}" class="btn btn-primary float-right"><i class="zmdi zmdi-arrow-left"></i></a>
                </div>
             </div> 
            </div>
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            <div class="body">
                <form method="post" action="{{url('Admin/RoleManagement/Add')}}">
                    {{ csrf_field() }}
                        <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                           <label for="role">Role</label>
                               <input type="text" name="role" class="form-control" value="{{ $role['name'] ?? '' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="modules">Modules</label>
                            <div class="form-group">
                                <select name="modules" id="modules" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                    <option value="">-- Select Module --</option>
                                    @foreach($modules as $module)
                                    <option value="{{$module->id}}">{{$module->module}}</option>
                                    @endforeach                                   
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Access control part start -->
                    
                                <fieldset style="border:1px solid #DCDCDC" id="access-control">
                                     <legend>Access Control:</legend>
                                           <div class="row text-center">
                                              <div class="col-md-2 offset-md-4">
                                                <b>Create</b>
                                              </div>
                                              <div class="col-md-2">
                                                <b>Update</b>
                                              </div>
                                              <div class="col-md-2">
                                                <b>Delete</b>
                                              </div>
                                           </div>
                                        <!-- access row here -->
                                        <table id="moduleChild" class="table">
                                        <button class="btn btn-raised btn-primary waves-effect" type="submit">Save</button>
                                        <button class="btn btn-raised btn-primary waves-effect" type="button" id="select-all"> >> </button>
                                        </table>
                                </fieldset>
                </form>
            </div>
        </div>
    </div>
@stop
@section('page-script')
<script src="{{asset('public/assets/bundles/footable.bundle.js')}}"></script>
<script src="{{asset('public/assets/js/pages/tables/footable.js')}}"></script>
<script>

$(document).ready(function(){


    var row=1;
    $('#access-control').hide();
    $('#modules').on('change',function(){
        $('#access-control').show();
        var id=$(this).val();
        var url = "{{ url('Admin/RoleManagement/aj_moduleChild')}}";
        var token = "{{ csrf_token()}}";
        $.post(url,{id:id, _token:token},function(data){
                  $('#moduleChild').html(data.map(function (val, i) {                      
                        accessRow =  "<tr class='row text-center'>"+
                                        "<td class='col-md-1'>"+
                                          "<input type='checkbox' name='control["+row+"]' value='"+val.id+"' class='myCheck'>"+
                                          "</td>"+
                                          "<td class='col-md-3'>"+ val.module +
                                         "</td>"+
                                    "<td class='col-md-2'>"+
                                    "<input type='checkbox' name='create["+row+"]' class='myCheck'>"+
                                    "</td>"+
                                    "<td class='col-md-2'><input type='checkbox' name='update["+row+"]' class='myCheck'>"+
                                    "</td>"+
                                    "<td class='col-md-2'><input type='checkbox' name='delete["+row+"]' class='myCheck'>"+
                                    "</td>"+
                                    "</tr>";
                                    row++;
                        return accessRow;     
                    })); 
        });
    
    });
});
</script>
<script>
    $('#select-all').click(function(){
        
        if( $(".myCheck").prop('checked')== true)
            $(".myCheck").prop("checked",false); 
        else
            $(".myCheck").prop("checked", true);
    });
    
</script>
@stop