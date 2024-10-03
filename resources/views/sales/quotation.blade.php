@extends('layout.master')
@section('title', 'Quotation')
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

var CustomerURL = "{{ url('leadSearch') }}";
var ItemURL = "{{ url('itemSearch') }}";
var token =  "{{ csrf_token()}}";
var getItemDetailURL = "{{ url('getItemDetail') }}";
var TaxRateURL = "{{ url('getTaxRates') }}";
var rowId=1;

function addRow()
{
    rowId++;
    var row ='<tr id="row'+rowId+'">'+
                '<td><button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow(\'row'+rowId+'\');"><i class="zmdi zmdi-delete"></i></button></td>'+
                '<td>'+
                    //item
                    '<input type="text" name="name[]" id="name'+rowId+'" class="form-control prod_field" onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail('+rowId+');" required>'+
                    '<input type="hidden" name="name_ID[]" id="name'+rowId+'_ID">'+
                '</td>'+
                '<td>'+
                    //description
                    '<input type="text" name="description[]" id="description'+rowId+'" class="form-control desc-inst-field">'+
                '</td>'+
                '<td>'+
                    //qty
                    '<input type="number" name="qty[]" id="qty'+rowId+'" class="qty form-control" step="any" onblur="qtyRateTotal('+rowId+')" required>'+
                '</td>'+
                '<td>'+
                    //rate
                    '<input type="number" name="rate[]" id="rate'+rowId+'" step="any" class="form-control qty" onblur="qtyRateTotal('+rowId+')">'+
                '</td>'+
                '<td>'+
                    //amount
                    '<input type="number" name="amount[]" id="amount'+rowId+'" step="any" class="form-control qty" value="1.000">'+
                '</td>'+
                '<td>'+
                    '<select name="tax_class[]" id="tax_class'+rowId+'" class="form-control show-tick ms select2 qty" data-placeholder="Select" onchange="getTaxAmount(this.id, '+rowId+', token);">'+
                            '<option value="">-select-</option>'+
                        @foreach($taxList as $tax)
                            '<option {{ ( $tax->name == ($cashAccountDetail->coa_id ?? '')) ? 'selected' : '' }} value="{{ $tax->name }}">{{ $tax->name }} @ {{ $tax->rate }}</option>'+
                        @endforeach
                    '</select>'+
                '</td>'+
                '<td>'+
                    //tax amount
                    '<input type="number" name="tax_amount[]" id="tax_amount'+rowId+'" step="any" class="form-control qty" value="0.000">'+
                '</td>'+
                '<td>'+
                    //total amount
                    '<input type="number" name="total_amount[]" id="total_amount'+rowId+'" step="any" class="total_amount form-control qty" value="1.000" readonly>'+
                '</td>'+
                '<td>'+
                    //instruction
                    '<input type="text" name="insturction[]" id="insturction'+rowId+'" class="form-control desc-inst-field" max="180">'+
                '</td>'+
                '<td>'+
                    //required by
                    '<input type="date" name="required_by[]" id="required_by'+rowId+'" class="form-control">'+
                '</td>'+
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
        $('#description'+number).val('sku:'+data.sku+'|Unit:'+data.unit);
        $('#rate'+number).val(data.sale_price);

    });
}

function getTaxAmount(taxName, index, token)
{
    taxName = $('#'+taxName).val();
    $.post(TaxRateURL,{ taxName: taxName, _token : token }, function (data){
        taxRate = getNum(data);
        console.log('tax rate'+taxRate) ;
        amount = getNum($('#amount'+index).val(),2);
        console.log('amount'+amount);
        taxAmount = percentVal(taxRate, amount, 2);
        console.log('tax amount'+taxAmount);
        totalAmount = +getNum(amount) + +getNum(taxAmount);
        console.log('total amount'+totalAmount);
        $('#tax_amount'+index).val(taxAmount);
        $('#total_amount'+index).val(totalAmount);
        $('#sub_total').val(sumAll('total_amount'));

    });
}

function qtyRateTotal(index)
{
    amount = getNum($('#qty'+index).val())*getNum($('#rate'+index).val());
    $('#amount'+index).val(getNum(amount,2));
    $('#total_amount'+index).val(getNum(amount,2));
    $('#sub_total').val(sumAll('total_amount'));
    getTaxAmount('tax_class'+index,index,token);

}

function getInquiries()
{

    cstId = $('#customer_ID').val();
    $('#inquiry_id').empty();
    $('#inquiry_id').append("<option value='' >-Select-</option>");
    $.post("{{ url('getInquiries') }}",{ id: cstId, _token : "{{ csrf_token()}}" }, function (data){
       $.each( data, function( key, value ) {
           $('#inquiry_id').append("<option value='"+value.id+"' >"+value.month+"-"+value.number+"</option>");
        //alert( value.date );
      });


    }), json;
}
</script>

@stop
@php
    if($Quotation->id ?? '')
        $disabled = "disabled";

