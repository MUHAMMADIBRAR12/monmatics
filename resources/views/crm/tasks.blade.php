@extends('layout.master')
@section('title', 'Tasks')
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
        var contactURL = "{{ url('contactsSearch') }}";
        var url = "{{ url('') }}";
        var userURL = "{{ url('userSearch') }}";
        var token = "{{ csrf_token() }}";
    </script>
@stop
@section('content')

    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="header">
                <h2>Tasks</h2>
            </div>
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            <div class="body">
                <form method="post" action="{{ url('Crm/TasksSave') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $task->id ?? '' }}">

                    <input type="hidden" name="backURL" value="{{ $backURL }}">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="name">Subject</label>
                            <div class="form-group">
                                <input type="text" name="subject" class="form-control"
                                    value="{{ $task->subject ?? old('subject') }}" placeholder="Subject" required>
                                {{-- value="{{ $customer->tax_number ?? old('tax_number') }}" --}}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="category">Status</label>
                            <div class="form-group">
                                <select name="status" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">Select Status</option>
                                    @foreach ($status as $status)
                                        <option value="{{ $status->description }}"
                                            {{ $status->description == ($task->status ?? '') ? 'selected' : '' }}>
                                            {{ $status->description }}</option>
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

                                    <select name="related_to_type" id="related_to"
                                        class=" form-control show-tick ms select2" required>
                                        <option value="" selected disabled>Select Type</option>
                                        @php
                                            $relatedTo = $priority ? 'priority' : null;
                                    
                                            $relatedOptions = appLib::$related_to;
                                        @endphp
                                        @foreach ($relatedOptions as $related_to)
                                            <option value="{{ $related_to }}"
                                            {{ $related_to == $relatedTo ? 'selected' : '' }}
                                            {{$related_to ==  ($task->related_to_type ?? '')   ? 'selected' : ''  }}>
                                                {{ $related_to }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-8 col-md-8">
                                    <input type="text" name="related" class="form-control related"
                                        id="{{ $task->related_to_type ?? $relatedTo }}"
                                        value="{{ isset($relatedToInfo) ? $relatedToInfo->id : (isset($projectId) ? $project->name : '') }} {{ $project->name ?? '' }} {{ $task->contact_username ?? '' }}  {{ $task->projectname ?? '' }} {{ $task->customer_name ?? '' }} {{ $task->cusname ?? '' }}"
                                        placeholder="Search" onkeyup="autoFill(this.id, url+'/'+this.id+'Search', token);">
                                    <input type="hidden" name="related_ID" class="related_ID"
                                        id="{{ isset($task->related_to_type) ? $task->related_to_type . '_ID' : $relatedTo .'_ID' }}"
                                        value="{{ isset($relatedToInfo) ? $relatedToInfo->id : (isset($task->related_id) ? $task->related_id : '') }} {{ $project->id ?? '' }}" >
                                </div>


                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="code">Contact Name</label>
                            <div class="form-group">
                                <input type="text" name="contact" id="contact" class="form-control"
                                    value="{{ $task->contact_name ?? '' }}" placeholder="Contact"
                                    onkeyup="autoFill(this.id, contactURL, token)">
                                <input type="hidden" name="contact_ID" id="contact_ID"
                                    value="{{ $task->contact_id ?? '' }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="note">Start Date</label>
                            <div class="form-group">
                                <input type="date" name="start_date" class="form-control"
                                    value="{{ $task->start_date ?? date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="note">Due Date</label>
                            <div class="form-group">
                                <input type="date" name="due_date" class="form-control"
                                    value="{{ $task->due_date ?? date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="location">Priority</label>
                            <select name="priority" class="form-control show-tick ms select2" data-placeholder="Select">
                                <option value="">Select Priority</option>
                                @foreach ($priority as $priority)
                                    <option value="{{ $priority->description }}"
                                        {{ $priority->description == ($task->priority ?? '') ? 'selected' : '' }}>
                                        {{ $priority->description }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="email">Assign To</label>
                            <div class="form-group">
                                <input type="text" name="assign" id="assign" class="form-control"
                                    value="{{ $task->user_name ?? '' }}" placeholder="Contact"
                                    onkeyup="autoFill(this.id, userURL, token)">
                                <input type="hidden" name="assign_ID" id="assign_ID"
                                    value="{{ $task->assigned_to ?? '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <label for="fax">Description</label>
                            <div class="form-group">
                                <div class="form-group">
                                    <textarea name="description" rows="4" class="form-control no-resize" placeholder="Description">{{ $task->description ?? '' }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for=""></label>
                                <div class="form-group">
                                    <button class="btn btn-raised btn-primary waves-effect" type="submit">Save</button>
                                </div>
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
    <script>
        $('#related_to').on('change', function() {
            var $relatedOptions = $(this).val();
            $('.related').attr("id", $relatedOptions);
            $('.related_ID').attr("id", `${$relatedOptions}_ID`);
            $relatedOptions_url = `${$relatedOptions}Search`;
            console.log($relatedOptions);
        });
    </script>
@stop
