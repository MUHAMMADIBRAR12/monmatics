@extends('layout.master')
@section('title', 'Call')
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
<link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}"/>
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
var contactURL = "{{ url('contactsSearch') }}";
var userURL = "{{ url('userSearch') }}";
var token =  "{{ csrf_token()}}";
var url="{{ url('') }}";
</script>
@stop
@section('content')

    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="header">
                <h2>Call</h2>
            </div>
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            <div class="body">
                <form method="post" action="{{url('Crm/Calls/Add')}}" enctype="multipart/form-data">
                    @csrf 
                    <input type="hidden" name="id" value="{{  $call->id ?? ''}}">
                    <input type="hidden" name="backURL" value="{{ $backURL ?? '' }}">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="name">Subject</label>
                            <div class="form-group">
                                <input type="text" name="subject" class="form-control" value="{{  $call->subject ?? ''}}" placeholder="Subject"  required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="category">Status</label>
                            <div class="form-group">
                                <select name="status" class="form-control show-tick ms select2" data-placeholder="Select" >
                                    <option value="">Select Status</option>
                                    @foreach($status as $statuses)
                                        <option value="{{$statuses->description}}" {{ ( $statuses->description == ( $call->status ?? '')) ? 'selected' : '' }} >{{$statuses->description}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Related To</label>
                                <div class="row">
                                    <div class="col-sm-4 col-md-4">
                                        <select name="related_to_type" id="related_to" class=" form-control show-tick ms select2" >
                                            <option value="">Select Type</option>
                                        @php
                                        $related=appLib::$related_to;
                                        @endphp
                                        @foreach($related as $related_to)
                                        <option value="{{$related_to}}" {{ ( $related_to == ( $call->related_to_type ?? $relatedTo ?? '' )) ? 'selected' : '' }} >{{$related_to}}</option>

                                        @endforeach
                                        </select>
                                </div>
                                <div class="col-sm-8 col-md-8">
                                    <input type="text" name="related" class="form-control related" id="{{ $call->related_to_type ?? '' }}"  value="{{ isset($relatedToInfo) ? $relatedToInfo->name : '' }}" placeholder="Search" onkeyup="autoFill(this.id, url+'/'+this.id+'Search', token);">


                                    <input type="hidden" name="related_ID" class="related_ID" id="{{(isset($call->related_to_type)) ? $call->related_to_type.'_ID':''}}" value="{{  $relatedToInfo->id ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="code">Contact Name</label>
                                <div class="form-group">
                                    <input type="text" name="contact" id="contact" class="form-control" value="{{ $call->contact_name ?? ''  }}" placeholder="Contact" onkeyup="autoFill(this.id, contactURL, token)" >
                                    <input type="hidden" name="contact_ID" id="contact_ID" value="{{ $call->contact_id ?? ''  }}">
                                </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="email">Start Date</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        @php
                                            if(isset($call))
                                            {
                                            $s_date=appLib:: setDateFormat($call->start_date);
                                            $s_hour=appLib:: getHour($call->start_date);
                                            $s_minutes=appLib:: getMinutes($call->start_date);
                                            $e_date=appLib:: setDateFormat($call->end_date);
                                            $e_hour=appLib:: getHour($call->end_date);
                                            $e_minutes=appLib:: getMinutes($call->end_date);
                                            }
                                        @endphp

                                        <input type="date" name="start_date"   class="form-control" value="{{ $s_date ?? date('Y-m-d')  }}"  >
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <select name="s_hour" id="s_hour" class=" form-control show-tick ms select2" >
                                            <option value="">Hrs</option>
                                        @php
                                        $hours=appLib::$hours;
                                        @endphp
                                        @foreach($hours as $hour)
                                            <option value="{{$hour}}" {{ ( $hour == ( $s_hour ?? '')) ? 'selected' : '' }} >{{$hour}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <select name="s_minute" id="s_minute" class=" form-control show-tick ms select2" >
                                            <option value="">Min</option>
                                        @php
                                        $minutes=appLib::minutes();
                                        @endphp
                                        @foreach($minutes as $minute)
                                            <option value="{{$minute}}" {{ ( $minute == ( $s_minutes ?? '')) ? 'selected' : '' }} >{{$minute}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="email">End Date</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="date" name="end_date"   class="form-control" value="{{ $e_date ?? date('Y-m-d')  }}"  >
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <select name="e_hour" id="e_hour" class=" form-control show-tick ms select2" >
                                            <option value="">Hrs</option>
                                        @php
                                        $hours=appLib::$hours;
                                        @endphp
                                        @foreach($hours as $hour)
                                            <option value="{{$hour}}" {{ ( $hour == ( $e_hour ?? '' ) ) ? 'selected' : '' }} >{{$hour}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <select name="e_minute" id="e_minute" class=" form-control show-tick ms select2" >
                                            <option value="">Min</option>
                                        @php
                                        $minutes=appLib::minutes();
                                        @endphp
                                        @foreach($minutes as $minute)
                                            <option value="{{$minute}}" {{ ( $minute == ( $e_minutes ?? '')) ? 'selected' : '' }} >{{$minute}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                        <label for="category">Communication Type</label>
                            <div class="form-group">
                                <select name="communication_type" class="form-control show-tick ms select2" data-placeholder="Select" >
                                    <option value="">Select Communication Type</option>
                                    @foreach($communication_types as $type)
                                        <option value="{{$type->description}}" {{ ( $type->description == ( $call->communication_type ?? '')) ? 'selected' : '' }} >{{$type->description}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="email">Assign To</label>
                            <div class="form-group">
                                <input type="text" name="assign" id="assign" class="form-control" value="{{ $call->user_name ?? ''  }}" placeholder="Contact" onkeyup="autoFill(this.id, userURL, token)">
                                <input type="hidden" name="assign_ID" id="assign_ID" value="{{ $call->assigned_to ?? ''  }}" >
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <label for="fax">Description</label>
                            <div class="form-group">
                                <textarea name="description" rows="4" class="form-control no-resize" placeholder="Description"> {{ $call->description ?? ''  }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
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