@endphp
@section('content')

    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="header">
                <h2><strong>Sales</strong> Quotation</h2>
            </div>
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            <div class="body">
                <form method="post" action="{{url('Sales/Quotation/Add')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $Quotation->id ?? ''  }}">
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <label for="fiscal_year"><button type="button" class="btn btn-primary" style="align:right" target='_blank' onclick="window.location.href = '{{ url('Crm/Customers/Create/l') }}';" >+</button>Customer </label>
                            <div class="form-group">
                                <input type="text" name="customer" id="customer" onkeyup="autoFill(this.id,CustomerURL,token)" value="{{ $Quotation->name ?? ''  }}" class="form-control autocomplete" required  onblur="getInquiries()">
                                <input type="hidden" name="customer_ID" id="customer_ID" value="{{ $Quotation->cust_id ?? ''  }}" required>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="date">Date</label>
                            <div class="form-group">
                                @php $toDate = date('m/d/Y'); @endphp
                                <input type="date" name="date" id='vdate'  class="form-control" value="{{ $Quotation->date ?? date("Y-m-d")  }}"  required {{ $disabled ?? '' }}>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <label for="fiscal_year">Inquiry No.</label>
                            <div class="form-group">
                                <select name="inquiry_id" id="inquiry_id" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">--Select--</option>
                                </select>

                            </div>
                        </div>
                       @if($Quotation->month ?? '')
                        <div class="col-lg-2 col-md-6">
                            <label for="code">Quotation No.</label>
                            <div class="form-group">
                                {{ ($Quotation->month ?? '') }}-{{($Quotation->number ?? '') }}
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
                                        <th class="table_header table_header_100">Rate</th>
                                        <th class="table_header table_header_100">Amount</th>
                                        <th class="table_header table_header_100">Tax</th>
                                        <th class="table_header table_header_100">Tax</th>
                                        <th class="table_header table_header_100">Total Amount</th>
                                        <th class="table_header">Instruction</th>
                                        <th class="table_header">Required by</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                $count = (isset($QuotationDetail))?count($QuotationDetail):1;
                                for($i=1;$i<=$count; $i++)
                                {

                                    if(isset($QuotationDetail))
                                    {
                                         $lineItem = $QuotationDetail[($i-1)];
                                        $description = "sku:". $lineItem->sku ."| Unit:" .$lineItem->primary_unit;
                                    }
                                    @endphp
                                    <tr id="row{{$i}}">
                                        <td><button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow('row{{$i}}');"><i class="zmdi zmdi-delete"></i></button></td>
                                        <td>
                                            <!-- item -->
                                            <input type="text" name="name[]" id="name{{$i}}" onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail({{$i}});" value="{{ $lineItem->name ?? ''  }}" class="form-control prod_field" required>
                                            <input type="hidden" name="name_ID[]" id="name{{$i}}_ID" value="{{ $lineItem->prod_id ?? ''  }}" required>
                                        </td>
                                        <td>
                                            <!--description -->
                                            <input type="text" name="description[]" id="description{{$i}}" class="form-control desc-inst-field" value="{{ $description ??  '' }}">
                                        </td>
                                        <td>
                                            <!-- qty -->
                                            <input type="number" step="any" name="qty[]" id="qty{{$i}}" class="form-control qty" value="{{ $lineItem->qty ?? '' }}" onblur="qtyRateTotal({{$i}})">
                                        </td>
                                        <td>
                                            <!-- rate -->
                                            <input type="number" name="rate[]" id="rate{{$i}}" step="any" class="form-control qty" value="{{ $lineItem->rate ?? 0 }}"  onblur="qtyRateTotal({{$i}})">
                                        </td>
                                        <td>
                                            <!--amount -->
                                            <input type="number" name="amount[]" id="amount{{$i}}" step="any" class="form-control qty" value="{{ $lineItem->amount ?? 0 }}">
                                        </td>
                                        <td>
                                            <!--tax -->
                                            <select name="tax_class[]" id="tax_class{{$i}}" class="form-control show-tick ms select2 qty" data-placeholder="Select" onchange="getTaxAmount(this.id, {{$i}}, token);">
                                                <option value="">-select-</option>
                                            @foreach($taxList as $tax)
                                                <option {{ ( $tax->name == ($lineItem->tax_class ?? '')) ? 'selected' : '' }} value="{{ $tax->name }}">{{ $tax->name }} @ {{ $tax->rate }}</option>
                                            @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <!-- tax amount -->
                                            <input type="number" name="tax_amount[]" id="tax_amount{{$i}}" step="any" class="form-control qty" value="{{ $lineItem->tax_amount ?? 0 }}">
                                        </td>
                                        <td>
                                            <!-- total amount -->
                                            <input type="number" name="total_amount[]" id="total_amount{{$i}}" step="any" class="total_amount form-control qty" value="{{ $lineItem->total_amount ?? 0 }}" readonly="readonly"></td>
                                        <td>
                                            <!-- instruction -->
                                            <input type="text" name="insturction[]"  class="form-control desc-inst-field" value="{{ $lineItem->instruction ?? '' }}">
                                        </td>
                                        <td>
                                            <!-- required by -->
                                            <input type="date" name="required_by[]" id="required_by" class="form-control" value="{{ $lineItem->required_by ?? ''  }}"></td>
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
                        <div class="table-responsive">
                            <table id="voucher" class="table table-striped m-b-0">
                                <tr>
                                    <td>Total Amount :</td>
                                    <td class="qty"><input type="number" name="sub_total" id="sub_total" step="any" class="form-control qty" value="{{ $lineItem->rate ?? 0 }}"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="fiscal_year">Note</label>
                            <div class="form-group" id="note">
                                <textarea name="note" maxlength="120" rows="4" class="form-control no-resize"  placeholder="">{{ $Quotation->note ?? '' }}</textarea>
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
                                    <td><a target="_blank" href="{{asset('assets/attachments/'. $attachment->file)}}" download >{{ $attachment->file }}</a></td>
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
<script>
    $(document).ready(function(){
        $('#sub_total').val(sumAll('total_amount'));
    });
</script>
@stop
