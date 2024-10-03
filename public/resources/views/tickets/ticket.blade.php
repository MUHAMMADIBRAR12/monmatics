@extends('layout.master')
@section('title', 'New Ticket')
@section('parentPageTitle', 'Ticket')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.standalone.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/dropify/css/dropify.min.css') }}" />
    <style>
        .dropify {
            width: 200px;
            height: 200px;
        }

        th.project .project {
            display: block;
        }

        .colseTicket {
            float: right;
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
    <?php use App\Libraries\appLib; ?>
    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="header">
                <h2><strong>Ticket</strong></h2>
            </div>
            <div class="colseTicket">
                <a href="{{ url('Tmg/Listing/New') }}" onclick="return confirm('do you want to close?')"
                    class="btn btn-raised btn-primary waves-effect">
                    Close
                </a>
            </div>
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            <div class="body">

                <form method="post" action="{{ url('Ticket/update') }}" enctype="multipart/form-data"
                    onsubmit="updateDescription()">
                    {{ csrf_field() }}

                    <input type="hidden" name="id" value="{{ $ticket->id ?? '' }}">
                    <input type="hidden" name="backURL" value="{{ $backURL }}">
                    <div class="row">
                        <div class="col-lg-2">
                            <label for="">Ticket #</label>
                            <p>{{ $ticket->number ?? '-' }}</p>
                        </div>
                        <div class="col-lg-2">
                            <label for="">Department</label>
                            <select name="department" id="department" class="form-control show-tick ms select2"
                                data-placeholder="Select">
                                <option value="1" selected disabled>--Select--</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->name }}"
                                        {{ $department->name == ($ticket->department ?? '') ? 'selected' : '' }}
                                        {{ $department->name == ($sessiondepartment ?? '') ? 'selected' : '' }}>
                                        {{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <label for="fiscal_year">Category</label>
                            <div class="form-group">
                                <select name="category" id="category" class="form-control show-tick ms select2">
                                    <option value="1" selected disabled>--Select--</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->description }}"
                                            {{ $category->description == ($ticket->category ?? '') ? 'selected' : '' }}
                                            {{ $category->description == ($sessioncategory ?? '') ? 'selected' : '' }}>
                                            {{ $category->description }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="col-lg-2 col-md-2">
                            <label for="fiscal_year">Priority</label>
                            <div class="form-group">
                                <select name="priority" id="priority" class="form-control show-tick ms select2">
                                    <option value="1" selected disabled>--Select--</option>
                                    @foreach ($priorities as $priority)
                                        <option value="{{ $priority->description }}"
                                            {{ $priority->description == ($ticket->priority ?? '') ? 'selected' : '' }}
                                            {{ $priority->description == ($sessionpriority ?? '') ? 'selected' : '' }}>
                                            {{ $priority->description }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="col-lg-2 col-md-2">
                            <label for="fiscal_year">Status</label>
                            <div class="form-group">
                                <select name="status" id="status" class="form-control show-tick ms select2">
                                    <option value="1" selected disabled>--Select--</option>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->description }}"
                                            {{ $status->description == ($ticket->status ?? '') ? 'selected' : '' }}
                                            {{ $status->description == ($sessionstatus ?? '') ? 'selected' : '' }}>
                                            {{ $status->description }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">Email</label>
                            <div class="form-group" id="">
                                <input type="email" name="email" class="form-control"
                                    value="{{ $ticket->email ?? '' }}" id="mail_c">
                                @if (session()->has('mail'))
                                    <div class="text-danger">
                                        {{ session()->get('mail') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label for="code">Subject</label>
                            <div class="form-group">
                                <input type="text" name="subject" class="form-control"
                                    value="{{ old('email') ?? ($ticket->subject ?? '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="">Related To</label>
                            <div class="row">
                                <div class="col-sm-4 col-md-4">
                                    <select name="related_to_type" id="related_to"
                                        class=" form-control show-tick ms select2">
                                        <option value="" value="1" selected disabled>Select Type</option>
                                        @php
                                            $projectFind = DB::table('prj_projects')
                                                ->where('id', $project->id ?? '')
                                                ->first();
                                            $relatedTo = $projectFind ? 'project' : null;
                                            $relatedOptions = appLib::$related_to;
                                        @endphp
                                        @foreach ($relatedOptions as $related_to)
                                            <option value="{{ $related_to }}"
                                                {{ $related_to == $relatedTo ? 'selected' : '' }}
                                                {{ $related_to == ($ticket->related_to ?? '') ? 'selected' : '' }}
                                                {{ $related_to == ($sessionrelated_to ?? '') ? 'selected' : '' }}>
                                                {{ $related_to }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-8 col-md-8">
                                    <input type="text" name="related" class="form-control related"
                                        id="{{ $ticket->related_to ?? ($relatedTo ?? ($sessionrelated_to ?? '')) }}"
                                        value="{{ isset($relatedToInfo) ? $relatedToInfo->id : (isset($projectId) ? $project->name : '') }} {{ $project->name ?? '' }} {{ $ticket->project_name ?? '' }} {{ $ticket->customer_name ?? '' }} {{ $ticket->contact_firstname ?? '' }} {{ $ticket->contact_lastname ?? '' }} {{ $customer->name ?? '' }} {{ $customer2->name ?? '' }} {{ $projects->name ?? '' }} {{ $contact->first_name ?? '' }}  {{ $contact->last_name ?? '' }}"
                                        placeholder="Search" onkeyup="autoFill(this.id, url+'/'+this.id+'Search', token);">
                                    <input type="hidden" name="related_ID" class="related_ID"
                                        id="{{ ($ticket->related_to ?? ($relatedTo ?? ($sessionrelated_to ?? ''))) . '_ID' }}"
                                        value="{{ isset($relatedToInfo) ? $relatedToInfo->id : (isset($task->related_id) ? $task->related_id : '') }} {{ $project->id ?? '' }} {{ $ticket->related_to_id ?? '' }} {{ $sessionrelated_to_id ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="table-responsive">

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <label for="fiscal_year">Description</label>
                            <div class="form-group" id="note">

                                <textarea name="body">{{ $ticket->body ?? '' }}</textarea>

                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6">

                            <label for="fiscal_year">Attachment</label>
                            <br>
                            @if ($attachmentRecord ?? '')
                                <table>
                                    @php $i=0 ; @endphp
                                    @foreach ($attachmentRecord as $attachment)
                                        @php $i++ ; @endphp
                                        <tr id='attRow{{ $i }}'>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="deleteFileA('{{ $attachment->id }}', {{ $i }})"><i
                                                        class="zmdi zmdi-delete"></i></button>
                                            </td>
                                            <td>
                                                <a href="{{ url('download/' . $attachment->id) }}"
                                                    download>{{ $attachment->file }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            @endif
                            <input name="file" type="file" class="dropify">

                        </div>
                        <div class="col-md-4">
                            <label for="email">Assign To</label>
                            <div class="form-group">
                                <input type="text" name="assign" id="assign" class="form-control"
                                    value="{{ $assignedToName->name ?? '' }}" placeholder="Assign To"
                                    onkeyup="autoFill(this.id, userURL, token)">

                                <input type="hidden" name="assign_ID" id="assign_ID"
                                    value="{{ $ticket->assign_to ?? '' }}">
                            </div>
                        </div>

                    </div>

                    <div class="row" style="background:lightgrey; margin-top: 15px;">

                        <div class="col-md-2">
                            <button class="btn btn-raised btn-primary waves-effect" type="submit">Save</button>
                        </div>
                        <div class="col-md-2">
                            <input type="checkbox" name="more_ticket" value="1" id="more_ticket" class="ml-2">
                            <label>Create more Ticket</label>
                        </div>
                        <div class="col-md-2">
                            <span id="send_customer">
                                <input type="checkbox" name="send_customer" value="1" id="more_ticket"
                                    class="ml-2">
                                <label>Mail To Customer</label>
                            </span>
                        </div>
                        <div class="col-md-2">
                            <input type="checkbox" name="is_global" id="is_global" class="ml-2">
                            <label>Global</label>
                        </div>
                        <div class="col-md-2">
                            <input type="checkbox" name="closed"
                                {{ ($ticket->is_closed ?? '') == 'on' ? 'checked' : '' }} id="closed" class="ml-2">
                            <label>Close Ticket</label>
                        </div>
                        <div class="col-md-2">
                            <input type="checkbox" name="is_billable"
                                {{ ($ticket->is_billable ?? '') == 'on' ? 'checked' : '' }} id="closed" class="ml-2">
                            <label>Billable</label>
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
    <script src="{{ asset('public/assets/bundles/footable.bundle.js') }}"></script>
    <script src="{{ asset('public/assets/js/pages/tables/footable.js') }}"></script>
    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>

    <script>
        $('#related_to').on('change', function() {
            var $relatedOptions = $(this).val();
            $('.related').attr("id", $relatedOptions);
            $('.related_ID').attr("id", `${$relatedOptions}_ID`);
            $relatedOptions_url = `${$relatedOptions}Search`;
            console.log($relatedOptions);
        });

        function deleteFileA(id, num) {
            var x = confirm("Are you sure you want to delete?");
            if (x) {
                var url = '{{ url('delete/') }}';
                deleteFile(url, id, token);
                $('#attRow' + num).html('');
            }
        }
    </script>
    <script>
        function updateDescription() {
            var descriptionMain = document.getElementById('descriptionmain');
            var descriptionInput = document.getElementById('description');
            descriptionInput.value = descriptionMain.innerHTML;
        }
    </script>
    <script>
        $(document).ready(function() {
            CKEDITOR.replace('body');
        });
    </script>




@stop
