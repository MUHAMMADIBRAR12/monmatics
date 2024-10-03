
@extends('layout.master')
@section('title','Vendor Payment')
@section('parentPageTitle', 'Inventory')
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
              <button class="btn btn-primary" style="align:right" onclick="window.location.href = '{{ url('Account/VendorPayment/Create')}}'">Add New Transaction </button>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover " id="igp">
                        <thead>
                            <tr>
                                <th class="px-1 py-0">Actions</th>
                                <th>Document No.</th>
                                <th>Date</th>
                                <th>Pay From</th>
                                <th>Pay To</th>
                                <th>Amount</th>
                                <th>Advance Amount</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vendor_payment_list as $vendor_payment)
                            <tr>
                                <td class="action">
                                    <button class="btn btn-success btn-sm p-0 m-0" onclick="window.location.href = '{{ url('Account/VendorPayment/Create/'.$vendor_payment->id)}}';" ><i class="zmdi zmdi-edit px-2 py-1"></i></button>
                                </td>
                                <td class="column_size">{{$vendor_payment->month ?? ''}}-{{appLib::padingZero($vendor_payment->number  ?? '')}}</td>
                                <td class="column_size">{{ date(appLib::showDateFormat(), strtotime($vendor_payment->date))}}</td>
                                <td class="column_size">{{$vendor_payment->received_in}}</td>
                                <td class="column_size">{{$vendor_payment->received_from}}</td>
                                <td class="column_size text-right">{{$vendor_payment->amount}}</td>
                                <td class="column_size text-right">{{$vendor_payment->advance_amount}}</td>
                                <td class="column_size">{{$vendor_payment->note}}</td>
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
$('#igp').DataTable( {
    dom: 'Bfrtip',
    buttons: [
        { extend: 'pageLength', className:'btn cl mr-2 px-3 rounded'},
        { extend: 'copy', className: 'btn bg-dark mr-2 px-3 rounded', title:'Products',
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7] // Exclude the first column from copy operation
          }
        },
        { extend: 'csv', className: 'btn btn-info mr-2 px-3 rounded', title:'Products',
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7] // Exclude the first column from CSV export
          }
        },
        { extend: 'pdf', className: 'btn btn-danger mr-2 px-3 rounded', title:'Products',
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7] // Exclude the first column from PDF export
          }
        },
        { extend: 'excel', className: 'btn btn-warning mr-2 px-3 rounded',title:'Products',
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7] // Exclude the first column from Excel export
          }
        },
        { extend: 'print', className: 'btn btn-success mr-2 px-3 rounded',title:'Products',
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7], // Exclude the first column from print view
            stripHtml: false // Preserve the HTML formatting of the table
          },
          customize: function (win) {
            $(win.document.body).find('table').find('.action').remove(); // Remove the Actions column from print view
          }
        },
        { extend: 'colvis', className:'visible btn rounded'},
    ],
    "bDestroy": true,
    "lengthMenu": [[100, 200, 500, -1], [100, 200, 500, "All"]],
    "columnDefs": [
      { targets: [0], visible: true, searchable: false }, // Display the Actions column in the website table
      { className: "dt-right", targets: [1, 2, 3] }, // Apply right alignment to columns 1, 2, and 3
    ],
});
</script>




@stop
