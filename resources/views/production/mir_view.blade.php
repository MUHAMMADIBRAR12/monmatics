@extends('layout.master')
@section('title', 'Production')
@section('parentPageTitle', 'Material Issue Request')
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
            <h2><strong>Material Issue</strong> Details</h2>
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
                            <div class="form-group "><p class="text-primary">{{ $mir->month ?? '' }}-{{appLib::padingZero($mir->number  ?? '')}}</p></div>
                        </div>
                        <div class="col-md-2">
                            <label for="name">Date</label>
                            <div class="form-group "><p class="text-primary">{{ date(appLib::showDateFormat(), strtotime($mir->date ?? ''))  }}</p></div>
                        </div>
                        <div class="col-md-2">
                            <label for="sku">Warehouse</label>
                        <div class="form-group"><p class="text-primary">{{ $mir->warehouse_name ?? ''  }}</p></div>
                        </div>
                        <div class="col-md-2">
                            <label for="sku">status</label>
                            <div class="form-group"><p class="text-primary">{{ $mir->status ?? ''  }}</p></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <label for="name">Person</label>
                            <div class="form-group "><p class="text-primary">{{ $mir->person ?? ''  }}</p></div>
                        </div>
                        <div class="col-md-2">
                            <label for="name">Department</label>
                            <div class="form-group "><p class="text-primary">{{ $mir->department_name ?? ''  }}</p></div>
                        </div>
                        <div class="col-md-2">
                            <label for="sku">Batch No.</label>
                        <div class="form-group"><p class="text-primary">{{ $mir->batch ?? ''  }}</p></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="sku">Note</label>
                            <div class="form-group"><p class="text-primary">{{ $mir->note ?? ''  }}</p></div>
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
                                        <th class="table_header table_header_100">Qty in Stock</th>
                                        <th class="table_header table_header_100">Qty Order</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($mirDetails as $mirDetail)
                                    <tr>
                                        <td class="text-center">{{$mirDetail->name ?? ''}}</td>
                                        <td class="text-center">{{$mirDetail->description ?? ''}}</td>
                                        <td class="text-center">{{$mirDetail->unit ?? ''}}</td>
                                        <td class="text-center">{{$mirDetail->qty_in_stock ?? ''}}</td>
                                        <td class="text-center">{{$mirDetail->qty_order ?? ''}}</td>
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
            @if($mir->editable ?? ''==1)
            <div class="row">
                <table class=" table-responsive align-center">
                    <tr>
                        <td><button class="btn btn-primary" onclick="window.location.href = '{{ url('Production/MIR/Create/'.$mir->id) }}';"><i class="zmdi zmdi-edit"></i>  Edit</button></td>
                    </tr>
                </table>
            </div>
            @endif

        </div>
</div>
</div>
@stop
