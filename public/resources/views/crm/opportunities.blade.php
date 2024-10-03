@extends('layout.master')
@section('title', 'Opportunities')
@section('parentPageTitle', 'Crm')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')
<?php  use App\Libraries\appLib; ?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{asset('public/assets/plugins/select2/select2.css')}}"/>
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
</style>
<script lang="javascript/text">
var leadURL = "{{ url('leadCustmerSearch') }}";
var userURL = "{{ url('userSearch') }}";
var token =  "{{ csrf_token()}}";
</script>
@stop
@section('content')

    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="header">
                <h2>Opportunities</h2>
            </div>
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            <div class="body">
                <form method="post" action="{{url('Crm/Opportunities/Add')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{  $opportunity->id ?? ''}}">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="name">Opportunity Name</label>
                            <div class="form-group">
                                <input type="text" name="opportunity" class="form-control" value="{{  $opportunity->name ?? ''  }}" placeholder="Subject"  required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="code">Lead</label>
                            <div class="form-group">
                                <input type="text" name="lead" id="lead" class="form-control" value="{{  $opportunity->cust_name ?? ''  }}" placeholder="Lead" onkeyup="autoFill(this.id, leadURL, token)" required>
                                <input type="hidden" name="lead_ID" id="lead_ID" value="{{ $opportunity->cust_id ?? ''  }}">
                            </div>    
                        </div> 
                    </div>

                    <div class="row">                        
                        <div class="col-md-6">
                            <label for="location">Currency</label>
                            <select name="currency" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                <option value="">Select Currency</option>
                                @foreach($currencies as $currency)
                                    <option value="{{$currency->code}}" {{ ( $currency->code == ( $opportunity->cur_name ?? '')) ? 'selected' : '' }}>{{$currency->code}}</option> 
                                @endforeach              
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="amount">Opportunity Amount</label>
                            <div class="form-group">
                                <input type="number" name="amount"  class="form-control" value="{{  $opportunity->amount ?? ''  }}" placeholder="Opportunity Amount"  required> 
                           </div>
                        </div> 
                    </div>
                    
                    <div class="row">                        
                        <div class="col-md-6">
                            <label for="note">Expected close Date</label>
                            <div class="form-group">
                                <input type="date" name="close_date"  class="form-control" value="{{ $opportunity->close_date ?? date('Y-m-d')  }}"  required>
                            </div>
                        </div>  
                        <div class="col-md-6">
                            <label for="location">Type</label>
                            <select name="lead_type" class="form-control show-tick ms select2" data-placeholder="Select" >
                                <option value="">Select Type</option>
                                @foreach($types as $type)
                                    <option value="{{$type->description}}" {{ ( $type->description == (  $opportunity->lead_type ?? '')) ? 'selected' : '' }} >{{$type->description}}</option> 
                                @endforeach              
                            </select> 
                        </div>                      
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="location">Sale Stage</label>
                            <select name="sale_stage" class="form-control show-tick ms select2" data-placeholder="Select">
                                <option value="">Select Sale Stage</option>
                                @foreach($sale_stages as $sale_stage)
                                    <option value="{{$sale_stage->description}}" {{ ( $sale_stage->description == (  $opportunity->sale_stage ?? '')) ? 'selected' : '' }} >{{$sale_stage->description}}</option>
                                @endforeach              
                            </select>   
                        </div>
                        <div class="col-md-6">
                            <label for="location">Lead Source</label>
                            <select name="lead_source" class="form-control show-tick ms select2" data-placeholder="Select">
                                <option value="">Select Type</option>
                                @foreach( $lead_sources as  $lead_source)
                                    <option value="{{ $lead_source->description}}" {{ (  $lead_source->description == (  $opportunity->lead_source ?? '')) ? 'selected' : '' }} >{{ $lead_source->description}}</option>  
                                @endforeach              
                            </select> 
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label for="compaign">Compaign</label>
                            <select name="compaign" class="form-control show-tick ms select2" data-placeholder="Select">
                                <option value="">Select Type</option>             
                            </select> 
                        </div>
                        <div class="col-md-6">
                            <label for="next_step">Next Step</label>
                            <input type="text" name="next_step"  value="{{ $opportunity->next_step ?? ''  }}" class="form-control" placeholder="Next Step">
                        </div>          
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label for="email">Assign To</label>
                            <div class="form-group">
                                <input type="text" name="assign" id="assign" class="form-control" value="{{ $opportunity->user_name ?? ''  }}" placeholder="Contact" onkeyup="autoFill(this.id, userURL, token)">
                                <input type="hidden" name="assign_ID" id="assign_ID" value="{{ $opportunity->assigned_to ?? ''  }}" >   
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="fax">Description</label>
                            <div class="form-group">
                                <textarea name="description" rows="4" class="form-control no-resize" placeholder="Description"> {{ $opportunity->description ?? ''  }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class=" ml-auto">
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
<script>
$('#related_to').on('change',function(){
    var related_to=$(this).val();
    $('.related').attr("id",related_to);
    $('.related_ID').attr("id",`${related_to}_ID`);
    related_to_url=`${related_to}Search`;
    console.log(related_to);
});
</script>
@stop