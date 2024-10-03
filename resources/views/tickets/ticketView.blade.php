@extends('layout.master')
@section('title', 'TicketView')
@section('parentPageTitle', 'Accounts')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/dropify/css/dropify.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.standalone.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    <style>
        .dropify {
            width: 100px;
            height: 100px;
        }

        th.project .project {
            display: block;
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
            @if (session()->has('delete_message'))
                <div class="alert alert-danger">
                    {{ session()->get('delete_message') }}
                </div>
            @endif
            @if (session()->has('insert_message'))
                <div class="alert alert-success">
                    {{ session()->get('insert_message') }}
                </div>
            @endif
            <div class="body">
                <a href="{{ url('Tmg/Listing/me') }}" class="btn btn-primary waves-effect"
                    style="float: inline-end;">Back</a>

                <a href="{{ url('Tmg/Ticket') }}" class="btn btn-primary">New Ticket</a>
                <div class="row">

                    {{-- $ticket-> ?? '' --}}


                    <div class="col-lg-2">
                        <label for="">Ticket #</label>
                        <p>{{ $ticket->number ?? '' }}</p>
                    </div>
                    <div class="col-lg-2">
                        <label for="">Department</label>
                        <p>{{ $ticket->department ?? '' }}</p>
                    </div>
                    <div class="col-lg-2 col-md-2">
                        <label for="fiscal_year">Category</label>
                        <p>{{ $ticket->category ?? 'Unassigned' }}</p>

                    </div>
                    <div class="col-lg-2 col-md-2">
                        <label for="fiscal_year">Priority</label>
                        <p>{{ $ticket->priority ?? '' }}</p>
                    </div>
                    <div class="col-lg-2 col-md-2">
                        <label for="fiscal_year">Status</label>
                        <p>{{ $ticket->status ?? 'No Status' }}</p>

                    </div>
                    <div class="col-lg-2 col-md-2">
                        <label for="fiscal_year">Create Time</label>
                        <p>{{ date(appLib::showDateFormat(). ' H:i:s', strtotime($ticket->created_at)) }}</p>

                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <label for="fiscal_year">Email</label>
                        <p>{{ $ticket->email ?? '' }}</p>
                    </div>


                    <div class="col-lg-3 col-md-6">
                        <label for="code">Subject</label>
                        <p>{{ $ticket->subject ?? '' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label for="">Related To</label>
                        @php
                            $project = \App\Models\Project::find($ticket->related_to_id);
                        @endphp
                        <div class="row">
                            <p>{{ $ticket->related_to ?? '' }}</p>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <label for="fiscal_year">Description</label>
                        {!! $ticket->body ?? '' !!}
                    </div>




                    <div class="col-md-4">
                        <label for="email">Assign To</label>
                        <div class="form-group">
                            @php
                                $assignedTo = \App\Models\User::find($ticket->assign_to ?? '');
                            @endphp
                            <p>{{ $assignedTo->firstName ?? '' }} {{ $assignedTo->lastName ?? '' }}</p>
                            {{-- <p>{{ $ticket->assign_to ?? '' }}</p> --}}
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label for="code">Billable</label>
                        <p>{{ $ticket->is_billable ?? '' == 'on' ? 'Yes' : 'No' }}</p>
                    </div>
                </div>
                <form method="post" action="{{ url('Ticket/updateHistory') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $ticket->id ?? '' }}">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <label for="fiscal_year">Description</label>
                            <div class="form-group" id="note">
                                <textarea name="body">{{ $transmain->note ?? '' }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="email">Assign To</label>
                            <div class="form-group">
                                <input type="text" name="assign" id="assign" class="form-control"
                                    value="{{ $task->assigned_to ?? '' }}" placeholder="Assign To"
                                    onkeyup="autoFill(this.id, userURL, token)">
                                <input type="hidden" name="assign_ID" id="assign_ID"
                                    value="{{ $task->assigned_to ?? '' }} {{ $ticket->assign_to ?? '' }}">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <label for="fiscal_year">Status</label>
                            <div class="form-group">
                                <select name="status" id="status" class="form-control show-tick ms select2">
                                    <option value="">--Select--</option>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->description }}"
                                            {{ $status->description == $selectedValue ? 'selected' : '' }}>
                                            {{ $status->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="email">From Time</label>
                            <div class="form-group">
                                <input type="time" name="from_time" id="from_time" class="form-control"
                                    max="2050-06-14T00:00" value="{{ $task->from_time ?? '' }}"
                                    oninput="calculateTotalTime()">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="email">To Time</label>
                            <div class="form-group">
                                <input type="time" name="to_time" id="to_time" class="form-control"
                                    max="2050-06-14T00:00" value="{{ $task->to_time ?? '' }}"
                                    oninput="calculateTotalTime()">
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2">
                            <label for="fiscal_year">Total Time</label>
                            <div class="form-group">
                                <input type="text" name="total_time" class="form-control" id="total_time">
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-6">

                            <label for="fiscal_year">Attachment</label>
                            <br>
                            @if ($attachmentRecord ?? '')
                                <table>
                                    @php $i=0 ; @endphp
                                    {{-- @foreach ($attachmentRecord as $attachment) --}}
                                    @php $i++ ; @endphp
                                    <tr id='attRow{{ $i }}'>
                                        {{-- <td>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="deleteFileA('{{ $attachment->id }}', {{ $i }})"><i
                                                        class="zmdi zmdi-delete"></i></button>
                                            </td>
                                            <td>
                                                <a href="{{ url('download/' . $attachment->id) }}"
                                                    download>{{ $attachment->file }}</a>
                                            </td> --}}
                                    </tr>
                                    {{-- @endforeach --}}
                                </table>
                            @endif
                            <input name="file" type="file" class="dropify">

                        </div>

                    </div>

                    <div class="row" style="background:lightgrey; margin-top: 15px;">
                        <div class="col-lg-2">
                            <input type="checkbox" {{ $ticket->is_closed == 'on' ? 'checked' : '' }} name="closed"
                                id="closed" class="ml-2">
                            <label>Close Ticket</label>
                        </div>
                        <div class="col-lg-4">
                            <input type="checkbox" name="send_mail" id="send_mail" value="1" class="ml-2"
                                checked>
                            <label>Send update to Customer</label>
                        </div>
                        <div class="col-lg-1">
                            <button class="btn btn-raised btn-primary waves-effect" type="submit">Save</button>
                        </div>
                </form>
                <div class="col-lg-1">
                    <a href="{{ url('Tmg/Listing/me') }}" class="btn btn-raised waves-effect">
                        Close
                    </a>
                </div>
            </div>

            <div class="row" style="background:lightgrey; margin-top: 15px;">
                <div class="col-lg-10">
                    <h4 style="padding:10px">Task History</h4>
                </div>
            </div>
            <div class="row">
                <div class="table-responsive">
                    <table id="transactionList"
                        class="table table-bordered table-striped table-hover js-exportable dataTable "
                        style="width:100%">
                        <tr>
                            <th><Strong>Ticket Description</Strong></th>
                            <th><strong>Updated By</strong></th>
                            <th><strong>Date</strong></th>
                            <th><strong>Total Time</strong></th>
                            <th><strong>Action</strong></th>

                        </tr>

                        <tbody>
                            @foreach ($ticketsHistory as $ticketHistory)
                                @php
                                    $user = \App\Models\User::find($ticketHistory->created_by);
                                    $attachments = \DB::table('sys_attachments')
                                        ->where('source_id', '=', $ticketHistory->id)
                                        ->get();
                                @endphp

                                <tr id="historyRow{{ $ticketHistory->id }}">
                                    <td style="padding:10px">{!! $ticketHistory->body ?? '' !!} </td>
                                    <td style="padding:10px">{{ $user->name ?? '' }} </td>
                                    <td style="padding:10px">{{ $ticketHistory->created_at ?? '' }} </td>
                                    <td style="padding:10px">{{ $ticketHistory->total_time ?? '' }} </td>

                                    <input type="hidden" name="project" value="{{ $ticket->id ?? '' }}">

                                    <td style="padding:10px; height:10px;">
                                        <a href="{{ url('Tmg/Task/delete', $ticketHistory->id) }}"
                                            onclick="return confirm('Are you sure to delete??')">
                                            <i class="fa fa-trash text-danger" style="font-size: 20px"
                                                aria-hidden="true"></i>
                                        </a>

                                        @foreach ($attachments as $attachment)
                                            <a href="{{ url('download/' . $attachment->id) }}" download>
                                                <i class="zmdi zmdi-download text-success" style="font-size: 22px"></i>
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>




                    </table>
                </div>
            </div>
        </div>
    </div>
    <span class="text-center">Total Spend Time: {{ $totalTime ?? '' }}</span>

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
        $(document).ready(function() {
            CKEDITOR.replace('body');
        });
    </script>
    <script>
        $('#related_to').on('change', function() {
            var related_to = $(this).val();
            $('.related').attr("id", related_to);
            $('.related_ID').attr("id", `${related_to}_ID`);
            related_to_url = `${related_to}Search`;
            console.log(related_to);
        });
    </script>

<script>
    function calculateTotalTime() {
        var fromTime = document.getElementById('from_time').value;
        var toTime = document.getElementById('to_time').value;

        var fromDateTime = new Date('1970-01-01T' + fromTime);
        var toDateTime = new Date('1970-01-01T' + toTime);

        var timeDifference = toDateTime - fromDateTime;
        var totalSeconds = timeDifference / 1000;

        var hours = Math.floor(totalSeconds / 3600);
        var minutes = Math.floor((totalSeconds % 3600) / 60);

        var formattedHours = ('0' + hours).slice(-2);
        var formattedMinutes = ('0' + minutes).slice(-2);

        var formattedTime = formattedHours + ':' + formattedMinutes;

        document.getElementById('total_time').value = formattedTime;
    }
</script>





@stop
