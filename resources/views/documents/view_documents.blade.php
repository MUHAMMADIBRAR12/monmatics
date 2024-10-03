@extends('layout.master')
@section('title', 'DocumentView')
@section('parentPageTitle', 'Document')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/dropify/css/dropify.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    <style>
        .dropify {
            width: 100px;
            height: 100px;
        }

        th.project .project {
            display: block;
        }
    </style>

@stop
@section('content')
    <?php use App\Libraries\appLib; ?>
    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="header">
                @if (Session::has('delete_msg'))
                <div class="alert alert-danger" id="deleteAlert">
                    {{ Session::get('delete_msg') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true" onclick="hideAlert()">&times;</span>
                    </button>
                </div>
            @endif

                <h2><strong>Document</strong></h2>
            </div>
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            <div class="body">
                <div class="row">
                    {{-- $ticket-> ?? '' --}}
                    <div class="col-lg-2">
                        <label for="">Title</label>
                        <p>{{ $document->title ?? '' }}</p>
                    </div>
                    <div class="col-lg-2">
                        <label for="">Volume</label>
                        <p>{{ $document->volume ?? '' }}</p>
                    </div>
                    <div class="col-lg-2 col-md-2">
                        <label for="fiscal_year">Department</label>
                        <p>{{ $document->department ?? 'Unassigned' }}</p>

                    </div>
                    <div class="col-lg-2 col-md-2">
                        <label for="fiscal_year">Parent Document</label>
                        <p>{{ $document->parent_title ?? 'Unassigned' }}</p>

                    </div>

                </div>

                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <label for="fiscal_year">Description</label>
                        <div class="form-group" id="note">
                            <p>{{ $document->description ?? '' }}</p>
                        </div>
                    </div>

                </div>

            </div>

            <div class="row" style="background:lightgrey; margin-top: 15px;">
                <div class="col-lg-10">
                    <h4 style="padding:10px">Documents Attachments</h4>
                </div>
            </div>
            <div class="row">
                <div class="table-responsive">
                    <table id="transactionList"
                        class="table table-bordered table-striped table-hover js-exportable dataTable " style="width:100%">

                        <tbody>
                            @foreach ($attachmentRecord as $attachmentRecord)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td style="padding:10px">{{ $attachmentRecord->file ?? '' }} </td>
                                    <td style="padding:10px; height:10px;">
                                        <a href="{{ url('download/' . $attachmentRecord->id) }}" download><i
                                                class="zmdi zmdi-download text-success" style="font-size: 22px"></i></a>

                                        <a href="{{ url('Document/Delete/Attchment', $attachmentRecord->id) }}"
                                            ><i class="zmdi zmdi-delete text-danger " style="font-size: 22px"></i></a>
                                    </td>
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
    <script src="{{ asset('public/assets/plugins/dropify/js/dropify.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/pages/forms/dropify.js') }}"></script>
    <script src="{{ asset('public/assets/js/sw.js') }}"></script>
@stop
