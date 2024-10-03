@extends('layout.master')
@section('title','Purchase Order List')
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
              <button class="btn btn-primary" style="align:right" onclick="window.location.href = '{{ url('Purchase/PurchaseOrder/Create') }}';" >New Purchase Order</button>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="po">
                        <thead>
                            <tr>
                                <th class="px-1 py-0">Actions</th>
                                <th>Date</th>
                                <th>Purchase Order Number</th>
                                <th>Purchase Person</th>
                                <th>Vendor</th>
                                <th>warehouse</th>
                                <th>description</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchaseOrders as $purchaseOrder)
                            <tr>
                                <td class="action">
                                    @if($purchaseOrder->editable==1)
                                    <button class="btn btn-success btn-sm p-0 m-0" onclick="window.location.href = '{{ url('Purchase/PurchaseOrder/Create/'.$purchaseOrder->id)}}';" ><i class="zmdi zmdi-edit px-2 py-1"></i></button>
                                    @endif
                                    <button class="btn btn-primary btn-sm p-0 m-0" onclick="window.location.href = '{{ url('Purchase/PurchaseOrder/View/'.$purchaseOrder->id) }}';" ><i class="zmdi zmdi-view-day px-2 py-1"></i></button>
                                    <a class="btn btn-danger btn-sm del text-white p-0 m-0" data-toggle="modal" data-target="#exampleModalCenter{{ $purchaseOrder->id }}">
                                        <input type="hidden" id="purchaseOrderId" value="{{ $purchaseOrder->id }}">
                                        <i class="zmdi zmdi-delete px-2 py-1"></i>
                                    </a>

                                </td>
                                <td class="column_size">{{ date(appLib::showDateFormat(), strtotime($purchaseOrder->date))}}</td>
                                <td class="column_size">{{ $purchaseOrder->month ?? '' }}-{{appLib::padingZero($purchaseOrder->number  ?? '')}}</td>
                                <td class="column_size">{{$purchaseOrder->purchaser}}</td>
                                <td class="column_size">{{$purchaseOrder->name}}</td>
                                <td class="column_size">{{$purchaseOrder->w_name}}</td>
                                <td class="column_size">{{$purchaseOrder->note}}</td>
                                <td class="column_size">{{$purchaseOrder->status}}</td>
                            </tr>
                            <div class="modal fade" id="exampleModalCenter{{ $purchaseOrder->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">Confirmation</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete this purchase order?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                            <!-- Form to handle the deletion -->
                                            <form action="{{ route('purchaseOrder.destroy', ['id' => $purchaseOrder->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-primary">Yes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Modal for delete confirmation -->
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('page-script')
{{-- @include('datatable-list'); --}}
<script>
$('#po').DataTable( {
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
                    title: 'Purchase Order List',
                    exportOptions: {
                   columns: [1,2,3,4,5,6,7] // Exclude columns with the class 'actions'
            }
                },
                {
                    extend: 'csv',
                    className: 'btn btn-info mr-2 px-3 rounded',
                    title: 'Purchase Order List',
                    exportOptions: {
                   columns: [1,2,3,4,5,6,7] // Exclude columns with the class 'actions'
            }
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-danger mr-2 px-3 rounded',
                    title: 'Purchase Order List',
                    exportOptions: {
                   columns: [1,2,3,4,5,6,7] // Exclude columns with the class 'actions'
            }
                },
                {
                    extend: 'excel',
                    className: 'btn btn-warning mr-2 px-3 rounded',
                    title: 'Purchase Order List',
                    exportOptions: {
                   columns: [1,2,3,4,5,6,7] // Exclude columns with the class 'actions'
            }
                },
                {
                    extend: 'print',
                    className: 'btn btn-success mr-2 px-3 rounded',
                    title: 'Purchase Order List',
                    exportOptions: {
                   columns: [1,2,3,4,5,6,7] // Exclude columns with the class 'actions'
            }
                },
                {
                    extend: 'colvis',
                    className: 'visible btn rounded', exportOptions: {
                   columns: [1,2,3,4,5,6,7] // Exclude columns with the class 'actions'
            }
                },
            ],
    "bDestroy": true,
    "lengthMenu": [[100, 200, 500, -1], [100, 200, 500, "All"]],
    "columnDefs": [
      { className: "dt-right", "targets": [1,2] },
    ],

});

//purchase order  delete

</script>
@stop
