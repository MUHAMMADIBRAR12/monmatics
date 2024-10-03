@extends('layout.master')
@section('title', 'Customer Outstanding')
@section('parentPageTitle', 'Reports')
@section('page-style')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/jquery-datatable/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/datatables.min.css" />
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <style>
        #ledger tr td {
            padding-top: 1px;
            padding-bottom: 1px;
        }
    </style>
@stop
@section('content')
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Customer</strong> Outstanding</h2>
                </div>
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Account</label>
                                <input type="text" name="account" id="account"
                                    onkeyup="autoFill(this.id,CustomerURL,token)" class="form-control autocomplete">
                                <input type="hidden" name="account_ID" id="account_ID" value="">
                                <input type="hidden" name="account_name" id="account_name" value="">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>From</label>
                                <div class="input-group">
                                    <input type="date" id="from_date" class="form-control">
                                </div>
                            </div>

                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>To</label>
                                <div class="input-group">
                                    <input type="date" id="to_date" class="form-control">

                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <button id="generate" type="button"
                                class="my-4 btn btn-raised btn-primary btn-round waves-effect">Generate </button>
                        </div>
                    </div>
                    <table id="ledger" class="table w-100  table-bordered table-striped table-hover js-exportable  ">
                        <thead>
                            <tr>
                                <th>Sr</th>
                                <th>Inv No</th>
                                <th>Date</th>
                                <th>Due Date</th>
                                <th>Amount</th>
                                <th>Paid</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-md-2">
                            <label>Total Amount</label>
                        </div>
                        <div class="col-md-2">
                            <label id="total_amount">0.00</label>
                        </div>
                        <div class="col-md-2">
                            <label>Total Paid</label>
                        </div>
                        <div class="col-md-2">
                            <label id="total_paid">0.00</label>
                        </div>
                        <div class="col-md-2">
                            <label>Total Balance</label>
                        </div>
                        <div class="col-md-2">
                            <label id="total_balance">0.00</label>
                        </div>
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
        function myDataTable() {
            fname = 'Customer Outstanding ' + $('input[name=account_name]').val() + '   ' + $('#from_date').val() + '   ' +
                $('#to_date').val();

            dt = $('#ledger').DataTable({
                scrollY: '50vh',
                scrollX: true,
                scrollCollapse: true,
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'pageLength',
                        className: 'btn cl mr-2 px-3 rounded'
                    },
                    {
                        extend: 'copy',
                        className: 'btn bg-dark mr-2 px-3 rounded',
                        filename: fname,
                        title: fname,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'csv',
                        className: 'btn btn-info mr-2 px-3 rounded',
                        filename: fname,
                        title: fname,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-danger mr-2 px-3 rounded',
                        filename: fname,
                        title: fname,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-warning mr-2 px-3 rounded',
                        filename: fname,
                        title: fname,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-success mr-2 px-3 rounded',
                        filename: fname,
                        title: fname,
                        exportOptions: {
                            columns: ':visible'
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
                        "targets": [0, 1, 2, 3]
                    },
                    {
                        className: "dt-left column_size",
                        "targets": []
                    },
                    {
                        className: "dt-right column_size",
                        "targets": [2, 3]
                    },
                    {
                        className: "dt-right column_size",
                        "targets": [0, 1, 4, 5, 6]
                    },
                ],
                "bDestroy": true,
            });

            return dt;

        }
    </script>


    <script>
        var CustomerURL = "{{ url('accountSearch') }}";
        var token = "{{ csrf_token() }}";
        var url = "{{ url('customerOutstanding') }}";
        $('#generate').click(function() {

            t = myDataTable();
            t.rows().remove().draw();
            var mydata = [];
            var sr = 0;
            var coa_id = $('#account_ID').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var total_amount = 0;
            var total_paid = 0;
            var total_balance = 0;
            $.post(url, {
                coa_id: coa_id,
                from_date: from_date,
                to_date: to_date,
                _token: token
            }, function(data) {
                var remainBalance = 0;
                var sr = 1;

                data.map(function(val, i) {
                    balance = getNum(val.total_inv_amount - val.amount_received, 2);
                    t.row.add([
                        '<td>' + sr + '</td>',
                        '<td><a href="{{ url('Sales/Invoice/Create/pdf/c/') }}/' + val.id +
                        '">' + val.inv_num + '</a></td>',
                        '<td>' + val.date1 + '</td>',
                        '<td>' + val.date2 + '</td>',
                        '<td>' + val.total_inv_amount + '</td>',
                        '<td>' + val.amount_received + '</td>',
                        '<td>' + balance + '</td>',
                        '</tr>',
                    ]).draw(false);
                    sr++;
                    total_amount = +getNum(total_amount) + +getNum(val.total_inv_amount);
                    total_paid = +getNum(total_paid) + +getNum(val.amount_received);
                    total_balance = +getNum(total_balance) + +getNum(balance);
                    $('#account_name').val(val.name);
                });

                $('#total_amount').html(showBalance(total_amount));
                $('#total_paid').html(showBalance(total_paid));
                $('#total_balance').html(showBalance(total_balance));
            });
        });
    </script>



    {{-- <script>
        var CustomerURL = "{{ url('accountSearch') }}";
        var token = "{{ csrf_token() }}";
        var url = "{{ url('customerOutstanding') }}";
        $('#generate').click(function() {
            var mydata = [];
            var sr = 0;
            var coa_id = $('#account_ID').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var total_amount = 0;
            var total_paid = 0;
            var total_balance = 0;
            $.post(url, {
                coa_id: coa_id,
                from_date: from_date,
                to_date: to_date,
                _token: token
            }, function(data) {
                var remainBalance = 0;
                data.map(function(val, i) {
                    balance = getNum(val.total_inv_amount - val.amount_received, 2);
                    var da = {
                        "Sr": sr++,
                        "Customer": val.name,
                        "Inv No": val.inv_num,
                        "Date": val.date,
                        "Due Date": val.due_date,
                        "Amount": showBalance(val.total_inv_amount),
                        "Paid": showBalance(val.amount_received),
                        "Balance": showBalance(balance),
                    };
                    mydata.push(da);
                    total_amount = +getNum(total_amount) + +getNum(val.total_inv_amount);
                    total_paid = +getNum(total_paid) + +getNum(val.amount_received);
                    total_balance = +getNum(total_balance) + +getNum(balance);
                });
                console.log(mydata);
                $('#total_amount').html(showBalance(total_amount));
                $('#total_paid').html(showBalance(total_paid));
                $('#total_balance').html(showBalance(total_balance));

                $('#ledger').DataTable({
                    data: mydata,
                    columns: [{
                            data: 'Sr'
                        },
                        {
                            data: 'Customer'
                        },
                        {
                            data: 'Inv No'
                        },
                        {
                            data: 'Date'
                        },
                        {
                            data: 'Due Date'
                        },
                        {
                            data: 'Amount'
                        },
                        {
                            data: 'Paid'
                        },
                        {
                            data: 'Balance'
                        },
                    ],
                    dom: 'Bfrtip',

                    buttons: [{
                            extend: 'pageLength',
                            className: 'btn cl mr-2 px-3 rounded'
                        },
                        {
                            extend: 'copy',
                            exportOptions: {
                                columns: ':visible',
                            },
                            className: 'btn btn-secondary mr-2 px-3 rounded',
                            title: 'Customer Outstanding'
                        },
                        {
                            extend: 'csv',
                            exportOptions: {
                                columns: ':visible',
                            },
                            className: 'btn btn-info mr-2 px-3 rounded',
                            title: 'Customer Outstanding'
                        },
                        {
                            extend: 'pdf',
                            exportOptions: {
                                columns: ':visible',
                            },
                            className: 'btn btn-danger mr-2 px-3 rounded',
                            title: 'Customer Outstanding'
                        },
                        {
                            extend: 'excel',
                            exportOptions: {
                                columns: ':visible',
                            },
                            className: 'btn btn-warning mr-2 px-3 rounded',
                            title: 'Customer Outstanding'
                        },
                        {
                            extend: 'print',
                            exportOptions: {
                                columns: ':visible',
                            },
                            className: 'btn btn-success mr-2 px-3 rounded',
                            title: 'Customer Outstanding'
                        },
                        {
                            extend: 'colvis'
                        },

                    ],
                    "lengthMenu": [
                        [100, 200, 500, -1],
                        [100, 200, 500, "All"]
                    ],
                    "columnDefs": [{
                            className: "dt-right",
                            "targets": [5, 6, 7]
                        },

                    ],
                    "bDestroy": true,
                });
            });
        });
    </script> --}}
@stop
