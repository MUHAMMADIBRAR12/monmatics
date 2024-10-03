@extends('layout.master')
@section('title', 'Template List')
@section('parentPageTitle', 'Crm')
@section('page-style')
<link rel="stylesheet" href="{{asset('public/assets/plugins/footable-bootstrap/css/footable.bootstrap.min.css')}}" />
<link rel="stylesheet" href="{{asset('public/assets/plugins/footable-bootstrap/css/footable.standalone.min.css')}}" />
@stop
@section('content')
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <a href="{{url('Crm/EmailTemplates/createTemplate/form')}}" class="btn btn-primary" >
                <b><i class="zmdi zmdi-plus"></i>Add New Template</b>
            </a>
            <div class="table-responsive contact">
                <table class="table table-hover mb-0 c_list c_table">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Template Name</th>
                            <th>Subject</th>
                            <th>Description</th>
                            <!-- <th>Created Date</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($templateList as $tlist)
                        <tr>
                            <td>
                                <a class="btn btn-primary btn-sm" href="{{url('Crm/EmailTemplates/edit/'.$tlist->id)}}">
                                    <i class="zmdi zmdi-edit"></i>
                                </a>
                                <a class="btn btn-danger btn-sm del" onclick="return confirm('Are you sure?')" href="{{url('Crm/EmailTemplates/delete/'.$tlist->id)}}">
                                    <i class="zmdi zmdi-delete text-white"></i>
                                </a>

                            </td>
                            <td>
                                <p class="c_name" style="white-space: normal; word-wrap: break-word; overflow-wrap: break-word; max-width: 200px;">{{$tlist->template_name}}</p>
                            </td>
                            <td>
                                <p class="c_name" style="white-space: normal; word-wrap: break-word; overflow-wrap: break-word; max-width: 200px;">{{$tlist->subject}}</p>
                                
                            </td>
                            <td style="max-width: 1000px; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; padding: 5px;">
                                {!! $tlist->body_text !!}
                            </td>

                            <!-- <td>
                                <span class="badge badge-success">{{$tlist->created_at}}</span>
                            </td> -->



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