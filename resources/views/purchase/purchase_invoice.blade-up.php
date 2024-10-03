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
</style>
<script lang="javascript/text">
    var CustomerURL = "{{ url('customerSearch') }}";
    var token = "{{ csrf_token()}}";
    var ItemURL = "{{ url('itemSearch') }}";
    var getItemDetailURL = "{{ url('getItemDetail') }}";
    var TaxRateURL = "{{ url('getTaxRates') }}";
    var rowId = 1;
    var attachmentURL = "{{ url('prAttachmentDelete') }}";
    var vendorURL = "{{ url('vendorSearch') }}";
    var searchCoaURL = "{{ url('coaSearch')}}";
</script>
<script>
    function addRow() {
        rowId++;
        var row = '<tr id="row' + rowId + '">' +
            '<td>' +
            '<button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow(\'row' + rowId + '\');totalAmount()"><i class="zmdi zmdi-delete"></i></button>' +
            '</td>' +
            '<td>' +
            '<input type="number" name="code[]" id="code" class="form-control" value="">' +
            '</td>' +
            '<td>' +
            '<input type="text" name="name[]" id="name' + rowId + '" class="form-control autocomplete" onkeyup="autoFill(this.id, searchCoaURL, token)" value="" required>' +
            '<input type="hidden" name="name_ID[]" id="name' + rowId + '_ID" required>' +
            '</td>' +
            '<td>' +
            '<input type="text" name="description[]"  class="form-control" value="">' +
            '</td>' +
            '<td>' +
            '<input type="number" name="amount[]" class="amount form-control"  step="0.01" value="" onblur="totalAmount()" required>' +
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
                            <select name="grn_id" id="grn_id" class="form-control show-tick ms select2" data-placeholder="Select" required>
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
                                    <th class="table_header table_header_100">Tax %</th>
                                    <th class="table_header table_header_100">Tax Amount</th>
                                    <th class="table_header table_header_100">Discount %</th>
                                    <th class="table_header table_header_100">Discount Amount</th>
                                    <th class="table_header table_header_100">Delivery Charges</th>
                                    <th class="table_header table_header_100">Net Amount</th>
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
                                            <input type="hidden" name="item_ID[]" value="{{ $lineItem->prod_id ?? ''  }}">
                                            <input type="hidden" name="prod_coa_id" value="{{ $lineItem->prod_coa_id ?? ''  }}">

                                        </td>
                                        <td>
                                            <!-- unit -->
                                            <label>{{ $lineItem->unit ?? ''  }}</label>
                                            <input type="hidden" name="unit[]" value="{{ $lineItem->unit ?? ''  }}">
                                        </td>
                                        <td>
                                            <!-- qty -->
                                            <label class="qty">{{ Str::currency($lineItem->qty ?? '0')  }}</label>
                                            <input type="hidden" name="qty[]" value="{{ Str::currency($lineItem->qty ?? '0')  }}">
                                        </td>
                                        <td>
                                            <!-- rate -->
                                            <label class="qty">{{ Str::currency($lineItem->rate ?? '0')  }}</label>
                                            <input type="hidden" name="rate[]" value="{{ Str::currency($lineItem->rate ?? '0')  }}">
                                        </td>
                                        <td>
                                            <!-- amount -->
                                            <label class="qty">{{ Str::currency($amount ?? '0')  }}</label>
                                            <input type="hidden" name="gross_amount[]" id="amount{{$i}}" class="gross_amount" value="{{ Str::currency($amount ?? '0')  }}">
                                        </td>
                                        <td>
                                            <!-- tax % -->
                                            <input type="number" step="any" name="tax[]" id="tax{{$i}}" class="form-control qty" value="{{ Str::currency($lineItem->tax_percent ?? '0')  }}" onblur="rowCalculate({{$i}})">
                                        </td>
                                        <td>
                                            <!-- tax Amount -->
                                            <input type="text" name="tax_amount[]" id="tax_amount{{$i}}" class="form-control qty tax_amount" value="{{ Str::currency($lineItem->tax_amount ?? '0')  }}" readonly>
                                        </td>
                                        <td>
                                            <!-- Discount % -->
                                            <input type="number" step="any" name="discount[]" id="discount{{$i}}" class="form-control qty" value="{{ Str::currency($lineItem->disc_percent ?? '0')  }}" onblur="rowCalculate({{$i}})">
                                        </td>
                                        <td>
                                            <!-- Discount amount  -->
                                            <input type="number" name="discount_amount[]" id="discount_amount{{$i}}" value="{{ Str::currency($lineItem->disc_amount ?? '0')  }}" class="form-control qty discount_amount" readonly>
                                        </td>
                                        <td>
                                            <!-- delivery charges -->
                                            <input type="number" step="any" name="delivery_charges[]" id="delivery_charges{{$i}}" value="{{  Str::currency($lineItem->delivery_charges ?? '0')  }}" class="form-control qty delivery_charges" onblur="rowCalculate({{$i}})">
                                        </td>
                                        <td>
                                            <!-- Net amount -->
                                            <input type="text" name="net_amount[]" id="net_amount{{$i}}" value="{{ Str::currency($lineItem->net_amount ?? '') }}" class="form-control qty net_amount" readonly>
                                        </td>
                                    </tr>
                                    @endif
                                    @php
                                    }
                                    @endphp
                            </tbody>
                            <script>
                                rowId = {
                                    {
                                        $i
                                    }
                                };
                            </script>
                        </table>
                    </div>
                </div>
                <div class="row" style="margin-top: 3%;">
                    <div class="table-responsive">
                        <table id="voucher" class="table table-striped m-b-0">
                            <thead>
                                <tr class="bg-purple">
                                    <th><button type="button" class="btn btn-primary" style="align:right" onclick="addRow();">+</button></th>
                                    <th>A/c Code</th>
                                    <th>Account Name</th>
                                    <th>Narration</th>
                                    <th>Amount</th>

                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $count = (isset($transDetail))?count($transDetail):1;

                                for($i=1;$i<=$count; $i++) { if(isset($transDetail)) { $trans=$transDetail[($i-1)]; $amount=($trans->credit>0)?$trans->credit:$trans->debit;
                                    }
                                    @endphp
                                    <tr id="row{{$i}}">
                                        <td>
                                            <!-- Delete Button -->
                                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteRow('row{{$i}}');totalAmount();"><i class="zmdi zmdi-delete"></i></button>
                                        </td>

                                        <td>

                                            <input type="number" name="code[]" id="code" class="form-control" value="{{ $trans->code ?? ''  }}">
                                        </td>

                                        <td>
                                            <!-- Account Name -->
                                            <input type="text" name="name[]" id="name{{$i}}" onkeyup="autoFill(this.id, searchCoaURL, token)" value="{{ $trans->name ?? ''  }}" class="form-control autocomplete" required>
                                            <input type="hidden" name="name_ID[]" id="name{{$i}}_ID" value="{{ $trans->coa_id ?? ''  }}" required>
                                        </td>
                                        <td>
                                            <!-- Description -->
                                            <input type="text" name="description[]" class="form-control" value="{{ $trans->description ?? '' }}">
                                        </td>
                                        <td>
                                            <!-- Amount -->
                                            <input type="number" step="any" name="amount[]" class="form-control amount" value="{{ $amount ?? '' }}" onblur="totalAmount();" required>
                                        </td>

                                        <!-- <td  class="project">                                            
                                            <input type="text" name="cost_center[]" id="cost_center{{$i}}" class="form-control" value="{{ $trans->project_name ?? '' }}" onkeyup="autoFill(this.id, projectURL, token)">
                                            <input type="hidden" name="cost_center_ID[]" id="cost_center{{$i}}_ID" value="{{ $trans->cost_center_id ?? '' }}">
                                        </td> -->

                                    </tr>
                                    @php } @endphp
                            </tbody>
                            <script>
                                totalAmount();
                                rowId = {
                                    {
                                        $i
                                    }
                                };
                            </script>
                        </table>
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
                                <input type="text" class="form-control qty" name="gross_amount" id="gross_amount" readonly>
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
                            '<input type="hidden" name="qty[]"   value="' + val.qty + '">' +
                            '</td>' +
                            '<td>' +
                            //rate
                            '<label class="qty">' + val.rate + '</label>' +
                            '<input type="hidden" name="rate[]"   value="' + val.rate + '">' +
                            '</td>' +
                            '<td>' +
                            //amount
                            '<label class="qty">' + val.amount + '</label>' +
                            '<input type="hidden" name="gross_amount[]" id="amount' + rowId + '" class="gross_amount"  value="' + val.amount + '">' +
                            '</td>' +
                            '<td>' +
                            //tax %
                            '<input type="number" step="any" name="tax[]" id="tax' + rowId + '" class="form-control qty" onblur="rowCalculate(' + rowId + ')">' +
                            '</td>' +
                            '<td>' +
                            //tax Amount
                            '<input type="text"  name="tax_amount[]" id="tax_amount' + rowId + '" class="form-control qty tax_amount"  readonly>' +
                            '</td>' +
                            '<td>' +
                            //Discount %
                            '<input type="number" step="any" name="discount[]" id="discount' + rowId + '" class="form-control qty" onblur="rowCalculate(' + rowId + ')">' +
                            '</td>' +
                            '<td>' +
                            //Discount amount
                            '<input type="text"  name="discount_amount[]" id="discount_amount' + rowId + '" class="form-control qty discount_amount" readonly>' +
                            '</td>' +
                            '<td>' +
                            //Delivery Charges
                            '<input type="number" step="any" name="delivery_charges[]" id="delivery_charges' + rowId + '" class="form-control delivery_charges qty" onblur="rowCalculate(' + rowId + ')">' +
                            '</td>' +
                            '<td>' +
                            //Net amount
                            '<input type="text"  name="net_amount[]" id="net_amount' + rowId + '" class="form-control qty net_amount" readonly>' +
                            '</td>' +
                            '</tr>';
                        $('#table').append(rw);
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

        amount = $('#amount' + num).val();
        tax = ($('#tax' + num).val() == '') ? 0 : $('#tax' + num).val();
        tax_amount = percentVal(tax, amount);
        $('#tax_amount' + num).val(tax_amount);
        discount = ($('#discount' + num).val() == '') ? 0 : $('#discount' + num).val();
        discount_amount = percentVal(discount, amount);
        $('#discount_amount' + num).val(discount_amount);
        delivery_charges = $('#delivery_charges' + num).val();
        netAmount = parseFloat(amount) + parseFloat(tax_amount) - parseFloat(discount_amount) + parseFloat(delivery_charges);
        $('#net_amount' + num).val(netAmount);
        sumAllFields();

        /*
           
           
          
          // delivery_charges=($('#delivery_charges'+num).val()=='')?  0 : $('#delivery_charges'+num).val();
          
         //  delivery_charges= delivery_charges,amount;
          

           //
           $('#net_amount'+num).val((getNum(amount,2)) + (+getNum(tax_amount,2)) - (getNum(discount_amount)));
           
           */
    }

    function sumAllFields() {
        $('#gross_amount').val(sumAll('gross_amount'));
        $('#total_discount').val(sumAll('discount_amount'));
        $('#total_tax').val(sumAll('tax_amount'));
        $('#total_delivery_charges').val(sumAll('delivery_charges'));
        $('#total_net_amount').val(sumAll('net_amount'));
    }
</script>



@stop