@extends('layout.master')
@section('title', 'Sale Invoice')
@section('parentPageTitle', 'Sales FMCG')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')

<?php  use App\Libraries\appLib; ?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!--<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">-->
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="{{asset('public/assets/plugins/dropify/css/dropify.min.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/css/sw.css')}}"/>
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
    .label-col
    {
        color:#bababa;
    }
    
</style>
<script lang="javascript/text">
var token =  "{{ csrf_token()}}";
var ItemURL = "{{ url('itemSearch') }}";
var getItemDetailURL = "{{ url('getItemDetail') }}";
var TaxRateURL = "{{ url('getTaxRates') }}";
var rowId=1;
var attachmentURL = "{{ url('prAttachmentDelete') }}";
var vendorURL= "{{ url('vendorSearch') }}";
</script>
@stop
@section('content')
    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="body">
                <form method="post" action="{{url('Sales_Fmcg/SaleInvoice/Add')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $sal_invoice->id ?? ''}}">
                    <input type="hidden" name="trans_id" value="{{ $sal_invoice->trans_id ?? ''}}">
                    <div class="row">
                        <div class="col-lg-2 col-md-2">
                            <label for="fiscal_year">Sale Invoice #</label><br>
                            <label for="fiscal_year">{{ $sal_invoice->month ?? '' }}-{{appLib::padingZero($sal_invoice->number  ?? '')}}</label>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <label for="date">Date</label>
                            <div class="form-group">
                                <input type="date" name="date" id='date'  class="form-control" value="{{ $sal_invoice->date ?? date('Y-m-d')  }}"  required>
                            </div>
                        </div> 
                        <div class="col-lg-3 col-md-3">
                            <label for="date">Due Date</label>
                            <div class="form-group">
                                <input type="date" name="due_date" id="due_date"  class="form-control" value="{{ $sal_invoice->due_date ?? date('Y-m-d')  }}"  required>
                            </div>
                        </div> 
                        <div class="col-lg-3 col-md-3">
                            <label for="date">D.O Number</label>
                            @if(isset($sal_invoice))
                            <br>
                            <label style="color:#bababa">{{ $sal_invoice->do_num ?? '' }}</label>
                            @else
                            <select name="do_id" id="do_id" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                   <option  value="">Select D.O number</option>
                                    @foreach($delivery_orders as $delivery_order)
                                    <option value="{{$delivery_order->id}}">{{$delivery_order->doc_number}}</option>
                                    @endforeach
                            </select>
                            @endif
                        </div>
                    </div> 
                    <div class="row">
                        <div class=" col-md-3">
                            <label>D.O Date</label><br>
                            <label id="do_date" style="color:#bababa">{{$sal_invoice->do_date ?? '' }}</label>
                        </div>
                        <div class="col-md-3">
                            <label>Tracking Date</label><br>
                            <label id="tracking_date" style="color:#bababa">{{$sal_invoice->do_tracking_date ?? '' }}</label>
                        </div>    
                        <div class="col-md-3">
                            <label>Warehouse</label><br>
                            <label id="warehouse" style="color:#bababa">{{$sal_invoice->warehouse_name ?? '' }}</label> 
                            <input type="hidden" name="warehouse_id" id="warehouse_id">
                        </div>
                    </div>  

                    <div class="row">
                        <div class=" col-md-3">
                            <label>Sale Order Number</label><br>
                            <label id="so_num" style="color:#bababa">{{$sal_invoice->so_num ?? '' }}</label>
                        </div>
                        <div class="col-md-3">
                            <label>Sale Order Date</label><br>
                            <label id="so_date" style="color:#bababa">{{$sal_invoice->so_date ?? '' }}</label>
                        </div>    
                        <div class="col-md-3">
                            <label>Sale Order By</label><br>
                            <label id="so_created_by" style="color:#bababa">{{$so_approvel->cust_balance ?? '' }}</label> 
                        </div>
                    </div>  

                    <div class="row">
                        <div class="col-md-2">
                            <label>Customer</label><br>
                            <label id="cust_name" style="color:#bababa">{{$sal_invoice->cust_name ?? '' }}</label>
                            <input type="hidden" name="do_cust_id" id="do_cust_id">
                        </div>
                        <div class="col-md-3">
                            <label>Phone</label><br>
                            <label id="cust_phone" style="color:#bababa">{{$sal_invoice->cust_phone ?? '' }}</label>
                        </div>    
                        <div class="col-md-3">
                            <label>Address</label><br>
                            <label id="cust_add" style="color:#bababa">{{$sal_invoice->cust_add ?? '' }}</label> 
                        </div>
                        <div class="col-md-2">
                            <label>Town</label><br>
                            <label id="cust_location"  style="color:#bababa">{{$sal_invoice->cust_location ?? '' }}</label> 
                        </div>
                        <div class="col-md-2">
                            <label>Balance</label><br>
                            <label style="color:#bababa" id="balance">{{$current_balance ?? '' }}</label> 
                        </div>
                    </div>                  
                    <div class="row">
                        <div class="table-responsive">
                            <table id="voucher" class="table table-striped m-b-0">
                                <thead>
                                    <tr class="bg-purple">
                                        <th class="table_header ">Product</th>
                                        <th class="table_header">Qty</th>
                                        <th class="table_header table_header_100">Base Rate</th>
                                        <th class="table_header table_header_100">Sales Tax</th>
                                        <th class="table_header table_header_100">FED</th>
                                        <th class="table_header table_header_100">Further Tax</th>
                                        <th class="table_header table_header_100">Tax Price</th>
                                        <th class="table_header table_header_100">L/U</th>
                                        <th class="table_header table_header_100">Freight</th>
                                        <th class="table_header table_header_100">Other</th>
                                        <th class="table_header table_header_100">Fixed Margin</th>
                                        <th class="table_header table_header_100">Value Before Discount</th>
                                        <th class="table_header table_header_100">Trade Offer</th>
                                        <th class="table_header table_header_100">Discounts</th>
                                        <th class="table_header table_header_100">Adv Payment</th>
                                        <th class="table_header table_header_100">Total Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                $count = (isset($sal_invoice_detail))?count($sal_invoice_detail):1;
                               
                                for($i=1;$i<=$count; $i++)
                                { 
                                    
                                    if(isset($sal_invoice_detail))
                                    {
                                        $lineItem = $sal_invoice_detail[($i-1)];
                                        
                                    }                                    
                                    @endphp                               
                                    <tr id="row{{$i}}" class="rowData">
                                        <td>
                                            <label class="prod_field">{{ $lineItem->prod_name ?? ''  }}</label>
                                        </td>
                                        <td class="text-center">
                                            <!-- qty -->
                                            <label>{{ $lineItem->qty_approved ?? ''  }}</label>
                                            <input type="hidden"  class="qty-num" value="{{ $lineItem->qty_approved ?? ''  }}">
                                        </td>
                                        <td class="text-center">
                                            <!-- base rate -->
                                            <label >{{ $lineItem->base_rate ?? ''  }}</label>
                                            <input type="hidden"  class="base_rate_num" value="{{ $lineItem->base_rate ?? ''  }}">
                                        </td>
                                        <td class="text-center">
                                            <!-- sales tax -->
                                            <label>{{ $lineItem->sales_tax ?? ''  }}</label>
                                            <input type="hidden"  class="sales_tax_num" value="{{ $lineItem->sales_tax ?? ''  }}">
                                        </td>
                                        <td class="text-center">
                                            <!-- fed -->
                                            <label>{{ $lineItem->fed ?? ''  }}</label>
                                            <input type="hidden" class="fed_tax_num" value="{{ $lineItem->fed ?? ''  }}">
                                        </td>
                                        <td class="text-center">
                                            <label>{{ $lineItem->further_tax ?? ''  }}</label>
                                            <input type="hidden"  class="further_tax_num" value="{{ $lineItem->further_tax ?? ''  }}">
                                        </td>
                                        <td>
                                            <label>{{ $lineItem->inclusive_value ?? ''  }}</label>
                                            <input type="hidden"  class="total_tax_num" value="{{ $lineItem->inclusive_value ?? ''  }}">
                                        </td>
                                        <td class="text-center">
                                            <label>{{ $lineItem->lu ?? ''  }}</label>
                                            <input type="hidden"  class="lu_num" value="{{ $lineItem->lu ?? ''  }}">
                                        </td>
                                        <td class="text-center">
                                            <label>{{ $lineItem->freight ?? ''  }}</label>
                                            <input type="hidden"  class="freight_num" value="{{ $lineItem->freight ?? ''  }}">
                                        </td>
                                        <td class="text-center">
                                            <label>{{ $lineItem->other_discount ?? ''  }}</label>
                                            <input type="hidden"  class="other_num" value="{{ $lineItem->other_discount ?? ''  }}">
                                        </td>
                                        <td class="text-center">
                                            <label>{{ $lineItem->fixed_margin ?? ''  }}</label>
                                            <input type="hidden"  class="margin_num" value="{{ $lineItem->fixed_margin ?? ''  }}">
                                        </td>
                                        <td class="text-center">
                                            <label>{{ $lineItem->amount_before_discount ?? ''  }}</label>
                                            <input type="hidden"  value="{{ $lineItem->amount_before_discount ?? ''  }}">
                                        </td>
                                        <td class="text-center">
                                            <label>{{ $lineItem->trade_offer ?? ''  }}</label>
                                            <input type="hidden"  class="trade_offer_num" value="{{ $lineItem->trade_offer ?? ''  }}">
                                        </td>
                                        <td class="text-center">
                                            <label>{{ $lineItem->discount ?? ''  }}</label>
                                            <input type="hidden"  class="discount_num" value="{{ $lineItem->discount ?? ''  }}">
                                        </td>
                                        <td class="text-center">
                                            <label>{{ $lineItem->adv_payment ?? ''  }}</label>
                                            <input type="hidden"   value="{{ $lineItem->adv_payment ?? ''  }}">
                                        </td>
                                        <td class="text-center">
                                            <label>{{ $lineItem->amount_after_discount ?? ''  }}</label>
                                            <input type="hidden"  class="net_amount_num" value="{{ $lineItem->amount_after_discount ?? ''  }}">
                                        </td>
                                    </tr>
                                @php 
                                        
                                    } 
                                @endphp
                                </tbody>  
                            </table>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-2">
                            <label for="">Total Products</label><br>
                            <label id="total_products_label" class="label-col">0.00</label> 
                        </div>
                        <div class="col-sm-2">
                            <label for="">Total Qty</label><br>
                            <label id="total_qty_label" class="label-col">0.00</label>
                        </div>
                        <div class="col-sm-2">
                            <label>Total Sales Tax</label><br>
                            <label id="total_sales_tax_label" class="label-col">0.00</label>
                            <input type="hidden" name="total_sales_tax" id="total_sales_tax_val">
                        </div>
                        <div class="col-sm-2">
                            <label>Total FED</label><br>
                            <label id="total_fed_tax_label" class="label-col">0.00</label>
                            <input type="hidden" name="total_fed_tax" id="total_fed_tax_val">
                        </div>
                        <div class="col-sm-2">
                            <label>Total Furthr Tax</label><br>
                            <label id="total_further_tax_label" class="label-col">0.00</label>
                            <input type="hidden" name="total_further_tax" id="total_further_tax_val">
                        </div>
                        <div class="col-sm-2">
                            <label>Total L/U</label><br>
                            <label id="total_lu_label" class="label-col">0.00</label>
                            <input type="hidden" name="total_lu" id="total_lu_val">
                        </div>
                        <div class="col-sm-2">
                            <label>Total Freight</label><br>
                            <label id="total_freight_label" class="label-col">0.00</label>
                            <input type="hidden" name="total_freight" id="total_freight_val">
                        </div>
                        <div class="col-sm-2">
                            <label>Total Other</label><br>
                            <label id="total_other_label" class="label-col">0.00</label>
                            <input type="hidden" name="total_other" id="total_other_val">
                        </div>
                        <div class="col-sm-2">
                            <label>Total Margin</label><br>
                            <label id="total_margin_label" class="label-col">0.00</label>
                            <input type="hidden" name="total_margin" id="total_margin_val">
                        </div>
                        <div class="col-sm-2">
                            <label>Total Inclusive</label><br>
                            <label id="total_inclusive_value_label" class="label-col">0.00</label>
                        </div>
                        <div class="col-sm-2">
                            <label>Total Discount</label><br>
                            <label id="total_discount_label" class="label-col">0.00</label>
                            <input type="hidden" name="total_discount" id="total_discount_val">
                        </div>
                        <div class="col-sm-2">
                            <label>Total Trade Offer</label><br>
                            <label id="total_trade_label" class="label-col">{{$sale_order->trade_offer ?? ''  }}</label>
                            <input type="hidden" name="total_trade_offer" id="total_trade_val">
                        </div>
                        <div class="col-sm-2">
                            <label for="">Net Amount</label><br>
                            <label id="total_net_amount_label" class="label-col">0.00</label>
                            <input type="hidden" name="total_net_amount" id="total_net_amount">
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
$('document').ready(function(){
    $('#do_id').on('change',function(){
        $('.rowData').html('');
        do_id=$(this).val();
        rowId=1;
        var url = "{{ url('Sales_Fmcg/do_detail')}}";
        $.post(url,{do_id:do_id, _token:token},function(data){
            console.log(data);
            data.map(function(val,i){
                var rw='<tr id="row'+rowId+'" class="rowData">'+
                        //do_detail_id
                        '<input type="hidden" name="do_detail_id[]" value="'+val.do_detail_id+'">'+
                        //soa_detail_id
                        '<input type="hidden" name="soa_detail_id[]" value="'+val.soa_detail_id+'">'+
                        '<td>'+
                            //product
                            '<label class="prod_field">'+val.prod_name+'</label>'+
                            '<input type="hidden" name="prod_coa_id" value="'+val.prod_coa_id+'">'+
                            '<input type="hidden" name="item_ID[]"  class="product" value="'+val.prod_id+'">'+
                        '</td>'+
                        '<td class="text-center">'+
                            //qty
                            '<label>'+val.qty+'</label>'+
                            '<input type="hidden" name="qty[]" class="qty-num" value="'+val.qty+'">'+
                        '</td>'+
                        '<td class="text-center">'+
                            //base rate
                            '<label >'+getNum(val.base_rate)+'</label>'+
                            '<input type="hidden" name="base_rate[]" class="base_rate_num" value="'+getNum(val.base_rate)+'">'+
                        '</td>'+
                        '<td class="text-center">'+
                            //sales tax
                            '<label>'+getNum(val.sales_tax)+'</label>'+
                            '<input type="hidden" name="sales_taX[]" class="sales_tax_num" value="'+getNum(val.sales_tax)+'">'+
                        '</td>'+
                        '<td class="text-center">'+
                            //fed
                            '<label>'+getNum(val.fed)+'</label>'+
                            '<input type="hidden" name="fed[]" class="fed_tax_num" value="'+getNum(val.fed)+'">'+
                        '</td>'+
                        '<td class="text-center">'+
                            //fuurther tax
                            '<label>'+getNum(val.further_tax)+'</label>'+
                            '<input type="hidden" name="further_tax[]" class="further_tax_num" value="'+getNum(val.further_tax)+'">'+
                        '</td>'+
                        '<td>'+
                            //tax price
                            '<label>'+getNum(val.tax_price)+'</label>'+
                            '<input type="hidden" name="tax_price[]" class="total_tax_num" value="'+getNum(val.tax_price)+'">'+
                        '</td>'+
                        '<td class="text-center">'+
                            //LU
                            '<label>'+getNum(val.lu)+'</label>'+
                            '<input type="hidden" name="lu[]" class="lu_num" value="'+getNum(val.lu)+'">'+
                        '</td>'+
                        '<td class="text-center">'+
                            //Freigt
                            '<label>'+getNum(val.freight)+'</label>'+
                            '<input type="hidden" name="freight[]" class="freight_num" value="'+getNum(val.freight)+'">'+
                        '</td>'+
                        '<td class="text-center">'+
                            //other
                            '<label>'+getNum(val.other)+'</label>'+
                            '<input type="hidden" name="other[]" class="other_num" value="'+getNum(val.other)+'">'+
                        '</td>'+
                        '<td class="text-center">'+
                            //Fixed margin
                            '<label>'+getNum(val.fixed_margin)+'</label>'+
                            '<input type="hidden" name="fixed_margin[]" class="margin_num" value="'+getNum(val.fixed_margin)+'">'+
                        '</td>'+
                        '<td class="text-center">'+
                            //Val before discount
                            '<label>'+getNum(val.amount_before_discount)+'</label>'+
                            '<input type="hidden" name="amount_before_discount[]" value="'+getNum(val.amount_before_discount)+'">'+
                        '</td>'+
                        '<td class="text-center">'+
                            //Trade offer
                            '<label>'+getNum(val.trade_offer)+'</label>'+
                            '<input type="hidden" name="trade_offer[]" class="trade_offer_num" value="'+getNum(val.trade_offer)+'">'+
                        '</td>'+
                        '<td class="text-center">'+
                            //Discount
                            '<label>'+getNum(val.discount)+'</label>'+
                            '<input type="hidden" name="discount[]" class="discount_num" value="'+getNum(val.discount)+'">'+
                        '</td>'+
                        '<td class="text-center">'+
                            //Adv payment
                            '<label>'+getNum(val.adv_payment)+'</label>'+
                            '<input type="hidden" name="adv_payment[]"  value="'+getNum(val.discount)+'">'+
                        '</td>'+
                        '<td class="text-center">'+
                            //Total Amount
                            '<label>'+getNum(val.total_amount)+'</label>'+
                            '<input type="hidden" name="net_amount[]" class="net_amount_num" value="'+getNum(val.total_amount)+'">'+
                        '</td>'+
                    '</tr>';
                $('#voucher').append(rw);
                $('#do_date').html(val.header.do_date);
                $('#warehouse').html(val.header.warehouse);
                $('#warehouse_id').val(val.header.warehouse_id);
                $('#so_num').html(val.header.so_num);
                $('#so_date').html(val.header.so_date);
                $('#so_created_by').html(val.header.user_name);
                $('#cust_name').html(val.header.cust_name);
                $('#do_cust_id').val(val.header.do_cust_id);
                $('#cust_add').html(val.header.cust_add);
                $('#cust_phone').html(val.header.cust_phone);
                $('#cust_location').html(val.header.cust_location); 
                $('#balance').html(val.balance);
                totalValues();
            });
        });
       
    });
});
</script>
<script>
totalValues();
function totalValues()
{
    total_sales_tax=sumAll('sales_tax_num');
    total_fed=sumAll('fed_tax_num');
    total_further_tax=sumAll('further_tax_num');
    total_lu=sumAll('lu_num');
    total_freight=sumAll('freight_num');
    total_other=sumAll('other_num');
    total_margin=sumAll('margin_num');
    total_trade_offer=sumAll('trade_offer_num');
    total_discount=sumAll('discount_num');
    $('#total_qty_label').html(sumAll('qty-num'));
    $('#total_sales_tax_label').html(total_sales_tax);
    $('#total_sales_tax_val').val(total_sales_tax);
    $('#total_fed_tax_label').html(total_fed);
    $('#total_fed_tax_val').val(total_fed);
    $('#total_further_tax_label').html(total_further_tax);
    $('#total_further_tax_val').val(total_further_tax);
    $('#total_lu_label').html(total_lu);
    $('#total_lu_val').val(total_lu);
    $('#total_freight_label').html(total_freight);
    $('#total_freight_val').val(total_freight);
    $('#total_other_label').html(total_other);
    $('#total_other_val').val(total_other);
    $('#total_margin_label').html(total_margin);
    $('#total_margin_val').val(total_margin);
    $('#total_inclusive_value_label').html(+sumAll('base_rate_num') + +sumAll('total_tax_num'));
    $('#total_discount_label').html(total_discount);
    $('#total_discount_val').val(total_discount);
    $('#total_net_amount_label').html(sumAll('net_amount_num'));
    $('#total_net_amount').val(sumAll('net_amount_num'));
    var numItems = $('.product').length;
    $('#total_products_label').html(numItems);
    $('#total_trade_label').html(total_trade_offer);
    $('#total_trade_val').val(total_trade_offer);
}
</script>
@stop