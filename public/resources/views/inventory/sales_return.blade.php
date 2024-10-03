@extends('layout.master')
@section('title', 'Sales Return')
@section('parentPageTitle', 'Inventory')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')

<?php  use App\Libraries\appLib; ?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!--<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">-->
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
var customerURL = "{{ url('customerSearch') }}";
var token =  "{{ csrf_token()}}";
var ItemURL = "{{ url('itemSearch') }}";
var getItemDetailURL = "{{ url('getItemDetail') }}";
var TaxRateURL = "{{ url('getTaxRates') }}";
var rowId=1;
var attachmentURL = "{{ url('attachmentDelete') }}";

</script>
@stop
@section('content')

    <div class="row clearfix">
        <div class="card col-lg-12">
            
            <div class="body">
            <form method="post" action="{{url('Inventory/SalesReturn/Save')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" id="id" value="{{ $salesReturn->id ?? ''  }}">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">SRN #</label><br>
                            <label for="fiscal_year">{{ $salesReturn->month ?? '' }}-{{appLib::padingZero($salesReturn->number  ?? '')}}</label>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label>Date</label>
                            <div class="form-group">
                                <input type="date" name="date" id='vdate'  class="form-control" value="{{ $salesReturn->date ?? date('Y-m-d')  }}"  required>
                            </div>
                        </div>  
                        <div class="col-lg-3 col-md-6">
                            <label>D.O No#</label>
                            @if(isset($salesReturn))
                            <br>
                            <label>{{ $salesReturn->do_num ?? '' }}</label>
                            @else
                            <div class="form-group">
                                <select name="do_num" id="do_num" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                   <option  value="">Select Do No#</option>
                                    @foreach($do_numbers as $do_number)
                                    <option value="{{$do_number->id}}" {{ ($do_number->id == ( $igp->warehouse ??'')) ? 'selected' : '' }}>{{$do_number->doc_number}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                        </div>                      
                    </div>     
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                        <label>Date:</label><br>
                        <label id="do_date">{{ $salesReturn->do_date ?? '' }}</label>
                        </div>
                        <div class="col-lg-3 col-md-6">
                        <label>Customer:</label><br>
                        <label id="cust_name">{{ $salesReturn->cust_name ?? '' }}</label>
                        <input type="hidden" name="cust_coa_id" id="cust_coa_id">
                        </div>
                        <div class="col-lg-3 col-md-6">
                        <label>Warehouse:</label><br>
                        <label id="warehouse_name">{{ $salesReturn->warehouse_name ?? '' }}</label>
                        <input type="hidden" name="warehouse_id" id="warehouse_id" value="{{ $salesReturn->warehouse_id ?? '' }}">
                        </div>
                    </div>      
                    <div class="row mt-1">
                        <div class="table-responsive">
                            <table id="sales_return" class="table table-striped m-b-0">
                                <thead>
                                    <tr class="bg-purple">
                                        <th class="table_header ">Product</th>
                                        <th class="table_header">unit</th>  
                                        <th class="table_header table_header_100">Qty Order</th>
                                        <th class="table_header table_header_100">Qty Return</th>
                                        <th class="table_header table_header_100">Rate Code</th>
                                        <th class="table_header table_header_100">Base Rate</th>
                                        <th class="table_header table_header_100">Sales Tax</th>
                                        <th class="table_header table_header_100">FED</th>
                                        <th class="table_header table_header_100">Further Tax</th>
                                        <th class="table_header table_header_100">Inclusive Value</th>
                                        <th class="table_header table_header_100">L/U</th>
                                        <th class="table_header table_header_100">Freight</th>
                                        <th class="table_header table_header_100">Other</th>
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
                                $count = (isset($salesReturnDetail))?count($salesReturnDetail):1;
                               
                                for($i=1;$i<=$count; $i++)
                                { 
                                    
                                    if(isset($salesReturnDetail))
                                    {
                                        $lineItem = $salesReturnDetail[($i-1)];
                                        
                                    }                                    
                                    @endphp                               
                                    <tr id="row{{$i}}" class="rowData">
                                        <td>
                                            <label class="prod_field">{{ $lineItem->prod_name ?? ''  }}</label>
                                            <input type="hidden" name="item_ID[]"  value="{{ $lineItem->prod_id ?? ''  }}">
                                        </td>
                                        <td>
                                            <label>{{ $lineItem->unit ?? ''  }}</label>
                                            <input type="hidden" name="unit[]"  value="{{ $lineItem->unit ?? ''  }}">
                                            <input type="hidden" name="operator_value[]" value="{{ $lineItem->operator_value ?? ''  }}">
                                        </td>
                                        <td class="px-2 qty">
                                            <label>{{ $lineItem->qty_issue ?? ''  }}</label>
                                            <input type="hidden" name="qty_issue[]" id="qty_issue{{$i}}"  value="{{  $lineItem->qty_issue ?? ''  }}">
                                        </td>
                                        <td>
                                            <input type="number" name="qty_return[]" id="qty_return{{$i}}" class="form-control qty" value="{{  $lineItem->qty_return ?? ''  }}" onblur="checkQty({{$i}})">
                                            <label id="qty_error{{$i}}" class="text-danger"></label>
                                        </td>
                                        <td>
                                            <label>{{$lineItem->rate_code_num ?? ''  }}</label>
                                            <input type="hidden" name="rate_code_id[]" value="{{ $lineItem->rate_code_id ?? ''  }}">
                                        </td>
                                        <td class="px-2 qty">
                                            <label>{{ $lineItem->base_rate ?? ''  }}</label>
                                            <input type="hidden" name="base_rate[]"  value="{{ $lineItem->base_rate ?? ''  }}">
                                        </td>
                                        <td class="px-2 qty">
                                            <label>{{ $lineItem->sales_tax ?? ''  }}</label>
                                            <input type="hidden" name="sales_tax[]"  value="{{ $lineItem->sales_tax ?? ''  }}">
                                        </td>
                                        <td class="px-2 qty">
                                            <label>{{ $lineItem->fed ?? ''  }}</label>
                                            <input type="hidden" name="fed[]"  value="{{ $lineItem->fed ?? ''  }}">
                                        </td>
                                        <td class="px-2 qty">
                                            <label>{{ $lineItem->further_tax ?? ''  }}</label>
                                            <input type="hidden" name="further_tax[]"  value="{{ $lineItem->further_tax ?? ''  }}">
                                        </td>
                                        <td class="px-2 qty">
                                            <label>{{ $lineItem->inclusive_value ?? ''  }}</label>
                                            <input type="hidden" name="inclusive_value[]"  value="{{ $lineItem->inclusive_value ?? ''  }}">
                                        </td>
                                        <td class="px-2 qty">
                                            <label>{{ $lineItem->lu ?? ''  }}</label>
                                            <input type="hidden" name="lu[]"  value="{{ $lineItem->lu ?? ''  }}">
                                        </td>
                                        <td class="px-2 qty">
                                            <label>{{ $lineItem->freight ?? ''  }}</label>
                                            <input type="hidden" name="freight[]"  value="{{ $lineItem->freight ?? ''  }}">
                                        </td>
                                        <td class="px-2 qty">
                                            <label>{{ $lineItem->other ?? ''  }}</label>
                                            <input type="hidden" name="other[]"  value="{{ $lineItem->other ?? ''  }}">
                                        </td>
                                        <td class="px-2 qty">
                                            <label>{{ $lineItem->fixed_margin ?? ''  }}</label>
                                            <input type="hidden" name="fixed_margin[]"  value="{{ $lineItem->qty_issue ?? ''  }}">
                                        </td>
                                        <td class="px-2 qty">
                                            <label>{{ $lineItem->amount_before_discount ?? ''  }}</label>
                                            <input type="hidden" name="value_before_discount[]"  value="{{ $lineItem->amount_before_discount ?? ''  }}">
                                        </td>
                                        <td class="px-2 qty">
                                            <label>{{ $lineItem->trade_offer ?? ''  }}</label>
                                            <input type="hidden" name="trade_offer[]"  value="{{ $lineItem->trade_offer ?? ''  }}">
                                        </td>
                                        <td class="px-2 qty">
                                            <label>{{ $lineItem->discount ?? ''  }}</label>
                                            <input type="hidden" name="discount[]"  value="{{ $lineItem->discount ?? ''  }}">
                                        </td>
                                        <td class="px-2 qty">
                                            <label>{{ $lineItem->amount_after_discount ?? ''  }}</label>
                                            <input type="hidden" name="value_after_discount[]"  value="{{ $lineItem->amount_after_discount ?? ''  }}">
                                        </td>
                                        <td class="px-2 qty">
                                            <label>{{ $lineItem->adv_payment ?? ''  }}</label>
                                            <input type="hidden" name="adv_payment[]"  value="{{ $lineItem->adv_payment ?? ''  }}">
                                        </td>
                                    </tr>
                                @php 
                                        
                                    } 
                                @endphp
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
                                <textarea name="note" maxlength="120" rows="4" class="form-control no-resize"  placeholder="">{{ $salesReturn->note ?? '' }}</textarea>
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
                            @endif
                            <input name="file" type="file" class="dropify"> 
                        </div>
                    </div>
                   
                    <div class="row">                        
                        <div class="ml-auto">
                            <button class="btn btn-raised btn-primary waves-effect" type="submit" id="save">Save</button>
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

    // fill item detail through Gin No.
    $('#do_num').on('change',function(){
        $('.rowData').html('');
        rowId=0;
        var do_id=$(this).val();
        var url= "{{ url('Inventory/DoDetail') }}";
        $.post(url,{do_id:do_id, _token:token},function(data){
            data.map(function(val,i){
                rowId++;
                var rw='<tr id="row'+rowId+'" class="rowData">'+
                    '<td>'+
                        '<label class="prod_field">'+val.prod_name+'</label>'+
                        '<input type="hidden" name="item_ID[]"  value="'+val.prod_id+'">'+
                    '</td>'+
                    '<td>'+
                        '<label>'+val.unit+'</label>'+
                        '<input type="hidden" name="unit[]"  value="'+val.unit+'">'+
                        '<input type="hidden" name="operator_value[]" value="'+val.operator_value+'">'+
                    '</td>'+
                    '<td class="px-2 qty">'+
                        '<label>'+val.qty_issue+'</label>'+
                        '<input type="hidden" name="qty_issue[]" id="qty_issue'+rowId+'"  value="'+val.qty_issue+'">'+
                    '</td>'+
                    '<td>'+
                        '<input type="number" name="qty_return[]" id="qty_return'+rowId+'" class="form-control qty" onblur="checkQty('+rowId+')">'+
                        '<label id="qty_error'+rowId+'" class="text-danger"></label>'+
                    '</td>'+
                    '<td class="px-2 qty">'+
                        //rate code
                        '<label>'+val.rate_code_num+'</label>'+
                        //rate code id
                        '<input type="hidden" name="rate_code_id[]" value="'+val.rate_code_id+'">'+
                    '</td>'+
                    '<td class="px-2 qty">'+
                        '<label>'+val.base_rate+'</label>'+
                        '<input type="hidden" name="base_rate[]"  value="'+val.base_rate+'">'+
                    '</td>'+
                    '<td class="px-2 qty">'+
                        '<label>'+val.sales_tax+'</label>'+
                        '<input type="hidden" name="sales_tax[]"  value="'+val.sales_tax+'">'+
                    '</td>'+
                    '<td class="px-2 qty">'+
                        '<label>'+val.fed+'</label>'+
                        '<input type="hidden" name="fed[]"  value="'+val.fed+'">'+
                    '</td>'+
                    '<td class="px-2 qty">'+
                        '<label>'+val.further_tax+'</label>'+
                        '<input type="hidden" name="further_tax[]"  value="'+val.further_tax+'">'+
                    '</td>'+
                    '<td class="px-2 qty">'+
                        '<label>'+val.inclusive_value+'</label>'+
                        '<input type="hidden" name="inclusive_value[]"  value="'+val.inclusive_value+'">'+
                    '</td>'+
                    '<td class="px-2 qty">'+
                        '<label>'+val.lu+'</label>'+
                        '<input type="hidden" name="lu[]"  value="'+val.lu+'">'+
                    '</td>'+
                    '<td class="px-2 qty">'+
                        '<label>'+val.freight+'</label>'+
                        '<input type="hidden" name="freight[]"  value="'+val.freight+'">'+
                    '</td>'+
                    '<td class="px-2 qty">'+
                        '<label>'+val.other+'</label>'+
                        '<input type="hidden" name="other[]"  value="'+val.other+'">'+
                    '</td>'+
                    '<td class="px-2 qty">'+
                        '<label>'+val.fixed_margin+'</label>'+
                        '<input type="hidden" name="fixed_margin[]"  value="'+val.fixed_margin+'">'+
                    '</td>'+
                    '<td class="px-2 qty">'+
                        '<label>'+val.value_before_discount+'</label>'+
                        '<input type="hidden" name="value_before_discount[]"  value="'+val.value_before_discount+'">'+
                    '</td>'+
                    '<td class="px-2 qty">'+
                        '<label>'+val.trade_offer+'</label>'+
                        '<input type="hidden" name="trade_offer[]"  value="'+val.trade_offer+'">'+
                    '</td>'+
                    '<td class="px-2 qty">'+
                        '<label>'+val.discount+'</label>'+
                        '<input type="hidden" name="discount[]"  value="'+val.discount+'">'+
                    '</td>'+
                    '<td class="px-2 qty">'+
                        '<label>'+val.value_after_discount+'</label>'+
                        '<input type="hidden" name="value_after_discount[]"  value="'+val.value_after_discount+'">'+
                    '</td>'+
                    '<td class="px-2 qty">'+
                        '<label>'+val.adv_payment+'</label>'+
                        '<input type="hidden" name="adv_payment[]"  value="'+val.adv_payment+'">'+
                    '</td>'+
                '</tr>';
                $('#sales_return').append(rw);
                $('#do_date').html(val.header.date);
                $('#cust_name').html(val.header.cust_name);
                $('#cust_coa_id').val(val.header.cust_coa_id);
                $('#warehouse_name').html(val.header.warehouse_name); 
                $('#warehouse_id').val(val.header.warehouse_id); 
            }); 
        });
    });
});
function checkQty(row)
    {
        if(parseFloat($('#qty_return'+row).val()) > parseFloat($('#qty_issue'+row).val()))
        {
            $('#qty_error'+row).html('qty return must be less than qty issue');
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