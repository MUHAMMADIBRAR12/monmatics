@extends('layout.master')
@section('title', 'Voucher')
@section('parentPageTitle', 'Accounts')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="{{asset('public/assets/plugins/dropify/css/dropify.min.css')}}"/>
<style>

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

var CustomerURL = "{{ url('customerSearch') }}";
var ItemURL = "{{ url('itemSearch') }}";
var token =  "{{ csrf_token()}}";
var getItemDetailURL = "{{ url('getItemDetail') }}";
var rowId=1;

function addRow()
{
    rowId++;
    var row = '<tr id="row'+rowId+'">'+
                 '<td><button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow(\'row'+rowId+'\');"><i class="zmdi zmdi-delete"></i></button></td>'+
                 '<td><input type="text" name="name[]" id="name'+rowId+'" class="form-control autocomplete" onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail('+rowId+');" value="" required>'+
                 '    <input type="hidden" name="name_ID[]" id="name'+rowId+'_ID" required></td>'+
                 '<td class="description" id="description'+rowId+'"></td></td>'+
                 '<td class="qty"><input type="number" name="qty[]" class="qty form-control"  step="any" value="" required></td>'+
                 '<td><input type="insturction" name="insturction[]" id="insturction'+rowId+'" class="form-control" value="" max="180"></td>'+
                 '<td><input type="date" name="required_by[]" id="required_by'+rowId+'" class="form-control" value=""></td>'+
             '</tr>';
     $('#voucher').append(row);
}



// CurId is dropdown Id and rateId is Rate element id
function getCurrencyRate(curId, rateId)
{
    curCode = $('#'+curId).val();
    $.post("{{ url('getCurrencyRate') }}",{ code: curCode, _token : "{{ csrf_token()}}" }, function (data){
        $('#'+rateId).val(data);
    });

}
function totalAmount()
{
    totalAmountX = sumAll('amount');
    $('#total_amount').html($('#cur_id').val()+totalAmountX+'/-');
}

function getItemDetail(number)
{
    itemId = $('#name'+number+'_ID').val();
    $.post("{{ url('getItemDetail') }}",{ id: itemId, _token : "{{ csrf_token()}}" }, function (data){
        $('#description'+number).html('sku:'+data.sku+'|Unit:'+data.unit);
    });
}
</script>

@stop
@php
    if($Inquiry->id ?? '')
        $disabled = "disabled";

@endphp
@section('content')

    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="header">
                <h2><strong>Sales</strong> Inquiry</h2>
            </div>
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            <div class="body">
                <form method="post" action="{{url('Sales/Inquiry/Add')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $Inquiry->id ?? ''  }}">
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <label for="fiscal_year"><button type="button" class="btn btn-primary" style="align:right" target='_blank' onclick="window.location.href = '{{ url('Crm/Customers/Create/l') }}';" >+</button>Customer </label>
                            <div class="form-group">
                                <input type="text" name="customer" id="customer" onkeyup="autoFill(this.id,CustomerURL,token)" value="{{ $Inquiry->name ?? ''  }}" class="form-control autocomplete" required {{ $disabled ?? '' }}>
                                <input type="hidden" name="customer_ID" id="customer_ID" value="{{ $Inquiry->cust_id ?? ''  }}" required>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="date">Date</label>
                            <div class="form-group">
                                @php $toDate = date('m/d/Y'); @endphp
                                <input type="date" name="date" id='vdate'  class="form-control" value="{{ $Inquiry->date ?? date("Y-m-d")  }}"  required {{ $disabled ?? '' }}>
                            </div>
                        </div>

                       @if($Inquiry->month ?? '')
                        <div class="col-lg-2 col-md-6">
                            <label for="code">Inquiry No.</label>
                            <div class="form-group">
                                {{ ($Inquiry->month ?? '') }}-{{($Inquiry->number ?? '') }}
                            </div>
                        </div>
                       @endif
                    </div>
                    <div class="row">
                        <div class="table-responsive">
                            <table id="voucher" class="table table-striped m-b-0">
                                <thead>
                                    <tr class="bg-purple">
                                        <th><button type="button" class="btn btn-primary" style="align:right" onclick="addRow();" >+</button></th>
                                        <th class="table_header ">Item</th>
                                        <th class="table_header">Description</th>
                                        <th class="table_header table_header_100">Qty</th>
                                        <th class="table_header">Instruction</th>
                                        <th class="table_header">Required by</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                $count = (isset($InquiryDetail))?count($InquiryDetail):1;
                                for($i=1;$i<=$count; $i++)
                                {
                                    if(isset($InquiryDetail))
                                    {
                                        $lineItem = $InquiryDetail[($i-1)];
                                        $description = "sku:". $lineItem->sku ."| Unit:" .$lineItem->primary_unit;
                                    }
                                    @endphp
                                    <tr id="row{{$i}}">
                                        <td><button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow('row{{$i}}');"><i class="zmdi zmdi-delete"></i></button></td>
                                        <td class="autocomplete"><input type="text" name="name[]" id="name{{$i}}" onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail({{$i}});" value="{{ $lineItem->name ?? ''  }}" class="form-control autocomplete" required>
                                            <input type="hidden" name="name_ID[]" id="name{{$i}}_ID" value="{{ $lineItem->prod_id ?? ''  }}" required>
                                        </td>
                                        <td class="description" id='description{{$i}}'>{{ $description  }}</td>
                                        <td class="qty"><input type="number" step="any" name="qty[]" id="qty" class="form-control qty" value="{{ $lineItem->qty ?? '' }}"></td>
                                        <td class=""><input type="text" name="insturction[]"  class="form-control" value="{{ $lineItem->instruction ?? '' }}"></td>
                                        <td class="amount"><input type="date" name="required_by[]" id="required_by" class="form-control" value="{{ $lineItem->required_by ?? ""  }}"></td>
                                    </tr>
                                @php } @endphp
                                </tbody>
                                <script>
                                    rowId = {{$i}};
                                </script>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="fiscal_year">Note</label>
                            <div class="form-group" id="note">
                                <textarea name="note" maxlength="120" rows="4" class="form-control no-resize"  placeholder="">{{ $Inquiry->note ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label for="fiscal_year">Attachment</label>
                            <br>
                            @if($attachments ?? '')
                                <table>
                                @foreach($attachments as $attachment)
                                <tr>
                                    <td><button  type="button" class="btn btn-danger btn-sm" onclick="alert('Delete option is not available now.');"><i class="zmdi zmdi-delete"></i></button></td>
                                    <td><a href="{{asset('assets/attachments/'. $attachment->file)}}" download >{{ $attachment->file }}</a></td>
                                </tr>
                                @endforeach
                                </table>
                            @endif
                            <input name="file[]" type="file" class="dropify">
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
