@extends('layout.master')
@section('title', 'New Options')
@section('parentPageTitle', 'Admin')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')
<link rel="stylesheet" href="{{asset('public/assets/plugins/select2/select2.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/plugins/morrisjs/morris.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/plugins/jquery-spinner/css/bootstrap-spinner.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/plugins/bootstrap-select/css/bootstrap-select.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/plugins/nouislider/nouislider.min.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/plugins/dropify/css/dropify.min.css')}}"/>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<?php  use App\Libraries\appLib; ?>
<style>
.input-group-text {
    padding: 0 .75rem;
}

.amount{
    width: 150px;
    text-align: right;
}
.table td{
    padding: 0.10rem;            
}
.dropify
{
    width: 200px;
    height: 200px;
}
</style>
<script lang="javascript/text">
var saveOptionURL = "{{ url('Admin/FormOptions/Add') }}";
var token =  "{{ csrf_token()}}";
</script>
@stop

@section('content')

    <div class="row clearfix d-flex justify-content-center text-center">
        <div class="card col-md-6 ">
            <div class="header">
                <h2><strong>New</strong> Options</h2>
            </div>
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            <div class="body">
                    <span id="msg" style="color:green;font-weight: bold "></span>
                    <input type="hidden" name="id" id="id" value="{{ $optionData->id ?? ''  }}">
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-8">
                            <label for="name">Option Name</label>
                            <div class="form-group">
                                <select name="option" id="option" class=" form-control show-tick ms select2">
                                    <option value="">Select Type</option>
                                @php
                                    $options=appLib::$options_array;
                                @endphp
                                    @foreach($options as $key => $value) 
                                        <option value="{{$value}}" {{ ( $value == ( $optionData->type ??'')) ? 'selected' : '' }}>{{$key}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>                                               
                    </div>
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-8">
                            <label for="name">Value</label>
                            <div class="form-group">
                                <input type="text" name="value" id="value" class="form-control" value="{{ $optionData->description ?? ''  }}"  required>
                                <span id="error" style="color:red"></span>
                            </div>
                        </div>                                               
                    </div>
                    <div class="row d-flex justify-content-center">
                        <div>
                            <button class="btn btn-raised btn-success waves-effect" id="save" >Save</button>
                            <button class="btn btn-raised btn-primary waves-effect" onclick="window.location.href = '{{ url('Admin/FormOptions/List') }}';" >Back</button>
                        </div>
                    </div>
                
            </div>
        </div>
    </div>
@stop
@section('page-script')
<script src="{{asset('public/assets/plugins/dropify/js/dropify.min.js')}}"></script>
<script src="{{asset('public/assets/js/pages/forms/dropify.js')}}"></script>
<script src="{{asset('public/assets/js/sw.js')}}"></script>
<script>
$('#save').click(function(){
    var id=$('#id').val();
    var option_type=$('#option').val();
    var value=$('#value').val();
    if(value=='')
    {
        $('#error').text("Plase Add a Value");
    }
    else
    {
        $.post(saveOptionURL,{id:id,option_type:option_type,value:value, _token:token},function(data){
                $('#msg').text(data);
                $('#value').val('');
                $('#id').val('');
            }); 
    }
});
</script>
@stop