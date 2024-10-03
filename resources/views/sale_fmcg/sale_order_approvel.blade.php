@extends('layout.master')
@section('title', 'Sales Order Approvel')
@section('parentPageTitle', 'Sales')
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

function addRow()
{
    rowId++;
    
    var row ='<tr id="row'+rowId+'" class="rowData">'+
                '<td>'+
                    '<button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow(\'row'+rowId+'\');"><i class="zmdi zmdi-delete"></i></button>'+
                '</td>'+
                '<td>'+
                    //product
                    '<input type="text" name="item[]" id="item'+rowId+'" class="form-control field-width"  onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail('+rowId+');">'+
                    '<input type="hidden" name="item_ID[]" id="item'+rowId+'_ID" >'+
                '</td>'+
                '<td>'+
                    //unit
                    '<input type="text" name="unit[]" id="unit'+rowId+'" class="form-control qty" readonly>'+
                '</td>'+
                '<td>'+
                    //stock(all warehouses)
                    '<input type="text" name="qty_stock[]" id="qty_stock'+rowId+'" class="form-control qty" readonly>'+
                '</td>'+
                '<td>'+
                    //qty ordered
                    '<input type="number" step="any" name="qty_ordered[]" id="qty_ordered'+rowId+'"  class="form-control" >'+
                '</td>'+
                '<td>'+
                    //qty approved
                    '<input type="number" step="any" name="qty_approved[]" id="qty_approved'+rowId+'"  class="form-control qty" required >'+
                    
                '</td>'+
                '<td>'+
                    //rate
                    '<input type="number" step="any" name="rate[]" id="rate'+rowId+'"  class="form-control qty">'+
                    
                '</td>'+
                '<td>'+
                    //gross amount
                    '<input type="number" step="any" name="gross_amount[]" id="gross_amount'+rowId+'"  class="form-control qty">'+
                '</td>'+
            '</tr>';
     $('#voucher').append(row);
}


function qtyRateTotal(index)
{
    amount = getNum($('#qty'+index).val())*getNum($('#rate'+index).val());
    $('#amount'+index).val(getNum(amount,2));
    $('#total_amount'+index).val(getNum(amount,2));
    $('#sub_total').val(sumAll('total_amount'));
  
}

