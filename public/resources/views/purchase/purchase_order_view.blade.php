@extends('layout.master')
@section('title', 'Purchase')
@section('parentPageTitle', 'Purchase Order')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')
<?php  use App\Libraries\appLib; ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{asset('public/assets/plugins/dropify/css/dropify.min.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/css/sw.css')}}"/>
<style>
/* .input-group-text {
    padding: 0 .75rem;
}
.table td{
    padding: 0.2rem;
}
.dropify
{
    width: 200px;
    height: 200px;
} */
</style>
@stop
@section('content')
<div class="row clearfix">
    <div class="card col-lg-12">
        <div class="header">
            <h2><strong>Purchase Order</strong> Details</h2>
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
                            <div class="form-group "><p class="text-primary">{{ $purchaseOrder->month ?? '' }}-{{appLib::padingZero($purchaseOrder->number  ?? '')}}</p></div>
                        </div>
                        <div class="col-md-2">
                            <label for="name">Date</label>
                            <div class="form-group "><p class="text-primary">{{  date(appLib::showDateFormat(), strtotime($purchaseOrder->date ?? '')) }}</p></div>
                        </div>
                        <div class="col-md-2">
                            <label for="sku">Warehouse</label>
                        <div class="form-group"><p class="text-primary">{{ $purchaseOrder->warehouse_name ?? ''  }}</p></div>
                        </div>
                        <div class="col-md-2">
                            <label for="sku">status</label>
                            <div class="form-group"><p class="text-primary">{{ $purchaseOrder->status ?? ''  }}</p></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <label for="name">P.O Type</label>
                            <div class="form-group "><p class="text-primary">{{ $purchaseOrder->month ?? '' }}-{{appLib::padingZero($purchaseOrder->number  ?? '')}}</p></div>
                        </div>
                        <div class="col-md-2">
                            <label for="name">P.R No</label>
                            <div class="form-group "><p class="text-primary">{{  date(appLib::showDateFormat(), strtotime($purchaseOrder->date ?? '')) }}</p></div>
                        </div>
                        <div class="col-md-2">
                            <label for="sku">Vendor</label>
                        <div class="form-group"><p class="text-primary">{{ $purchaseOrder->warehouse_name ?? ''  }}</p></div>
                        </div>
                        <div class="col-md-2">
                            <label for="sku">Purchaser</label>
                            <div class="form-group"><p class="text-primary">{{ $purchaseOrder->purchaser ?? ''  }}</p></div>
                        </div>
                        <div class="col-md-2">
                            <label for="sku">Importance</label>
                            <div class="form-group"><p class="text-primary">{{ $purchaseOrder->importance ?? ''  }}</p></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="sku">Note</label>
                            <div class="form-group"><p class="text-primary">{{ $purchaseOrder->note ?? ''  }}</p></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="table-responsive">
                            <table  class="table table-striped m-b-0">
                                <thead>
                                    <tr class="bg-purple" style="line-height: 15px;">
                                        <th class="table_header" >Product</th>
                                        <th class="table_header">Description</th>
                                        <th class="table_header">Unit</th>
                                        <th class="table_header" >Qty Order</th>
                                        <th class="table_header">Rate</th>
                                        <th class="table_header">Amount</th>
                                        <th class="table_header">Discount %</th>
                                        <th class="table_header">Discount Amount</th>
                                        <th class="table_header">Tax %</th>
                                        <th class="table_header">Tax Amount</th>
                                        <th class="table_header">Delivery charges</th>
                                        <th class="table_header">Net Amount</th>
                                        <th class="table_header">Required Date</th>
                                        <th class="table_header">Packing Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($purchaseOrderDetails as $poDetail)
                                    <tr>
                                        <td>{{$poDetail->name ?? ''}}</td>
                                        <td>{{$poDetail->description ?? ''}}</td>
                                        <td class="text-center">{{$poDetail->unit ?? ''}}</td>
                                        <td class="text-center">{{ Str::currency($poDetail->qty ?? '0')}}</td>
                                        <td class="text-center">{{ Str::currency($poDetail->rate ?? '0')}}</td>
                                        <td class="text-center">{{$poDetail->amount ?? ''}}</td>
                                        <td class="text-center">{{Str::currency($poDetail->discount ?? '0')}}</td>
                                        <td class="text-center">{{ Str::currency($poDetail->discount_amount ?? '0')}}</td>
                                        <td class="text-center">{{ Str::currency($poDetail->tax_percent ?? '0')}}</td>
                                        <td class="text-center">{{ Str::currency($poDetail->tax_amount ?? '0')}}</td>
                                        <td class="text-center">{{Str::currency($poDetail->delivery_charges ?? '0')}}</td>
                                        <td class="text-center">{{Str::currency($poDetail->net_amount ?? '0')}}</td>
                                        <td class="text-center">{{ date(appLib::showDateFormat(), strtotime($poDetail->required_by_date ?? ''))}}</td>
                                        <td class="text-center">{{$poDetail->packing_detail ?? ''}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
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
            @if($purchaseOrder->editable ?? ''==1)
            <div class="row">
                <table class=" table-responsive align-center">
                    <tr>
                        <td><button class="btn btn-primary" onclick="window.location.href = '{{ url('Purchase/PurchaseOrder/Create/'.$purchaseOrder->id) }}';"><i class="zmdi zmdi-edit"></i>  Edit</button></td>
                    </tr>
                </table>
            </div>
            @endif
        </div>
</div>
</div>
@stop
