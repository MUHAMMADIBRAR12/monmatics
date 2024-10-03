@extends('layout.master')
@section('title', $title)
@section('parentPageTitle', 'Reports')
@section('page-style')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/datatables.min.css" />
    <link rel="stylesheet" href="{{ asset('public/assets/css/list.css') }}">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <style>
        #ledger tr td {
            padding-top: 1px;
            padding-bottom: 1px;
        }

        a,
        a:hover,
        a:focus,
        a:active {
            text-decoration: none;
            color: inherit;
        }

        .link-style:visited {
            color: white;
        }
    </style>
@stop
@section('content')
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Account</label>
                                <select name="account_id" id="account_ID" class="form-control show-tick ms select2"
                                    data-placeholder="Select" required>
                                    <option value="">Select Accounts</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}" data="{{ $account->name }}">{{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12 pl-1">
                            <div class="form-group">
                                <label>Type</label>
                                <select name="type" id="type" class="form-control show-tick ms select2"
                                    data-placeholder="Select">
                                    <option value="0">All</option>
                                    <option value="2">Payment</option>
                                    <option value="1">Receipt</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 pl-1">
                            <div class="form-group">
                                <label>From</label>
                                <div class="input-group">
                                    <input type="date" id="from_date" class="form-control">
                                </div>
                            </div>

                        </div>
                        <div class="col-md-3 col-sm-12 pl-1">
                            <div class="form-group">
                                <label>To</label>
                                <div class="input-group">
                                    <input type="date" id="to_date" class="form-control">

                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row clearfix">
                        <div class="col-md-3 col-sm-12 pl-1">
                            <input type="checkbox" name="openingBalance" id="openingBalance" checked="checked">
                            <label for="openingBalance">Opening Balance</label>
                        </div>
                        <div class="col-md-1 col-sm-12 pl-1">
                            <label for=""></label>
                            <button id="generate" type="button"
                                class="btn  btn-primary waves-effect font-weight-bold px-2">Generate </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="table" class="table table-bordered table-striped table-hover js-exportable dataTable"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sr</th>
                                    <th>Print</th>
                                    <th>Document</th>
                                    <th>Date</th>
                                    <th style="text-align:center">Description</th>
                                    <th style="text-align:center">Receipts</th>
                                    <th style="text-align:center">Payments</th>
                                    <th style="text-align:center">Balance</th>

                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <p id="total_receipts" class="font-weight-bold"></p>
                        </div>
                        <div class="col-md-4">
                            <p id="total_payments" class="font-weight-bold"></p>
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
            var dd = $('#account_ID').find('option:selected').attr('data');
            fname = 'Cash Book '  + $('#from_date').val() + '   ' + $('#to_date').val();
            t = $('#table').DataTable({
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
                        filename: fname,
                        title: fname,
                    },
                    {
                        extend: 'csv',
                        className: 'btn btn-info mr-2 px-3 rounded',
                        filename: fname,
                        title: fname,
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-danger mr-2 px-3 rounded',
                        filename: fname,
                        title: fname,
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-warning mr-2 px-3 rounded',
                        filename: fname,
                        title: fname,
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-success mr-2 px-3 rounded',
                        filename: fname,
                        title: fname,
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
                        className: "dt-right column_size",
                        "targets": [5, 6, 7]
                    },
                    {
                        className: "column_size",
                        "targets": [1, 2, 3, 4]
                    },
                ],
                "bDestroy": true,
            });

            return t;

        }
    </script>
    <script>
        var token = "{{ csrf_token() }}";
        var url = "{{ url('Reports/CashBook/getBookDetails') }}";
        $('#generate').click(function() {
            t = myDataTable();
            t.rows().remove().draw();
            $('.even').remove();
            $('.odd').remove();
            var sr = 1;
            var mydata = [];
            var sr = 1;
            var account_id = $('#account_ID').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var type = $('#type').find(":selected").val();
            var isChecked = $('#openingBalance').is(':checked');
            var openingBalance = isChecked ? $('#openingBalance').val() : '';
            $.post(url, {
                account_id: account_id,
                from_date: from_date,
                to_date: to_date,
                type: type,
                openingBalance: openingBalance,
                _token: token
            }, function(data) {
                var remainBalance = 0;
                var total_receipts = 0;
                var total_payments = 0;
                data.map(function(val, i) {

                    var balance = val.credit - val.debit;
                    remainBalance = +remainBalance + +balance;
                    if (remainBalance < 0)

                        showBalance = '(' + currencyPatrn(remainBalance) + ')';
                    else
                        showBalance = currencyPatrn(remainBalance);
                    total_receipts = +total_receipts + +val.credit;
                    total_payments = +total_payments + +val.debit;
                    t.row.add([
                        '<td>' + sr + '</td>',
                        '<td><a target="_blank" href="' + val.print_route +
                        '" class="btn btn-danger btn-sm p-0 m-0 link-style" style="text-decoration: none;"><i class="zmdi zmdi-receipt px-2 py-1"></i></a></td>',
                        '<td><a href="' + val.route + '" class="text-danger">' + val
                        .document + '</a></td>',
                        '<td>' + val.date + '</td>',
                        '<td>' + val.description + '</td>',
                        '<td>' + currencyPatrn(val.credit) + '</td>',
                        '<td>' + currencyPatrn(val.debit) + '</td>',
                        '<td>' + showBalance + '</td>',
                        '</tr>',
                    ]).draw(false);
                    sr++;
                });
                $('#total_receipts').html('Total Receipts:  ' + currencyPatrn(total_receipts));
                $('#total_payments').html('Total Payments:  ' + currencyPatrn(total_payments));
            });
        });
    </script>
@stop
