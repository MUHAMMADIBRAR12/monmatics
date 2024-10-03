@extends('layout.master')
@section('title', 'Unit')
@section('parentPageTitle', 'Inventory')
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
var token =  "{{ csrf_token()}}";
</script>
@stop
@section('content')
    
    <div class="row clearfix d-flex justify-content-center text-center">
        <div class="card col-md-6 ">
            <div class="header">
                <h2><strong>Unit</strong></h2>
            </div>
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            <div class="body">
                <form action="{{url('Inventory/Unit/Add')}}" method="post">
                @csrf
                    <span id="msg" style="color:green;font-weight: bold "></span>
                    <input type="hidden" name="id" id="id" value="{{ $unit->id ?? ''  }}">
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-8">
                            <label for="name">Code</label>
                            <div class="form-group">
                                <input type="text" name="code" class="form-control"  value="{{ $unit->code ?? ''  }}">
                            </div>
                        </div>                                               
                    </div>
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-8">
                            <label for="name">Name</label>
                            <div class="form-group">
                                <input type="text" name="name" id="name" class="form-control" value="{{ $unit->name ?? ''  }}"  required>
                            </div>
                        </div>                                               
                    </div>
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-8">
                            <label for="name">Base Unit</label>
                            <div class="form-group">
                                <select name="base_unit" id="base_unit" class=" form-control show-tick ms select2">
                                    <option value="-1">Select Base Unit</option>  
                                    @foreach($base_units as $base_unit)
                                    <option value="{{$base_unit->id}}" {{ ( $base_unit->id == ( $unit->base_unit ??'')) ? 'selected' : '' }}>{{$base_unit->name}}</option> 
                                    @endforeach 
                                </select>   
                            </div>
                        </div>                                               
                    </div>
                    @if(($unit->base_unit ?? '') <> -1)
                    <div id="{{(isset($unit->base_unit)? '':'display-fields')}}">
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-8">
                            <label for="name">Operator</label>
                            <div class="form-group">
                                <select name="operator" id="operator" class=" form-control show-tick ms select2">
                                    <option value="">Select Operator</option>
                                    <option value="*" {{ (( $unit->operator ??'') == '*') ? 'selected' : '' }}>*</option> 
                                    <option value="+" {{ (( $unit->operator ??'') == '+') ? 'selected' : '' }}>+</option> 
                                </select>   
                            </div>
                        </div>                                               
                    </div>
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-8">
                            <label for="name">Value</label>
                            <div class="form-group">
                               <input type="text" name="value" id="value" class="form-control" value="{{ $unit->operator_value ?? ''  }}">  
                            </div>
                        </div>                                               
                    </div>
                    </div>
                    @endif
                    <div class="row d-flex justify-content-center">
                        <div>
                            <button class="btn btn-raised btn-success waves-effect" >Save</button>
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
    </form>
@stop
@section('page-script')
<script src="{{asset('public/assets/plugins/dropify/js/dropify.min.js')}}"></script>
<script src="{{asset('public/assets/js/pages/forms/dropify.js')}}"></script>
<script src="{{asset('public/assets/js/sw.js')}}"></script>
<script>
$('document').ready(function(){
$('#display-fields').hide();
$('#base_unit').on('change',function(){
    var base_unit=$(this).val();
    console.log(base_unit);
    if(base_unit == -1)
    {
        $('#display-fields').hide();
    }
    else
    {
        $('#display-fields').show();
    }
    
});
});
</script>
@stop