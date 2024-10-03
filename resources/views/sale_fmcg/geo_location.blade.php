@extends('layout.master')
@section('title', 'Geo Location')
@section('parentPageTitle', 'Sales')
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
var saveOptionURL = "{{ url('Admin/FormOptionsSave') }}";
var token =  "{{ csrf_token()}}";
</script>
@stop

@section('content')

    <div class="row clearfix d-flex justify-content-center text-center">
        <div class="card col-md-6 ">
            <div class="header">
                <h2><strong>New</strong> Geo Location</h2>
            </div>
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            <div class="body">
                <form action="{{url('Sales_Fmcg/GeoLocation/Add')}}" method="post">
                @csrf
                    <input type="hidden" name="id" id="id" value="{{ $geoLoaction->id ?? ''  }}">
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-8">
                            <label for="name">Parent Location</label>
                            <div class="form-group">
                                <select name="parent_id" id="paren_id" class=" form-control show-tick ms select2">
                                    <option value="-1">Select Parent Location</option>
                                    @foreach($parent_location as $parent)
                                    <option value="{{$parent->id}}" {{ ( $parent->id == ( $geoLoaction->location_id ??'')) ? 'selected' : '' }} >{{$parent->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>                                               
                    </div>
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-8">
                            <label for="name">Location Name</label>
                            <div class="form-group">
                                <input type="text" name="name" id="name" class="form-control" value="{{ $geoLoaction->name ?? ''  }}"  required>
                            </div>
                        </div>                                               
                    </div>
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-8">
                            <label for="name">Label</label>
                            <div class="form-group">
                                <input type="text" name="label" id="label" class="form-control" value="{{ $geoLoaction->label ?? ''  }}"  required>
                            </div>
                        </div>                                               
                    </div>
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="checkbox" name="trans_location" id="trans_location" value="Yes" {{ (( $geoLoaction->trans ??'')== 'yes') ? 'checked' : '' }} >
                                <label for="trans_location">Trans Location</label>
                            </div>
                        </div>                                               
                    </div>
                    <div class="row d-flex justify-content-center">
                        <div>
                            <button class="btn btn-raised btn-success waves-effect" id="save" >Save</button> 
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
@section('page-script')
<script src="{{asset('public/assets/plugins/dropify/js/dropify.min.js')}}"></script>
<script src="{{asset('public/assets/js/pages/forms/dropify.js')}}"></script>
<script src="{{asset('public/assets/js/sw.js')}}"></script>
@stop