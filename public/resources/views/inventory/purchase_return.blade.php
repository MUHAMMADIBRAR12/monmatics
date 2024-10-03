@extends('layout.master')
@section('title', 'Purchase Return')
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
var CustomerURL = "{{ url('customerSearch') }}";
var token =  "{{ csrf_token()}}";
var ItemURL = "{{ url('itemSearch') }}";
var getItemDetailURL = "{{ url('getItemDetail') }}";
var TaxRateURL = "{{ url('getTaxRates') }}";
var rowId=1;
var attachmentURL = "{{ url('attachmentDelete') }}";
var vendorURL= "{{ url('vendorSearch') }}";
</script>

@stop
@section('content')

    <div class="row clearfix">
        <div class="card col-lg-12">
        
            <div class="body">
            <form method="post" action="{{url('Inventory/PurchaseReturn/Add')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" id="id" value="{{ $purchaseReturn->id ?? ''  }}">
                    <input type="hidden" name="grn_id" id="grn_id" value="{{ $purchaseReturn->grn_id ?? ''  }}">
                    <input type="hidden" name="trm_id" value="{{ $purchaseReturn->trm_id ?? ''}}">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">PR No#</label><br>
                            <label for="fiscal_year">{{ $purchaseReturn->month ?? '' }}-{{appLib::padingZero($purchaseReturn->number  ?? '')}}</label>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="date">Date</label>
                            <div class="form-group">
                                <input type="date" name="date"   class="form-control" value="{{ $purchaseReturn->date ?? date('Y-m-d')  }}"  required>
                            </div>
                        </div>                        
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">Purchase Invoice No#</label><br>
                            @if(isset($purchaseReturn))
                            <label>{{ $purchaseReturn->pur_inv_num ?? ''}}</label>
                            @else
                            <div class="form-group">
                                <select name="pur_inv" id="pur_inv" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                   <option  value="">Select Purchase Invoice</option>
                                   @foreach($purchase_invoices as $purchase_invoice)
                                   <option value="{{$purchase_invoice->id}}" {{ ( $purchase_invoice->id == ( $purchaseReturn->grn_id ??'')) ? 'selected' : '' }}>{{$purchase_invoice->inv_num}}</option>
                                   @endforeach
                                </select>
                            </div> 
                            @endif
                        </div>  
                        <div class="col-lg-3 col-md-6">
                            <label>Date</label><br>
                           <label id="pur_inv_date">{{ $purchaseReturn->pur_inv_date ?? '' }}</label>
                        </div>     
                    </div>  
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <label>Warehouse</label><br>
                            <label id="warehouse">{{ $purchaseReturn->warehouse_name?? '' }}</label>
                            <input type="hidden" name="warehouse_ID" id="warehouse_ID" value="{{ $purchaseReturn->warehouse_id ?? '' }}" required>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="">Vendor</label><br>
                            <label id="vendor">{{ $purchaseReturn->v_name ?? '' }}</label>
                            <input type="hidden" name="vendor_ID" id="vendor_ID" value="{{ $purchaseReturn->ven_coa_id ?? '' }}" required>
                        </div> 
                        <div class="col-lg-3 col-md-6">
                            <label>Address</label><br>
                            <label id="vendor_address">{{ $purchaseReturn->address ?? '' }}</label>
                        </div> 
                        <div class="col-lg-3 col-md-6">
                            <label>Phone</label><br>
                            <label id="vendor_phone">{{ $purchaseReturn->phone ?? '' }}</label>
                        </div> 
                    </div>               
                    <div class="row mt-1">
                        <div class="table-responsive">
                            <table id="table" class="table table-striped m-b-0">
                                <thead>
                                    <tr class="bg-purple">
                                        <th class="table_header_100">Product</th>
                                        <th class="table_header_100">Unit</th>
                                        <th class="qty table_header_100">Qty Received</th>
                                        <th class=" table_header_100">Qty Return</th>
                                        <th class="qty table_header_100">Rate</th> 
                                        <th class="qty table_header_100">Gross Amount</th>
                                        <th class="table_header table_header_100">Tax %</th>
                                        <th class="table_header table_header_100">Tax Amount</th>
                                        <th class="table_header table_header_100">Discount %</th>
                                        <th class="table_header table_header_100">Discount Amount</th>
                                        <th class="table_header table_header_100">Net Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                $count = (isset($purchaseReturnDetails))?count($purchaseReturnDetails):1;
                               
                                for($i=1;$i<=$count; $i++)
                                { 
                                    
                                    if(isset($purchaseReturnDetails))
                                    {
                                        $lineItem = $purchaseReturnDetails[($i-1)];
                                        
                                    }                                    
                                    @endphp                               
                                    <tr id="row{{$i}}" class="rowData">
                                    <td> 
                                        <!-- Product -->  
                                        <label class="prod_field">{{ $lineItem->name ?? ''  }}</label>
                                        <input type="hidden" name="item_ID[]"  value="{{ $lineItem->prod_id ?? ''  }}">
                                        <input type="hidden" name="prod_coa_id" value="{{ $lineItem->prod_coa_id ?? ''  }}">
                                    </td>
                                    <td>
                                        <!-- unit -->
                                        <label>{{ $lineItem->unit ?? ''  }}</label>
                                        <input type="hidden" name="unit[]"   value="{{ $lineItem->unit ?? ''  }}">
                                    </td>
                                    <td>
                                        <!-- qty -->
                                        <label class="qty">{{ $lineItem->qty_received ?? ''  }}</label>
                                        <input type="hidden" name="qty_received[]" id="qty_received{{$i}}"   value="{{ $lineItem->qty_received ?? ''  }}">
                                        <input type="hidden" name="qty_out[]" value="{{ $lineItem->qty_out ?? ''  }}">
                                    </td>
                                    <td>
                                        <input type="number" step="any" name="qty_return[]" id="qty_return{{$i}}" value="{{ $lineItem->qty_return ?? ''  }}" class="form-control qty" onblur="checkQty({{$i}});rowCalculation({{$i}})">
                                        <label id="qty_error{{$i}}" class="text-danger"></label>
                                    </td>
                                    <td>
                                        <!-- rate -->
                                        <label class="qty" id="rate_label{{$i}}">{{ $lineItem->rate ?? ''  }}</label>
                                        <input type="hidden" name="rate[]" id="rate{{$i}}"  value="{{ $lineItem->rate ?? ''  }}">
                                    </td>
                                    <td>
                                        <!--Gross amount -->
                                        <label class="qty" id="gross_amount_label{{$i}}">{{ $lineItem->gross_amount ?? ''  }}</label>
                                        <input type="hidden" name="gross_amount[]" id="gross_amount{{$i}}"  class="gross_amount"  value="{{ $lineItem->gross_amount ?? ''  }}">
                                    </td>
                                    <td>
                                        <!--Tax % -->
                                        <label class="qty" id="tax_label{{$i}}">{{ $lineItem->tax_percent ?? ''  }}</label>
                                        <input type="hidden" name="tax[]" id="tax{{$i}}"  value="{{ $lineItem->tax_percent ?? ''  }}">
                                    </td>
                                    <td>
                                        <!-- Tax Amount -->
                                        <label class="qty" id="tax_amount_label{{$i}}">{{ $lineItem->tax_amount ?? ''  }}</label>
                                        <input type="hidden" name="tax_amount[]"  id="tax_amount{{$i}}" class="tax_amount"  value="{{ $lineItem->tax_amount ?? ''  }}">
                                    </td>
                                    <td>
                                        <!-- Discount % -->
                                        <label class="qty" id="discount_label{{$i}}">{{ $lineItem->disc_percent ?? ''  }}</label>
                                        <input type="hidden" name="discount[]" id="discount{{$i}}"   value="{{ $lineItem->disc_percent ?? ''  }}">
                                    </td>
                                    <td>
                                        <!-- Discount Amount -->
                                        <label class="qty" id="discount_amount_label{{$i}}">{{ $lineItem->disc_amount ?? ''  }}</label>
                                        <input type="hidden" name="disc_amount[]" id="discount_amount{{$i}}" class="discount_amount"  value="{{ $lineItem->disc_amount ?? ''  }}">
                                    </td>
                                    <td>
                                        <!-- net amount -->
                                        <label class="qty" id="net_amount_label{{$i}}">{{ $lineItem->net_amount ?? ''  }}</label>
                                        <input type="hidden" name="net_amount[]" id="net_amount{{$i}}"  class="net_amount" value="{{ $lineItem->net_amount ?? ''  }}">
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
                                <textarea name="note" maxlength="120" rows="4" class="form-control no-resize"  placeholder="">{{  $purchaseReturn->note ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row ">
                                <div class="col-sm-6">
                                    <label for="">Gross Amount</label>
                                    <input type="text"   class="form-control" name="total_gross_amount" id="gross_amount"  readonly>
                                </div>
                                <div class="col-sm-6">
                                    <label for="">Total Tax</label>
                                    <input type="text"   class="form-control" name="total_tax" id="total_tax" readonly>
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-sm-6">
                                    <label for="">Total Discount</label>
                                    <input type="text"   class="form-control" name="total_discount" id="total_discount" readonly>
                                </div>
                                <div class="col-sm-6">
                                    <label for="">Net amount</label>
                                    <input type="text" name="total_net_amount"  class="form-control" id="total_net_amount" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">   
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
                        <div class="col-md-12">
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
sumAllFields();
$(document).ready(function(){
// add purchase order details through purchase order number
$('#pur_inv').on('change',function(){
    $('.rowData').html('');
     rowId=0;
    var pur_inv=$(this).val();
    var url = "{{ url('Inventory/purInvDetail')}}";
    $.post(url,{pur_inv:pur_inv, _token:token},function(data){
        data.map(function(val,i){
            rowId++;
            var rw='<tr id="row'+rowId+'" class="rowData">'+
                        '<td>'+
                            //product
                            '<label class="prod_field">'+val.prod_name+'</label>'+
                            '<input type="hidden" name="item_ID[]"  value="'+val.prod_id+'">'+
                            '<input type="hidden" name="prod_coa_id" value="'+val.prod_coa_id+'">'+
                        '</td>'+
                        '<td>'+
                            //unit
                            '<label>'+val.unit+'</label>'+
                            '<input type="hidden" name="unit[]"   value="'+val.unit+'">'+
                        '</td>'+
                        '<td>'+
                            //qty received
                            '<label class="qty">'+val.qty_received+'</label>'+
                            '<input type="hidden" name="qty_received[]" id="qty_received'+rowId+'"   value="'+val.qty_received+'">'+
                        '</td>'+
                        '<td>'+
                            //qty return 
                            '<input type="number" step="any" name="qty_return[]" id="qty_return'+rowId+'" class="form-control qty" onblur="checkQty('+rowId+');rowCalculation('+rowId+')">'+
                            '<label id="qty_error'+rowId+'" class="text-danger"></label>'+
                        '</td>'+
                        '<td>'+
                            //rate
                            '<label class="qty" id="rate_label'+rowId+'">'+val.rate+'</label>'+
                            '<input type="hidden" name="rate[]" id="rate'+rowId+'"  value="'+val.rate+'">'+
                        '</td>'+
                        '<td>'+
                            //gross amount
                            '<label class="qty" id="gross_amount_label'+rowId+'">'+val.gross_amount+'</label>'+
                            '<input type="hidden" name="gross_amount[]" id="gross_amount'+rowId+'" class="gross_amount" value="'+val.gross_amount+'">'+
                        '</td>'+
                        '<td>'+
                            //Tax %
                            '<label class="qty" id="tax_label'+rowId+'">'+val.tax_percent+'</label>'+
                            '<input type="hidden" name="tax[]" id="tax'+rowId+'"  value="'+val.tax_percent+'">'+
                        '</td>'+
                        '<td>'+
                            //Tax Amount
                            '<label class="qty" id="tax_amount_label'+rowId+'">'+val.tax_amount+'</label>'+
                            '<input type="hidden" name="tax_amount[]" id="tax_amount'+rowId+'" class="tax_amount" value="'+val.tax_amount+'">'+
                        '</td>'+
                        '<td>'+
                            //Discount %
                            '<label class="qty" id="discount_label'+rowId+'">'+val.disc_percent+'</label>'+
                            '<input type="hidden" name="discount[]" id="discount'+rowId+'"  value="'+val.disc_percent+'">'+
                        '</td>'+
                        '<td>'+
                            //Discount Amount
                            '<label class="qty" id="discount_amount_label'+rowId+'">'+val.disc_amount+'</label>'+
                            '<input type="hidden" name="disc_amount[]" id="discount_amount'+rowId+'" class="discount_amount"   value="'+val.disc_amount+'">'+
                        '</td>'+
                        '<td>'+
                            //net amount
                            '<label class="qty" id="net_amount_label'+rowId+'">'+val.net_amount+'</label>'+
                            '<input type="hidden" name="net_amount[]" id="net_amount'+rowId+'" class="net_amount" value="'+val.net_amount+'">'+
                        '</td>'+
                    '</tr>';
                $('#table').append(rw);
                $('#pur_inv_date').html(val.header.date);
                $('#warehouse').html(val.header.warehouse_name);    
                $('#warehouse_ID').val(val.header.warehouse_id);    
                $('#vendor').html(val.header.ven_name);
                $('#vendor_ID').val(val.header.ven_coa_id);
                $('#vendor_address').html(val.header.address);
                $('#vendor_phone').html(val.header.phone);
                $('#grn_id').val(val.header.grn_id);
        }); 
    });
   
});

});
function checkQty(row)
    {
        if(parseFloat($('#qty_return'+row).val()) > parseFloat($('#qty_received'+row).val()))
        {
            $('#qty_error'+row).html('qty return must be less than qty received');
            $('#save').hide();
        }
        else
        {
            $('#qty_error'+row).html('');
            $('#save').show();
           
        }

    }
function rowCalculation(row)
{
    qty_return=$('#qty_return'+row).val();
    rate=$('#rate'+row).val();
    tax=$('#tax'+row).val();
    discount=$('#discount'+row).val();
    gross_amount=getNum(qty_return,4) * getNum(rate,4);
    tax_amount=getNum(gross_amount) * getNum(tax)/100;
    discount_amount=getNum(gross_amount) * getNum(discount)/100;
    net_amount=+getNum(gross_amount) + +getNum(tax_amount) - +getNum(discount_amount);
    $('#gross_amount'+row).val(gross_amount);
    $('#gross_amount_label'+row).html(gross_amount);
    $('#tax_amount_label'+row).html(tax_amount);
    $('#tax_amount'+row).val(tax_amount);
    $('#discount_amount_label'+row).html(discount_amount);
    $('#discount_amount'+row).val(discount_amount);
    $('#net_amount_label'+row).html(net_amount);
    $('#net_amount'+row).val(net_amount);
    sumAllFields();

}
function sumAllFields()
{
    $('#gross_amount').val(sumAll('gross_amount'));
    $('#total_discount').val(sumAll('discount_amount'));
    $('#total_tax').val(sumAll('tax_amount'));
    $('#total_net_amount').val(sumAll('net_amount'));
}
</script>
@stop