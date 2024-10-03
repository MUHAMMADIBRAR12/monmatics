@extends('layout.master')
@section('title', 'Purchase Expenses')
@section('parentPageTitle', 'Purchase')
@section('page-style')
<link rel="stylesheet" href="{{asset('public/assets/plugins/footable-bootstrap/css/footable.bootstrap.min.css')}}" />
<link rel="stylesheet" href="{{asset('public/assets/plugins/footable-bootstrap/css/footable.standalone.min.css')}}" />
@stop
@section('content')
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <a href="{{url('Purchase/PurchaseExpenses')}}" class="btn btn-primary" >
                <b><i class="zmdi zmdi-plus"></i> New Expenses</b>
            </a>
            <div class="table-responsive contact">
                <table class="table table-hover mb-0 c_list c_table">
                    <thead>
                        <tr>
                            <th>Purchase Expenses</th>
                            <th>Subject</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                       @foreach(($PoLists ??'') as $PoList)
                        <tr>
                            <td>
                                <p class="c_name">{{$PoList->po_number ?? ''}}</p>
                            </td>
                            <td>
                                <p class="c_name"></p>
                            </td>
                        
                            <td>
                                <a class="btn btn-primary btn-sm" href="{{url('Purchase/PurchaseExpensesView/'.$PoList->pe_id) ??''}}">
                                    <i class="zmdi zmdi-edit"></i>
                                </a>
                                <a class="btn btn-danger btn-sm del" onclick="return confirm('Are you sure?')" href="">
                                    <i class="zmdi zmdi-delete text-white"></i>
                                </a>

                            </td>
                        </tr>
                       @endforeach 
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal for delete confirmation -->
<div class="modal fade" id="modalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Are You Sure to Delete The Template</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer">
                <a class="btn btn-secondary" data-dismiss="modal">No</a>
                <a class="btn btn-primary model-delete" href="">Yes</a>
            </div>
        </div>
    </div>
</div>
@stop
@section('page-script')
<script src="{{asset('public/assets/bundles/footable.bundle.js')}}"></script>
<script src="{{asset('public/assets/js/pages/tables/footable.js')}}"></script>
<script>
    $(document).ready(function() {
        $(document).on('click', '.del', function() {
            var id = $(this).find('#userId').val();
            $(".model-delete").attr("href", "EmailTemplates/.$blist->id" + id);
        });
    });
</script>
@stop