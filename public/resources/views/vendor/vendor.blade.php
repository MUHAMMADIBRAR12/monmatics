@extends('layout.master')
@section('title', 'Vendor')
@section('parentPageTitle', 'Vendor')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{asset('public/assets/plugins/select2/select2.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/plugins/morrisjs/morris.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/plugins/jquery-spinner/css/bootstrap-spinner.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/plugins/bootstrap-select/css/bootstrap-select.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/plugins/nouislider/nouislider.min.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/plugins/dropify/css/dropify.min.css')}}"/>
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
<script lang="javascript/text">
var token =  "{{ csrf_token()}}";
var vendorURL= "{{ url('vendorSearch') }}";
var locationURL = "{{ url('locationSearch') }}";
</script>
@stop
@section('content')
    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="header">
                <h2>Vendor</h2>
            </div>
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            <div class="body">
                <form method="post" action="{{url('Vendor/VendorManagement/Add')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $vendor->pa_ven_id ?? ''}}">
                    <input type="hidden" name="coa_id" value="{{ $vendor->coa_id ?? ''}}">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="name">Name</label>
                            <div class="form-group">
                                <input type="text" name="name" id="vendor" value="{{ $vendor->name ?? ''  }}" placeholder="Vendor Name" class="form-control" onkeyup="autoFill(this.id, vendorURL, token)" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="category">Category</label>
                            <div class="form-group">
                                <select name="category" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                    <option value="">Select Category</option> 
                                    @foreach($categories as $category)
                                        <option {{ ( $category->category == ( $vendor->category ??'')) ? 'selected' : '' }} value="{{ $category->category}}" >{{ $category->category}}</option> 
                                    @endforeach     
                                </select>
                            </div>
                        </div> 
                    </div>
                    <div class="row">                        
                        <div class="col-md-6">
                            <label for="coa_id">Main Account</label>
                            <div class="form-group">
                                <select name="parent_coa_id" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                    <option value="">Select Account</option> 
                                    @foreach($coaAccount as $account)
                                        <option {{ ( $account->coa_id == ( $vendor->coa_coa_id ??'')) ? 'selected' : '' }} value="{{ $account->coa_id}}" >{{ $account->name}}</option> 
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="code">Account Code</label>
                            <div class="form-group">
                                <input type="autofill" name="code" class="form-control" value="{{ $vendor->code ?? ''  }}" placeholder="Account Code" required>
                            </div>
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="type">Credit Limit</label>
                            <div class="form-group">
                                <select name="credit_limit" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">--Select Credit Limit--</option> 
                                    @foreach($credit_limits as $credit_limit)
                                        <option  {{ ( $credit_limit->description == ($vendor->credit_limit ?? '')) ? 'selected' : '' }}  value="{{ $credit_limit->description}}" >{{ $credit_limit->description}}</option> 
                                    @endforeach
                                </select>    
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="type">Type</label>
                            <div class="form-group">
                                <select name="type" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">--Select Type--</option> 
                                    @foreach($vendor_types as $vendor_type)
                                        <option  {{ ( $vendor_type->description == ($vendor->type ?? '')) ? 'selected' : '' }}  value="{{ $vendor_type->description}}" >{{ $vendor_type->description}}</option> 
                                    @endforeach
                                </select>    
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="code">Tax Number</label>
                            <div class="form-group">
                                <input type="text" name="tax_number" class="form-control" value="{{ $vendor->tax_number ?? ''  }}" placeholder="Tax Number">
                            </div>
                        </div> 
                    </div>
                    <div class="row">                        
                        <div class="col-md-6">
                            <label for="note">Note</label>
                            <div class="form-group"><textarea name="note" rows="4" class="form-control no-resize" placeholder="Description">{{ $vendor->note ?? ''  }}</textarea>
                            </div>
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="location">Location</label>
                            <div class="form-group">
                                <input type="autofill" name="location" id="location" class="form-control" value="{{ $vendor->location ?? ''  }}" placeholder="Location Name" onkeyup="autoFill(this.id, locationURL, token)">
                                <input type="hidden" name="location_ID" id="location_ID">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="phone">Phone</label>
                            <div class="form-group">
                                <input type="autofill" name="phone" class="form-control" value="{{ $vendor->phone ?? ''  }}" placeholder="Phone"  required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="email">Email</label>
                            <div class="form-group">
                                <input type="email" name="email" class="form-control" value="{{ $vendor->email ?? ''  }}" placeholder="Email"  >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="fax">Fax</label>
                            <div class="form-group">
                                <input type="autofill" name="fax" class="form-control" value="{{ $vendor->fax ?? ''  }}" placeholder="Fax">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <label for="address">Address</label>
                            <div class="form-group"><textarea name="address" rows="3" class="form-control no-resize" placeholder="Location Address">{{ $vendor->address ?? ''  }}</textarea>
                            </div>
                        </div>                       
                    </div>

                    
                    <div class="row">
                        <div class="ml-auto">
                        
                        <div class="form-group">
                          <input type="radio" id="active" name="status" value="1" {{ (( $vendor->status ??'') == 1) ? 'checked' : '' }}>
                          <label for="male">Active</label>
                          <input type="radio" id="inactive" name="status" value="0" {{ (( $vendor->status ??'') == 0) ? 'checked' : '' }} >
                          <label for="female">InActive</label>                     
                        </div>
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

@stop