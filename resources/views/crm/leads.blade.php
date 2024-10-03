@extends('layout.master')
@section('title','Leads')
@section('parentPageTitle', 'Crm')
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
var CustomerURL = "{{ url('customerSearch') }}";
var locationURL = "{{ url('locationSearch') }}";
var token =  "{{ csrf_token()}}";
</script>
@stop

@section('content')

    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="header">
                <h2><strong>Leads</strong> Details</h2>
            </div>
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            <div class="body">
                <form method="post" action="{{url('Crm/Leads/Add')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $customer->id ?? ''  }}">                    
                    <div class="row">
                        <div class="col-md-6">
                            <label for="name">Name</label>
                            <div class="form-group">
                                <input type="text" name="name" id="customer" onkeyup="autoFill(this.id,CustomerURL,token)" value="{{ $customer->name ?? ''  }}" class="form-control" required>
                               <!-- <input type="autofill" name="name" class="form-control" value="{{ $customer->name ?? ''  }}" placeholder="Name"  required>  -->
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="category">Category</label>
                            <div class="form-group">
                                <select name="category" class="form-control show-tick ms select2" data-placeholder="Select" >
                                    <option value="">Select Category</option> 
                                    @foreach($categories as $category)
                                        <option value="{{ $category->category}}" {{ ( $category->category == ($customer->category ?? '')) ? 'selected' : '' }}>{{ $category->category}}</option> 
                                    @endforeach
                                </select>
                            </div>
                        </div> 
                    </div>
                    
                    
                    <div class="row">
                        <div class="col-md-3">
                            <label for="lead_source">Lead Source</label>
                            <select name="lead_source" class="form-control show-tick ms select2" data-placeholder="Select">
                                <option value="">Select Type</option>
                                @foreach( $lead_sources as  $lead_source)
                                    <option value="{{ $lead_source->description}}" {{ (  $lead_source->description == (  $customer->lead_source ?? '')) ? 'selected' : '' }} >{{ $lead_source->description}}</option>  
                                @endforeach              
                            </select> 
                        </div>
                        <div class="col-md-3">
                            <label for="status">Status</label>
                            <div class="form-group">
                                <select name="status" class="form-control show-tick ms select2" data-placeholder="Select" >
                                    <option value="">--Select Status--</option> 
                                    <option  {{ (($customer->status ?? '') =='Active') ? 'selected' : '' }}  value="Active" >Active</option> 
                                    <option  {{ (($customer->status ?? '') =='Suspended') ? 'selected' : '' }}  value="Suspended" >Suspended</option> 
                                    <option  {{ (($customer->status ?? '') =='Closed') ? 'selected' : '' }}  value="Closed" >Closed</option> 
                                </select>    
                            </div>
                        </div> 
                        <div class="col-md-3">
                            <label for="type">Type</label>
                            <div class="form-group">
                                <select name="type" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">--Select Type--</option> 
                                    @foreach($customer_types as $customer_type)
                                        <option  {{ ( $customer_type->description == ($customer->type ?? '')) ? 'selected' : '' }}  value="{{ $customer_type->description}}" >{{ $customer_type->description}}</option> 
                                    @endforeach
                                </select>    
                            </div>
                        </div>
                    </div>
                   
                    <div class="row">
                        <div class="col-md-4">
                            <label for="phone">Phone</label>
                            <div class="form-group">
                                <input type="autofill" name="phone[]" class="form-control" value="{{ $customerAddress->phone ?? '' }}" placeholder="Phone">
                            </div>
                            @error('phone.*')
                                <div class="alert alert-danger">Phone format is invalid</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="email">Email</label>
                            <div class="form-group">
                                <input type="text" name="email[]" class="form-control" value="{{ $customerAddress->email ?? '' }}" placeholder="Email">
                            </div>
                            @error('email.*')
                                <div class="alert alert-danger">Email format is invalid</div>
                            @enderror
                        </div>
                    </div>
                    
                        <!-- <div class="col-md-4">
                            <label for="fax">Fax</label>
                            <div class="form-group">
                                <input type="autofill" name="fax[]" class="form-control" value="{{ $customerAddress->fax ?? ''  }}" placeholder="Fax">
                            </div>
                        </div> -->
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="note">Note</label>
                            <div class="form-group">
                                <textarea name="note" rows="4" class="form-control no-resize" placeholder="Description">{{ $customer->note ?? ''  }}</textarea>
                            </div>
                        </div> 
                        <div class="col-md-8">
                            <label for="address">Address</label>
                            <div class="form-group"><textarea name="address[]" rows="3" class="form-control no-resize" placeholder="Location Address">{{ $customerAddress->address ?? ''  }}</textarea>
                            </div>
                        </div>                       
                    </div>
                    <div class="row" style="display:none">
                        @for($i=0;$i<=3;$i++)
                            <div class="col-md-3">
                                <label for="attachment">Attachment {{ $i+1 }}</label>
                                <div class="form-group">
                                    @if($attachments[$i] ?? '')                                    
                                        <button  type="button" class="btn btn-danger btn-sm" onclick="alert('Delete option is not available now.');"><i class="zmdi zmdi-delete"></i></button>
                                        <a href="{{asset('assets/products/'.$attachments[$i]->file)}}" download >{{$attachments[$i]->file}}</a>
                                    @else
                                        <input name="file[]" type="file" class="dropify">
                                    @endif
                                </div>
                            </div> 
                        @endfor
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

@stop