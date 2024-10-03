@extends('layout.master')
@section('title', 'Inventory')
@section('parentPageTitle', 'Products')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')

<style>
.input-group-text {
    padding: 0 .75rem;
}

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
@stop
@section('content')
<div class="row clearfix">
    <div class="card col-lg-12">
        <div class="header">
            <h2><strong>{{ $product->name ?? ''  }}</strong> Details</h2>
        </div>
        <div class="body">               
            <input type="hidden" name="id" value="{{ $product->id ?? ''  }}">
            <ul class="nav nav-tabs p-0 mb-3">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#home">Product Detail</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#attachments">Attachments & Barcode</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#ledger">Product Ledger</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#settings">SETTINGS</a></li>
            </ul>   
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane in active" id="home">    
                <div class="row">
                    <div class="col-md-3">
                        <label for="name">Name</label>
                        <div class="form-group "><p class="text-primary">{{ $product->name ?? ''  }}</p></div>
                    </div>                        
                    <div class="col-md-3">
                        <label for="sku">SKU</label>
                       <div class="form-group"><p class="text-primary">{{ $product->sku ?? ''  }}</p></div>
                    </div>                        
                    <div class="col-md-3">
                        <label for="sku">UPC/Code</label>
                       <div class="form-group"><p class="text-primary">{{ $product->code ?? ''  }}</p></div>
                    </div>
                </div>
                <div class="row">                        
                    <div class="col-md-3">
                        <label for="coa_id">Main Account</label>
                       <div class="form-group"><p class="text-primary">{{$product->coa_id  }}</p>  </div>
                    </div>                        
                    <div class="col-md-3">
                        <label for="category">Category</label>
                       <div class="form-group"><p class="text-primary">{{$product->category}}</p></div>
                    </div>                        
                    <div class="col-md-3">
                        <label for="type">Type</label>
                        <div class="form-group"><p class="text-primary">{{$product->type}}</p></div>
                    </div>                        
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label for="primary_unit">Primary Unit</label>
                       <div class="form-group"><p class="text-primary">{{$product->primary_unit}}</p></div>
                    </div>                        
                    <div class="col-md-3">
                        <label for="secondary_unit">Secondary Unit</label>
                       <div class="form-group"><p class="text-primary">{{$product->secondary_unit}}</p></div>
                    </div>                        
                    <div class="col-md-3">
                        <label for="reorder">Reorder Level</label>
                       <div class="form-group"><p class="text-primary">{{ $product->reorder ?? ''  }}</p></div>
                    </div>
                </div>
                <div class="row">                        
                    <div class="col-md-3">
                        <label for="purchase_price">Purchase Price</label>
                       <div class="form-group"><p class="text-primary">{{ $product->purchase_price}}</p></div>
                    </div> 
                    <div class="col-md-3">
                        <label for="sale_price">Sale Price</label>
                       <div class="form-group"><p class="text-primary">{{ $product->sale_price ?? ''  }}</p> </div>
                    </div> 
                </div>
                <div class="row">                        
                    <div class="col-md-4">
                        <label for="purchase_description">Purchase Description</label>
                       <div class="form-group"><p class="text-primary">{{ $product->purchase_description ?? ''  }}</p></div>
                    </div> 
                    <div class="col-md-4">
                        <label for="sales_description">Sales Description</label>
                       <div class="form-group"><p class="text-primary">{{ $product->sales_description ?? ''  }}</p></div>
                    </div> 
                    <div class="col-md-4">
                        <label for="description">Description</label>
                       <div class="form-group"><p class="text-primary">{{ $product->description ?? ''  }}</p> </div>
                    </div> 
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="attachments">            
                <div class="row">
                    @foreach($attachments as $attachment)
                        <div class="col-md-3">
                            <label for="attachment">Attachment</label>
                            <div class="form-group">                                                              
                                <a href="{{asset('assets/products/'.$attachment->file)}}" download >
                                    <img src="{{asset('assets/products/'.$attachment->file)}}">{{$attachment->file}}</img></a>                                    
                            </div>
                        </div> 
                    @endforeach
                </div>      
            </div>
        </div>
        <div class="row">
            <table class=" table-responsive align-center">
                <tr>
                    <td><button class="btn btn-primary" onclick="window.location.href = '{{ url('Inventory/Product/'.$product->id) }}';"><i class="zmdi zmdi-edit"></i> | Edit</button></td>
                    <td><button class="btn btn-primary" onclick="window.location.href = '{{ url('Inventory/Product/'.$product->id) }}';"><i class="zmdi zmdi-add"></i> | Add Units</button></td>
                </tr>
            </table>
        </div>
    </div>
</div>
</div>
@stop
