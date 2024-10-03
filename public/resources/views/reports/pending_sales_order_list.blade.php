@extends('layout.master')
@section('title', 'Pending Sales Order')
@section('parentPageTitle', 'Reports')
@section('page-style')
    <?php use App\Libraries\appLib; ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/datatables.min.css" />
    <link rel="stylesheet" href="{{ asset('public/assets/css/sw.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/list.css') }}">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <style>
        .label_text {
            color: #bababa;
        }
    </style>
@stop
@section('content')
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="body">
                    <div class="row">
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
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover " id="igp">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Sale Order#</th>
                                    <th>Customer</th>
                                    <th>Approvel Date</th>
                                    <th>Approvel #</th>
                                    <th>D.O Date</th>
                                    <th>D.O#</th>
                                    <th>D.O-Tracking Date</th>
                                    <th>Invoice Date</th>
                                    <th>Invoice#</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pending_sales_orders as $so)
                                    <tr>
                                        <td class="column_size">{{ $so->so_date }}</td>
                                        <td class="column_size">{{ $so->so_no }}</td>
                                        <td class="column_size">{{ $so->cust_name }}</td>
                                        <td class="column_size">{{ $so->soa_date ?? 'Pending' }}</td>
                                        <td class="column_size">{{ $so->soa_no ?? 'Pending' }}</td>
                                        <td class="column_size">{{ $so->do_date ?? 'Pending' }}</td>
                                        <td class="column_size">{{ $so->do_no ?? 'Pending' }}</td>
                                        <td class="column_size">{{ $so->do_tracking_date ?? 'Pending' }}</td>
                                        <td class="column_size">{{ $so->inv_date ?? 'Pending' }}</td>
                                        <td class="column_size">{{ $so->inv_no ?? 'Pending' }}</td>
                                        <td class="column_size">Un-Post</td>
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
        t = $('#igp').DataTable({
            scrollY: '50vh',
            scrollX: true,
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
                    title: 'Products',
                    orientation: 'landscape',
                    customize: function(doc) {
                        doc.defaultStyle.fontSize = 8; //<-- set fontsize to 16 instead of 10
                    }
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
                className: "column_size",
                "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
            }, ],

        });
        var token = "{{ csrf_token() }}";
        var url = "{{ url('getPendingSalesOrder') }}";
        $('#generate').click(function() {
            t.rows().remove().draw();
            $('.even').remove();
            $('.odd').remove();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            $.post(url, {
                from_date: from_date,
                to_date: to_date,
                _token: token
            }, function(data) {
                data.map(function(val, i) {
                    soa_date = (val.soa_date) ? val.soa_date : 'Pending';
                    soa_no = (val.soa_no) ? val.soa_no : 'Pending';
                    do_date = (val.do_date) ? val.do_date : 'Pending';
                    do_no = (val.do_no) ? val.do_no : 'Pending';
                    do_tracking_date = (val.do_tracking_date) ? val.do_tracking_date : 'Pending';
                    inv_date = (val.inv_date) ? val.inv_date : 'Pending';
                    inv_no = (val.inv_no) ? val.inv_no : 'Pending';
                    t.row.add([
                        '<td class="column_size">' + val.so_date + '</td>',
                        '<td class="column_size">' + val.so_no + '</td>',
                        '<td class="column_size">' + val.cust_name + '</td>',
                        '<td class="column_size">' + soa_date + '</td>',
                        '<td class="column_size">' + soa_no + '</td>',
                        '<td class="column_size">' + do_date + '</td>',
                        '<td class="column_size">' + do_no + '</td>',
                        '<td class="column_size">' + do_tracking_date + '</td>',
                        '<td class="column_size">' + inv_date + '</td>',
                        '<td class="column_size">' + inv_no + '</td>',
                        '<td class="column_size">Un-Post</td>',
                    ]).draw(false);
                });
            });
        });
    </script>
@stop
