@extends('layout.master')
@section('title', 'Post Transaction List')
@section('parentPageTitle', 'Accounts')
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

        .pointer {
            cursor: pointer;
        }

        .print:visited {
            color: white;
        }
    </style>
    <script lang="javascript/text">
        var accountsURL = "{{ url('transactionAccountSearch') }}";
        var token = "{{ csrf_token() }}";
    </script>
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
                                <input type="text" name="account" id="account" class="form-control"
                                    onkeyup="autoFill(this.id,accountsURL, token)">
                                <input type="hidden" name="account_ID" id="account_ID">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-group">
                                <label>Type</label>
                                <select name="type" id="type" class="form-control show-tick ms select2"
                                    data-placeholder="Select">
                                    <option value="0">All</option>
                                    @foreach ($voucher_types as $types)
                                        <option value="{{ $types->id }}">{{ $types->type }}</option>
                                    @endforeach
                                </select>
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
                        <div class="col-md-3 col-sm-12 ml-0">
                            <div class="form-group">
                                <label>To</label>
                                <div class="input-group">
                                    <input type="date" id="to_date" class="form-control">

                                </div>
                            </div>
                        </div>
                        <div class="col-md-1 col-sm-12 ml-0">
                            <button id="generate" type="button"
                                class="btn btn-outline-primary px-2 mt-4 btn-circle">Generate </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <button class="btn btn-info" id="post">Post</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="transactionList"
                            class="table table-bordered table-striped table-hover js-exportable dataTable"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th class="p-1"><input type="checkbox" id="check-all"></th>
                                    <th class="p-1">Print</th>
                                    <th class="p-1">Sr</th>
                                    <th class="p-1">Document</th>
                                    <th class="p-1">Date</th>
                                    <th class="p-1 text-center">Account Name</th>
                                    <th class="p-1 text-center">Description</th>
                                    <th class="p-1 text-center">Debit</th>
                                    <th class="p-1 text-center">Credit</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
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
                    title: 'Products',
                    exportOptions: {
                    columns: [0, 3, 4, 5, 6, 7,8] // Exclude columns 1 and 2 (Edit and Print)
                }
                },
                {
                    extend: 'csv',
                    className: 'btn btn-info mr-2 px-3 rounded',
                    title: 'Products',
                    exportOptions: {
                    columns: [0, 3, 4, 5, 6, 7,8] // Exclude columns 1 and 2 (Edit and Print)
                }
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-danger mr-2 px-3 rounded',
                    title: 'Products',
                    exportOptions: {
                    columns: [0, 3, 4, 5, 6, 7,8] // Exclude columns 1 and 2 (Edit and Print)
                }
                },
                {
                    extend: 'excel',
                    className: 'btn btn-warning mr-2 px-3 rounded',
                    title: 'Products',
                    exportOptions: {
                    columns: [0, 3, 4, 5, 6, 7,8] // Exclude columns 1 and 2 (Edit and Print)
                }
                },
                {
                    extend: 'print',
                    className: 'btn btn-success mr-2 px-3 rounded',
                    title: 'Products',
                    exportOptions: {
                    columns: [0, 3, 4, 5, 6, 7,8] // Exclude columns 1 and 2 (Edit and Print)
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
                    "targets": [7, 8]
                },
                {
                    className: "column_size",
                    "targets": [0, 1, 2, 3, 4, 5, 6]
                },
            ],
            "bDestroy": true,
        });
    </script>
    <script>
        var token = "{{ csrf_token() }}";
        var url = "{{ url('getUnPostTransactionList') }}";

        $('#generate').click(function() {
            t.rows().remove().draw();
            $('.even').remove();
            $('.odd').remove();
            var sr = 1;
            var account_id = $('#account_ID').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var type = $('#type').val();
            $.post(url, {
                account_id: account_id,
                from_date: from_date,
                to_date: to_date,
                type: type,
                _token: token
            }, function(data) {
                var sr = 1;
                data.map(function(val, i) {
                    console.log(val.route);
                    t.row.add([
                        '<td><input type="checkbox" name="post" class="myCheck" id="chk' +
                        i + '" value="' + val.id + '"></td>',
                        '<td><button class="btn btn-danger btn-sm p-0 m-0"><a target="_blank" href="' +
                        val.print_route +
                        '" class="btn btn-danger print btn-sm p-0 m-0"><i class="zmdi zmdi-receipt px-2 py-1"></i></a></button></td>',
                        '<td>' + sr + '</td>',
                        // '<td><a href="'+val.route+'" class="text-danger">'+val.document+'</a></td>',
                        '<td>' + val.document + '</td>',
                        '<td>' + val.date + '</td>',
                        '<td>' + val.account_name + '</td>',
                        '<td>' + val.description + '</td>',
                        '<td>' + val.debit + '</td>',
                        '<td>' + val.credit +
                        ' <input type="hidden" id="recv_invoice_id" value="' + val
                        .recv_invoice_id + '"> </td>',
                    ]).draw(false);
                    sr++;
                });
            });
        });

        $('#check-all').click(function() {
            if ($("#check-all").prop('checked') == true) {
                $(".myCheck").prop("checked", true);
            } else {
                $(".myCheck").prop("checked", false);
            }

        });

        $('#post').on('click', function() {

            $('input[name="post"]:checked').each(function() {
                console.log(this.value);
                var url = "{{ url('updatePostStatus') }}";
                recv_invoice_id = $('#recv_invoice_id').val();
                $.post(url, {
                    id: this.value,
                    recv_invoice_id: recv_invoice_id,
                    _token: token
                }, function(data) {

                });
            });
            $("#generate")[0].click();
        });
    </script>
@stop
