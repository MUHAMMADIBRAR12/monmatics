@extends('layout.master')
@section('title','Purchase Invoice List')
@section('parentPageTitle', 'Purchase')
@section('page-style')
<?php  use App\Libraries\appLib; ?>
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/datatables.min.css"/>
<link rel="stylesheet" href="{{asset('public/assets/css/sw.css')}}">
<link rel="stylesheet" href="{{asset('public/assets/css/list.css')}}">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
@stop
@section('content')
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
              <button class="btn btn-primary" style="align:right" onclick="window.location.href = '{{ url('Purchase/PurchaseInvoice/Create') }}';" >New Purchase Invoice</button>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="po">
                        <thead>
                            <tr>
                                <th class="px-1 py-0">View</th>
                                <th>Invoice #</th>
                                <th>Date</th>
                                <th>GRN No.</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchase_invoices as $invoice)
                            <tr>
                                <td class="action">
                                    @if($invoice->post_status!=='Posted')
                                        <button class="btn btn-success btn-sm p-0 m-0" onclick="window.location.href = '{{ url('Purchase/PurchaseInvoice/Create/'.$invoice->id) }}';" ><i class="zmdi zmdi-edit px-2 py-1"></i></button>
                                    @endif
                                    <button class="btn btn-primary btn-sm p-0 m-0" ><i class="zmdi zmdi-view-day px-2 py-1"></i></button>
                                </td>
                                <td class="column_size">{{$invoice->month ?? '' }}-{{appLib::padingZero($invoice->number  ?? '')}}</td>
                                <td class="column_size">{{ date(appLib::showDateFormat(), strtotime($invoice->date))}}</td>
                                <td class="column_size">{{$invoice->grn_num}}</td>
                                <td class="column_size">{{$invoice->note}}</td>
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
$('#po').DataTable( {
    scrollY: '50vh',
    scrollCollapse: true,
    dom: 'Bfrtip',
    buttons: [{
                    extend: 'pageLength',
                    className: 'btn cl mr-2 px-3 rounded'
                },
                {
                    extend: 'copy',
                    className: 'btn  bg-dark mr-2 px-3 rounded',
                    title: 'Purchase Invoice List ',
                    exportOptions: {
                columns: [1,2,3,4] // Exclude columns with the class 'actions'
            }
                },
                {
                    extend: 'csv',
                    className: 'btn btn-info mr-2 px-3 rounded',
                    title: 'Purchase Invoice List ',
                    exportOptions: {
                columns: [1,2,3,4] // Exclude columns with the class 'actions'
            }
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-warning mr-2 px-3 rounded',
                    title: 'Purchase Invoice List',
                    exportOptions: {
                columns: [1,2,3,4], // Exclude columns with the class 'actions'
            },
            customize: function (doc) {
    // Decrease the font size for table cells
    doc.defaultStyle.fontSize = 10; // Adjust the value as needed

    // Decrease the font size for table headers (th)
    doc.content[1].table.headerRows = 1;
    doc.content[1].table.body[0].forEach(function (cell) {
        cell.fontSize = 20; // Adjust the value as needed
    });

    // Decrease the margin between columns
    doc.content[1].layout = {
        hLineWidth: function (i, node) {
            return (i === 0 || i === node.table.body.length) ? 0.5 : 0.2;
        },
        vLineWidth: function (i) {
            return 0;
        },
        paddingLeft: function (i) {
            return 2;
        },
        paddingRight: function (i) {
            return 0;
        },
        paddingTop: function (i) {
            return 2;
        },
        paddingBottom: function (i) {
            return 2;
        }
    };

    // Wrap the table in a container element with margins
    doc.content[1] = {
        margin: [120, 20, 20, 20], // left, top, right, bottom margins
        table: doc.content[1].table
    };

    // Wrap the text into two to three lines
    doc.content[1].table.body.forEach(function (row) {
        row.forEach(function (cell) {
            cell.styles = {
                cellWidth: 'wrap',
                cellPadding: 10
            };
        });
    });
}


                },

                {
                    extend: 'excel',
                    className: 'btn btn-warning mr-2 px-3 rounded',
                    title: 'Purchase Invoice List ',


                    exportOptions: {
                columns: [1,2,3,4] // Exclude columns with the class 'actions'
            }
                },
                {
                    extend: 'print',
                    className: 'btn btn-success mr-2 px-3 rounded',
                    title: 'Purchase Invoice List ',
                    exportOptions: {
                columns: [1,2,3,4] // Exclude columns with the class 'actions'
            }
                },
                {
                    extend: 'colvis'
                },
            ],
    "bDestroy": true,
    "lengthMenu": [[100, 200, 500, -1], [100, 200, 500, "All"]],
    "columnDefs": [
      { className: "dt-right", "targets": [1,2] },
    ],

});
</script>
@stop
