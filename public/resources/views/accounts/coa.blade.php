@extends('layout.master')
@section('title', 'Chart of Accounts')
@section('parentPageTitle', 'Accounts')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')

<link rel="stylesheet" href="{{asset('public/assets/plugins/select2/select2.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/plugins/morrisjs/morris.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/plugins/jquery-spinner/css/bootstrap-spinner.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/plugins/bootstrap-select/css/bootstrap-select.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/plugins/nouislider/nouislider.min.css')}}"/>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
.input-group-text {
    padding: 0 .75rem;
}
</style>
<script>
var searchCoaURL = "{{ url('coaSearch')}}";
var token = "{{ csrf_token()}}";
</script>
@stop
@section('content')
@php
    if( $data->coa_id ?? '')
        $coa_id = $data->coa_id;
    elseif(!empty($coa_id))
        $coa_id=$coa_id;
    else
        $coa_id=0;
@endphp
    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="header">
                <h2><strong>Chart of</strong> Accounts</h2>
            </div>
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            @if(session()->has('error'))
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
        @endif
            <div class="body">
                <!--<form method="post" action="{{url('Accounts/Coa')}}" enctype="multipart/form-data">-->
                <form method="post" action="{{url('Accounts/CoaSave')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $data->id ?? ''  }}">

                    <div class="row">
                        <div class="col-md-6">
                            <label for="fiscal_year">Parent Account</label>
                            <div class="form-group">
                                <select name="parent_id" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                    <option>Select Account</option>
                                    @foreach($coa as $account)
                                        <option {{ ( $account->id == $coa_id) ? 'selected' : '' }} value="{{ $account->id }}">{{ $account->name }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="code">Account Code</label>
                            <div class="form-group">
                                <input type="autofill" name="code" class="form-control" value="{{ $data->code ?? ''  }}"  required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="name">Account Name</label>
                            <div class="form-group">
                                <input type="text" name="name" id="name"  onkeyup="autoFill(this.id, searchCoaURL, token)" value="{{ $data->name ?? ''  }}" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="radio">&nbsp;</label>
                            <div class="form-group">
                                <input type="radio" name="trans_group" id="radio1" value="0" @if($data ?? '') {{ ( $data->trans_group == 0 ) ? 'checked' : '' }} @endif  required>
                                <label for="trans_group">Group Account</label>
                                 <input type="radio" name="trans_group" id="radio2" value="1" @if($data ?? '') {{ ( $data->trans_group == 1 ) ? 'checked' : '' }} @endif  required>
                                <label for="trans_group">Transaction Account </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="ml-auto">
                            <button class="btn btn-raised btn-primary waves-effect" type="submit">Save</button>
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
