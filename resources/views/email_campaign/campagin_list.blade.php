@extends('layout.master')
@section('title', 'Campagin List')
@section('parentPageTitle', 'Crm')
@section('page-style')
<link rel="stylesheet" href="{{asset('public/assets/plugins/footable-bootstrap/css/footable.bootstrap.min.css')}}" />
<link rel="stylesheet" href="{{asset('public/assets/plugins/footable-bootstrap/css/footable.standalone.min.css')}}" />
@stop
@section('content')
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <a href="{{url('Crm/EmailCampaign/createcampaign/form')}}" class="btn btn-primary" >
                <b><i class="zmdi zmdi-plus"></i>Add New Campagin</b>
            </a>
           {{-- <a href="{{url('Crm/EmailCampaign/SendEmail')}}" class="btn btn-primary " >
                <b><i class="zmdi zmdi-plus"></i>Send Email</b>
            </a>  --}}
            <div class="table-responsive contact">
                <table class="table table-hover mb-0 c_list c_table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Start Date</th>
                            <th>Category</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        use App\Libraries\appLib;
                     @endphp
                        @foreach($campaginList as $clist)
                        <tr>
                            <td>
                           <p>{{$clist->campaign_name}}</p>
                            </td>
                            <td>
                                <p class="c_name">{{date(appLib::showDateFormat(), strtotime($clist->start_date))}}</p>
                            </td>
                            <td>
                                <p class="c_name">{{$clist->category ?? ''}}</p>
                            </td>
                           <td>
                                <a class="btn btn-primary btn-sm" href="{{url('Crm/EmailCampaign/edit/'.$clist->id)}}">
                                    <i class="zmdi zmdi-edit"></i>
                                </a>
                                <a class="btn btn-danger btn-sm del" onclick="return confirm('Are you sure?')" href="{{url('Crm/EmailCampaign/delete/'.$clist->id)}}">
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
                <h5 class="modal-title" id="exampleModalLongTitle">Are You Sure to Delete The Campagin</h5>

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
            $(".model-delete").attr("href", "EmailTemplates/.$clist->id" + id);
        });
    });
</script>
@stop
