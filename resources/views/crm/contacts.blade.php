@extends('layout.master')
@section('title', 'Contact Details')
@section('parentPageTitle', 'CRM')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')
<?php  use App\Libraries\appLib; ?>

<link rel="stylesheet" href="{{asset('assets/plugins/select2/select2.css')}}"/>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{asset('public/assets/plugins/morrisjs/morris.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/plugins/jquery-spinner/css/bootstrap-spinner.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/plugins/bootstrap-select/css/bootstrap-select.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/plugins/nouislider/nouislider.min.css')}}"/>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
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
fieldset{
border:1px solid  #ced4da;

}
</style>
<script lang="javascript/text">
var leadURL = "{{ url('leadSearch') }}";
var token =  "{{ csrf_token()}}";
var contactURL = "{{ url('contactsSearch') }}";
</script>
@stop
@section('content')

    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="header">
                <h2><strong>Contact</strong> Details</h2>
            </div>
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            <div class="body">

                <!-- form start -->
                <form method="post" action="{{url('Crm/Contacts/Add')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $contact->id ?? '' }}">
                    <input type="hidden" name="backURL" value="{{ $backURL ?? '' }}">
                    <!-- first row start -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-sm-4 col-md-4">
                                    <label for="type">Type</label>
                                    <select name="type" id="type" class="form-control show-tick ms select2">
                                        <option value="">Select Type</option>
                                        @php
                                            $types = appLib::$types;
                                        @endphp
                                        @foreach($types as $type)
                                            <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-8 col-md-8">
                                    <label for="fname">First Name</label>
                                    <input type="text" name="fname" id="contact" class="form-control" value="{{ old('fname', $contact->first_name ?? '') }}" placeholder="First Name" onkeyup="autoFill(this.id, contactURL, token)" required>
                                </div>
                            </div>
                            
                        </div>
                        <div class="col-md-4">
                            <label for="name">Last Name</label>
                            <div class="form-group">
                              <input type="text"  name="lname" class="form-control" value="{{ old('lname',$contact->last_name ?? '' )}}" placeholder="Last Name"  required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="title">Title</label>
                            <div class="form-group">
                               <input type="text" name="title" class="form-control" value="{{old('title',  $contact->title ?? '') }}" placeholder="Title">
                            </div>
                        </div>
                    </div>
                    <!-- first row end -->
                    <!-- Second row start -->
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label for="">Related To</label>
                            <div class="row">
                                <div class="col-sm-4 col-md-4">
                                    <select name="related_to_type" id="related_to" class="form-control show-tick ms select2">
                                        <option value="">Select</option>
                                        @php
                                            $related = appLib::$contact_related_to;
                                        @endphp
                                        @foreach($related as $related_to)
                                            <option value="{{$related_to}}" {{ ( $related_to == ( old('related_to_type') ?? $contact->related_to_type ?? $relatedTo ?? '' )) ? 'selected' : '' }} >{{$related_to}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-8 col-md-8">
                                    <input type="text" name="related" class="form-control related" id="{{ old('related_to_type') ?? $contact->related_to_type ?? '' }}" value="{{ old('related') ?? (isset($relatedToInfo) ? $relatedToInfo->name : '') }}" placeholder="Search" onkeyup="autoFill(this.id, url+'/'+this.id+'Search', token);">
                                    <input type="hidden" name="related_ID" class="related_ID" id="{{(isset($contact->related_to_type)) ? $contact->related_to_type.'_ID':''}}" value="{{  old('related_ID') ?? $relatedToInfo->id ?? '' }}">
                                </div>
                            </div>
                            
                            
                        </div>
                        <div class="col-md-6">
                            <label for="account">Assigned To</label>
                            <input type="text" name="assign" id="assign" class="form-control" value="{{ old('assign',$contact->user_name ?? '')  }}" placeholder="Contact" onkeyup="autoFill(this.id, userURL, token)">
                            <input type="hidden" name="assign_ID" id="assign_ID" value="{{ $contact->assigned_to ?? ''  }}" >
                        </div>
                    </div>
                    <!-- Second row end -->
                    <!-- Third row start -->
                    <div class="row mt-2">
                        <div class="col-md-4">
                            <label for="mobile">Mobile</label>
                            <div class="form-group">
                                <input type="text" name="mobile" class="form-control" value="{{ old('mobile',$contact->mobile ?? '') }}" placeholder="Mobile">
                                @error('mobile')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email',$contact->email ?? '') }}" placeholder="Email">
                            @error('email')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="office_phone">Phone Office</label>
                            <input type="text"  name="office_phone" class="form-control" value="{{ old('office_phone',$contact->phone_office ?? '') }}" placeholder="Office Phone No.">
                            @error('office_phone')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        </div>
                    </div>
                    <!-- Third row end -->
                    <!-- Fourth row start -->
                    <div class="row mt-2">
                        <div class="col-md-6">
                          <fieldset style="padding:1rem">
                            <legend style="font-size:1rem">Primary Address:</legend>
                            <label for="p_address">Address</label>
                            <input type="text"  name="pri_address" class="form-control" value="{{ old('pri_address',$contact->primary_address ?? '') }}" placeholder="Address" >
                            <label for="p_city" class="mt-3">City</label>
                            <input type="text"  name="pri_city" class="form-control" value="{{ old('pri_city',$contact->primary_city ?? '') }}" placeholder="City">
                            <label for="p_state" class="mt-3">State</label>
                            <input type="text"  name="pri_state" class="form-control" value="{{ old('pri_state',$contact->primary_state ?? '') }}" placeholder="State" >
                            <label for="p_code" class="mt-3">Postal Code</label>
                            <input type="text"  name="pri_code" class="form-control" value="{{ old('pri_code',$contact->primary_postal_code ?? '') }}" placeholder="Postal Code">
                            <label for="p_country" class="mt-3">country</label>
                            <input type="text"  name="pri_country" class="form-control" value="{{ old('pri_country',$contact->primary_country ?? '') }}" placeholder="Country">
                          </fieldset>
                        </div>
                        <div class="col-md-6">
                          <fieldset style="padding:1rem">
                            <legend style="font-size:1rem">Other Address:</legend>
                            <label for="p_address">Address</label>
                            <input type="text"  name="oth_address" class="form-control" value="{{ old('oth_address',$contact->other_address ?? '' ) }}" placeholder="Address">
                            <label for="p_city" class="mt-3">City</label>
                            <input type="text"  name="oth_city" class="form-control" value="{{ old('oth_city',$contact->other_city ?? '' )}}" placeholder="City">
                            <label for="p_state" class="mt-3">State</label>
                            <input type="text"  name="oth_state" class="form-control" value="{{ old('oth_state',$contact->other_state ?? '') }}" placeholder="State">
                            <label for="p_code" class="mt-3">Postal Code</label>
                            <input type="text"  name="oth_code" class="form-control" value="{{ old('oth_code',$contact->other_postal_code ?? '')}}" placeholder="Postal Code">
                            <label for="p_country" class="mt-3">country</label>
                            <input type="text"  name="oth_country" class="form-control" value="{{ old('oth_country',$contact->other_country ?? '')}}" placeholder="Country">
                          </fieldset>
                        </div>
                    </div>
                    <!-- Fourth row end -->
                    <!-- Fifth row start -->
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label for="">Description</label>
                            <textarea name="description"  class="form-control" >{{ old('description',$contact->description ?? '')}}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="modules">Profile Image</label>
                            <input type="file" name="profile" class="dropify">
                        </div>
                    </div>
                    <!-- Fifth row end -->
                    <!-- Sixth row start -->
                    <div class="row">
                        <div class="ml-auto mr-3">
                            <button class="btn btn-raised btn-primary waves-effect" type="submit">Save</button>
                        </div>
                    </div>
                    <!-- Fifth row end-->
                </form>
                <!-- form end -->
            </div>
        </div>
    </div>
@stop
@section('page-script')
<script src="{{asset('public/assets/plugins/dropify/js/dropify.min.js')}}"></script>
<script src="{{asset('public/assets/js/pages/forms/dropify.js')}}"></script>
<script src="{{asset('public/assets/js/sw.js')}}"></script>
<script>
var url="{{ url('') }}";
var userURL = "{{ url('userSearch') }}";
$('#related_to').on('change',function(){
    var related_to=$(this).val();
    $('.related').attr("id",related_to);
    $('.related_ID').attr("id",`${related_to}_ID`);
    related_to_url=`${related_to}Search`;
    console.log(related_to);
});
</script>
@stop
