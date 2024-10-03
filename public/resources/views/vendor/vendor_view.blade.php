@extends('layout.master')
@section('title', 'Vendor Detail')
@section('parentPageTitle', 'Vendor')
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
            <h2><strong>{{ $vendor->name ?? ''  }}</strong> Details</h2>
        </div>
        <div class="body">               
            <ul class="nav nav-tabs p-0 mb-3">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#home">General</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#contacts">Contacts</a></li>
            </ul>   
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane in active" id="home">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="name">Name</label>
                            <div class="form-group "><p class="text-primary">{{ $vendor->name ?? ''  }}</p></div>
                        </div>                        
                        <div class="col-md-3">
                            <label for="sku">Category</label>
                            <div class="form-group"><p class="text-primary">{{ $vendor->category ?? ''  }}</p></div>
                        </div>                        
                        <div class="col-md-3">
                            <label for="sku">Type</label>
                            <div class="form-group"><p class="text-primary">{{ $vendor->type ?? ''  }}</p></div>
                        </div>
                    </div>
                    <div class="row">                        
                        <div class="col-md-3">
                            <label for="coa_id">Company Name</label>
                            <div class="form-group"><p class="text-primary">{{ $vendor->company_name ?? ''  }}</p>  </div>
                        </div>                        
                        <div class="col-md-3">
                            <label for="category">Mobile</label>
                            <div class="form-group"><p class="text-primary">{{ $vendor->phone ?? ''  }}</p></div>
                        </div>                        
                        <div class="col-md-3">
                            <label for="type">Email</label>
                            <div class="form-group"><p class="text-primary">{{ $vendor->email ?? ''  }}</p></div>
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="primary_unit">Address</label>
                            <div class="form-group"><p class="text-primary">{{ $vendor->address ?? ''  }}</p></div>
                        </div>                        
                        <div class="col-md-3">
                            <label for="secondary_unit">Location</label>
                            <div class="form-group"><p class="text-primary">{{ $vendor->location ?? ''  }}</p></div>
                        </div>                        
                        <div class="col-md-3">
                            <label for="reorder">Fax</label>
                            <div class="form-group"><p class="text-primary">{{ $vendor->fax ?? ''  }}</p></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="sale_price">Description</label>
                            <div class="form-group"><p class="text-primary">{{ $vendor->note?? ''  }}</p> </div>
                        </div> 
                    </div> 
                    <div class="row">
                        <table class=" table-responsive align-center">
                            <tr>
                                <td><button class="btn btn-primary" onclick="window.location.href = '{{ url('Vendor/VendorManagement/Create/'.$vendor->id) }}';"><i class="zmdi zmdi-edit"></i> | Edit</button></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="contacts">  
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover js-exportable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Title</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vendor_contacts as $contact)
                            <tr>
                                <td>{{$contact->contact_name}}</td>
                                <td>{{$contact->mobile}}</td>
                                <td>{{$contact->email}}</td>
                                <td>{{$contact->title}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('public/assets/js/sw.js')}}"></script>

@stop
