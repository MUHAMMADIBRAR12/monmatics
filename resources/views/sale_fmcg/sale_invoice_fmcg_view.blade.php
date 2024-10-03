@extends('layout.master')
@section('title','Sale Invoice FMCG View')
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
                    <div class="row">
                        <div class="col-lg-2 col-md-2">
                            <label for="fiscal_year">Sale Invoice #</label><br>
                            <label style="color:#bababa">{{ $sale_invoice->month ?? '' }}-{{appLib::padingZero($sale_invoice->number  ?? '')}}</label>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <label for="date">Date</label>
                            <div class="form-group">
                                <label style="color:#bababa">{{ $sale_invoice->date ?? '' }}</label>
                            </div>
                        </div> 
                        <div class="col-lg-3 col-md-3">
                            <label for="date">Due Date</label>
                            <div class="form-group">
                                <label style="color:#bababa">{{ $sale_invoice->due_date ?? '' }}</label>
                            </div>
                        </div>
                    </div> 
                    <div class="row">
                        <div class=" col-md-3">
                            <label>D.O Date</label><br>
                            <label style="color:#bababa">{{ $sale_invoice->do_date ?? '' }}</label>
                        </div>
                        <div class="col-md-3">
                            <label>Tracking Date</label><br>
                            <label id="tracking_date" style="color:#bababa">{{$so_approvel->cust_balance ?? '' }}</label>
                        </div>    
                        <div class="col-md-3">
                            <label>Warehouse</label><br>
                            <label style="color:#bababa">{{ $sale_invoice->warehouse_name ?? '' }}</label>
                        </div>
                    </div>  

                    <div class="row">
                        <div class=" col-md-3">
                            <label>Sale Order Number</label><br>
                            <label style="color:#bababa">{{ $sale_invoice->so_num ?? '' }}</label>
                        </div>
                        <div class="col-md-3">
                            <label>Sale Order Date</label><br>
                            <label style="color:#bababa">{{ $sale_invoice->so_date ?? '' }}</label>
                        </div>    
                        <div class="col-md-3">
                            <label>Sale Order By</label><br>
                            <label  style="color:#bababa">{{ $sale_invoice->user_name ?? '' }}</label> 
                        </div>
                    </div>  

                    <div class="row">
                        <div class="col-md-2">
                            <label>Customer</label><br>
                            <label  style="color:#bababa">{{ $sale_invoice->cust_name ?? '' }}</label>
                        </div>
                        <div class="col-md-3">
                            <label>Phone</label><br>
                            <label  style="color:#bababa">{{ $sale_invoice->cust_phone ?? '' }}</label>
                        </div>    
                        <div class="col-md-3">
                            <label>Address</label><br>
                            <label  style="color:#bababa">{{ $sale_invoice->cust_add ?? '' }}</label>
                        </div>
                        <div class="col-md-2">
                            <label>Town</label><br>
                            <label  style="color:#bababa">{{ $sale_invoice->cust_location ?? '' }}</label> 
                        </div>
                        <div class="col-md-2">
                            <label>Balance</label><br>
                            <label style="color:#bababa">{{$current_balance ?? '' }}</label> 
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
                                    @foreach($sale_invoice_fmcg_details as $invoice_detail)
                                    <tr>
                                        <td> <label class="prod_field">{{$invoice_detail->prod_name}} <input type="hidden" class="product" value="{{$invoice_detail->prod_name}}"> </label> </td>
                                        <td> <label>{{$invoice_detail->qty_approved}} <input type="hidden" class="qty-num" value="{{$invoice_detail->qty_approved}}"></label> </td>
                                        <td> <label>{{$invoice_detail->base_rate}}</label></td>
                                        <td> <label>{{$invoice_detail->sales_tax}}  <input type="hidden" class="sales_tax_num" value="{{$invoice_detail->sales_tax}}"></label> </td>
                                        <td> <label>{{$invoice_detail->fed}}  <input type="hidden" class="fed_tax_num" value="{{$invoice_detail->fed}}"></label></td>
                                        <td> <label>{{$invoice_detail->further_tax}}  <input type="hidden" class="further_tax_num" value="{{$invoice_detail->further_tax}}"></label> </td>
                                        <td> <label>{{$invoice_detail->inclusive_value}}</label></td>
                                        <td> <label>{{$invoice_detail->lu}} </label></td>
                                        <td> <label>{{$invoice_detail->freight}} </label> </td>
                                        <td> <label>{{$invoice_detail->other_discount}}</label> </td>
                                        <td> <label>{{$invoice_detail->fixed_margin}}</label> </td>
                                        <td> <label>{{$invoice_detail->amount_before_discount}}</label> </td>
                                        <td> <label>{{$invoice_detail->trade_offer}} <input type="hidden" class="trade_offer_num" value="{{$invoice_detail->trade_offer}}"></label> </td>
                                        <td> <label>{{$invoice_detail->discount}} <input type="hidden" class="discount_num" value="{{$invoice_detail->discount}}"></label> </td>
                                        <td> <label>{{$invoice_detail->adv_payment}}</label> </td>
                                        <td> <label>{{$invoice_detail->amount_after_discount}}  <input type="hidden" class="net_amount_num" value="{{$invoice_detail->amount_after_discount}}"></label> </td>
                                    </tr>
                                    @endforeach
                                </tbody>  
                            </table>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-2">
                            <label for="">Total Products</label><br>
                            <label id="total_products_label" class="label-col"></label> 
                        </div>
                        <div class="col-sm-2">
                            <label for="">Total Qty</label><br>
                            <label id="total_qty_label" class="label-col">0.00</label>
                        </div>
                        <div class="col-sm-2">
                            <label>Total Sales Tax</label><br>
                            <label id="total_sales_tax_label" class="label-col">0.00</label>
                        </div>
                        <div class="col-sm-2">
                            <label>Total FED</label><br>
                            <label id="total_fed_tax_label" class="label-col">0.00</label>
                        </div>
                        <div class="col-sm-2">
                            <label>Total Furthr Tax</label><br>
                            <label id="total_further_tax_label" class="label-col">0.00</label>
                        </div>
                        <div class="col-sm-2">
                            <label>Total Inclusive</label><br>
                            <label id="total_inclusive_value_label" class="label-col">0.00</label>
                        </div>
                        <div class="col-sm-2">
                            <label>Total Discount</label><br>
                            <label id="total_discount_label" class="label-col">0.00</label>
                        </div>
                        <div class="col-sm-2">
                            <label>Total Trade Offer</label><br>
                            <label id="total_trade_label" class="label-col">{{$sale_order->trade_offer ?? ''  }}</label>
                        </div>
                        <div class="col-sm-2">
                            <label for="">Net Amount</label><br>
                            <label id="total_net_amount_label" class="label-col">0.00</label>
                            <input type="hidden" name="total_net_amount" id="total_net_amount">
                        </div>
                    </div>
                    <div class="row">                        
                        <div class="ml-auto">
                            <button class="btn btn-raised btn-primary waves-effect" onclick="window.location.href = '{{ url('Sales_Fmcg/SaleInvoice/List')}}'">Back</button>
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
totalValues();
function totalValues()
{
    $('#total_qty_label').html(sumAll('qty-num'));
    $('#total_sales_tax_label').html(sumAll('sales_tax_num'));
    $('#total_fed_tax_label').html(sumAll('fed_tax_num'));
    $('#total_further_tax_label').html(sumAll('further_tax_num'));
    $('#total_inclusive_value_label').html(+sumAll('base_rate_num') + +sumAll('total_tax_num'));
    $('#total_discount_label').html(sumAll('discount_num'));
    $('#total_net_amount_label').html(sumAll('net_amount_num'));
    $('#total_net_amount').val(sumAll('net_amount_num'));
    var numItems = $('.product').length;
    $('#total_products_label').html(numItems);
    $('#total_trade_label').html(sumAll('trade_offer_num'));
}
</script>
@stop