@extends('layout.master')
@section('title', 'Purchase Expenses')
@section('parentPageTitle', 'Purchase')
@section('page-style')
<?php
use App\Libraries\appLib; 
?>

<link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.bootstrap.min.css') }}" />
<link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.standalone.min.css') }}" />
<!-- <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet"> -->
<style>
    ul {
        list-style-type: none;
    }
</style>
@stop
@section('content')

<div class="row clearfix">
    <div class="card col-lg-12">
        <div class="header">
            <div class="row">
                <div class="col-md-9">
                    <h2><strong>View Purchase</strong> Expenses</h2>
                </div>
                <div class="col-md-3">

                    <a href="{{ url('role_management')}}" class="btn btn-primary float-right"><i class="zmdi zmdi-arrow-left"></i></a>
                </div>
            </div>
        </div>
        @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
        @endif
        <div class="body">

            <form method="POST" action="{{url('Purchase/PurchaseExpenses/InventoryCost')}}" enctype="multipart/form-data">
                <input type="hidden" name="peId" value="{{$peId}}" >
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="role">Purchase Order No</label>
                            <p>{{ $poNumber ?? '' }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="submit" value="Apply Costs & Close PO"  class="btn btn-raised btn-primary waves-effect">
                        </div>
                    </div>
                </div>  
                <div class="row">
                    <div class="table-responsive">
                        <table id="table" class="table table-striped m-b-0">
                            <thead>
                                <tr class="bg-purple">
                                    <th>Product</th>
                                    <th>Unit</th>
                                    <th class="qty">Qty</th>
                                    <th class="qty">Rate</th>
                                    <th class="qty">Amount</th>
                                    <th class="qty">Other Expenses</th>
                                    <th class="qty">Net Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i=0; @endphp
                                @foreach ($grnProductDetails as $lineItem)
                                    
                                    <tr id="row{{$i}}" class="rowData">
                                        <td>
                                            <!-- grn detail id -->
                                            <input type="hidden" name="grn_detail_id[]" value="{{ $lineItem->grn_detail_id ?? ''  }}">
                                            <!-- Product -->
                                            <label class="prod_field">{{ $lineItem->prod_name ?? ''  }}</label>
                                            <input type="hidden" name="item_ID[]" id="item_ID{{$i}}" value="{{ $lineItem->prod_id ?? ''  }}">
                                            <input type="hidden" name="prod_coa_id" id="prod_coa_id{{$i}}" value="{{ $lineItem->prod_coa_id ?? ''  }}">

                                        </td>
                                        <td>
                                            <!-- unit -->
                                            <label>{{ $lineItem->unit ?? ''  }}</label>
                                            <input type="hidden" name="unit[]" id="unit{{$i}}" value="{{ $lineItem->unit ?? ''  }}">
                                        </td>
                                        <td>
                                            <!-- qty -->
                                            <label class="gqty">{{ Str::currency($lineItem->qty ?? '0')  }}</label>
                                            <input type="hidden" name="qty[]" class="gqty" id="gqty{{$i}}" value="{{ Str::currency($lineItem->qty ?? '0')  }}">
                                        </td>
                                        
                                        <td>
                                            <!-- rate -->
                                            <label class="qty">{{ Str::currency($lineItem->rate ?? '0')  }}</label>
                                            <input type="hidden" name="rate[]" class="vrate" id="vrate{{$i}}" value="{{ Str::currency($lineItem->rate ?? '0')  }}">
                                        </td>
                                       
                                        <td >
                                            <!-- amount -->
                                            <label class="qty">{{ Str::currency(($lineItem->qty * $lineItem->rate) ?? '0')  }}</label>
                                            <input type="hidden" name="gross_amount[]" id="gross_amount{{$i}}" class="gross_amount" value="{{$lineItem->qty * $lineItem->rate }}">
                                        </td>
                                        <td>
                                             <!--Other Expenses-->
                                            <input type="number" step="any" name="other_expenses[]" id="other_expenses{{$i}}" class="form-control other_expenses" value="{{  Str::currency($lineItem->other_expenses ?? '0')  }}" onblur="rowCalculate({{$i}})">
                                        </td>
                                        <td>
                                             <!--Net amount--> 
                                            <input type="text" name="net_amount[]" id="net_amount{{$i}}" value="{{ Str::currency($lineItem->net_amount ?? '0') }}" class="form-control qty net_amount" readonly>
                                        </td>
                                    </tr>
                                    @php $i++ @endphp
                                @endforeach       
                            <input type="hidden" name="rowId" id="rowId" value="{{$i}}" >
                            <script>
                                rowId = {{$i}};
                            </script>       
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
        <div class="row" style="margin-top: 2%;">
            <div class="col-sm-4">

                <div class="form-group">

                    <label>Apply Cost to Products</label>

                    <select name="app_cost" id="app_cost" class="form-control show-tick ms select2" data-placeholder="Select">
                        <option value="">Select to Apply Cost</option>
                        <option value="quantity">by quantity</option>
                        <option value="price">by price</option>
                    </select>

                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <button class="btn btn-raised btn-primary waves-effect" id="applyButton" type="button">Apply</button>
                </div>
            </div>
<!--            <div class="col-sm-6">
                <label for="fiscal_year">Note</label>
                <div class="form-group" id="note">
                    <textarea name="note" maxlength="120" rows="4" class="form-control" placeholder="">{{$purchase_invoice->note ?? '' }}</textarea>
                </div>
            </div>             -->
            
            <div class="col-md-2">
                <label for="">Gross Amount</label>
                <input type="text" class="form-control qty" name="gross_amount_total" id="gross_amount_total" readonly>
            </div>                                                
            <div class="col-md-2">
                    <label for="">Other Expenses</label>
                    <input type="text" class="form-control qty" name="total_other_expenses" id="total_other_expenses" readonly>
            </div>
            <div class="col-md-2">
                    <label for="">Net amount</label>
                    <input type="text" name="total_net_amount" class="form-control qty" id="total_net_amount" readonly>
            </div>                            
        </div>        
        @php $c=0; @endphp
        @foreach($purchaseInvoices as $purchaseInvoice)        
        
        <div class="body mt-2">
            <form method="post" action="" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $project->id ?? ''  }}">
                
                <div class="row">                    
                    <div class="col-md-3">
                        <label>
                            <input type="checkbox" name="products[]" id="chkproducts{{$c}}" class="pur_invoices" value="{{$purchaseInvoice->id}}" onclick="sumAllOE('expense_base_amount');">
                            Vendor</label>
                        
                               
                        <p>{{ $purchaseInvoice->name }}</p>                        
                    </div>
                    
                    <div class="col-md-2">
                        <label for="name">Vendor Reference</label>
                        <div class="form-group"><p>{{ $purchaseInvoice->vendor_reference }}</p></div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="role">Date</label>
                            <p>{{ $purchaseInvoice->date }}</p>
                        </div>
                    </div>
                    <div class="col-md-1 multicurrency">
                        <label for="fiscal_year">Currency</label>
                        <div class="form-group"><p>{{ $purchaseInvoice->cur_name }}</p></div>
                    </div>
                    <div class="col-md-1 multicurrency">
                        <label for="rate">Rate</label>
                        <div class="form-group"><p>{{ $purchaseInvoice->cur_rate }}</p></div>
                    </div>
                    
                    <div class="col-md-2">
                        <label for="name">Amount</label>
                        <div class="form-group"><p>{{ $purchaseInvoice->total_inv_amount }}</p></div>
                    </div>                    
                    <div class="col-md-2">
                        <label for="name">BC Amount</label>
                        <div class="form-group"><p>{{ $purchaseInvoice->total_inv_amount*$purchaseInvoice->cur_rate }}</p></div>
                        <input type="hidden" name="expense_base_amount[]" id="expense_base_amount" class="expense_base_amount"  value="{{ $purchaseInvoice->total_inv_amount*$purchaseInvoice->cur_rate }}">
                    </div>                    
                </div>
                
            </form>
        </div>
            @php $c++; @endphp
        @endforeach
        
    </div>
