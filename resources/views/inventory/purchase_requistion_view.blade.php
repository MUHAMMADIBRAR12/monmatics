@extends('layout.master')
@section('title', 'Inventory')
@section('parentPageTitle', 'Purchase Requistion')
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
            <h2><strong>Purchase Requistion</strong> Details</h2>
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
                        <div class="col-md-3">
                            <label for="name">Number</label>
                            <div class="form-group "><p class="text-primary">{{ $purchaseRequestion->month ?? '' }}-{{appLib::padingZero($purchaseRequestion->number  ?? '')}}</p></div>
                        </div>
                        <div class="col-md-3">
                            <label for="name">Date</label>
                            <div class="form-group "><p class="text-primary">{{ $purchaseRequestion->date ?? ''  }}</p></div>
                        </div>                          
                        <div class="col-md-3">
                            <label for="sku">Warehouse</label>
                        <div class="form-group"><p class="text-primary">{{ $purchaseRequestion->warehouse_name ?? ''  }}</p></div>
                        </div>                        
                        <div class="col-md-3">
                            <label for="sku">status</label>
                            <div class="form-group"><p class="text-primary">{{ $purchaseRequestion->status ?? ''  }}</p></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="sku">Note</label>
                            <div class="form-group"><p class="text-primary">{{ $purchaseRequestion->note ?? ''  }}</p></div>
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
                                        <th class="table_header table_header_100">Qty in stock</th>
                                        <th class="table_header table_header_100">Reorder Level</th>
                                        <th class="table_header table_header_100">Qty Order</th>
                                        <th class="table_header table_header_100">Required By date</th>
                                        <th class="table_header table_header_100">Packing Detail</th>
                                    </tr>
                                </thead> 
                                <tbody>
                                    @foreach($purchaseRequestionDetails as $prDetail)
                                    <tr>
                                        <td>{{$prDetail->name ?? ''}}</td>
                                        <td>{{$prDetail->description ?? ''}}</td>
                                        <td class="text-center">{{$prDetail->unit ?? ''}}</td>
                                        <td class="text-center">{{$prDetail->qty_in_stock ?? ''}}</td>
                                        <td class="text-center">{{$prDetail->reorder_level ?? ''}}</td>
                                        <td class="text-center">{{$prDetail->qty_ordered ?? ''}}</td>
                                        <td class="text-center">{{$prDetail->required_by_date ?? ''}}</td>
                                        <td class="text-center">{{$prDetail->packing_detail ?? ''}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>           
                        </div>
                    </div>    
                </div>
                <div role="tabpanel" class="tab-pane" id="attachments">            
                    <div class="row">
                        @foreach($purchaseRequestionAttachment as $attachment)
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
            @if($purchaseRequestion->editable==1)
            <div class="row">
                <table class=" table-responsive align-center">
                    <tr>
                        <td><button class="btn btn-primary" onclick="window.location.href = '{{ url('Inventory/PurchaseRequistion/Create/'.$purchaseRequestion->id) }}';"><i class="zmdi zmdi-edit"></i>  Edit</button></td>
                    </tr>
                </table>
            </div>
            @endif
        </div>
</div>
</div>
@stop
