@extends('layout.master')
@section('title', 'GRN-WIP')
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
                            <div class="form-group "><p class="text-primary">{{  $grn->month ?? '' }}-{{appLib::padingZero( $grn->number  ?? '')}}</p></div>
                        </div>
                        <div class="col-md-2">
                            <label for="name">Date</label>
                            <div class="form-group "><p class="text-primary">{{ $grn->date ?? ''  }}</p></div>
                        </div>                          
                        <div class="col-md-2">
                            <label for="sku">Warehouse</label>
                        <div class="form-group"><p class="text-primary">{{  $grn->warehouse_name ?? ''  }}</p></div>
                        </div>                        
                        <div class="col-md-2">
                            <label for="sku">Batch</label>
                            <div class="form-group"><p class="text-primary">{{  $grn->batch ?? ''  }}</p></div>
                        </div>
                        <div class="col-md-2">
                            <label for="sku">Account</label>
                            <div class="form-group"><p class="text-primary">{{  $grn->vendor_name ?? ''  }}</p></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <label for="name">Product</label>
                            <div class="form-group "><p class="text-primary">{{ $grn->prod_name ?? ''  }}</p></div>
                        </div>
                        <div class="col-md-2">
                            <label for="name">Unit</label>
                            <div class="form-group "><p class="text-primary">{{ $grn->unit ?? ''  }}</p></div>
                        </div>                          
                        <div class="col-md-2">
                            <label for="sku">Qty</label>
                            <div class="form-group"><p class="text-primary">{{ $grn->qty_in ?? ''  }}</p></div>
                        </div>  
                        <div class="col-md-2">
                            <label for="sku">Total Production Cost</label>
                            <div class="form-group"><p class="text-primary">{{ $grn->amount ?? '' }}</p></div>
                        </div> 
                        <div class="col-md-2">
                            <label for="sku">Unit Cost</label>
                            <div class="form-group"><p class="text-primary">{{ $grn->rate ?? ''  }}</p></div>
                        </div>                       
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="sku">Note</label>
                            <div class="form-group"><p class="text-primary">{{ $grn->note ?? ''  }}</p></div>
                        </div>
                    </div>
                    <div class="row">
                        <h6>Product Issue</h6>
                        <div class="table-responsive">
                            <table  class="table table-striped m-b-0">
                                <thead>
                                    <tr class="bg-purple">
                                        <th class="table_header ">Product</th>
                                        <th class="table_header">Description</th>
                                        <th class="table_header table_header_100">Unit</th>
                                        <th class="table_header table_header_100">Qty</th>
                                        <th class="table_header table_header_100">Rate</th>
                                        <th class="table_header table_header_100">Amount</th>
                                    </tr>
                                </thead> 
                                <tbody>
                                    @foreach($pi_detail as $detail)
                                    <tr>
                                        <td>{{$detail->prod_name}}</td>
                                        <td>{{$detail->description}}</td>
                                        <td>{{$detail->unit}}</td>
                                        <td>{{$detail->qty}}</td>
                                        <td>{{$detail->rate}}</td>
                                        <td>{{$detail->amount}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>           
                        </div>
                    </div>    
                </div>
                    <div class="row mt-4">
                        <h6>Product Used By Vendor</h6>
                        <div class="table-responsive">
                            <table  class="table table-striped m-b-0">
                                <thead>
                                    <tr class="bg-purple">
                                        <th class="table_header ">Product</th>
                                        <th class="table_header">Description</th>
                                        <th class="table_header table_header_100">Unit</th>
                                        <th class="table_header table_header_100">Qty</th>
                                        <th class="table_header table_header_100">Rate</th>
                                        <th class="table_header table_header_100">Amount</th>
                                    </tr>
                                </thead> 
                                <tbody>
                                    @foreach($pv_detail as $detail)
                                    <tr>
                                        <td>{{$detail->prod_name}}</td>
                                        <td>{{$detail->description}}</td>
                                        <td>{{$detail->unit}}</td>
                                        <td>{{$detail->qty}}</td>
                                        <td>{{$detail->rate}}</td>
                                        <td>{{$detail->amount}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>           
                        </div>
                    </div>    
                </div>
                    <div class="row mt-4">

                        <h6>Services Used By Vendor</h6>
                        <div class="table-responsive">
                            <table  class="table table-striped m-b-0">
                                <thead>
                                    <tr class="bg-purple">
                                        <th class="table_header ">Product</th>
                                        <th class="table_header">Description</th>
                                        <th class="table_header table_header_100">Unit</th>
                                        <th class="table_header table_header_100">Qty</th>
                                        <th class="table_header table_header_100">Rate</th>
                                        <th class="table_header table_header_100">Amount</th>
                                    </tr>
                                </thead> 
                                <tbody>
                                    @foreach($sv_detail as $detail)
                                    <tr>
                                        <td>{{$detail->prod_id}}</td>
                                        <td>{{$detail->description}}</td>
                                        <td>{{$detail->unit}}</td>
                                        <td>{{$detail->qty}}</td>
                                        <td>{{$detail->rate}}</td>
                                        <td>{{$detail->amount}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>           
                        </div>
                    </div>    
                </div>
                <div role="tabpanel" class="tab-pane" id="attachments">            
                    <div class="row">
                          
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
