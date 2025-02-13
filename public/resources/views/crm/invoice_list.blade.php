@extends('layout.master')
@section('title', 'Invoice')
@section('parentPageTitle', 'Sales')
@section('page-style')
    <?php use App\Libraries\appLib; ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/datatables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.0.2/css/dataTables.dateTime.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{ asset('public/assets/css/sw.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/list.css') }}">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script>
        var CustomerURL = "{{ url('customerSearch') }}";
        var token = "{{ csrf_token() }}";
    </script>
@stop
@section('content')
    @php
        $type = $type ?? 'c';
    @endphp
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="body">
                    <div class="table-responsive">
                        <button class="btn btn-primary" style="align:right"
                            onclick="window.location.href = '{{ url('Sales/Invoice/Create/' . $type) }}';">New
                            Invoice</button>
                        <div class="row mt-1">
                            <div class="col-md-12">
                                <table>
                                    <tr>
                                        <td>Customer:</td>
                                        <td>
                                            <input type="text" id="customer"
                                                onkeyup="autoFill(this.id,CustomerURL,token)" class="form-control"
                                                style="height:30px;width:230px">
                                            <input type="hidden" id="customer_ID">
                                        </td>
                                        <td>Start Date:</td>
                                        <td><input type="date" id="from_date" class="form-control"
                                                style="height:30px;width:160px"></td>

                                        <td>End Date:</td>
                                        <td><input type="date" id="to_date" class="form-control"
                                                style="height:30px;width:160px"></td>
                                        <td>Status:</td>
                                        <td>
                                            <div class="form-group">
                                                <select name="status" id="status"
                                                    class="form-control show-tick ms select2 mt-3"
                                                    data-placeholder="Select">
                                                    <option value="">--select--</option>
                                                    <option value="draft">Draft</option>
                                                    <option value="approved">Approved</option>
                                                    <option value="partialpaid">Partial Paid</option>
                                                    <option value="paid">Paid</option>
                                                    <option value="unpaid">Unpaid</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td><button type="button" class="btn btn-success m-0 px-1" id="report">Generate
                                                Report</button></td>
                                    </tr>
                                </table>

                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-hover" id="invoices" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="px-1 py-0">Action</th>
                                    <th>Number</th>
                                    <th>Customer Name</th>
                                    <th>Date</th>
                                    <th>Due Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>

                                </tr>
                            </thead>
                            <tbody id="invoice_body">
                                @foreach ($Invoices as $invoice)
                                    <tr>
                                        <td class="action">
                                            <button class="btn btn-success btn-sm p-0 m-0"
                                                onclick="window.location.href = '{{ url('Sales/Invoice/Create/' . $type . '/' . $invoice->id) }}';"><i
                                                    class="zmdi zmdi-edit px-2 py-1"></i></button>
                                            <button class="btn btn-primary btn-sm p-0 m-0"
                                                onclick="window.location.href = '{{ url('Sales/Invoice/View/' . $type . '/' . $invoice->id) }}';"><i
                                                    class="zmdi zmdi-eye px-2 py-1"></i></button>
                                            <button class="btn btn-danger btn-sm p-0 m-0"
                                                onclick="window.open('{{ url('Sales/Invoice/Create/pdf/' . $type . '/' . $invoice->id) }}');"><i
                                                    class="zmdi zmdi-receipt px-2 py-1"></i></button>
                                        </td>
                                        <td class="column_size text-center">
                                            {{ $invoice->month ?? '' }}-{{ appLib::padingZero($invoice->number ?? '') }}
                                        </td>
                                        <td class="column_size">{{ $invoice->name }}</td>
                                        <td class="column_size text-center">{{ $invoice->date }}</td>
                                        <td class="column_size text-center">{{ $invoice->due_date }}</td>
                                        <td class="column_size">{{ $invoice->total_inv_amount }}</td>
                                        <td class="column_size">{{ $invoice->status }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row" style="float: right">

                            <div class="col-md-6 " >
                                <label>Total Balance</label>
                            </div>
                            <div class="col-md-4">
                                <label id="total_balance">{{ $totalInvAmount }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('page-script')
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/datetime/1.0.2/js/dataTables.dateTime.min.js"></script>
    <script src="{{ asset('public/assets/js/sw.js') }}"></script>
    @include('datatable-list');
    <script>
        $('#invoices').DataTable({
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
            "columnDefs": [{
                className: "dt-right",
                "targets": [5]
            }, ],

        });
    </script>
    <script>
        $('#report').click(function() {
            $('.rowData').html('');
            $('.even').html('');
            $('.odd').html('');
            var show_status = '';
            var url = "{{ url('invoice/Report') }}";
            var token = "{{ csrf_token() }}";
            from_date = $('#from_date').val();
            to_date = $('#to_date').val();
            status = $('#status').val();
            customer_id = $('#customer_ID').val();
            $.post(url, {
                customer_id: customer_id,
                from_date: from_date,
                to_date: to_date,
                status: status,
                _token: token
            }, function(data) {
                console.log(data);

                data.map(function(val, i) {

                    var myUrl = "{{ url('Sales/Invoice/Create/') }}";
                    finalUrl = myUrl.concat('/', val.id);
                    // console.log(finalUrl);
                    var row = '<tr class="rowData">' +
                        '<td> <button class="btn btn-success btn-sm p-0 m-0" onclick=\"(goTo(\'' +
                        finalUrl +
                        '\'))"><i class="zmdi zmdi-edit px-2 py-1"></i></button></td>' +
                        '<td>' + val.inv_num + '</td>' +
                        '<td>' + val.name + '</td>' +
                        '<td>' + val.date + '</td>' +
                        '<td>' + val.due_date + '</td>' +
                        '<td class="text-right">' + val.total_inv_amount + '</td>' +
                        '<td>' + val.status + '</td>' +
                        '</tr>';
                    $('#invoices').append(row);

                });

            });

        });



        function goTo(url) {
            document.location.href = url;
        }
    </script>
@stop
