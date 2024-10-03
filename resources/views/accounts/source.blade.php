@extends('layout.master')
@section('title',$source)
@section('parentPageTitle', 'Accounts')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')


<link rel="stylesheet" href="{{asset('public/assets/plugins/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('public/assets/plugins/morrisjs/morris.css')}}" />
<link rel="stylesheet" href="{{asset('public/assets/plugins/jquery-spinner/css/bootstrap-spinner.css')}}" />
<link rel="stylesheet" href="{{asset('public/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css')}}" />
<link rel="stylesheet" href="{{asset('public/assets/plugins/bootstrap-select/css/bootstrap-select.css')}}" />
<link rel="stylesheet" href="{{asset('public/assets/plugins/nouislider/nouislider.min.css')}}" />
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<style>
    .input-group-text {
        padding: 0 .75rem;
    }
</style>
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
            <h2><strong>{{$source}}</strong></h2>
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
            <form method="post" action="{{url('Accounts/ExpenseSave/'.$source)}}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $record->id ?? ''  }}">

                <div class="row">
                    <div class="col-md-6">
                        <label for="fiscal_year">Parent Account</label>
                        <div class="form-group">
                            <select name="parent_id" id="parent" class="form-control show-tick ms select2"
                                data-placeholder="Select" required>
                                <option>Select Account</option>
                                @foreach($data as $account)
                                @if($account->id ?? '')
                                @if($account->trans_group ==0)
                                <option value="{{ $account->id}}" {{ ( $account->id == ( $record->coa_id ??'')) ?
                                    'selected' : '' }} >{{ $account->name}}</option>
                                @endif
                                @endif
                                @endforeach
                                <!--
                                        <optgroup label="Picnic">s
                                            <option>Mustard</option>
                                            <option>Ketchup</option>
                                            <option>Relish</option>
                                                <optgroup label="Camping">
                                                    <option>Tent</option>
                                                    <option>Flashlight</option>
                                                    <option>Toilet Paper</option>
                                                </optgroup>
                                        </optgroup>
                                    -->

                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="code">{{$source}} Code</label>
                        <div class="form-group">
                            <input type="autofill" name="code" class="form-control" value="{{ $record->code ?? ''  }}"
                                required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label for="name">{{$source}} Name</label>
                        <div class="form-group">
                            <input type="text" name="name" class="form-control" value="{{ $record->name ?? ''  }}"
                                required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="name">Opening Balance</label>
                        <div class="form-group">
                            <input type="number" step="any" name="balance" id="balance" class="form-control qty"
                                value="{{ $record->balance ?? ''  }}" required>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="radio">&nbsp;</label>
                        <div class="form-group">
                            <input type="radio" name="trans_group" class="trans_group" id="radio1" value="0" {{ ((
                                $record->trans_group ??'') == '0') ? 'checked' : '' }} required>
                            <label for="trans_group" class="mr-4">Group Account</label>
                            <input type="radio" name="trans_group" class="trans_group" id="radio2" value="1" {{ ((
                                $record->trans_group ??'') == '1') ? 'checked' : '' }} required>
                            <label for="trans_group">Transaction Account </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="ml-auto">
                        <button class="btn btn-raised btn-primary waves-effect" type="submit">Save</button>
                        <!--                            <button class="btn btn-raised btn-primary waves-effect" type="submit">Save & New</button>-->
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
@section('page-script')
<script src="{{asset('public/assets/bundles/footable.bundle.js')}}"></script>
<script src="{{asset('public/assets/js/pages/tables/footable.js')}}"></script>
{{-- <script>
    $('#balance').prop('disabled', true);
$('.trans_group').click(function(){
   var num=$(this).val();
    if(num==1)
    {
        $('#balance').prop('disabled',false);
    }
});
</script> --}}
@stop