</script>
@stop
@section('content')
    <div class="row clearfix">
        <div class="card col-lg-12">
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            <div class="body">
                <form method="post" action="{{url('Sales_Fmcg/SaleOrderApprovel/Add')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $so_approvel->id ?? ''}}">
                    <div class="row">
                        <div class="col-lg-2 col-md-2">
                            <label for="fiscal_year">Approvel No #</label><br>
                            <label for="fiscal_year">{{ $so_approvel->month ?? '' }}-{{appLib::padingZero($so_approvel->number  ?? '')}}</label>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <label for="date">Date</label>
                            <div class="form-group">
                                <input type="date" name="date" id='date'  class="form-control" value="{{ $so_approvel->date ?? date('Y-m-d')  }}"  required>
                            </div>
                        </div>  
                        <div class="col-lg-3 col-md-3">
                            <label for="fiscal_year">Sale Order No.</label>
                            <div class="form-group">
                                @if(isset($so_approvel->so_id))
                                    <label>{{ $so_approvel->so_month ?? '' }}-{{appLib::padingZero($so_approvel->so_number  ?? '')}}</label>
                                @else
                                <select name="sale_order_no" id="sale_order_no" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option  value="">Select Sale Order No.</option>
                                    @foreach($sale_orders as $so)
                                    <option  value="{{$so->id}}" {{($so->id == ($so_approvel->so_id ??'')) ? 'selected' : '' }}>{{$so->doc_number}}</option>
                                    @endforeach
                                </select>
                                @endif
                            </div>   
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <label>Sale Order Date</label><br>
                            <label id="sale_order_date" style="color:#bababa">{{$so_approvel->so_date ?? ''}}</label>
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <label>Sale Order By</label><br>
                            <label id="sale_order_by" style="color:#bababa">{{$so_approvel->user_name ?? ''}}</label>
                        </div>     
                    </div> 
                    <div class="row">
                        <div class="col-lg-2 col-md-2">
                            <label for="">Customer</label><br>
                            <label id="customer" style="color:#bababa">{{$so_approvel->cust_name ?? '' }}</label>
                            <input type="hidden" name="customer_ID" id="customer_ID" value="{{$so_approvel->cust_id ?? '' }}">
                            <input type="hidden" name="cust_stn" id="cust_stn">
                        </div>
                        <div class="col-lg-1 col-md-1">
                            <label>Balance</label><br>
                            <label id="customer_balance_label" style="color:#bababa">{{$so_approvel->cust_balance ?? '' }}</label>
                            <input type="hidden" name="customer_balance" id="customer_balance"> 
                        </div>    
                        <div class="col-lg-2 col-md-2">
                            <button class="btn btn-light mt-4" type="button">Ledger</button>
                            <label for="">(Ledger 2 months)</label>
                        </div> 
                        <div class="col-lg-3 col-md-3">
                            <label>Avg. Recovery Days</label><br>
                            <label></label> 
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <label>Credit Days</label><br>
                            <label id="credit_days_label" style="color:#bababa">{{$so_approvel->credit_days ?? ''}}</label>
                            <input type="hidden" name="credit_days" id="credit_days">   
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <label>Credit Amount</label><br> 
                            <label id="credit_amount_label" style="color:#bababa">{{$so_approvel->credit_amount ?? ''}}</label>
                            <input type="hidden" name="credit_amount" id="credit_amount">  
                        </div>   
                    </div>  

                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <label for="">Address</label><br>
                            <label id="cust_address_label" style="color:#bababa">{{$so_approvel->cust_address ?? ''}}</label> 
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <label for="">Town</label><br>
                            <label id="cust_location_label" style="color:#bababa">{{$so_approvel->cust_town ?? ''}}</label> 
                            <input type="hidden" name="town" id="cust_location" value="{{$so_approvel->cust_town ?? ''}}">
                        </div>       
                    </div>                   
                    <div class="row">
                        <div class="table-responsive">
                            <table id="voucher" class="table table-striped m-b-0">
                                <thead>
                                    <tr class="bg-purple">
                                        <th class="table_header ">Product</th>
                                        <th class="table_header">Unit</th>
                                        <th class="table_header table_header_100">Stock(All Warehouses)</th>
                                        <th class="table_header table_header_100">Qty Ordered</th>
                                        <th class="table_header table_header_100">Qty Approved</th>
                                        <th class="table_header table_header_100">Rate Code</th>
                                        <th class="table_header table_header_100">Base Rate</th>
                                        <th class="table_header table_header_100">Sals Tax</th>
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
                                        <th class="table_header table_header_100">Value After Discount</th>
                                        <th class="table_header table_header_100">Adv Payment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                $count = (isset($so_detail_approvel))?count($so_detail_approvel):1;
                               
                                for($i=1;$i<=$count; $i++)
                                { 
                                    if(isset($so_detail_approvel))
                                    {
                                        $lineItem = $so_detail_approvel[($i-1)];
                                    }                                    
                                    @endphp
                                    @if(isset($lineItem->rate_code_id) && $lineItem->rate_code_id==-1)
                                        <tr id="row{{$i}}" class="rowData skuRow">
                                                <td>
                                                    <!-- Product -->
                                                    <input type="text"  class="form-control prod_field" value="{{ $lineItem->prod_name ?? ''  }}" readonly>
                                                    <input type="hidden" name="item_ID[]" id="item{{$i}}_ID" value="{{ $lineItem->prod_id ?? ''  }}" class="product">
                                                </td>
                                                <td>
                                                    <!-- unit -->
                                                    <input type="text"  name="unit[]"  value="{{ $lineItem->unit ?? ''  }}" class="form-control unit-field">
                                                </td>
                                                <td>
                                                    <!-- stock(all warehouses) -->
                                                    <input type="number"  name="qty_stock[]" value="{{ $lineItem->qty_stock ?? ''  }}" class="form-control qty" readonly>
                                                </td>
                                                <td>
                                                    <!-- qty ordered-->
                                                    <input type="number"  name="qty_ordered[]"  value="{{ $lineItem->qty_ordered ?? ''  }}" class="form-control qty" readonly>  
                                                </td>
                                                <td>
                                                    <!-- qty approved -->
                                                    <input type="number"  name="qty_approved[]"   value="{{ $lineItem->qty_approved ?? ''  }}" class="form-control qty qty-num" required>
                                                </td>
                                                <td>
                                                    <!-- rate code -->
                                                    <input type="text"  class="form-control">
                                                    <input type="hidden" name="rate_code_id[]"  value="{{ $lineItem->rate_code_id ?? ''  }}">
                                                </td>
                                                <td>
                                                    <!-- base rate -->
                                                    <input type="number"   name="base_rate[]"  class="form-control qty" value="0" readonly>
                                                </td>
                                                <td>
                                                    <!-- sales tax  --> 
                                                    <input type="number"  name="sales_tax[]" id="sales_tax{{$i}}" class="form-control qty sales_tax_num"  value="{{ $lineItem->sales_tax ?? ''  }}" readonly>
                                                </td>
                                                <td>
                                                    <!-- fed --> 
                                                    <input type="number"  name="fed[]"  class="form-control qty"  value="0"  readonly>
                                                </td>
                                                <td>
                                                    <!-- furthr tax -->
                                                    <input type="number"   name="further_tax[]"  class="form-control qty"   value="0"  readonly> 
                                                </td>
                                                <td>
                                                    <!-- inclusvie value -->
                                                    <input type="number"   name="inclusive_value[]"  class="form-control qty" value="0" readonly>
                                                </td>
                                                <td>
                                                    <!-- lu -->
                                                    <input type="number"  name="lu[]"  class="form-control qty" value="0" readonly>
                                                </td>
                                                <td>
                                                    <!-- freight -->
                                                    <input type="number" name="freight[]"  class="form-control qty" value="0" readonly>
                                                </td>
                                                <td>
                                                    <!-- other discount  -->
                                                    <input type="number" name="other_discount[]"  class="form-control qty" value="0" readonly>
                                                </td>
                                                <td>
                                                    <!-- rate -->
                                                    <input type="number" name="rate[]" class="form-control qty"  value="0" readonly>
                                                </td>
                                                <td>
                                                    <!-- fixed margin -->
                                                    <input type="number"   name="fixed_margin[]"  class="form-control qty" value="0" readonly>
                                                </td>
                                                <td>
                                                    <!-- amount_before discount -->
                                                    <input type="number"   name="amount_before_discount[]"  class="form-control qty" value="0" readonly>
                                                </td>
                                                <td>
                                                    <!-- trade offer -->
                                                    <input type="number" name="trade_offer[]" value="0" class="form-control qty" readonly>
                                                </td>
                                                <td>
                                                    <!-- discount  -->
                                                    <input type="number"  name="discount[]"  value="0" class="form-control qty" readonly>
                                                </td>
                                                <td>
                                                    <!-- amount after discounts  -->
                                                    <input type="number"  name="amount_after_discount[]" value="0" class="form-control qty" readonly>
                                                </td>
                                                <td>
                                                    <!-- Adv Payment -->
                                                    <input type="number"   name="adv_payment[]"  value="0"  class="form-control qty" readonly>
                                                </td>
                                            </tr>
                                    @else
                                        <tr id="row{{$i}}" class="rowData">
                                            <td>
                                                <!-- Product -->
                                                <input type="text"  class="form-control prod_field" value="{{ $lineItem->prod_name ?? ''  }}" readonly>
                                                <input type="hidden" name="item_ID[]" id="item{{$i}}_ID" value="{{ $lineItem->prod_id ?? ''  }}" class="product">
                                            </td>
                                            <td>
                                                <!-- unit -->
                                                <input type="text"  name="unit[]" id="unit{{$i}}" value="{{ $lineItem->unit ?? ''  }}" class="form-control unit-field">
                                            </td>
                                            <td>
                                                <!-- stock(all warehouses) -->
                                                <input type="number"  name="qty_stock[]" id="qty_stock{{$i}}" value="{{ $lineItem->qty_stock ?? ''  }}" class="form-control qty" readonly>
                                            </td>
                                            <td>
                                                <!-- qty ordered-->
                                                <input type="number"  name="qty_ordered[]" id="qty_ordered{{$i}}" value="{{ $lineItem->qty_ordered ?? ''  }}" class="form-control qty" readonly>  
                                            </td>
                                            <td>
                                                <!-- qty approved -->
                                                <input type="number" step="any" name="qty_approved[]" id="qty_approved{{$i}}" onblur="gridCalculate({{$i}})"  value="{{ $lineItem->qty_approved ?? ''  }}" class="form-control qty qty-num" required>
                                                <!-- error msg -->
                                                <label id="qty_error{{$i}}" class="text-danger"></label>
                                            </td>
                                            <td>
                                                <!-- rate code -->
                                                <select name="rate_code_id[]" id="rate_code{{$i}}" onchange="setRateCode({{$i}})"  class="form-control show-tick ms select2 rate_code" style="width:120px">
                                                    <option  value="">Select Rate Code</option>
                                                    @if(isset($rate_codes))
                                                    @foreach($rate_codes as $rate)
                                                    <option value="{{$rate->id}}" {{ ( $rate->id == ( $lineItem->rate_code_id??'')) ? 'selected' : '' }}>{{ $rate->rate_num}}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </td>
                                            <td>
                                                <!-- base rate -->
                                                <input type="number" step="any"  name="base_rate[]" id="base_rate{{$i}}" class="form-control qty" value="{{ $lineItem->base_rate ?? ''  }}" readonly>
                                                <input type="hidden" id="base_rate_hidden{{$i}}" value="{{ $lineItem->base_rate ?? ''  }}">
                                            </td>
                                            <td>
                                                <!-- sales tax  --> 
                                                <input type="number" step="any"  name="sales_tax[]" id="sales_tax{{$i}}" class="form-control qty sales_tax_num"  value="{{ $lineItem->sales_tax ?? ''  }}" readonly>
                                                <input type="hidden" id="sales_tax_hidden{{$i}}" value="{{ $lineItem->sales_tax ?? ''  }}">
                                            </td>
                                            <td>
                                                <!-- fed --> 
                                                <input type="number" step="any"  name="fed[]" id="fed{{$i}}" class="form-control qty fed_tax_num"  value="{{ $lineItem->fed ?? ''  }}"  readonly>
                                                <input type="hidden"  id="fed_hidden{{$i}}" value="{{ $lineItem->fed?? ''  }}">
                                            </td>
                                            <td>
                                                <!-- furthr tax -->
                                                <input type="number" step="any"  name="further_tax[]" id="further_tax{{$i}}" class="form-control qty further_tax_num"   value="{{ $lineItem->further_tax ?? ''  }}"  readonly>
                                                <input type="hidden" id="further_tax_hidden{{$i}}"   value="{{ $lineItem->further_tax ?? ''  }}">
                                            </td>
                                            <td>
                                                <!-- inclusvie value -->
                                                <input type="number" step="any"  name="inclusive_value[]" id="inclusive_value{{$i}}" class="form-control qty inclusive_num" value="{{ $lineItem->inclusive_value ?? ''  }}" readonly>
                                            </td>
                                            <td>
                                                <!-- lu -->
                                                <input type="number" step="any"  name="lu[]" id="lu{{$i}}" class="form-control qty" value="{{ $lineItem->lu ?? ''  }}" readonly>
                                                <input type="hidden"  id="lu_hidden{{$i}}"  value="{{ $lineItem->lu ?? ''  }}">
                                            </td>
                                            <td>
                                                <!-- freight -->
                                                <input type="number" step="any"  name="freight[]" id="freight{{$i}}" class="form-control qty" value="{{ $lineItem->freight ?? ''  }}" readonly>
                                                <input type="hidden"  id="freight_hidden{{$i}}"  value="{{ $lineItem->freight ?? ''  }}">
                                            </td>
                                            <td>
                                                <!-- other discount  -->
                                                <input type="number" step="any"  name="other_discount[]" id="other_discount{{$i}}" class="form-control qty" value="{{ $lineItem->other_discount ?? ''  }}" readonly>
                                                <input type="hidden"  id="other_discount_hidden{{$i}}"  value="{{ $lineItem->other_discount ?? ''  }}">
                                            </td>
                                            <td>
                                                <!-- rate -->
                                                <input type="number" step="any"  name="rate[]" id="rate{{$i}}" class="form-control qty"  value="{{ $lineItem->rate ?? ''  }}" readonly>
                                            </td>
                                            <td>
                                                <!-- fixed margin -->
                                                <input type="number" step="any"  name="fixed_margin[]" id="fixed_margin{{$i}}" class="form-control qty" value="{{ $lineItem->fixed_margin ?? ''  }}" readonly>
                                                <input type="hidden" id="fixed_margin_hidden{{$i}}" value="{{ $lineItem->fixed_margin ?? ''  }}">
                                            </td>
                                            <td>
                                                <!-- amount_before discount -->
                                                <input type="number" step="any"  name="amount_before_discount[]" id="amount_before_discount{{$i}}" class="form-control qty" value="{{ $lineItem->amount_before_discount?? ''  }}" readonly>
                                            </td>
                                            <td>
                                                <!-- trade offer -->
                                                <input type="number" step="any"  name="trade_offer[]" id="trade_offer{{$i}}" value="{{ $lineItem->trade_offer?? ''  }}" class="form-control qty" readonly>
                                            </td>
                                            <td>
                                                <!-- discount  -->
                                                <input type="number" step="any"  name="discount[]" id="discount{{$i}}" value="{{ $lineItem->discount?? ''  }}" class="form-control qty" readonly>
                                            </td>
                                            <td>
                                                <!-- amount after discounts  -->
                                                <input type="number" step="any"  name="amount_after_discount[]" id="amount_after_discount{{$i}}" value="{{ $lineItem->amount_after_discount?? ''  }}" class="form-control qty" readonly>
                                            </td>
                                            <td>
                                                <!-- Adv Payment -->
                                                <input type="number" step="any"  name="adv_payment[]" id="adv_payment{{$i}}" value="{{ $lineItem->adv_payment?? ''  }}"  class="form-control qty" readonly>
                                                <input type="hidden" id="adv_payment_hidden{{$i}}" value="{{ $lineItem->adv_payment?? ''  }}">
                                            </td>
                                        </tr>
                                    @endif
                                    @php     
                                    } 
                                @endphp
                                </tbody>  
                                <script>                                    
                                    rowId = {{$i-1}};
                                </script>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="ml-auto">
                            <button class="btn btn-raised btn-success waves-effect" id="generate-bill" type="button">Apply Discounts & T/O</button>
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
                            <label id="total_trade_offer_label" class="trade_offer_label label-col">{{$sale_order->trade_offer ?? ''  }}</label>
                        </div>
                        <div class="col-sm-2">
                            <label for="">Net Amount</label><br>
                            <label id="total_net_amount_label" class="label-col">0.00</label>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label for="fiscal_year">Note</label>
                            <div class="form-group" id="note">
                                <textarea name="note" id="note" maxlength="120" rows="4" class="form-control">{{$so_approvel->note ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label for="fiscal_year">Attachment</label>
                            <br>
                            @if($attachmentRecord ?? '')
                                <table>
                                @foreach($attachmentRecord as $attachment)
                                <tr>
                                    <td><button  type="button" class="btn btn-danger btn-sm" id="{{ $attachment->file }}" onclick="delattach(attachmentURL,this.id,token)"><i class="zmdi zmdi-delete"></i></button></td>
                                    <td><a target="_blank" href="{{asset('assets/attachments/'. $attachment->file)}}" download id="attachment">{{ $attachment->file }}</a></td>
                                </tr>
                                @endforeach
                                </table>
                            @else
                            <td id="img_box">
                                <a target="_blank"  download id="get-attachment"> <img  src="" id="img" width="150" height="150" style="display:none"> </a>
                            </td>
                            @endif
                            <input name="file"  type="file"  class="dropify"> 
                            <input type="hidden" name="file" id="file">
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

    //this work when we select sale order number
    $('#sale_order_no').on('change',function(){
        $('.rowData').html('');
        rowId=0;
        var so_id=$(this).val();
        var url= "{{ url('Sales_Fmcg/SaleOrderDetail') }}";
        img_path="{{asset('public/assets/attachments/')}}";
        console.log(img_path);
        $.post(url,{so_id:so_id, _token:token},function(data){
            //get rate code 
            data.map(function(val,i){
                rowId++;
                //this function helps to get rate code of customer and apply previos rate which is set on sale order time.
                getRateCode(url,val.cust_id,rowId,val.rate_code_id);
                if(val.rate_code_id==-1)
                {
                    // **********************these are the rows of free sku **************************************************

                    var rw='<tr id="row'+rowId+'" class="rowData skuRow">'+
                        '<td class="text-center">'+
                            //product
                            '<input type="text"  id="item'+rowId+'" class="form-control prod_field" value="'+val.prod_name+'" readonly>'+
                            '<input type="hidden" name="item_ID[]" id="item'+rowId+'_ID" value="'+val.prod_id+'" class="product">'+
                        '</td>'+
                        '<td>'+
                            //unit
                            '<input type="text" name="unit[]" id="unit'+rowId+'" class="form-control unit-field" value="'+val.unit+'" readonly>'+
                        '</td>'+
                        '<td>'+
                            //stock al warehouse
                            '<input type="number" step="any" name="qty_stock[]" id="qty_stock'+rowId+'" class="form-control qty"  value="'+val.qty_stock+'" readonly>'+
                        '</td>'+
                        '<td>'+
                            //qty ordered
                            '<input type="number" step="any" name="qty_ordered[]" id="qty_ordered'+rowId+'"  value="'+val.qty_ordered+'" class="form-control qty qty-order-num" readonly>'+
                        '</td>'+
                        '<td>'+
                            //qty approved
                            '<input type="number" step="any" name="qty_approved[]" id="qty_approved'+rowId+'" class="form-control qty-num qty" readonly>'+
                        '</td>'+
                        '<td>'+
                            //rate Code
                            '<input type="text" class="form-control">'+
                            '<input type="hidden" name="rate_code_id[]" value="'+val.rate_code_id+'">'+
                        '</td>'+
                        '<td>'+
                            //base rate
                            '<input type="number" step="any"  class="form-control qty" name="base_rate[]" id="base_rate'+rowId+'"  value="'+val.base_rate+'" readonly>'+
                            '<input type="hidden"  id="base_rate_hidden'+rowId+'"  value="'+val.base_rate+'">'+
                        '</td>'+
                        '<td>'+
                            //sales tax 
                            '<input type="number" step="any"  name="sales_tax[]" id="sales_tax'+rowId+'" class="form-control qty sales_tax_num"  value="'+val.sales_tax+'" readonly>'+
                            '<input type="hidden" id="sales_tax_hidden'+rowId+'"  value="'+val.sales_tax+'">'+
                        '</td>'+
                        '<td>'+
                            //fed 
                            '<input type="number" step="any"  name="fed[]" id="fed'+rowId+'" class="form-control qty"  value="'+val.fed+'" readonly>'+
                            '<input type="hidden"  id="fed_hidden'+rowId+'"  value="'+val.fed+'">'+
                        '</td>'+
                        '<td>'+
                            //furthr tax 
                            '<input type="number" step="any"  name="further_tax[]" id="further_tax'+rowId+'" class="form-control qty"  value="'+val.further_tax+'" readonly>'+
                            '<input type="hidden" id="further_tax_hidden'+rowId+'"  value="'+val.further_tax+'">'+
                        '</td>'+
                        '<td>'+
                            //inclusvie value
                            '<input type="number" step="any"  name="inclusive_value[]" id="inclusive_value'+rowId+'" class="form-control qty"  value="'+val.inclusive_value+'" readonly>'+
                        '</td>'+
                        '<td>'+
                            //lu
                            '<input type="number" step="any"  name="lu[]" id="lu'+rowId+'" class="form-control qty"  value="'+val.lu+'" readonly>'+
                            '<input type="hidden"  id="lu_hidden'+rowId+'"  value="'+val.lu+'">'+
                        '</td>'+
                        '<td>'+
                            //freight
                            '<input type="number" step="any"  name="freight[]" id="freight'+rowId+'" class="form-control qty"  value="'+val.freight+'" readonly>'+
                            '<input type="hidden"  id="freight_hidden'+rowId+'"  value="'+val.freight+'">'+
                        '</td>'+
                        '<td>'+
                            //other discount
                            '<input type="number" step="any"  name="other_discount[]" id="other_discount'+rowId+'" class="form-control qty"  value="'+val.other_discount+'" readonly>'+
                            '<input type="hidden"  id="other_discount_hidden'+rowId+'"  value="'+val.other_discount+'">'+
                        '</td>'+
                        '<td>'+
                            //rate
                            '<input type="number" step="any"  name="rate[]" id="rate'+rowId+'" class="form-control qty"  value="'+val.rate+'" readonly>'+
                        '</td>'+
                        '<td>'+
                            //fixed margin
                            '<input type="number" step="any"  name="fixed_margin[]" id="fixed_margin'+rowId+'" class="form-control qty"  value="'+val.fixed_margin+'" readonly>'+
                            '<input type="hidden" id="fixed_margin_hidden'+rowId+'" value="'+val.fixed_margin+'">'+
                        '</td>'+
                        '<td>'+
                            //amount_before discount
                            '<input type="number" step="any"  name="amount_before_discount[]" id="amount_before_discount'+rowId+'" class="form-control qty"  value="'+val.amount_before_discount+'" readonly>'+
                        '</td>'+
                        '<td>'+
                            //trade offer
                            '<input type="number" step="any"  name="trade_offer[]" id="trade_offer'+rowId+'" value="0.00" class="form-control qty" readonly>'+
                        '</td>'+
                        '<td>'+
                            //discount
                            '<input type="number" step="any"  name="discount[]" id="discount'+rowId+'" value="'+val.discount+'" class="form-control qty" readonly>'+
                        '</td>'+
                        '<td>'+
                            //amount after discounts
                            '<input type="number" step="any"  name="amount_after_discount[]" id="amount_after_discount'+rowId+'" value="'+val.amount_after_discount+'" class="form-control qty" readonly>'+
                        '</td>'+
                        '<td>'+
                            //Adv Payment
                            '<input type="number" step="any"  name="adv_payment[]" id="adv_payment'+rowId+'"  class="form-control qty" readonly>'+
                            '<input type="hidden" id="adv_payment_hidden'+rowId+'" value="'+val.adv_payment+'">'+
                        '</td>'+
                    '</tr>';
                    $('#voucher').append(rw);
                }
                else
                {
                    // ***************** other products *************************************
                    var rw='<tr id="row'+rowId+'" class="rowData">'+
                        '<td class="text-center">'+
                            //product
                            '<input type="text"  id="item'+rowId+'" class="form-control prod_field" value="'+val.prod_name+'" readonly>'+
                            '<input type="hidden" name="item_ID[]" id="item'+rowId+'_ID" value="'+val.prod_id+'" class="product">'+
                        '</td>'+
                        '<td>'+
                            //unit
                            '<input type="text" name="unit[]" id="unit'+rowId+'" class="form-control unit-field" value="'+val.unit+'" readonly>'+
                        '</td>'+
                        '<td>'+
                            //stock al warehouse
                            '<input type="number" step="any" name="qty_stock[]" id="qty_stock'+rowId+'" class="form-control qty"  value="'+val.qty_stock+'" readonly>'+
                        '</td>'+
                        '<td>'+
                            //qty ordered
                            '<input type="number" step="any" name="qty_ordered[]" id="qty_ordered'+rowId+'"  value="'+val.qty_ordered+'" class="form-control qty qty-order-num" readonly>'+
                        '</td>'+
                        '<td>'+
                            //qty approved
                            '<input type="number" step="any" name="qty_approved[]" id="qty_approved'+rowId+'" class="form-control qty-num qty" onblur="gridCalculate('+rowId+');checkQty('+rowId+')" required>'+
                            //error msg
                            '<label id="qty_error'+rowId+'" class="text-danger"></label>'+
                        '</td>'+
                        '<td>'+
                            //rate Code
                            '<select name="rate_code_id[]" id="rate_code'+rowId+'" onchange="setRateCode('+rowId+')"  class="form-control show-tick ms select2 rate_code" style="width:120px">'+
                                '<option  value="">Select Rate Code</option>'+
                            '</select>'+
                        '</td>'+
                        '<td>'+
                            //base rate
                            '<input type="number" step="any"  class="form-control qty" name="base_rate[]" id="base_rate'+rowId+'"  value="'+val.base_rate+'" readonly>'+
                            '<input type="hidden"  id="base_rate_hidden'+rowId+'"  value="'+val.base_rate+'">'+
                        '</td>'+
                        '<td>'+
                            //sales tax 
                            '<input type="number" step="any"  name="sales_tax[]" id="sales_tax'+rowId+'" class="form-control qty sales_tax_num"  value="'+val.sales_tax+'" readonly>'+
                            '<input type="hidden" id="sales_tax_hidden'+rowId+'"  value="'+val.sales_tax+'">'+
                        '</td>'+
                        '<td>'+
                            //fed 
                            '<input type="number" step="any"  name="fed[]" id="fed'+rowId+'" class="form-control qty fed_tax_num"  value="'+val.fed+'" readonly>'+
                            '<input type="hidden"  id="fed_hidden'+rowId+'"  value="'+val.fed+'">'+
                        '</td>'+
                        '<td>'+
                            //furthr tax 
                            '<input type="number" step="any"  name="further_tax[]" id="further_tax'+rowId+'" class="form-control qty further_tax_num"  value="'+val.further_tax+'" readonly>'+
                            '<input type="hidden" id="further_tax_hidden'+rowId+'"  value="'+val.further_tax+'">'+
                        '</td>'+
                        '<td>'+
                            //inclusvie value
                            '<input type="number" step="any"  name="inclusive_value[]" id="inclusive_value'+rowId+'" class="form-control qty inclusive_num"  value="'+val.inclusive_value+'" readonly>'+
                        '</td>'+
                        '<td>'+
                            //lu
                            '<input type="number" step="any"  name="lu[]" id="lu'+rowId+'" class="form-control qty"  value="'+val.lu+'" readonly>'+
                            '<input type="hidden"  id="lu_hidden'+rowId+'"  value="'+val.lu+'">'+
                        '</td>'+
                        '<td>'+
                            //freight
                            '<input type="number" step="any"  name="freight[]" id="freight'+rowId+'" class="form-control qty"  value="'+val.freight+'" readonly>'+
                            '<input type="hidden"  id="freight_hidden'+rowId+'"  value="'+val.freight+'">'+
                        '</td>'+
                        '<td>'+
                            //other discount
                            '<input type="number" step="any"  name="other_discount[]" id="other_discount'+rowId+'" class="form-control qty"  value="'+val.other_discount+'" readonly>'+
                            '<input type="hidden"  id="other_discount_hidden'+rowId+'"  value="'+val.other_discount+'">'+
                        '</td>'+
                        '<td>'+
                            //rate
                            '<input type="number" step="any"  name="rate[]" id="rate'+rowId+'" class="form-control qty"  value="'+val.rate+'" readonly>'+
                        '</td>'+
                        '<td>'+
                            //fixed margin
                            '<input type="number" step="any"  name="fixed_margin[]" id="fixed_margin'+rowId+'" class="form-control qty"  value="'+val.fixed_margin+'" readonly>'+
                            '<input type="hidden" id="fixed_margin_hidden'+rowId+'" value="'+val.fixed_margin+'">'+
                        '</td>'+
                        '<td>'+
                            //amount_before discount
                            '<input type="number" step="any"  name="amount_before_discount[]" id="amount_before_discount'+rowId+'" class="form-control qty"  value="'+val.amount_before_discount+'" readonly>'+
                        '</td>'+
                        '<td>'+
                            //trade offer
                            '<input type="number" step="any"  name="trade_offer[]" id="trade_offer'+rowId+'" value="0.00" class="form-control qty trade-offer-num" readonly>'+
                        '</td>'+
                        '<td>'+
                            //discount
                            '<input type="number" step="any"  name="discount[]" id="discount'+rowId+'" value="'+val.discount+'" class="form-control qty" readonly>'+
                        '</td>'+
                        '<td>'+
                            //amount after discounts
                            '<input type="number" step="any"  name="amount_after_discount[]" id="amount_after_discount'+rowId+'" value="'+val.amount_after_discount+'" class="form-control qty" readonly>'+
                        '</td>'+
                        '<td>'+
                            //Adv Payment
                            '<input type="number" step="any"  name="adv_payment[]" id="adv_payment'+rowId+'"  class="form-control qty" readonly>'+
                            '<input type="hidden" id="adv_payment_hidden'+rowId+'" value="'+val.adv_payment+'">'+
                        '</td>'+
                    '</tr>';
                    $('#voucher').append(rw);
                    $('#sale_order_date').html(val.date);
                    $('#sale_order_by').html(val.user_name);
                    $('#customer').html(val.cust_name);
                    $('#customer_ID').val(val.cust_id);
                    $('#cust_stn').val(val.stn ? val.stn : 0);
                    $('#credit_days_label').html(val.credit_limit);    
                    $('#credit_days').val(val.credit_limit); 
                    $('#credit_amount_label').html(val.credit_amount);  
                    $('#credit_amount').val(val.credit_amount);  
                    $('#cust_address_label').html(val.address);
                    $('#cust_location_label').html(val.location);
                    $('#cust_location').val(val.location);
                    $('#customer_balance_label').html(val.cust_balance);
                    $('#customer_balance').val(val.cust_balance);
                    $('textarea#note').val(val.remarks);
                    if(val.file !== null)
                    {
                        $('#get-attachment').attr("href",img_path+'/'+val.file); 
                        $("#img").attr("src",img_path+'/'+val.file); 
                        $("#img").show();
                        $('#file').val(val.file);
                    }
                    totalValues();
                }
            }); 
            
        });
       
    });

    function getRateCode(url,id,rowId,rate_code_id)
    {
        $.post(url,{coa_id:id,_token:token},function(data){
            data.map(function(list,i){
                //here we add all latest and old rate code of releted user into rate-code selection box
                var option='<option value="'+list.id+'">'+list.rate_num+'</option>';
                $('#rate_code'+rowId).append(option);
                $('#rate_code'+rowId+' option[value='+rate_code_id+']').prop("selected", true);
            });
        }); 
    }
});

