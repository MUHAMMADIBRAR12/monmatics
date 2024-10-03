@extends('layout.master')
@section('title','D.O Tracking View')
@section('parentPageTitle','Sales FMCG')
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
            <h2><strong>Inward Gate Pass</strong> Details</h2>
        </div>
        <div class="body">               
            <input type="hidden" name="id" value="{{ $product->id ?? ''  }}">
            <ul class="nav nav-tabs p-0 mb-3">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#home">General Detail</a></li>
               <!-- <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#attachments">Attachments</a></li> -->
            </ul>   
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane in active" id="home">    
                    <div class="row">
                        <div class="col-md-2">
                            <label for="name">Number</label>
                            <div class="form-group "><p class="text-primary">{{ $do_tracking->month ?? '' }}-{{appLib::padingZero($do_tracking->number  ?? '')}}</p></div>
                        </div>
                        <div class="col-md-2">
                            <label for="name">Date</label>
                            <div class="form-group "><p class="text-primary">{{ $do_tracking->do_tracking_date ?? ''  }}</p></div>
                        </div>                                               
                        <div class="col-md-2">
                            <label for="name">Delivery Order Date</label>
                            <div class="form-group "><p class="text-primary">{{ $do_tracking->date ?? ''  }}</p></div>
                        </div>                          
                        <div class="col-md-3">
                            <label for="sku">Warehouse</label>
                        <div class="form-group"><p class="text-primary">{{ $do_tracking->warehouse_name ?? ''  }}</p></div>
                        </div>                        
                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <label for="name">Customer</label>
                            <div class="form-group "><p class="text-primary">{{ $do_tracking->cust_name?? ''  }}</p></div>
                        </div>
                        <div class="col-md-2">
                            <label for="name">Address</label>
                            <div class="form-group "><p class="text-primary">{{ $do_tracking->address ?? ''  }}</p></div>
                        </div>                          
                        <div class="col-md-2">
                            <label for="sku">Town</label>
                            <div class="form-group"><p class="text-primary">{{ $do_tracking->location ?? ''  }}</p></div>
                        </div>                    
                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <label>Veh No.</label>
                            <div class="form-group"><p class="text-primary">{{ $do_tracking->veh_no ?? ''  }}</p></div>
                        </div>
                        <div class="col-md-2">
                            <label>Bilty No.</label>
                            <div class="form-group "><p class="text-primary">{{ $do_tracking->bilty_no ?? ''  }}</p></div>
                        </div>                          
                        <div class="col-md-2">
                            <label>Delivery Name</label>
                            <div class="form-group"><p class="text-primary">{{ $do->delivery_name ?? ''  }}</p></div>
                        </div>                        
                        <div class="col-md-3">
                            <label>Delivery Confirmation By</label>
                            <div class="form-group"><p class="text-primary">{{ $do->delivery_confirm_by ?? ''  }}</p></div>
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <label>Delivery Date</label>
                            <div class="form-group"><p class="text-primary">{{ $do_tracking->delivery_date ?? ''  }}</p></div>
                        </div>
                        <div class="col-md-2">
                            <label>Freight</label>
                            <div class="form-group "><p class="text-primary">{{ $do_tracking->freight ?? ''  }}</p></div>
                        </div>                          
                        <div class="col-md-2">
                            <label>Transit Loss</label>
                            <div class="form-group"><p class="text-primary">{{ $do->transit_loss ?? ''  }}</p></div>
                        </div>                        
                        <div class="col-md-2">
                            <label>Other Amount</label>
                            <div class="form-group"><p class="text-primary">{{ $do->other_amount ?? ''  }}</p></div>
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="sku">Note</label>
                            <div class="form-group"><p class="text-primary">{{ $do->remarks ?? ''  }}</p></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="table-responsive">
                            <table  class="table table-striped m-b-0">
                                <thead>
                                    <tr class="bg-purple">
                                        <th class="table_header ">Product</th>
                                        <th class="table_header table_header_100">Qty Issue</th>
                                        <th class="table_header table_header_100">Qty Delivered</th>
                                        <th class="table_header table_header_100">Remarks</th>
                                    </tr>
                                </thead> 
                                <tbody>
                                    @foreach($do_tracking_detail as $detail)
                                    <tr>
                                        <td class="text-center">{{$detail->prod_name}}</td>
                                        <td class="text-center">{{$detail->qty_delivery}}</td>
                                        <td class="text-center">{{$detail->qty_delivered}}</td>
                                        <td class="text-center">{{$detail->remarks}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>           
                        </div>
                    </div>    
                </div>
                <!-- <div role="tabpanel" class="tab-pane" id="attachments">            
                    <div class="row">
                        
                    </div>          
                </div> -->
            </div>
            @if($igp->editable ?? ''==1)
            <div class="row">
                <table class=" table-responsive align-center">
                    <tr>
                        <td><button class="btn btn-primary" onclick="window.location.href = '{{ url('Inventory/IGP/Create/'.$igp->id) }}';"><i class="zmdi zmdi-edit"></i>  Edit</button></td>
                    </tr>
                </table>
            </div>
            @endif
           
        </div>
</div>
</div>
@stop
