@extends('layout.master')
@section('title', 'Notes List')
@section('parentPageTitle', 'Crm')
<?php use App\Libraries\appLib; ?>
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
                        onclick="window.location.href = '{{ url('Crm/Notes/Create') }}';">Add New Note</button>
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
                                <label for="">Related To</label>
                                <select name="related_to_type" id="related_to_type" class="form-control show-tick ms select2"
                                    data-placeholder="Select">
                                    <option value="" selected disabled>Select Related</option>
                                    @php
                                        $related = appLib::$related_table;
                                    @endphp
                                    @foreach ($related as $key => $value)
                                        <option value="{{ $key }}">{{ ucfirst($key) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                        </div>
                        <div class="col-md-1">
                            <label for=""></label>
                            <button id="generate" type="button"
                                class="btn btn-primary waves-effect font-weight-bold mt-3">Generate </button>
                        </div>


                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="notes">
                            <thead>
                                <tr>
                                    <th class="px-1 py-0">SR#</th>
                                    <th>Subject</th>
                                    <th>Related To</th>
                                    <th>Short Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($notesList as $key => $note)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td class="column_size"><a
                                                href="{{ url('Crm/Notes/Create/' . $note['id']) }}">{{ $note['subject'] }}</a>
                                        </td>
                                        <td class="column_size">{{ isset($note['related_to']) ? $note['related_to']->name : '' }}</td>

                                        <td class="column_size">{{ $note['description'] }}</td>
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
        t = $('#notes').DataTable({
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
        var url = "{{ url('Crm/getNotesList') }}";

        $('#generate').click(function() {
            t.rows().remove().draw();
            $('.even').remove();
            $('.odd').remove();
            var sr = 1;
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var related_to_type = $('#related_to_type').val();
            $.post(url, {
                from_date: from_date,
                to_date: to_date,
                related_to_type: related_to_type,
                _token: token
            }, function(data) {
                var sr = 1;
                data.map(function(val, i) {
                    t.row.add([
                        '<td>' + sr + '</td>',
                        '<td class="column_size"><a href="{{ url('Crm/Notes/Create/') }}/' +
                        val
                        .id + '">' +
                        val.subject + '</a></td>',
                        '<td class="column_size">' + val.related_to.name + '</td>',
                        '<td class="column_size">' + val.description + '</td>',
                        '</tr>',
                    ]).draw(false);
                    sr++;
                });
            });
        });
    </script>

@stop