//function apply trade offer if exist and return data
$('#generate-bill').on('click',function(){
    tradeOfferURL = "{{ url('getApprovelTradeOffer') }}";
    total_discount=0;
    total_trade=0;
    total_net_amount=0;
    cust_id=$('#customer_ID').val();
    cust_location=$('#cust_location').val();
    date=$('#date').val();
    
    /*
     * Remove Free row Class     
     */
    $('.skuRow').remove();
    $('.freeRows').remove();
    $('.product').each(function(i){
        i++
        value_after_trade=0;
        item_id=$('#item'+i+'_ID').val();
        qty=getNum($('#qty_approved'+i).val());
        rate=getNum($('#amount_before_discount'+i).val());
        $.post(tradeOfferURL,{item_id:item_id,cust_id:cust_id,date:date,qty:qty,rate:rate,cust_location:cust_location, _token:token},function(data){
            $('#trade_offer'+i).val(data.discount_amount);
            value_after_trade=$('#amount_before_discount'+i).val() - data.discount_amount;
            total_trade+=data.discount_amount;
            $('#total_trade_offer_label').html(total_trade);
            if(data.free_qty>0)
            {
                rowId++;
                var rw='<tr id="row'+rowId+'" class="rowData freeRows">'+
                    '<td>'+
                        '<input type="text" name="item[]"  class="form-control"  value="'+data.free_sku+'">'+
                        '<input type="hidden" name="item_ID[]"  class="product"  value="'+data.free_sku+'">'+
                    '</td>'+
                    '<td>'+
                        //unit
                        '<input type="text" name="unit[]"   class="form-control" value="TRAY" readonly>'+
                    '</td>'+
                    '<td>'+
                        '<input type="number" name="qty_stock[]"   class="form-control qty" readonly>'+
                    '</td>'+
                    '<td>'+
                        //qty ordered                        
                        '<input type="number"  name="qty_ordered[]"  class="form-control qty qty-num" readonly>'+
                    '</td>'+
                    '<td>'+
                        //qty approved
                        '<input type="number" name="qty_approved[]"  value="'+data.free_qty+'"  class="form-control qty" readonly>'+
                    '</td>'+
                    '<td>'+
                        //rate Code
                        '<input type="text"  class="form-control">'+
                        '<input type="hidden"  name="rate_code_id[]" class="form-control" value="-1">'+
                    '</td>'+
                    '<td>'+
                        //base rate
                        '<input type="number"  name="base_rate[]"    value="0" class="form-control qty" readonly>'+
                    '</td>'+
                    '<td>'+
                        //Sales tax
                        '<input type="number"  name="sales_tax[]"   value="0" class="form-control qty" readonly>'+
                    '</td>'+
                    '<td>'+
                        //FED
                        '<input type="number"  name="fed[]" value="0"  class="form-control qty" readonly>'+
                    '</td>'+
                    '<td>'+
                        //Further tax
                        '<input type="number"  name="further_tax[]"    value="0" class="form-control qty" readonly>'+
                    '</td>'+
                    '<td>'+
                        //inclusive value
                        '<input type="number"  name="inclusive_value[]"   value="0"  class="form-control qty" readonly>'+
                    '</td>'+
                    '<td>'+
                        //L/U
                        '<input type="number"  name="lu[]"   value="0" class="form-control qty" readonly>'+
                    '</td>'+
                    '<td>'+
                        //Freight
                        '<input type="number" name="freight[]"   value="0" class="form-control qty" readonly>'+
                    '</td>'+
                    '<td>'+
                        //Other
                        '<input type="number"  name="other_discount[]" value="0" class="form-control qty" readonly>'+
                    '</td>'+
                    '<td>'+
                        //Rate
                        '<input type="number"  name="rate[]"  value="0" class="form-control qty" readonly>'+
                    '</td>'+
                    '<td>'+
                        //Fixed margin
                        '<input type="number"  name="fixed_margin[]"  value="0" class="form-control qty" readonly>'+
                    '</td>'+
                    '<td>'+
                        //value befor discount 
                        '<input type="number"  name="amount_before_discount[]"   value="0" class="form-control qty" readonly>'+
                    '</td>'+
                    '<td>'+
                        //Trade offer
                        '<input type="number"  name="trade_offer[]"  value="0" class="form-control qty" readonly>'+
                    '</td>'+
                    '<td>'+
                        //discounts
                        '<input type="number" name="discount[]" value="0" class="form-control qty" readonly>'+
                    '</td>'+
                    '<td>'+
                        //value after discount
                        '<input type="number"  name="amount_after_discount[]" value="0" class="form-control qty" readonly>'+
                    '</td>'+
                    '<td>'+
                        //adv payment
                        '<input type="number"  name="adv_payment[]"   value="0" class="form-control qty" readonly>'+
                    '</td>'+    
            '</tr>';
            $('#voucher').append(rw);  
            }
            customerDiscount(i,value_after_trade);
        });
        
    });
    totalValues();
});

