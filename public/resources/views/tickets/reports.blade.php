@extends('layout.master')
@section('title', 'Reports')
@section('parentPageTitle', 'Time Sheet')
@section('page-style')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/datatables.min.css" />
    <link rel="stylesheet" href="{{ asset('public/assets/css/list.css') }}">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <style>
        #ledger tr td {
            padding-top: 1px;
            padding-bottom: 1px;
        }

        .pointer {
            cursor: pointer;
        }

        .dt-buttons {
            gap: 10px;
        }

        .searchopen {
            float: right;
            cursor: pointer;
        }

        .searchopen i {
            font-size: 20px
        }

        /* .bothth {
                                                                                                                                                            height: 30px;
                                                                                                                                                            margin-top: 50px;
                                                                                                                                                        } */
    </style>
    <?php use App\Libraries\appLib; ?>
    <script lang="javascript/text">
        var accountsURL = "{{ url('transactionAccountSearch') }}";
        var token = "{{ csrf_token() }}";
    </script>
    <script lang="javascript/text">
        var contactURL = "{{ url('contactsSearch') }}";
        var url = "{{ url('') }}";
        var userURL = "{{ url('userSearch') }}";
        var token = "{{ csrf_token() }}";
    </script>
@stop
@section('content')
    <div class="row clearfix">





        <div class="col-lg-12">
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            @if (session()->has('error'))
                <div class="alert alert-danger">
                    {{ session()->get('error') }}
                </div>
            @endif
            <div class="card">
                <div class="header">

                </div>
                <div class="body">
                    <span class="searchopen"><i class="zmdi zmdi-menu" onclick="openSearch()"></i></span>
                    <hr style="opacity: 0;" class="hr" />
                    <form style="display: none;" action="" id="searchforme">
                        <div class="row clearfix">
                            <div class="col-md-1 col-sm-12">
                                <div class="form-group">
                                    <label>Ticket ID</label>
                                    <input type="text" name="ticket_id" id="account"
                                        value="{{ request('ticket_id') }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12 pl-1">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" name="email" id="email" class="form-control"
                                        value="{{ request('email') }}">
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12 pl-1">
                                <div class="form-group">
                                    <label>From</label>
                                    <div class="input-group">
                                        <input type="date" id="from_date" name="from_date" class="form-control"
                                            value="{{ request('from_date') }}">
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-2 col-sm-12 pl-1">
                                <div class="form-group">
                                    <label>To</label>
                                    <div class="input-group">
                                        <input type="date" id="to_date" name="to_date" class="form-control"
                                            value="{{ request('to_date') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12 pl-1">
                                <div class="form-group">
                                    <label>Category</label>
                                    <select class="form-control show-tick ms select2" data-placeholder="Select"
                                        id="category" name="category" required>
                                        <option selected disabled>Select Category</option>
                                        @foreach ($categories as $category)
                                            <option id="category"
                                                value=" {{ $category->description }}"{{ old('category', request('category')) == $category->description ? 'selected' : '' }}>
                                                {{ $category->description }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12 pl-1">
                                <div class="form-group">
                                    <label>Priority</label>
                                    <select class="form-control show-tick ms select2" data-placeholder="Select"
                                        id="priority" name="priority" required>
                                        <option selected disabled>Select
                                            Priority</option>
                                        @foreach ($priorities as $priority)
                                            <option id="priority"
                                                value="  {{ $priority->description }}"{{ old('priority', request('priority')) == $priority->description ? 'selected' : '' }}>
                                                {{ $priority->description }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12 pl-1">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control show-tick ms select2" data-placeholder="Select"
                                        id="status" name="status" required>
                                        <option selected disabled>Select Status</option>
                                        @foreach ($statuses as $status)
                                            <option id="status" value="{{ $status->description }}"
                                                {{ old('status', request('status')) == $status->description ? 'selected' : '' }}>
                                                {{ $status->description }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12 pl-1">
                                <div class="form-group">
                                    <label>Departments</label>
                                    <select class="form-control show-tick ms select2" data-placeholder="Select"
                                        id="department" name="department" required>
                                        <option selected disabled>Select Department</option>
                                        @foreach ($departments as $department)
                                            <option
                                                value="  {{ $department->name }}"{{ old('department', request('department')) == $department->name ? 'selected' : '' }}>
                                                {{ $department->name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label for="">Assign To</label>
                                <input type="text" name="assign" id="assign" class="form-control"
                                    value="{{ request('assign') }}" placeholder="Assign To"
                                    onkeyup="autoFill(this.id, userURL, token)">

                                <input type="hidden" name="assign_ID" id="assign_ID"
                                    value="{{ request('assign_ID') }}">
                            </div>



                            <div class="col-md-2">
                                <label for="">Related To</label>
                                <select name="related_to_type" id="related_to"
                                    class=" form-control show-tick ms select2">
                                    <option value="1" selected disabled>Select Type</option>
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
                                            {{ $related_to == request('related_to_type') ? 'selected' : '' }}>
                                            {{ $related_to }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class=" col-md-2">
                                <label for="">Related To Type</label>
                                <input type="text" name="related" class="form-control related"
                                    id="{{ $ticket->related_to ?? ($relatedTo ?? ($sessionrelated_to ?? '')) }}"
                                    value="{{ request('related') }}" placeholder="Search"
                                    onkeyup="autoFill(this.id, url+'/'+this.id+'Search', token);">
                                <input type="hidden" name="related_ID" class="related_ID"
                                    id="{{ ($ticket->related_to ?? ($relatedTo ?? ($sessionrelated_to ?? ''))) . '_ID' }}"
                                    value="">
                            </div>
                            <div class="col-md-2">
                                <br>
                                <input type="checkbox" name="is_billable" id="is_billable">Billable
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary" style="height: 30px;margin-top: 30px;"
                                id="formSubmitButton">Search</button>
                            <button class="btn btn-primary " style=" height: 30px; margin-top: 30px;"
                                onclick="clearForm()">Clear</button>
                        </div>
                    </form>

                    <script>
                        function clearForm() {
                            document.getElementById("searchforme").reset();
                            document.getElementById("account").value = "";
                            document.querySelector(".related_ID").value = "";
                            document.querySelector(".related").value = "";
                            document.getElementById("email").value = "";
                            document.getElementById("from_date").value = "";
                            document.getElementById("to_date").value = "";
                            document.getElementById("assign").value = "";
                            document.getElementById("assign_ID").value = "";
                            var selectElement = document.getElementById("category");
                            selectElement.selectedIndex = 0;
                            var selectElement = document.getElementById("department");
                            selectElement.selectedIndex = 0;
                            var selectElement = document.getElementById("status");
                            selectElement.selectedIndex = 0;
                            var selectElement = document.getElementById("priority");
                            selectElement.selectedIndex = 0;
                            var selectElement = document.getElementById("related_to");
                            selectElement.selectedIndex = 0;
                            document.getElementById("searchForm").value = " ";

                        }
                    </script>
                    <form action="{{ url('Tmg/Ticket/BulkUpdate') }}" method="post" id="searchForm">
                        @csrf


                        <div class="table-responsive">
                            <table id="transactionList"
                                class="table table-bordered table-striped table-hover js-exportable dataTable "
                                style="width:100%">
                                <thead>
                                    <td><input type="checkbox" id="select_all_ids" onclick="selectAll()"></td>
                                    <th>View</th>
                                    <th>Ticket No.</th>
                                    <th>Date</th>
                                    <th style="text-align:center;">Subject</th>
                                    <th style="text-align:center;">Assign To</th>
                                    <th style="text-align:center;">Email</th>
                                    <th style="text-align:center;">Status</th>
                                    <th style="text-align:center;">Department</th>
                                    <th style="text-align:center;">Spend Time</th>

                                </thead>
                                <tbody>
                                    {{-- <p style="display: none">{{ $i = 1 }}</p> --}}
                                    @foreach ($tickets as $ticket)
                                        @php
                                            $totalTimeInSeconds = DB::table('tkt_tickets_history')
                                                ->where('tkt_id', '=', $ticket->id)
                                                ->select(DB::raw('SUM(TIME_TO_SEC(total_time)) as total_seconds'))
                                                ->value('total_seconds');

                                            if ($totalTimeInSeconds === null) {
                                                $totalTime = '00:00:00';
                                            } else {
                                                $totalTime = gmdate('H:i:s', $totalTimeInSeconds);
                                            }
                                        @endphp
                                        <tr>

                                            <td><input type="checkbox" name="ids[]" value="{{ $ticket->id }}"
                                                    class="bulkselect"></td>
                                            <td><button class="btn btn-danger btn-sm p-0 m-0"><a
                                                        href="{{ url('Tmg/View/' . $ticket->id) }}"
                                                        onclick="UpdateUrl('{{ url('Tmg/View/' . $ticket->id) }}')"
                                                        class="btn btn-danger print btn-sm p-0 m-0"><i
                                                            class="zmdi zmdi-receipt px-2 py-1"></i></a></button></td>
                                            <td>{{ $ticket->number ?? '' }} </td>
                                            <td>{{ date(appLib::showDateFormat().' H:i:s', strtotime($ticket->created_at ?? '')) }} </td>
                                            <td>{{ $ticket->subject ?? '' }} </td>
                                            <td>
                                                @php
                                                    $assignedUser = \App\Models\User::find($ticket->assign_to);
                                                @endphp
                                                {{ $assignedUser->firstName ?? '' }}{{ $assignedUser->lastName ?? '' }}
                                            </td>
                                            <td>{{ $ticket->email ?? '' }} </td>
                                            <td>{{ $ticket->status ?? '' }} </td>
                                            <td>{{ $ticket->department ?? '' }} </td>
                                            <td>{{ $totalTime ?? '' }} </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mainn mr-3" style="display: flex">
                            <div style="width: 15%">
                                <select class="form-control show-tick ms select2" data-placeholder="Select"
                                    id="status" name="status" required>
                                    <option selected disabled>Select Status</option>
                                    @foreach ($statuses as $status)
                                        <option id="status" value="{{ $status->description }}"
                                            {{ request('status') == $status->description ? 'selected' : '' }}>
                                            {{ $status->description }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <button class="btn btn-danger" onclick="searchUpdate()" id="searchUpdate">Update</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    </div>
@stop
@section('page-script')
    <script src="{{ asset('public/assets/bundles/datatablescripts.bundle.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/jquery-datatable/buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/jquery-datatable/buttons/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/jquery-datatable/buttons/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/jquery-datatable/buttons/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/jquery-datatable/buttons/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/jquery-datatable/buttons/buttons.print.min.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="{{ asset('public/assets/plugins/jquery-datatable/buttons/buttons.excel.min.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.colVis.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="{{ asset('public/assets/js/sw.js') }}"></script>
    <script>
        t = $('#transactionList').DataTable({
            scrollY: '50vh',
            "scrollX": true,
            scrollCollapse: true,
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'pageLength',
                    className: 'btn cl mr-2 px-3 rounded'
                },
                {
                    extend: 'copy',
                    className: 'btn bg-dark mr-2 px-3 rounded',
                    title: 'Products',
                    exportOptions: {
                        columns: [0, 3, 4, 5, 6, 7] // Exclude columns 1 and 2 (Edit and Print)
                    }
                },
                {
                    extend: 'csv',
                    className: 'btn btn-info mr-2 px-3 rounded',
                    title: 'Products',
                    exportOptions: {
                        columns: [0, 3, 4, 5, 6, 7] // Exclude columns 1 and 2 (Edit and Print)
                    }
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-danger mr-2 px-3 rounded',
                    title: 'Products',
                    exportOptions: {
                        columns: [0, 3, 4, 5, 6, 7] // Exclude columns 1 and 2 (Edit and Print)
                    }
                },
                {
                    extend: 'excel',
                    className: 'btn btn-warning mr-2 px-3 rounded',
                    title: 'Products',
                    exportOptions: {
                        columns: [0, 3, 4, 5, 6, 7] // Exclude columns 1 and 2 (Edit and Print)
                    }
                },

                {
                    extend: 'print',
                    className: 'btn btn-success mr-2 px-3 rounded',
                    title: 'Products',
                    exportOptions: {
                        columns: [0, 3, 4, 5, 6, 7] // Exclude columns 1 and 2 (Edit and Print)
                    }
                },
                {
                    extend: 'colvis',
                    className: 'visible btn rounded'
                },
            ],
            "lengthMenu": [
                [100, 200, 500, -1],
                [100, 200, 500, "All"]
            ],
            "columnDefs": [{
                    className: "dt-center column_size",
                    "targets": [0, 1, 2, 3, 4]
                },
                {
                    className: "dt-right column_size",
                    "targets": []
                },
                {
                    className: "column_size",
                    "targets": [0, 1, 2, 3, 4, 5, 6, 7]
                },
            ],
            "bDestroy": true,
        });
    </script>



    <script>
        function openSearch() {
            $('#searchforme').slideToggle();
        }

        function searchUpdate() {
            $('#searchForm').submit();
        }

        function UpdateUrl(url) {


            event.preventDefault();
            window.location.href = url;
        }

        function selectAll() {
            const bulkCheck = $('.bulkselect');

            if (bulkCheck.prop('checked')) {
                bulkCheck.prop('checked', false)
            } else {
                bulkCheck.prop('checked', true)
            }
        }
    </script>


    <script>
        $(document).ready(function() {
            $('#formSubmitButton').on('click', function(e) {
                e.preventDefault();
                var ticketId = $('#account').val();
                var email = $('#email').val();
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                var category = $('#category').val();
                var priority = $('#priority').val();
                var status = $('#status').val();
                var assign_ID = $('#assign_ID').val();
                var department = $('#department').val();
                var related_ID = $('.related_ID').val();
                var is_billable = $('#is_billable').prop('checked') ? 'on' : '';



                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'post',
                    url: '{{ route('search.ticket.reports') }}',
                    data: {
                        ticket_id: ticketId,
                        email: email,
                        from_date: from_date,
                        to_date: to_date,
                        priority: priority,
                        status: status,
                        assign_ID: assign_ID,
                        department: department,
                        related_ID: related_ID,
                        is_billable: is_billable,

                    },
                    success: function(data) {
                        var body = $('#transactionList tbody');
                        body.empty();
                        data.forEach(function(singleData) {
                            var row = '<tr>' +
                                '<td><input type="checkbox" name="ids[]" value="' +
                                singleData.id + '" class="bulkselect"></td>' +
                                '<td><button class="btn btn-danger btn-sm p-0 m-0"><a href="{{ url('Tmg/View/') }}/' +
                                singleData.id +
                                '" onclick="UpdateUrl(\'{{ url('Tmg/View/') }}/' +
                                singleData.id +
                                '\')" class="btn btn-danger print btn-sm p-0 m-0"><i class="zmdi zmdi-receipt px-2 py-1"></i></a></button></td>' +
                                '<td>' + (singleData.number ?? '') + '</td>' +
                                '<td>' + (singleData.created_at ?? '') + '</td>' +
                                '<td>' + (singleData.subject ?? '') + '</td>' +
                                '<td>' + (singleData.assigned_user_first_name +
                                    singleData.assigned_user_last_name ?? '') +
                                '</td>' +
                                '<td>' + (singleData.email ?? '') + '</td>' +
                                '<td>' + (singleData.status ?? '') + '</td>' +
                                '<td>' + (singleData.department ?? '') + '</td>' +
                                '<td>' + (singleData.total_history_time ?? '00:00:00') + '</td>' +
                                '</tr>';

                            body.append(row);
                        });
                    },
                });
            });
        });
    </script>

@stop
