@extends('layout.master')
@section('title',$info['title'])
@section('parentPageTitle', 'Accounts')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<link rel="stylesheet" href="{{asset('public/assets/plugins/dropify/css/dropify.min.css')}}" />
<style>
    .dropify {
        width: 200px;
        height: 200px;
    }

    th.project .project {
        display: block;
    }
</style>
<script lang="javascript/text">
    var rowId=1;
var searchCoaURL = "{{ url('coaSearch')}}";
var CurrencyURL = "{{ url('getCurrencyRate') }}";
var projectURL = "{{ url('projectSearch') }}";
var token = "{{ csrf_token()}}";

function addRow()
{
    rowId++;
    var row = '<tr id="row'+rowId+'">'+
                '<td>'+
                '<button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow(\'row'+rowId+'\');totalAmount()"><i class="zmdi zmdi-delete"></i></button>'+
                '</td>'+
                '<td>'+
                    '<input type="number" name="code[]" id="code" class="form-control" value=""  style="text-align: end;">'+
                '</td>'+
                '<td>'+
                    '<input type="text" name="name[]" id="name'+rowId+'" class="form-control autocomplete" onkeyup="autoFill(this.id, searchCoaURL, token)" value="" required>'+
                    '<input type="hidden" name="name_ID[]" id="name'+rowId+'_ID" required>'+
                '</td>'+
                '<td>'+
                    '<input type="text" name="description[]"  class="form-control" value="">'+
                '</td>'+
                '<td>'+
                    '<input type="number" name="amount[]" class="amount form-control" style="text-align: end;"  step="0.01" value="" onblur="totalAmount()" required>'+
                '</td>'+
                 '<td class="project">'+
                     '<input type="text" name="cost_center[]" id="cost_center'+rowId+'" class="form-control" onkeyup="autoFill(this.id, projectURL, token)">'+
                     '<input type="hidden" name="cost_center_ID[]" id="cost_center'+rowId+'_ID">'+
                 '</td>'+
             '</tr>';
     $('#voucher').append(row);
}

function totalAmount()
{
    totalAmountX = sumAll('amount');
    $('#total_amount').html(totalAmountX+'/-');

}

// delete file/image function
function deleteFileA(id, num)
{
    var x = confirm("Are you sure you want to delete?");
    if(x)
    {
        var url = '{{url("delete/")}}';
        deleteFile(url,id,token);
        $('#attRow'+num).html('');
     }
 }

</script>
@php
if($_GET['print'] ?? '')
echo "<script>
    window.open('".url('Accounts/CashVoucherPrint/'.$_GET['print'])."', '_blank')
</script>";
@endphp
@stop
@section('content')

