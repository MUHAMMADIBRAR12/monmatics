@extends('layout.master')
@section('title', 'Blog List')
@section('parentPageTitle', 'Blog')
@section('page-style')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="{{asset('public/assets/plugins/footable-bootstrap/css/footable.bootstrap.min.css')}}" />
<link rel="stylesheet" href="{{asset('public/assets/plugins/footable-bootstrap/css/footable.standalone.min.css')}}" />
@stop
@section('content')


<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <a href="{{url('blog/post/form')}}" class="btn btn-primary" >
                <b><i class="zmdi zmdi-plus"></i>Add New Post</b>
            </a>
            <div class="table-responsive contact">
                <table class="table table-hover mb-0 c_list c_table" id="posts">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Title</th>
                            <th>Image</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Description</th>
                            <th>Created_At</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($posts as $post)
                        <tr>
                            <td class="action">
                                <a class="btn btn-success btn-sm p-0 m-0"  href="{{url('blog/post/form/'.$post->id)}}">
                                    <i class="zmdi zmdi-edit px-2 py-1"></i>
                                </a>
                                <a class="btn btn-danger btn-sm del p-0 m-0" data-toggle="modal" data-target="#modalCenter">
                                       <input type="hidden" id="userId" value="{{$post->id}}">
                                       <i class="zmdi zmdi-delete text-white px-2 py-1"></i>
                                </a> 
                                
                                <a class="btn btn-primary btn-sm " href="{{ route('blog.viewPost', ['id' => $post->id]) }}">
                                    <i class="zmdi zmdi-eye  "></i>
                                </a>
                                
                            </td>
                            <td>
                                <p class="c_name" style="white-space: normal; word-wrap: break-word; overflow-wrap: break-word; max-width: 200px;">{{$post->title}}</p>
                            </td>
                            <td>
                                @if ($post)
                                <img src="{{ url('display/' . $post->id) }}"
                                     class="rounded-circle shadow " alt="post-image" style="max-width: 50px; border-radius: 10px;">
                             @else
                                 <p>No image available for this post.</p>
                             @endif
                            </td>
                            <td style="max-width: 200px; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; padding: 5px;">
                                {!! $post->category !!}
                            </td>
                            <td>
                                <span class="badge badge-success">{{$post->status}}</span>
                            </td>
                            <td style="max-width: 200px; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; padding: 5px;">
                                {!! Str::limit($post->description, 100, '...') !!}
                            </td>
                            <td>
                                <span>{{ $post->created_at }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    
                </table>
                <div class="d-flex justify-content-center">
                    {!! $posts->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for delete confirmation -->
<div class="modal fade" id="modalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Are You Sure to Delete The Post</h5>

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
@include('datatable-list');


<script src="{{asset('public/assets/bundles/footable.bundle.js')}}"></script>
<script src="{{asset('public/assets/js/pages/tables/footable.js')}}"></script>
<script>
    $(document).ready(function() {
        $(document).on('click', '.del', function() {
            var id = $(this).find('#userId').val();
            $(".model-delete").attr("href", "removepost/" + id);
        });
    });
</script>
<script>
    $('#posts').DataTable( {
        dom: 'Bfrtip',
        paging: false,
        buttons: [
            {
                extend: 'pageLength',
                className: 'btn cl mr-2 px-3 rounded'
            },
            {
                extend: 'copy',
                className: 'btn bg-dark mr-2 px-3 rounded',
                title: 'Posts',
                exportOptions: {
                    columns: [1,3,4,5] // Exclude columns with the class 'actions'
                }
            },
            {
                extend: 'csv',
                className: 'btn btn-info mr-2 px-3 rounded',
                title: 'Posts',
                exportOptions: {
                    columns: [1,3,4,5] // Exclude columns with the class 'actions'
                }
            },
            {
                extend: 'pdf',
                className: 'btn btn-danger mr-2 px-3 rounded',
                title: 'Posts',
                exportOptions: {
                    columns: [1,3,4,5] // Exclude columns with the class 'actions'
                }
            },
            {
                extend: 'excel',
                className: 'btn btn-warning mr-2 px-3 rounded',
                title: 'Posts',
                exportOptions: {
                    columns: [1,3,4,5] // Exclude columns with the class 'actions'
                }
            },
            {
                extend: 'print',
                className: 'btn btn-success mr-2 px-3 rounded',
                title: 'Posts',
                exportOptions: {
                    columns: [1,3,4,5] // Exclude columns with the class 'actions'
                }
            },
            { extend: 'colvis', className: 'visible btn rounded' }
        ],
        "bDestroy": true,
        "lengthMenu": [[100, 200, 500, -1], [100, 200, 500, "All"]],
            
    }); 
    
 
    </script>
@stop