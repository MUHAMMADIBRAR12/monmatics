@extends('layout.master')
@section('title','Daily Recovery')
@section('parentPageTitle', 'Accounts')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')
<?php  use App\Libraries\appLib; ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{asset('public/assets/plugins/select2/select2.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/plugins/morrisjs/morris.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/plugins/jquery-spinner/css/bootstrap-spinner.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/plugins/bootstrap-select/css/bootstrap-select.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/plugins/nouislider/nouislider.min.css')}}"/>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="{{asset('public/assets/plugins/dropify/css/dropify.min.css')}}"/>
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
var customerURL = "{{ url('customerSearch') }}";
var token =  "{{ csrf_token()}}";
</script>
@stop
@section('content')

    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="body">
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
                <form method="post" action="{{url('Accounts/DailyRecorvery/Add')}}">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{$daily_recovery->id ?? ''}}">
                    <input type="hidden" name="trm_id" value="{{$daily_recovery->trm_id ?? ''}}">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="">Document Number:  </label><br>
                            <label>{{$daily_recovery->month ?? ''}}-{{appLib::padingZero($daily_recovery->number  ?? '')}}</label>
                        </div>
                        <div class="col-md-3">
                            <label for="name">Date</label>
                            <div class="form-group">
                                <input type="date" name="date" class="form-control" value="{{$daily_recovery->date ?? ''}}"  required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label></label>
                            <div class="form-group">
                                <input type="radio" name="type" class="type"  value="1" {{(($daily_recovery->type ??'') == '1') ? 'checked' : '' }}>
                                <label>Cash</label>
                                <input type="radio" name="type"  class="ml-5 type" value="3" {{(($daily_recovery->type ??'') == '3') ? 'checked' : '' }}>
                                <label>Bank</label>
                                <input type="checkbox" name="status" id="staus" class="ml-5" value="cleared" {{(($daily_recovery->status ??'') == 'cleared') ? 'checked readonly' : '' }}>
                                <label>Cleared</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3" id="{{(isset($received_in))? '':'received_in_field'}}">
                            <label for="email">Received In</label>
                            @if(isset($received_in))
                            <div class="form-group">
                                <select name="received_in" id="received_in" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                   <option value="">Select Received In Account</option>
                                    @foreach($received_in as $account)
                                    <option value="{{$account->id}}" {{ ( $account->id == ($daily_recovery->received_coa_id ??'')) ? 'selected' : '' }}>{{$account->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @else
                            <div class="form-group">
                                <select name="received_in" id="received_in" class="form-control show-tick ms select2" data-placeholder="Select" required>

                                </select>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-3" id="{{(isset($received_in))? '':'cheque_field'}}">
                            <label for="cheque_no">Cheque No.</label>
                            <div class="form-group">
                                <input type="text" name="cheque_no"  class="form-control" value="{{ $daily_recovery->cheque_no ?? ''}}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>Ref No.</label>
                            <input type="text" name="ref_no" class="form-control" value="{{ $daily_recovery->ref_no ?? ''}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="fax">Received From</label>
                            <div class="form-group">
                                <input type="text" name="received_from" id="received_from" class="form-control" value="{{ $daily_recovery->received_from ?? ''}}"  placeholder="Customer" onkeyup="autoFill(this.id, customerURL, token)"  required>
                                <input type="hidden" name="received_from_ID" id="received_from_ID" value="{{ $daily_recovery->cst_coa_id ?? ''  }}" >
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="fax">Amount</label>
                            <div class="form-group">
                                <input type="number" step="any" name="amount" id="amount" class="form-control text-right"  value="{{ $daily_recovery->amount ?? ''}}">
                                <span id="amount-error" style="color:red"></span>
                           </div>
                        </div>
                        <div class="col-md-6">
                            <label>Description</label>
                            <div class="form-group">
                                <textarea name="description" maxlength="120" rows="2" class="form-control no-resize">{{$daily_recovery->description ?? ''}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class=" ml-auto">
                        <button class="btn btn-raised btn-primary waves-effect"  id="save" type="submit">Save</button>
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
<script>
//hide received-in select box and chque no field bydefault when we open the form
$('#received_in_field').hide();
$('#cheque_field').hide();
$('.type').click(function(){
    var  type=$(this).val();
    var url = "{{ url('received_in')}}";
    if(type==3)
    {
        parent_id = 9;
        //show received-in selecte box and chque no field
        $('#received_in_field').show();
        $('#cheque_field').show();
    }
    else
    {
        parent_id = 10;
        //show received-in select box
        $('#received_in_field').show();
        $('#cheque_field').hide();
    }
    $.post(url,{parent_id:parent_id, _token:token},function(data){
        //blank recevied in select-box
        $('#received_in').html('');
        $('#received_in').append('<option>select Account</option>');
        data.map(function(val,i){
            var option='<option value="'+val.id+'">'+val.name+'</option>';
                $('#received_in').append(option);
        });
    });

});

</script>
@stop
