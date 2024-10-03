@extends('layout.master')
@section('title', 'Tasks List')
@section('parentPageTitle', 'Crm')
@section('page-style')
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/datatables.min.css" />
    <link rel="stylesheet" href="{{ asset('public/assets/css/sw.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/list.css') }}">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
@stop
@section('content')

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="body">
                    <button class="btn btn-primary" style="align:right"
                        onclick="window.location.href = '{{ url('Crm/Tasks') }}';">New Tasks</button>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>From</label>
                                <div class="input-group">
                                    <input type="date" id="from_date" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>To</label>
                                <div class="input-group">
                                    <input type="date" id="to_date" class="form-control">

                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Status</label>
                                <select name="status" id="status" class="form-control show-tick ms select2"
                                    data-placeholder="Select">
                                    {{-- <option value="" selected disabled>Select Status</option> --}}
                                    @foreach ($status as $status)
                                        <option value="{{ $status->description }}">
                                            {{ $status->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <label for=""></label>
                            <button id="generate" type="button"
                                class="btn btn-primary waves-effect font-weight-bold mt-3">Generate </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="tasks">
                                <thead>
                                    <tr>
                                        <th>SR#</th>
                                        <th>Subject</th>
                                        <th>Start Date</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                       use App\Libraries\appLib;
                                    @endphp
                                    @foreach ($tasks as $key => $task)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td class="column_size"><a
                                                    href="{{ url('Crm/Tasks/' . $task->id) }}">{{ $task->subject }}</a></td>
                                            <td class="column_size">{{ date(appLib::showDateFormat(), strtotime($task->start_date)) }}</td>
                                            <td class="column_size">{{ $task->priority }}</td>
                                            <td class="column_size">{{ $task->status }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @stop
    @section('page-script')
        @include('datatable-list');
        <script>
            t = $('#tasks').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'pageLength',
                        className: 'btn cl mr-2 px-3 rounded'
                    },
                    {
                        extend: 'copy',
                        className: 'btn bg-dark mr-2 px-3 rounded',
                        title: 'Products'
                    },
                    {
                        extend: 'csv',
                        className: 'btn btn-info mr-2 px-3 rounded',
                        title: 'Products'
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-danger mr-2 px-3 rounded',
                        title: 'Products'
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-warning mr-2 px-3 rounded',
                        title: 'Products'
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-success mr-2 px-3 rounded',
                        title: 'Products'
                    },
                    {
                        extend: 'colvis',
                        className: 'visible btn rounded'
                    },
                ],
                "bDestroy": true,
                "lengthMenu": [
                    [100, 200, 500, -1],
                    [100, 200, 500, "All"]
                ],

            });
        </script>

<script>
    var token = "{{ csrf_token() }}";
    var url = "{{ url('Crm/getTasksList') }}";

    $('#generate').click(function() {
        t.rows().remove().draw();
        $('.even').remove();
        $('.odd').remove();
        var sr = 1;
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        var status = $('#status').val();

        // Check if "All" is selected
        if (status === "All") {
            status = ""; // Set the status value to an empty string to retrieve all tasks
        }

        $.post(url, {
            from_date: from_date,
            to_date: to_date,
            status: status,
            _token: token
        }, function(data) {
            var sr = 1;
            data.map(function(val, i) {
                t.row.add([
                    '<td>' + sr + '</td>',
                    '<td class="column_size"><a href="{{ url('Crm/Tasks/') }}/' + val
                    .id + '">' +
                    val.subject + '</a></td>',
                    '<td class="column_size">' + val.start_date + '</td>',
                    '<td class="column_size">' + val.priority + '</td>',
                    '<td class="column_size">' + val.status + '</td>',
                    '</tr>',
                ]).draw(false);
                sr++;
            });
        });
    });

</script>
    @stop
