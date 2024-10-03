@extends('layout.master')
@section('title', 'Good Received')
@section('parentPageTitle', 'Inventory')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')
<?php  use App\Libraries\appLib; ?>

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
            <h2><strong>Good Received</strong> Details</h2>
        </div>
        <div class="body">
            <ul class="nav nav-tabs p-0 mb-3">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#home">General Detail</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#attachments">Attachments</a></li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane in active" id="home">
                    <div class="row">
                        <div class="col-md-2">
                            <label for="name">Number</label>
                            <div class="form-group "><p class="text-primary">{{  $goodReceived->month ?? '' }}-{{appLib::padingZero( $goodReceived->number  ?? '')}}</p></div>
                        </div>
                        <div class="col-md-2">
                            <label for="name">Date</label>
                            <div class="form-group "><p class="text-primary">{{  date(appLib::showDateFormat(), strtotime($goodReceived->date ?? ''))  }}</p></div>
                        </div>
                        <div class="col-md-2">
                            <label for="sku">Warehouse</label>
                        <div class="form-group"><p class="text-primary">{{  $goodReceived->warehouse_name ?? ''  }}</p></div>
                        </div>
                        <div class="col-md-2">
                            <label for="sku">Vendor</label>
                            <div class="form-group"><p class="text-primary">{{  $goodReceived->vendor_name ?? ''  }}</p></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <label for="name">P.O No.</label>
                            <div class="form-group "><p class="text-primary">{{ $goodReceived->po_num ?? ''  }}</p></div>
                        </div>
                        <div class="col-md-2">
                            <label for="name">P.O Date</label>
                            <div class="form-group "><p class="text-primary">{{ $goodReceived->po_date ?? ''  }}</p></div>
                        </div>
                        <div class="col-md-2">
                            <label for="sku">I.G.P No</label>
                            <div class="form-group"><p class="text-primary">{{ $goodReceived->igp_num ?? ''  }}</p></div>
                        </div>
                        <div class="col-md-2">
                            <label for="sku">I.G.P Date</label>
                            <div class="form-group"><p class="text-primary">{{  date(appLib::showDateFormat(), strtotime($goodReceived->igp_date ?? '')) }}</p></div>
                        </div>
                        <div class="col-md-2">
                            <label for="sku">D.O Ref</label>
                            <div class="form-group"><p class="text-primary">{{ $goodReceived->do_ref ?? ''  }}</p></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="sku">Note</label>
                            <div class="form-group"><p class="text-primary">{{ $goodReceived->note ?? ''  }}</p></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="table-responsive">
                            <table  class="table table-striped m-b-0">
                                <thead>
                                    <tr class="bg-purple">
                                        <th class="table_header ">Product</th>
                                        <th class="table_header">Description</th>
                                        <th class="table_header table_header_100">Unit</th>
                                        <th class="table_header table_header_100">Qty Received</th>
                                        <th class="table_header table_header_100">Qty Approved</th>
                                        <th class="table_header table_header_100">Qty rejected</th>
                                        <th class="table_header table_header_100">Packing Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($goodReceivedDetails as $goodReceivedDetail)
                                    <tr>
                                        <td class="text-center">{{$goodReceivedDetail->name}}</td>
                                        <td class="text-center">{{$goodReceivedDetail->description}}</td>
                                        <td class="text-center">{{$goodReceivedDetail->unit}}</td>
                                        <td class="text-center">{{$goodReceivedDetail->qty_received}}</td>
                                        <td class="text-center">{{$goodReceivedDetail->qty_approved}}</td>
                                        <td class="text-center">{{$goodReceivedDetail->qty_rejected}}</td>
                                        <td class="text-center">{{$goodReceivedDetail->packing_detail}}</td>
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
            @if($goodReceived->editable ?? ''==1)
            <div class="row">
                <table class=" table-responsive align-center">
                    <tr>
                        <td><button class="btn btn-primary" onclick="window.location.href = '{{ url('Inventory/GoodReceived/Create/'.$goodReceived->id) }}';"><i class="zmdi zmdi-edit"></i>  Edit</button></td>
                    </tr>
                </table>
            </div>
            @endif

        </div>
</div>
</div>
@stop
