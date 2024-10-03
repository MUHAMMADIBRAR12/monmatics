@extends('layout.master')
@section('title', 'Document')
@section('parentPageTitle', 'Document')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>

    <link rel="stylesheet" href="{{ asset('public/assets/plugins/dropify/css/dropify.min.css') }}" />
    <style>
        .input-group-text {
            padding: 0 .75rem;
        }

        .amount {
            width: 150px;
            text-align: right;
        }

        .table td {
            padding: 0.10rem;
        }

        .dropify {
            width: 200px;
            height: 200px;
        }
    </style>
@stop
@section('content')

    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="header">
                <h2><strong>Document</strong> Details</h2>
            </div>
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            <div class="body">
                <form method="post" action="{{ url('document/create_document') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $id ?? '' }}">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="name">Document Title</label>
                            <div class="form-group">
                                <input type="text" name="title" class="form-control" value="{{ $documents->title ?? '' }}"
                                    required placeholder="Enter Title">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="name">Select Parent Document</label>
                            <div class="form-group">
                                <select name="parent_title" id="parent_document" class=" form-control show-tick ms select2">
                                    <option value="-1">Select Parent Document</option>
                                    @foreach ($parent_documents as $parent)
                                    <option value="{{$parent->title}}" {{  isset($documents->parent_title) ? 'selected' : '' }}>{{$parent->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Expiration Date</label>
                            <input type="date" name="expire_date" id="expire_date" class="form-control" value="{{ $documents->expiration_date ?? ''}}">
                        </div>
                        <div class="col-md-6">
                            <label>Ver / Volume</label>
                            <div class="form-group">
                                <input type="text" name="volume" id="volumn" class="form-control" value="{{ $documents->volume ?? ''}}"
                                    placeholder="Versions/Volumn">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="name">Department</label>
                            <div class="form-group">
                                <select name="department" id="category" class=" form-control show-tick ms select2">
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->name ?? ''}}" {{ isset($documents->department) ? 'selected' : '' }}>{{ $department->name ?? ''}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="fiscal_year">Description</label>
                            <div class="form-group" id="description">
                                <textarea name="description" maxlength="120" class="form-control no-resize" placeholder="">{{ $documents->description ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label for="fiscal_year">Attachment</label>
                            <br>
                            @if ($attachmentRecord ?? '')
                                <table>
                                    @foreach ($attachmentRecord as $attachment)
                                        <tr>
                                            <td><button type="button" class="btn btn-danger btn-sm"
                                                    id="{{ $attachment->file }}"
                                                    onclick="delattach(attachmentURL,this.id,token)"><i
                                                        class="zmdi zmdi-delete"></i></button></td>
                                            <td><a target="_blank"
                                                    href="{{ asset('assets/attachments/' . $attachment->file) }}" download
                                                    id="attachment">{{ $attachment->file }}</a></td>
                                        </tr>
                                    @endforeach
                                </table>
                            @endif
                            <input name="file" type="file" class="dropify">
                        </div>
                    </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" value="private" {{ isset($documents) && $documents->status == 'private' ? 'checked' : '' }} id="flexRadioDefault1">
                        <label class="form-check-label" for="flexRadioDefault1">
                            Private
                        </label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" value="public" {{ isset($documents) && $documents->status == 'public' ? 'checked' : '' }} id="flexRadioDefault2">
                        <label class="form-check-label" for="flexRadioDefault2">
                            Public
                        </label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" value="department" {{ isset($documents) && $documents->status == 'department' ? 'checked' : '' }} id="flexRadioDefault3">
                        <label class="form-check-label" for="flexRadioDefault3">
                            Department
                        </label>
                    </div>
                </div>
            </div>


            <div class="row">

            </div>

            <div class="col-md-12 mx-auto">
                <button class="btn btn-raised btn-primary waves-effect" type="submit">Save</button>
            </div>
        </div>
        </form>
    </div>
    </div>
    </div>
@stop
@section('page-script')
    <script src="{{ asset('public/assets/plugins/dropify/js/dropify.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/pages/forms/dropify.js') }}"></script>
    <script src="{{ asset('public/assets/js/sw.js') }}"></script>
@stop