</div>
@stop
@section('page-script')
<script src="{{asset('public/assets/bundles/footable.bundle.js')}}"></script>
<script src="{{asset('public/assets/js/pages/tables/footable.js')}}"></script>
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>


<script src="{{asset('public/assets/plugins/dropify/js/dropify.min.js')}}"></script>
<script src="{{asset('public/assets/js/pages/forms/dropify.js')}}"></script>
<script src="{{asset('public/assets/js/sw.js')}}"></script>

<script>
    function rowCalculate(num) {

        var netAmount = 0;
        amount = $('#gross_amount' + num).val();
//        tax = ($('#tax' + num).val() == '') ? 0 : $('#tax' + num).val();
//        tax_amount = percentVal(tax, amount);
//        $('#tax_amount' + num).val(tax_amount);
//        discount = ($('#discount' + num).val() == '') ? 0 : $('#discount' + num).val();
//        discount_amount = percentVal(discount, amount);
//        $('#discount_amount' + num).val(discount_amount);
//        delivery_charges = $('#delivery_charges' + num).val();

        other_expensesE = $('#other_expenses' + num).val();
        //netAmount = parseFloat(amount) + parseFloat(tax_amount) - parseFloat(discount_amount) + parseFloat(delivery_charges) + parseFloat(other_expensesE);
        netAmount = parseFloat(amount) +  parseFloat(other_expensesE);
        netAmount.toFixed(2);
        $('#net_amount' + num).val(netAmount);

        sumAllFields();

    }

    function sumAllFields() {
        $('#gross_amount_total').val(sumAll('gross_amount'));
//        $('#total_discount').val(sumAll('discount_amount'));
//        $('#total_tax').val(sumAll('tax_amount'));
//        $('#total_delivery_charges').val(sumAll('delivery_charges'));
    
//        $('#total_other_expenses').val(sumAll('expense_base_amount'));
        $('#total_other_expenses').val(sumAllOE('expense_base_amount'));
        $('#total_net_amount').val(sumAll('net_amount'));
    }

</script>
<script>
    $(document).ready(function() {
        
       // var rowId = 0;
        
        $('#applyButton').click(function() {
            sumAllFields();
            var selectedOption = $('#app_cost').val();
            var totalExpenses = $('#total_other_expenses').val();

            if (selectedOption == "quantity") {
                total_value = sumAll('gqty');
                type_qty_price = 'gqty';
            } 
            else if (selectedOption == "price") {
                total_value = sumAll('gross_amount') ;
                type_qty_price = 'gross_amount';
            }
            i = 0;
            //for (i = 0; i <= rowIdOE; i++) 
           // alert('RowID'+rowId);
            for (i = 0; i <= rowId; i++) 
            {                
                // Get perga of qty per share 
                percent = $('#'+type_qty_price+i).val() * 100 / total_value;
                prop = (percent * totalExpenses / 100).toFixed(2);
                $('#other_expenses' + i).val(prop);
            }
            
            // Row total of Grid 1
            for(i=0; i<=rowId; i++)
            {
                rowCalculate(i)
            }
        });
    });
    
    
    function sumAllOE(tclass)
    {
        var totalPrice = 0;
        $("."+tclass).each(function(i, td) {
           
            if($.isNumeric($(td).val()) && $('#chkproducts'+i).is(":checked"))
                totalPrice += parseFloat($(td).val());
        });    
        return totalPrice.toFixed(2);    
    }
</script>
@stop