function customerDiscount(row,valu_after_trade)
{
   
    customer_discounts=0;
    net_Amount_after_cust_discount=0;
    $.post("{{url('customerDiscount') }}",{date:$('#date').val(),coa_id:$('#customer_ID').val(),_token : "{{ csrf_token()}}"},function(data){
            if(data==0)
            {
                customer_discounts=0;
                $('#discount'+row).val(customer_discounts);
                net_Amount_after_cust_discount=valu_after_trade - customer_discounts;
                $('#amount_after_discount'+row).val(net_Amount_after_cust_discount);
            }
            else
            {
                
                if(parseFloat(data.amount_discount_percent)>0)
                {  
                   customer_discounts=$('#amount_before_discount'+row).val() * data.amount_discount_percent / 100;
                    $('#discount'+row).val(customer_discounts);
                    net_Amount_after_cust_discount=valu_after_trade - customer_discounts;
                    $('#amount_after_discount'+row).val(net_Amount_after_cust_discount);
                }
                else if(parseFloat(data.qty_discount)>0 )
                {
                     customer_discounts=($('#qty_approved'+row).val() * data.qty_discount);
                    $('#discount'+row).val(customer_discounts); 
                    net_Amount_after_cust_discount= valu_after_trade -  customer_discounts;
                    $('#amount_after_discount'+row).val(net_Amount_after_cust_discount);
                    
                }
                /*
                if(data.amount_discount_percent==null)
                {  
                    customer_discounts=$('#qty_approved'+row).val() * data.qty_discount;
                    $('#discount'+row).val(customer_discounts); 
                    net_Amount_after_cust_discount= valu_after_trade -  customer_discounts;
                    $('#amount_after_discount'+row).val(net_Amount_after_cust_discount);
                }
                else(data.qty_discount==null)
                {
                    customer_discounts=$('#amount_before_discount'+row).val() * data.amount_discount_percent / 100;
                    $('#discount'+row).val(customer_discounts);
                    net_Amount_after_cust_discount=valu_after_trade - customer_discounts;
                    console.log('after trade and customer'+net_Amount_after_cust_discount);
                    $('#amount_after_discount'+row).val(net_Amount_after_cust_discount);
                }
             
                 */
            }

            if( $('#customer_balance').val() >  net_Amount_after_cust_discount)
            {
                const [adv_payment_discount,net_amount_after_adv_payment]=applyAdvPaymentDiscount(row, net_Amount_after_cust_discount);
                customer_discounts+=adv_payment_discount;
                net_Amount_after_cust_discount+=net_amount_after_adv_payment;
            }
            else
            {
                $('#adv_payment'+row).val(0);
            }
        total_discount+=customer_discounts;
        total_net_amount+=net_Amount_after_cust_discount
        $('#total_discount_label').html(total_discount);
        $('#total_net_amount_label').html(total_net_amount);
    });
    
}
function applyAdvPaymentDiscount(row,val_Aft_cus_dis)
{
    adv_payment_discount=val_Aft_cus_dis * $('#adv_payment_hidden'+row).val() / 100 ;
    net_Amount=val_Aft_cus_dis - adv_payment_discount;
    $('#amount_after_discount'+row).val(net_Amount);
    $('#adv_payment'+row).val(adv_payment_discount);
    return [adv_payment_discount,net_Amount];
}

