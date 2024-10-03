@extends('layout.master')
@section('title', 'Sale Order View')
@section('parentPageTitle', 'Sales FMCG')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')
<?php  use App\Libraries\appLib; ?>
<link rel="stylesheet" href="{{asset('public/assets/css/sw.css')}}">
<style>
.input-group-text {
    padding: 0 .75rem;
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
@stop
@section('content')
<div class="row clearfix">
    <div class="card col-lg-12">
        <div class="header">
            <h2><strong>Sales Order</strong> Details</h2>
        </div>
        <div class="body">               
            <input type="hidden" name="id" value="{{ $product->id ?? ''  }}">
            <ul class="nav nav-tabs p-0 mb-3">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#home">General Detail</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#attachments">Attachments</a></li>
            </ul>   
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane in active" id="home">    
                    <div class="row">
                        <div class="col-md-2">
                            <label for="name">Number</label>
                            <div class="form-group "><p class="text-primary">{{ $sale_order->month ?? '' }}-{{appLib::padingZero($sale_order->number  ?? '')}}</p></div>
                        </div>
                        <div class="col-md-2">
                            <label for="name">Date</label>
                            <div class="form-group "><p class="text-primary">{{ $sale_order->date ?? ''  }}</p></div>
                        </div>                          
                        <div class="col-md-2">
                            <label for="sku">Current Balance</label>
                        <div class="form-group"><p class="text-primary">{{ $igp->warehouse_name ?? ''  }}</p></div>
                        </div>                        
                    </div>

                    <div class="row">
                    <div class="col-md-2">
                            <label for="sku">Customer</label>
                            <div class="form-group"><p class="text-primary">{{ $sale_order->cust_name ?? ''  }}</p></div>
                        </div>
                        <div class="col-md-4">
                            <label for="sku">Address</label>
                            <div class="form-group"><p class="text-primary">{{ $sale_order->cust_address ?? ''  }}</p></div>
                        </div>                      
                        <div class="col-md-2">
                            <label for="sku">Phone</label>
                            <div class="form-group"><p class="text-primary">{{ $sale_order->cust_phone ?? ''  }}</p></div>
                        </div>  
                        <div class="col-md-2">
                            <label for="sku">Town</label>
                            <div class="form-group"><p class="text-primary">{{ $sale_order->cust_town ?? ''  }}</p></div>
                        </div>        
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="sku">Note</label>
                            <div class="form-group"><p class="text-primary">{{ $sale_order->remarks ?? ''  }}</p></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="table-responsive">
                            <table  class="table table-striped m-b-0">
                                <thead>
                                    <tr class="bg-purple">
                                        <th>Product</th>
                                        <th class="table_header table_header_100">Unit</th>
                                        <th class="table_header">Stock(All warehouses)</th>
                                        <th class="table_header table_header_100">Qty Order</th>
                                        <th class="table_header table_header_100">Rate Code</th>
                                        <th class="table_header table_header_100">Base Rate</th>
                                        <th class="table_header table_header_100">Sales Tax</th>
                                        <th class="table_header table_header_100">FED</th>
                                        <th class="table_header table_header_100">Further Tax</th>
                                        <th class="table_header table_header_100">Inclusive Value</th>
                                        <th class="table_header table_header_100">L/U</th>
                                        <th class="table_header table_header_100">Freight</th>
                                        <th class="table_header table_header_100">Other</th>
                                        <th class="table_header table_header_100">Rate</th>
                                        <th class="table_header table_header_100">Fixed Margin</th>
                                        <th class="table_header table_header_100">Value Before Discount</th>
                                        <th class="table_header table_header_100">Trade Offer</th>
                                        <th class="table_header table_header_100">Discounts</th>
                                        <th class="table_header table_header_100">Value After Discounts</th>
                                        <th class="table_header table_header_100">Adv Payment</th>
                                    </tr>
                                </thead> 
                                <tbody>
                                @foreach($soDetails as $soDetail)
                                    <tr>
                                        <td ><label class="prod_field">{{$soDetail->prod_name}}</label></td> 
                                        <td class="text-center">{{$soDetail->unit}}</td>  
                                        <td class="text-center">{{$soDetail->qty_stock}}</td>   
                                        <td class="text-center">{{$soDetail->qty_ordered}} <input type="hidden" value="{{$soDetail->qty_ordered}}" class="qty-num"></td>   
                                        <td></td>
                                        <td class="text-center">{{$soDetail->base_rate}}</td>    
                                        <td class="text-center">{{$soDetail->sales_tax}} <input type="hidden" value="{{$soDetail->sales_tax}}}" class="sales_tax_num"></td>   
                                        <td class="text-center">{{$soDetail->fed}} <input type="hidden" value="{{$soDetail->fed}}" class="fed_num"></td>   
                                        <td class="text-center">{{$soDetail->further_tax}} <input type="hidden" value="{{$soDetail->further_tax}}" class="further_tax_num"></td>  
                                        <td class="text-center">{{$soDetail->inclusive_value}} <input type="hidden" value="{{$soDetail->inclusive_value}}" class="inclusive_num"></td>  
                                        <td class="text-center">{{$soDetail->lu}}</td>  
                                        <td class="text-center">{{$soDetail->freight}}</td>  
                                        <td class="text-center">{{$soDetail->other_discount}}</td>  
                                        <td class="text-center">{{$soDetail->rate}}</td>  
                                        <td class="text-center">{{$soDetail->fixed_margin}}</td>  
                                        <td class="text-center">{{$soDetail->amount_before_discount}} <input type="hidden" value="{{$soDetail->amount_before_discount}}" class="amount_before_discount_num"></td>  
                                        <td class="text-center">{{$soDetail->trade_offer}}  <input type="hidden" value="{{$soDetail->trade_offer}}" class="trade_offer_num"></td>  
                                        <td class="text-center">{{$soDetail->discount}} <input type="hidden" value="{{$soDetail->discount}}" class="discount_num"></td>  
                                        <td class="text-center">{{$soDetail->amount_after_discount}}  <input type="hidden" value="{{$soDetail->amount_after_discount}}" class="amount_after_discount_num"></td>  
                                        <td class="text-center">{{$soDetail->adv_payment}}</td>  
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>           
                        </div>
                    </div><hr>
                    <div class="row ">
                        <div class="col-md-2">
                            <label>Total Products</label>
                            <div class="form-group"><p class="text-primary" id="total_products_label"></p></div>
                        </div>
                        <div class="col-md-2">
                            <label>Total Qty</label>
                            <div class="form-group"><p class="text-primary" id="total_qty_label"></p></div>
                        </div>                      
                        <div class="col-md-2">
                            <label>Sales Tax</label>
                            <div class="form-group"><p class="text-primary" id="total_sales_tax_label"></p></div>
                        </div>  
                        <div class="col-md-2">
                            <label>FED</label>
                            <div class="form-group"><p class="text-primary" id="total_fed_label"></p></div>
                        </div> 
                        <div class="col-md-2">
                            <label>Further Tax</label>
                            <div class="form-group"><p class="text-primary" id="total_further_tax_label"></p></div>
                        </div>
                        <div class="col-md-2">
                            <label>Inclusive Value</label>
                            <div class="form-group"><p class="text-primary" id="total_inclusive_label"></p></div>
                        </div>         
                    </div><hr>   
                    <div class="row ">
                        <div class="col-md-2">
                            <label>Amount Before Discount</label>
                            <div class="form-group"><p class="text-primary" id="total_amount_before_discount_label"></p></div>
                        </div>
                        <div class="col-md-2">
                            <label>Trade offer</label>
                            <div class="form-group"><p class="text-primary" id="total_trade_offer_label"></p></div>
                        </div>                      
                        <div class="col-md-2">
                            <label>Discount</label>
                            <div class="form-group"><p class="text-primary" id="total_discount_label"></p></div>
                        </div>  
                        <div class="col-md-2">
                            <label>Net Amount</label>
                            <div class="form-group"><p class="text-primary" id="total_amount_after_discount_label"></p></div>
                        </div>     
                    </div><hr> 
                </div>
                <div role="tabpanel" class="tab-pane" id="attachments">            
                    <div class="row">
                        @foreach($attachmentRecord as $attachment)
                            <div class="col-md-3">
                                <div class="form-group">                                                              
                                    <a href="{{asset('assets/products/'.$attachment->file)}}" download >
                                        <img src="{{asset('public/assets/attachments/'.$attachment->file)}}"></img>
                                    </a>                                    
                                </div>
                            </div> 
                        @endforeach 
                    </div>          
                </div>
            </div>
            
           
        </div>
</div>
</div>
@stop
@section('page-script')
<script src="{{asset('public/assets/js/sw.js')}}"></script>
<script>
$(document).ready(function(){
    $('#total_products_label').html($('.product').length);
    $('#total_qty_label').html(sumAll('qty-num'));
    $('#total_sales_tax_label').html(sumAll('sales_tax_num'));
    $('#total_fed_label').html(sumAll('fed_num'));
    $('#total_further_tax_label').html(sumAll('further_tax_num'));
    $('#total_inclusive_label').html(sumAll('inclusive_num'));
    $('#total_amount_before_discount_label').html(sumAll('amount_before_discount_num'));
    $('#total_trade_offer_label').html(sumAll('trade_offer_num'));
    $('#total_discount_label').html(sumAll('discount_num'));
    $('#total_amount_after_discount_label').html(sumAll('amount_after_discount_num'));
});
</script>
@stop