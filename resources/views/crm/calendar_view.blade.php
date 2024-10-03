@extends('layout.master')
@section('title', 'Calendar')
@section('parentPageTitle', 'App')
@section('page-style')
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/fullcalendar/fullcalendar.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/bootstrap-select/css/bootstrap-select.css') }}" />
@stop
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>

    <input type="hidden" id="myurl" value="{{ url('Crm/CalendarEvents') }}">
    <input type="hidden" id="newtask" value="{{ url('Crm/TasksList') }}">


    <!-- Add New Event popup -->
    <div class="modal fade" id="addNewEvent" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><strong>Add</strong> an event</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Event Name</label>
                                <input class="form-control" placeholder="Enter name" type="text" name="category-name">
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Choose Event Color</label>
                                <select class="form-control" data-placeholder="Choose a color..." name="category-color">
                                    <option value="success">Success</option>
                                    <option value="danger">Danger</option>
                                    <option value="info">Info</option>
                                    <option value="primary">Primary</option>
                                    <option value="warning">Warning</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success save-event" data-dismiss="modal">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Direct Event popup -->
    <div class="modal fade" id="addDirectEvent" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Direct Event</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Event Name</label>
                                <input class="form-control" name="event-name" type="text" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Event Type</label>
                                <select name="event-bg" class="form-control">
                                    <option value="success">Success</option>
                                    <option value="danger">Danger</option>
                                    <option value="info">Info</option>
                                    <option value="primary">Primary</option>
                                    <option value="warning">Warning</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn save-btn btn-success">Save</button>
                    <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Event Edit Modal popup -->
    <div class="modal fade" id="eventEditModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Event</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Event Name</label>
                                <input class="form-control" name="event-name" type="text" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Event Type</label>
                                <select name="event-bg" class="form-control">
                                    <option value="success">Success</option>
                                    <option value="danger">Danger</option>
                                    <option value="info">Info</option>
                                    <option value="primary">Primary</option>
                                    <option value="warning">Warning</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn mr-auto delete-btn btn-danger">Delete</button>
                    <button class="btn save-btn btn-success">Save</button>
                    <button class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
@stop
@section('page-script')
    <script src="{{ asset('public/assets/bundles/fullcalendarscripts.bundle.js') }}"></script>
    <script src="{{ asset('public/assets/js/pages/calendar/calendar.js') }}"></script>
    <script>
        setInterval(function() {
            window.location.reload();
        }, 300000);
        console.log($('#myurl').val())
        console.log('the path' + window.location.origin + '/HCM/EmployeeForm/getEvents');
        console.log(window.location.pathname);
    </script>
<script>
    $(document).ready(function() {

        var calendar = $('#calendar');

        var booking = @json($events);

        calendar.fullCalendar({

            header: {
                left: 'title',
                center: '',
                right: 'month, agendaWeek, agendaDay, prev, next'
            },

            events: booking,
            editable: true,
            droppable: true,
            selectable: true,
            selectHelper: true,
            eventClick: function(event) {
                window.location.href = '{{ url('Crm/Tasks/') }}/' + event.id;
            }
        });
    });
</script>

@stop