function totalValues()
{
    $('#total_qty_label').html(sumAll('qty-num'));
    $('#total_sales_tax_label').html(sumAll('sales_tax_num'));
    $('#total_fed_tax_label').html(sumAll('fed_tax_num'));
    $('#total_further_tax_label').html(sumAll('further_tax_num'));
    $('#total_inclusive_value_label').html(sumAll('inclusive_num'));
    $('#total_before_discount_label').html(sumAll('before_discount_num'));
    $('#total_discount_label').html(sumAll('discount_num'));
    $('#total_net_amount_label').html(sumAll('net_amount_num'));
    var numItems = $('.product').length;
    console.log(numItems);
    $('#total_products_label').html(numItems);
    //$('#total_trade_offer_label').html(sumAll('trade-offer-num'));
}
</script>
<script>
    //this function perform row caculation like base rate,sales tax,fed and etc
    function gridCalculate(num)
    {
        qty_ordered=$('#qty_ordered'+num).val();
        qty_approved=$('#qty_approved'+num).val();
        $('#base_rate'+num).val($('#base_rate_hidden'+num).val() / qty_ordered * qty_approved);
        $('#sales_tax'+num).val($('#sales_tax_hidden'+num).val() / qty_ordered * qty_approved);
        $('#fed'+num).val($('#fed_hidden'+num).val() / qty_ordered * qty_approved);
        $('#further_tax'+num).val($('#further_tax_hidden'+num).val() / qty_ordered * qty_approved);
        $('#inclusive_value'+num).val(+$('#base_rate'+num).val() + +$('#sales_tax'+num).val() + +$('#fed'+num).val() + +$('#further_tax'+num).val());
        $('#lu'+num).val($('#lu_hidden'+num).val() / qty_ordered * qty_approved);
        $('#freight'+num).val($('#freight_hidden'+num).val() / qty_ordered * qty_approved);
        $('#other_discount'+num).val($('#other_discount_hidden'+num).val() / qty_ordered * qty_approved);
        $('#rate'+num).val($('#inclusive_value'+num).val() - $('#lu'+num).val() - $('#freight'+num).val() -  $('#other_discount'+num).val());
        $('#fixed_margin'+num).val($('#fixed_margin_hidden'+num).val() / qty_ordered * qty_approved);
        $('#amount_before_discount'+num).val($('#rate'+num).val() - $('#fixed_margin'+num).val());
        $('#amount_after_discount'+num).val($('#amount_before_discount'+num).val() - $('#discount'+num).val());
    }
    // this function cal if user select any rate code from selection box 
    function setRateCode(row)
    {
        var url="{{ url('applyRateCode') }}";
        $.post(url,{coa_id:$('#customer_ID').val(),prod_id:$('#item'+row+'_ID').val(),code_id:$('#rate_code'+row).val(),_token:token},function(data){
            console.log(data);
            setRowData(row,data);
            qtyRateTotal(row);

        });
    }
    //this function set value in their respective fields  in a  row
    function setRowData(row,data)
    {
        $('#base_rate_hidden'+row).val(data.base_rate);
        $('#sales_tax_hidden'+row).val(data.gst_tax_amount);
        $('#fed_hidden'+row).val(data.fed_tax_amount);
        $('#further_tax_hidden'+row).val(data.others_tax_amount*$('#cust_stn').val());
        $('#lu_hidden'+row).val(data.lu_margin_amount);
        $('#freight_hidden'+row).val(data.freight_amount);
        $('#other_discount_hidden'+row).val(data.others_margin_amount);
        $('#fixed_margin_hidden'+row).val(data.fix_margin_amount);
        $('#adv_payment_hidden'+row).val(data.advance_payment);
    }
    // this function wrk on the blur of qty order and rate code. qty is multiple with base rate,all taxes and all discounts
    function qtyRateTotal(row)
    {
        qty=$('#qty_approved'+row).val();
        if(qty>0)
        {
            $('#base_rate'+row).val(qty * $('#base_rate_hidden'+row).val());
            $('#sales_tax'+row).val(qty * $('#sales_tax_hidden'+row).val());
            $('#fed'+row).val(qty * $('#fed_hidden'+row).val());
            $('#further_tax'+row).val(qty * $('#further_tax_hidden'+row).val());
            $('#inclusive_value'+row).val(+$('#base_rate'+row).val() + +$('#sales_tax'+row).val() + +$('#fed'+row).val() + +$('#further_tax'+row).val());
            $('#lu'+row).val(qty * $('#lu_hidden'+row).val());
            $('#freight'+row).val(qty * $('#freight_hidden'+row).val());
            $('#other_discount'+row).val(qty * $('#other_discount_hidden'+row).val());
            $('#rate'+row).val($('#inclusive_value'+row).val() - $('#lu'+row).val() - $('#freight'+row).val() - $('#other_discount'+row).val());
            $('#fixed_margin'+row).val(qty * $('#fixed_margin_hidden'+row).val());
            $('#amount_before_discount'+row).val( $('#rate'+row).val() - $('#fixed_margin'+row).val());
        }
    }
    function checkQty(row)
    {
       
        if(parseFloat($('#qty_ordered'+row).val()) < parseFloat($('#qty_approved'+row).val()))
        {
            $('#qty_error'+row).html('qty Approved must be less than qty ordered');
            $('#save').hide();
        }
        else
        {
            $('#qty_error'+row).html('');
            $('#save').show();
        }
    }
</script>
@stop