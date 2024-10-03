@extends('layout.master')
@section('title', 'Note')
@section('parentPageTitle', 'Crm')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')
    <?php use App\Libraries\appLib; ?>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/morrisjs/morris.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/jquery-spinner/css/bootstrap-spinner.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/bootstrap-select/css/bootstrap-select.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/nouislider/nouislider.min.css') }}" />
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/dropify/css/dropify.min.css') }}" />
    <style>
        .input-group-text {
            padding: 0 .75rem;
        }

        .amount {
            width: 150px;
            text-align: right;
        }

        .table td {
            padding: 0.10rem;
        }

        .dropify {
            width: 200px;
            height: 200px;
        }
    </style>
    <script lang="javascript/text">
        var userURL = "{{ url('userSearch') }}";
        var token = "{{ csrf_token() }}";
        var url = "{{ url('') }}";
    </script>
@stop
@section('content')

    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="header">
                <h2>Note</h2>
            </div>
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            <div class="body">
                <form method="post" action="{{ url('Crm/Notes/Add') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $note->id ?? '' }}">
                    <input type="hidden" name="backURL" value="{{ $backURL ?? '' }}">

                    <div class="row">
                        <div class="col-md-6">
                            <label for="name">Subject</label>
                            <div class="form-group">
                                <input type="text" name="subject" class="form-control"
                                    value="{{ $note->subject ?? '' }}" placeholder="Subject" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="">Related To</label>
                            <div class="row">
                                <div class="col-sm-4 col-md-4">
                                    <select name="related_to_type" id="related_to"
                                        class=" form-control show-tick ms select2">
                                        <option value="">Select Type</option>
                                        @php
                                            $related = appLib::$related_to;
                                        @endphp
                                        @foreach ($related as $related_to)
                                        <option value="{{$related_to}}" {{ ( $related_to == ( $note->related_to_type ?? $relatedTo ?? '' )) ? 'selected' : '' }} >{{$related_to}}</option>

                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-8 col-md-8">
                                    <input type="text" name="related" class="form-control related" id="{{ $note->related_to_type ?? '' }}" value="{{ isset($relatedToInfo) ? $relatedToInfo->name : '' }}" placeholder="Search" onkeyup="autoFill(this.id, url+'/'+this.id+'Search', token);">
                                    <input type="hidden" name="related_ID" class="related_ID" id="{{(isset($note->related_to_type)) ? $note->related_to_type.'_ID':''}}" value="{{ isset($relatedToInfo) ? $relatedToInfo->id : '' }}">
                                </div>
                                
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="email">Assign To</label>
                            <div class="form-group">
                                <input type="text" name="assign" id="assign" class="form-control"
                                    value="{{ $note->user_name ?? '' }}" placeholder="Contact"
                                    onkeyup="autoFill(this.id, userURL, token)">
                                <input type="hidden" name="assign_ID" id="assign_ID"
                                    value="{{ $note->assigned_to ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="fax">Description</label>
                            <div class="form-group">
                                <textarea name="description" rows="4" class="form-control no-resize" placeholder="Description"> {{ $note->description ?? '' }}</textarea>
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
    <script src="{{ asset('public/assets/plugins/dropify/js/dropify.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/pages/forms/dropify.js') }}"></script>
    <script src="{{ asset('public/assets/js/sw.js') }}"></script>
@stop
