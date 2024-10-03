@extends('layout.master')
@section('title','Purchase Invoice')
@section('parentPageTitle', 'Purchase')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')

<?php

use App\Libraries\appLib; ?>



<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!--<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">-->
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="{{asset('public/assets/plugins/dropify/css/dropify.min.css')}}" />
<link rel="stylesheet" href="{{asset('public/assets/css/sw.css')}}" />
<style>
    .amount {
        width: 150px;
        text-align: right;
    }

    .table td {
        padding: 0.10rem;
    }

    .dropify {
        width: 200px;
        height: 200px;
    }
    
    .tempHide
    {
        /* display: none; */
    }
</style>
<script lang="javascript/text">
    var CustomerURL = "{{ url('customerSearch') }}";
    var token = "{{ csrf_token()}}";
    var ItemURL = "{{ url('itemSearch') }}";
    var getItemDetailURL = "{{ url('getItemDetail') }}";
    var TaxRateURL = "{{ url('getTaxRates') }}";
    var rowId = 1;
    var rowIdOE = 1;
    var attachmentURL = "{{ url('prAttachmentDelete') }}";
    var vendorURL = "{{ url('vendorSearch') }}";
    var searchCoaURL = "{{ url('coaSearch')}}";
</script>
<script>
    function addRow() {
        rowIdOE++;
        var row = '<tr id="row' + rowIdOE + '">' +
            '<td>' +
            '<button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow(\'row' + rowIdOE + '\');totalAmount()"><i class="zmdi zmdi-delete"></i></button>' +
            '</td>' +
            '<td>' +
            '<input type="text" name="vendor_name[]" id="vendor_name' + rowIdOE + '" class="form-control autocomplete" onkeyup="autoFill(this.id, searchCoaURL, token)" value="" required>' +
            '<input type="hidden" name="vendor_name_ID[]" id="vendor_name' + rowIdOE + '_ID" required>' +
            '</td>' +
            '<td>' +
            '<input type="text" name="expense_name[]" id="expense_name' + rowIdOE + '" class="form-control autocomplete" onkeyup="autoFill(this.id, searchCoaURL, token)" value="" required>' +
            '<input type="hidden" name="expense_name_ID[]" id="expense_name' + rowIdOE + '_ID" required>' +
            '</td>' +
            '<td>' +
            '<input type="text" name="description[]"  class="form-control" value="">' +
            '</td>' +
            '<td>' +
            '<input type="number" name="expense_amount[]" class="expense_amount form-control"  step="0.01" value="" onblur="sumAllFields();" required>' +
            '</td>' +

            '</tr>';
        $('#voucher').append(row);
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
            <form method="post" action="{{url('Purchase/PurchaseInvoice/Add')}}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $purchase_invoice->id ?? ''}}">
                <input type="hidden" name="trm_id" value="{{ $purchase_invoice->trm_id ?? ''}}">
                <div class="row">
                    <div class="col-lg-2 col-md-3">
                        <label>Purchase Invoice #</label><br>
                        <label>{{ $purchase_invoice->month ?? '' }}-{{appLib::padingZero($purchase_invoice->number ?? '')}}</label>
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <label>Date</label>
                        <div class="form-group">
                            <input type="date" name="date" id='vdate' class="form-control" value="{{$purchase_invoice->date ?? date('Y-m-d')  }}" required>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <label>G.R.N No.</label>
                        <div class="form-group">
                            @if(isset($purchase_invoice))
                            <label>{{$purchase_invoice->grn_num ?? ''}}</label>
                            @else
                            <select name="grn_id" id="grn_id" class="form-control show-tick ms select2" data-placeholder="Select" >
                                <option value="">Select GRN No.</option>
                                @foreach($grns as $grn)
                                <option value="{{$grn->id}}" {{ ( $grn->id == ( $purchaseOrder->warehouse ??'')) ? 'selected' : '' }}>{{$grn->doc_number}}</option>
                                @endforeach
                            </select>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <label>G.R.N Date</label><br>
                        <label id="grn_date">{{$purchase_invoice->grn_date ?? '' }}</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 multicurrency">
                            <label for="fiscal_year">Currency (Vendor Accounts)</label>
                            <div class="form-group">
                                <select name="cur_id" id="cur_id" class="form-control show-tick ms select2" data-placeholder="Select" onchange="getCurrencyRate(this.id, 'rate', CurrencyURL, token);" >                                    
                                    @foreach($currencies as $cur)
                                        <option {{ ( $cur->id == ($purchase_invoice->cur_id ?? '')) ? 'selected' : '' }} value="{{ $cur->id }}">{{  $cur->code  }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 multicurrency">
                            <label for="rate">Rate</label>
                            <div class="form-group">
                                <input type="number" step="any" name="cur_rate" id='cur_rate'   class="form-control" value="{{ $purchase_invoice->cur_rate ?? '1'}}" size='9' maxlength="9" >
                            </div>
                        </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <label>Warehouse</label><br>
                        <label id="warehouse_name">{{$purchase_invoice->warehouse_name ?? '' }}</label>
                        <input type="hidden" name="warehouse_id" id="warehouse_id">
                    </div>
                    <div class="col-lg-3">
                        <label>Supplier Name</label><br>
                        <label id="supp_name">{{$purchase_invoice->ven_name ?? '' }}</label>
                        <input type="hidden" name="supp_coa_id" id="supp_coa_id" value="{{$purchase_invoice->ven_coa_id ?? '' }}">
                    </div>
                    <div class="col-lg-3">
                        <label>address</label><br>
                        <label id="supp_address">{{$purchase_invoice->address ?? '' }}</label>
                    </div>
                    <div class="col-lg-3">
                        <label>Phone</label><br>
                        <label id="supp_phone">{{$purchase_invoice->phone ?? '' }}</label>
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
<!--                                    <th class="table_header table_header_100">Tax %</th>
                                    <th class="table_header table_header_100">Tax Amount</th>
                                    <th class="table_header table_header_100">Discount %</th>
                                    <th class="table_header table_header_100">Discount Amount</th>
                                    <th class="table_header table_header_100">Delivery Charges</th>
                                    <th class="table_header table_header_100">Other Expenses</th>
                                    <th class="table_header table_header_100">Net Amount</th>-->
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $count = (isset($purchase_invoice_detail))?count($purchase_invoice_detail):1;

                                for($i=1;$i<=$count; $i++) { if(isset($purchase_invoice_detail)) { $lineItem=$purchase_invoice_detail[($i-1)]; $amount=$lineItem->qty * $lineItem->rate;
                                    }
                                    @endphp
                                    @if( isset($purchase_invoice_detail) && $lineItem->qty > 0)
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
                                       
                                        <td>
                                            <!-- amount -->
                                            <label class="qty">{{ Str::currency($amount ?? '0')  }}</label>
                                            <input type="hidden" name="gross_amount[]" id="gross_amount{{$i}}" class="gross_amount" value="{{ Str::currency($amount ?? '0')  }}" required>
                                        </td>
<!--                                        <td>
                                             tax % 
                                            <input type="number" step="any" name="tax[]" id="tax{{$i}}" class="form-control qty" value="{{ Str::currency($lineItem->tax_percent ?? '0')  }}" onblur="rowCalculate({{$i}})">
                                        </td>
                                        <td>
                                             tax Amount 
                                            <input type="text" name="tax_amount[]" id="tax_amount{{$i}}" class="form-control qty tax_amount" value="{{ Str::currency($lineItem->tax_amount ?? '0')  }}" readonly>
                                        </td>
                                        <td>
                                             Discount % 
                                            <input type="number" step="any" name="discount[]" id="discount{{$i}}" class="form-control qty" value="{{ Str::currency($lineItem->disc_percent ?? '0')  }}" onblur="rowCalculate({{$i}})">
                                        </td>
                                        <td>
                                             Discount amount  
                                            <input type="number" name="discount_amount[]" id="discount_amount{{$i}}" value="{{ Str::currency($lineItem->disc_amount ?? '0')  }}" class="form-control qty discount_amount" readonly>
                                        </td>
                                        <td>
                                             delivery charges 
                                            <input type="number" step="any" name="delivery_charges[]" id="delivery_charges{{$i}}" value="{{  Str::currency($lineItem->delivery_charges ?? '0')  }}" class="form-control qty delivery_charges" onblur="rowCalculate({{$i}})">
                                        </td>
                                        <td>
                                             Other Expenses
                                            <input type="number" step="any" name="other_expenses[]" id="other_expenses{{$i}}" class="form-control other_expenses" value="{{  Str::currency($lineItem->other_expenses ?? '0')  }}" onblur="rowCalculate({{$i}})">
                                        </td>
                                        <td>
                                             Net amount 
                                            <input type="text" name="net_amount[]" id="net_amount{{$i}}" value="{{ Str::currency($lineItem->net_amount ?? '0') }}" class="form-control qty net_amount" readonly>
                                        </td>-->
                                    </tr>
                                    @endif
                                    @php
                                    }
                                    @endphp
                            </tbody>
                            <script>
                                rowId = {{$i}}
                            </script>
                        </table>
                    </div>
                </div>
                
                <div class="row tempHide" style="margin-top: 3%;" >
                    <div class="table-responsive">
                        <table id="voucher" class="table table-striped m-b-0">
                            <thead>
                                <tr class="bg-purple">
                                    <th><button type="button" class="btn btn-primary" style="align:right" onclick="addRow();">+</button></th>
                                    <th>Vendor A/c</th>
                                    <th>Expense A/c</th>
                                    <th>Narration</th>
                                    <th>Amount</th>

                                </tr>
                            </thead>
                            <tbody>                               
                                    @php $i=1; @endphp
                                    @foreach($purchase_invoice_expense ?? array() as $pur_invexp)
                                   
                                    <tr id="row{{$i}}">
                                        <td>
                                            <!-- Delete Button -->
                                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteRow('row{{$i}}');totalAmount();"><i class="zmdi zmdi-delete"></i></button>
                                        </td>

                                        <td>
                                            <!--Vendor  Account Name -->
                                            <!-- <input type="number" name="code[]" id="code" class="form-control" value="{{ $trans->code ?? ''  }}"> -->
                                            <input type="text" name="vendor_name[]" id="vendor_name{{$i}}" onkeyup="autoFill(this.id, searchCoaURL, token)" value="{{ $pur_invexp->vendor_coa_id ??  ''  }}" class="form-control autocomplete" required>
                                            <input type="hidden" name="vendor_name_ID[]" id="vendor_name{{$i}}_ID" value="{{ $pur_invexp->vendor_coa_id ?? ''  }}" required>
                                        </td>

                                        <td>
                                            <!--Expense  Account Name -->
                                            <input type="text" name="expense_name[]" id="expense_name{{$i}}" onkeyup="autoFill(this.id, searchCoaURL, token)" value="{{$pur_invexp->expense_coa_id ?? ''  }}" class="form-control autocomplete" required>
                                            <input type="hidden" name="expense_name_ID[]" id="expense_name{{$i}}_ID" value="{{ $pur_invexp->expense_coa_id ?? ''  }}" required>
                                        </td>
                                        <td>
                                            <!-- Description -->
                                            <input type="text" name="description[]" class="form-control" value="{{ $pur_invexp->narration ?? '' }}">
                                        </td>
                                        <td>
                                            <!-- Amount -->
                                            <input type="number" step="any" name="expense_amount[]" id="expense_amount{{$i}}" class="form-control amount expense_amount" value="{{ $pur_invexp->amount ?? '' }}" onblur="sumAllFields();" required>
                                        </td>

                                        <!-- <td  class="project">                                            
                                            <input type="text" name="cost_center[]" id="cost_center{{$i}}" class="form-control" value="{{ $trans->project_name ?? '' }}" onkeyup="autoFill(this.id, projectURL, token)">
                                            <input type="hidden" name="cost_center_ID[]" id="cost_center{{$i}}_ID" value="{{ $trans->cost_center_id ?? '' }}">
                                        </td> -->

                                    </tr>
                                    @php $i=$i+1; @endphp                                
                                    @endforeach
                            </tbody>                            
                        </table>
                    </div>
                </div>
        <script>
            rowIdOE = {{$i}};
        </script>
                <div class="row tempHide" style="margin-top: 6%;">
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
                        <div class="col-sm-4 mt-4">
                            <button class="btn btn-raised btn-primary waves-effect" id="applyButton" type="button">Apply</button>
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-top: 6%;">
                    <div class="col-sm-6">
                        <label for="fiscal_year">Note</label>
                        <div class="form-group" id="note">
                            <textarea name="note" maxlength="120" rows="4" class="form-control" placeholder="">{{$purchase_invoice->note ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row ">
                            <div class="col-sm-6">
                                <label for="">Gross Amount</label>
                                <input type="text" class="form-control qty" name="gross_amount_total" id="gross_amount_total" readonly>
                            </div>
                            <div class="col-sm-6">
                                <label for="">Total Tax</label>
                                <input type="text" class="form-control qty" name="total_tax" id="total_tax" readonly>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-sm-6">
                                <label for="">Total Discount</label>
                                <input type="text" class="form-control qty" name="total_discount" id="total_discount" readonly>
                            </div>
                            <div class="col-sm-6">
                                <label for="">Delivery Charges</label>
                                <input type="text" class="form-control qty" name="total_delivery_charges" id="total_delivery_charges" readonly>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-sm-6">
                                <label for="">Other Expenses</label>
                                <input type="text" class="form-control qty" name="total_other_expenses" id="total_other_expenses" readonly>
                            </div>
                            <div class="col-sm-3">
                                <label for="">Net amount</label>
                                <input type="text" name="total_net_amount" class="form-control qty" id="total_net_amount" readonly>
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
                                <td><button type="button" class="btn btn-danger btn-sm" id="{{ $attachment->file }}" onclick="delattach(attachmentURL,this.id,token)"><i class="zmdi zmdi-delete"></i></button></td>
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
    sumAllFields();
    // grn detail 
    $(document).ready(function() {
        $('#grn_id').on('change', function() {
            $('.rowData').html('');
            rowId = 0;
            var grn_id = $(this).val();
            var url = "{{ url('grn_detail')}}";
            $.post(url, {
                grn_id: grn_id,
                _token: token
            }, function(data) {
                console.log(data);
                data.map(function(val, i) {
                    if (val.qty > 0) {
                        rowId++;
                        var rw = '<tr id="row' + rowId + '" class="rowData">' +
                            '<td>' +
                            //grn detail id
                            '<input type="hidden" name="grn_detail_id[]" value="' + val.grn_detail_id + '">' +
                            //product
                            '<label class="prod_field">' + val.prod_name + '</label>' +
                            '<input type="hidden" name="item_ID[]"   value="' + val.prod_id + '">' +
                            '<input type="hidden" name="prod_coa_id" value="' + val.prod_coa_id + '">' +
                            '</td>' +
                            '<td>' +
                            //unit
                            '<label>' + val.unit + '</label>' +
                            '<input type="hidden" name="unit[]"   value="' + val.unit + '">' +
                            '</td>' +
                            '<td>' +
                            //qty
                            '<label class="qty">' + val.qty + '</label>' +
                            '<input type="hidden"  class="gqty"  name="qty[]" id="gqty' + rowId + '"   value="' + val.qty + '">' +
                            '</td>' +
                            '<td>' +
                            //rate
                            '<label class="qty">' + val.rate + '</label>' +
                            '<input type="hidden" class="vrate" name="rate[]" id="vrate"  value="' + val.rate + '">' +
                            '</td>' +
                            '<td>' +
                            //amount
                            '<label class="qty">' + val.amount + '</label>' +
                            '<input type="hidden" name="gross_amount[]" id="gross_amount' + rowId + '" class="gross_amount"  value="' + val.amount + '">' +
                            '</td>' +/*
                            '<td>' +
                            //tax %
                            '<input type="number" step="any" name="tax[]" id="tax' + rowId + '" class="form-control qty" value="0" onblur="rowCalculate(' + rowId + ')">' +
                            '</td>' +
                            '<td>' +
                            //tax Amount
                            '<input type="text"  name="tax_amount[]" id="tax_amount' + rowId + '" class="form-control qty tax_amount"  readonly value="0">' +
                            '</td>' +
                            '<td>' +
                            //Discount %
                            '<input type="number" step="any" name="discount[]" id="discount' + rowId + '" class="form-control qty" onblur="rowCalculate(' + rowId + ')" value="0">' +
                            '</td>' +
                            '<td>' +
                            //Discount amount
                            '<input type="text"  name="discount_amount[]" id="discount_amount' + rowId + '" class="form-control qty discount_amount" value="0" readonly>' +
                            '</td>' +
                            '<td>' +
                            //Delivery Charges
                            '<input type="number" step="any" name="delivery_charges[]" id="delivery_charges' + rowId + '" class="form-control delivery_charges qty" value="0" onblur="rowCalculate(' + rowId + ')">' +
                            '</td>' +
                            '<td>' +
                            //Other Expenses
                            '<input type="number" step="any"  name="other_expenses[]" id="other_expenses' + rowId + '" class="form-control Other_expenses qty" value="0" onblur="rowCalculate(' + rowId + ')" >' +
                            '</td>' +
                            '<td>' +
                            //Net amount
                            '<input type="text"  name="net_amount[]" id="net_amount' + rowId + '" class="form-control qty net_amount" value="0" readonly>' +
                            '</td>' + */
                            '</tr>';
                        $('#table').append(rw);
                        rowCalculate(rowId);
                    }
                    $('#grn_date').html(val.header.grn_date);
                    $('#warehouse_name').html(val.header.warehouse_name);
                    $('#warehouse_id').val(val.header.warehouse);
                    $('#supp_name').html(val.header.ven_name);
                    $('#supp_coa_id').val(val.header.coa_id);
                    $('#supp_address').html(val.header.address);
                    $('#supp_phone').html(val.header.phone);
                });
            });
        });
    });

    //row calculatation add tax in amount and subtract discount and show net amount
    function rowCalculate(num) {

        var netAmount = 0;
        amount = $('#gross_amount' + num).val();
        tax = ($('#tax' + num).val() == '') ? 0 : $('#tax' + num).val();
        tax_amount = percentVal(tax, amount);
        $('#tax_amount' + num).val(tax_amount);
        discount = ($('#discount' + num).val() == '') ? 0 : $('#discount' + num).val();
        discount_amount = percentVal(discount, amount);
        $('#discount_amount' + num).val(discount_amount);
        delivery_charges = $('#delivery_charges' + num).val();
        other_expensesE = $('#other_expenses' + num).val();
        netAmount = parseFloat(amount) + parseFloat(tax_amount) - parseFloat(discount_amount) + parseFloat(delivery_charges) + parseFloat(other_expensesE);
        netAmount.toFixed(2);
        $('#net_amount' + num).val(netAmount);

        sumAllFields();

    }

    function sumAllFields() {
        $('#gross_amount_total').val(sumAll('gross_amount'));
        $('#total_discount').val(sumAll('discount_amount'));
        $('#total_tax').val(sumAll('tax_amount'));
        $('#total_delivery_charges').val(sumAll('delivery_charges'));
        $('#total_other_expenses').val(sumAll('expense_amount'));
        $('#total_net_amount').val(sumAll('net_amount'));
    }
</script>

<script>
    $(document).ready(function() {

        $('#applyButton').click(function() {
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
            for (i = 0; i <= rowId; i++) 
            {                
                // Get perga of qty per share 
                percent = $('#'+type_qty_price+i).val() * 100 / total_value;
                prop = (percent * totalExpenses / 100).toFixed(2);
                $('#other_expenses' + i).val(prop);
            }
            
            // Row total of Grid 1
            for(i=1; i<=rowId; i++)
            {
                rowCalculate(i)
            }
        });
    });
</script>




@stop