<div class="row clearfix">
    <div class="card col-lg-12">
        <div class="header">
            <h2><strong>{{ $info['title'] }}</strong></h2>
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

            <form method="post" action="{{url('Accounts/cashVoucherSave')}}" enctype="multipart/form-data">
                {{ csrf_field() }}

                <input type="hidden" name="id" value="{{ $transmain->id ?? ''  }}">
                <input type="hidden" name="type" value="{{ $info['type'] ?? ''  }}">

                <div class="row">
                    <div class="col-lg-3">
                        <label for="">Type</label>
                        <select name="type" id="type" class="form-control show-tick ms select2"
                            data-placeholder="Select" required>
                            <option value="">Select Type</option>
                            <option {{ ( $option[0]==($transmain->voucher_type ?? '')) ? 'selected' : '' }} value="{{
                                $option[0] }}">Cash</option>
                            <option {{ ( $option[1]==($transmain->voucher_type ?? '')) ? 'selected' : '' }} value="{{
                                $option[1] }}">Bank</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="fiscal_year">@if($info['type']==1 || $info['type']==3){{ 'Recieved In' }}@else{{
                            'Pay from' }} @endif</label>
                        @if(isset($cashAccountDetail))
                        <div class="form-group">
                            <select name="coa_id" id="coa_id" class="form-control show-tick ms select2"
                                data-placeholder="Select" required>
                                <option value="">Select Account</option>
                                @foreach($coa as $account)
                                <option {{ ( $account->id == ($cashAccountDetail->coa_id ?? '')) ? 'selected' : '' }}
                                    value="{{ $account->id }}">{{ $account->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @else
                        <div class="form-group">
                            <select name="coa_id" id="coa_id" class="form-control show-tick ms select2"
                                data-placeholder="Select" required>
                                <option value="">Select Account</option>
                            </select>
                        </div>
                        @endif
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="date">Date</label>
                        <div class="form-group">
                            @php $toDate = date('m/d/Y'); @endphp
                            <input type="date" name="date" id='vdate' class="form-control"
                                value="{{ $transmain->date ?? date(" Y-m-d") }}" required>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 multicurrency">
                        <label for="fiscal_year">Currency</label>
                        <div class="form-group">
                            <select name="cur_id" id="cur_id" class="form-control show-tick ms select2"
                                data-placeholder="Select"
                                onchange="getCurrencyRate(this.id, 'rate', CurrencyURL, token);">
                                @foreach($currencies as $cur)
                                <option {{ ( $cur->id == ($cashAccountDetail->cur_id ?? '')) ? 'selected' : '' }}
                                    value="{{ $cur->id }}">{{ $cur->code }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 multicurrency">
                        <label for="rate">Rate</label>
                        <div class="form-group">
                            <input type="number" step="any" name="rate" id="rate" class="form-control" value="{{ $cashAccountDetail->cur_rate ?? '1'}}" style="text-align: end;">
                            @if ($errors->has('rate'))
                                <div id="rate-validation-error" style="color: red; font-size: 12px">{{ $errors->first('rate') }}</div>
                            @else
                                <div id="rate-validation-error"></div>
                            @endif
                        </div>
                    </div>


                    <div class="col-md-2">
                        <div id="clos_b">

                        </div>
                    </div>



                    @if($transmain->month ?? '')
                    <div class="col-lg-2 col-md-6">
                        <label for="code">Voucher No.</label>
                        <div class="form-group">
                            {{$transmain->tmnumber ?? '' }}
                        </div>
                    </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <label for="fiscal_year">Total Amount</label>
                        <div class="form-group" id="total_amount">

                        </div>
                    </div>
                    @if($info['type']==3 || $info['type']==4)
                    <div class="col-lg-3 col-md-6">
                        <label for="code">Refrence No.</label>
                        <div class="form-group">
                            <input type="text" name="cheque_number" class="form-control"
                                value="{{ $transmain->cheque_number ?? ''  }}" required>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="row">
                    <div class="table-responsive">
                        <table id="voucher" class="table table-striped m-b-0">
                            <thead>
                                <tr class="bg-purple">
                                    <th><button type="button" class="btn btn-primary" style="align:right"
                                            onclick="addRow();">+</button></th>
                                    <th>A/c Code</th>
                                    <th>Account Name</th>
                                    <th>Narration</th>
                                    <th>Amount</th>
                                    <th class="project">Cost Center</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $count = (isset($transDetail))?count($transDetail):1;

                                for($i=1;$i<=$count; $i++) { if(isset($transDetail)) { $trans=$transDetail[($i-1)];
                                    $amount=($trans->credit>0)?$trans->credit:$trans->debit;
                                    }
                                    @endphp
                                    <tr id="row{{$i}}">
                                        <td>
                                            <!-- Delete Button -->
                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick="deleteRow('row{{$i}}');totalAmount();"><i
                                                    class="zmdi zmdi-delete"></i></button>
                                        </td>

                                        <td>

                                            <input type="number" name="code[]" id="code" class="form-control"
                                                value="{{ $trans->code ?? ''  }}" style="text-align: end;">
                                        </td>

                                        <td>
                                            <!-- Account Name -->
                                            <input type="text" name="name[]" id="name{{$i}}"
                                                onkeyup="autoFill(this.id, searchCoaURL, token)"
                                                value="{{ $trans->name ?? ''  }}" class="form-control autocomplete"
                                                required>
                                            <input type="hidden" name="name_ID[]" id="name{{$i}}_ID"
                                                value="{{ $trans->coa_id ?? ''  }}" required>
                                        </td>
                                        <td>
                                            <!-- Description -->
                                            <input type="text" name="description[]" class="form-control"
                                                value="{{ $trans->description ?? '' }}">
                                        </td>
                                        <td>
                                            <!-- Amount -->
                                            <input type="number" step="any" name="amount[]" class="form-control amount"
                                                value="{{ $amount ?? '' }}" onblur="totalAmount();" required  style="text-align: end;">
                                        </td>

                                        <td class="project">
                                            <input type="text" name="cost_center[]" id="cost_center{{$i}}"
                                                class="form-control" value="{{ $trans->project_name ?? '' }}"
                                                onkeyup="autoFill(this.id, projectURL, token)">
                                            <input type="hidden" name="cost_center_ID[]" id="cost_center{{$i}}_ID"
                                                value="{{ $trans->cost_center_id ?? '' }}">
                                        </td>

                                    </tr>
                                    @php } @endphp
                            </tbody>
                            <script>
                                totalAmount();
                                    rowId = {{$i}};
                            </script>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label for="fiscal_year">Note</label>
                        <div class="form-group" id="note">
                            <textarea name="note" maxlength="120" rows="4" class="form-control no-resize"
                                placeholder="">{{ $transmain->note ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="col-sm-6 pr-0">
                        <label for="fiscal_year">Attachment</label>
                        <br>
                        @if($attachmentRecord ?? '')
                        <table>
                            @php $i=0 ; @endphp
                            @foreach($attachmentRecord as $attachment)
                            @php $i++ ; @endphp
                            <tr id='attRow{{ $i}}'>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="deleteFileA('{{$transmain->id}}', {{ $i}})"><i
                                            class="zmdi zmdi-delete"></i></button>
                                </td>
                                <td>
                                    <a href="{{url('download/'.$transmain->id)}}" download>{{ $attachment->file }}</a>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                        @endif
                        <input name="file" type="file" class="dropify">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="ml-auto">
                        <input type="checkbox" name="print" id="print" class="ml-2">
                        <label>Print</label>
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
<script>
    $(document).ready(function(){
        var url = "{{ url('accounts')}}";
        var token =  "{{ csrf_token()}}";
        $('#type').on('change',function(){
            type=$(this).val();
            if(type==1 || type==2)
            {
                coa_id=10;
            }
            else
            {
                coa_id=9;
            }
            $.post(url,{
                coa_id:coa_id,
                _token:token
            },function(data){
                console.log(data);
                $('#coa_id').html('');
                $('#coa_id').append('<option>Select Account </option>');
                data.map(function(val,i){
                    var option='<option value="'+val.id+'">'+val.name+'</option>';
                    $('#coa_id').append(option);
                });
            });
        });

        $('#coa_id').on('change',function() {

            var coa_id = $(this).val();

            $.ajax({
                type: 'POST',
                url: "{{ url('get_closing_balance') }}",
                data: {
                    coa_id: coa_id,
                    _token: token,
                },
                success: function(response) {
                    $('#clos_b').html('');
                    var data ='<label for="" class="mt-4">Closing Balance</label>'+
                                '<p>' + response.closing_balance + '</p>';
                        $('#clos_b').append(data);
                },
                error: function(error) {
                    console.log(error);
                }
            });

        });

    });
</script>


<script>
    const rateInput = document.getElementById('rate');
    const rateValidationError = document.getElementById('rate-validation-error');

    rateInput.addEventListener('input', function() {
        const rate = rateInput.value;
        const ratePattern = /^\d{0,4}(\.\d{0,4})?$/;

        if (!ratePattern.test(rate)) {
            rateValidationError.textContent = 'Rate should have a maximum of 4 digits before and after the decimal point.';
        } else {
            rateValidationError.textContent = '';
        }
    });
</script>
@stop
