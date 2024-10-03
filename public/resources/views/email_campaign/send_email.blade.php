@extends('layout.master')
@section('title', ' Campagin')
@section('parentPageTitle', 'Crm')

@section('page-style')
<link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.bootstrap.min.css') }}" />
<link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.standalone.min.css') }}" />
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
<style>
    ul {
        list-style-type: none;
    }
</style>
@stop
@section('content')

<div class="row clearfix">
    <div class="card col-lg-12">
        <div class="header">
            <div class="row">
                <div class="col-md-9">
                    <h2><strong>Send</strong> Emails</h2>
                </div>

            </div>
        </div>
        @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
        @endif
        <div class="body">
            <form method="POST" action="{{url('Crm/EmailCampaign/send/emails')}}">
                {{ csrf_field() }}


                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">

                            <select name="bulk_selection" id="bulk_selection" class="form-control show-tick ms select2 mt-4" data-placeholder="Select" required>
                                <option value="">Select Campaign</option>
                                @foreach($campaginList as $clist)
                                <option value="{{$clist->id}}">{{$clist->campaign_name}}</option>
                                @endforeach

                            </select>
                        </div>

                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="form-group">
                                <label for="role">Start Date</label>
                                <input type="date" name="camp_date" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mt-4">
                        <div class="form-group">
                            <div class="form-group">

                                <input type="submit" id="inbox_search" value="Run Now" class="btn btn-primary py-2 px-4 text-white">
                                <div id="inbox_results"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">

                            <select name="temp_selection" id="temp_selection" class="form-control show-tick ms select2 mt-4" data-placeholder="Select" required>
                                <option value="">Select Template</option>
                                @foreach($templateList as $tlist)
                                <option value="{{$tlist->id}}" >{{$tlist->template_name}}</option>


                                @endforeach

                            </select>
                        </div>

                    </div>
                 

                </div>




                <table class="table" id="category_table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" style="margin-left: 0px;" class="form-check-input mt-2" name="parent_status" id="checkAll">
                                <label class="mt-1" style="margin-left: 22px;">All</label>
                            </th>
                            <th>Name</th>
                            <th>Email</th>

                        </tr>
                    </thead>
                    <tbody id="email_campaign_tables">

                    </tbody>
                </table>
            </form>



        </div>
    </div>
</div>
@stop
@section('page-script')
<script src="{{asset('public/assets/bundles/footable.bundle.js')}}"></script>
<script src="{{asset('public/assets/js/pages/tables/footable.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>






<script>
    $(document).ready(function() {

        $('#bulk_selection').on('change', function() {
            $("#category_table ").empty();
            var campaign = $('#bulk_selection').val();
            console.log("Selected campaign:", campaign);

            $.ajax({
                url: "{{url('Crm/EmailCampaign/email/data')}}",
                type: 'GET',
                data: {
                    campaign: campaign,
                },
                dataType: 'json',
                success: function(response) {
                    $.each(response, function(key, value) {
                        var cData = '<tr>' +
                            '<td>' + value.name + '</td>' +
                            '<td>' + value.email + '</td>' +
                            '</tr>';
                        console.log("Generated HTML element:", cData);
                        $("#category_table").append(cData);
                    });
                },
                error: function() {
                    console.log('Error occurred while fetching data.');
                }
            });
        });

        $('#btn_add_to_list').on('click', function() {
            var bulk_check = $('input[name^="name_detail"]').map(function(idx, elem) {
                return $(elem).val();
            }).get();
            console.log(bulk_check);
        });

    });
</script>
<script>
    $(document).ready(function() {

        $('#temp_selection').on('change', function() {
            $("#category_table ").empty();
            var template = $('#temp_selection').val();
            console.log("Selected template:", template);

            $.ajax({
                url: "{{url('Crm/EmailCampaign/preview/template')}}",
                type: 'GET',
                data: {
                    template: template,
                },
                dataType: 'json',
                success: function(response) {
                    $.each(response, function(key, value) {
                        var cData = '<tr>' +
                            '<td>' + value.subject + '</td>' +
                            '<td>' + value.body_text + '</td>' +
                            '</tr>';
                        console.log("Generated HTML element:", cData);
                        $("#category_table").append(cData);
                    });
                },
                error: function() {
                    console.log('Error occurred while fetching data.');
                }
            });
        });

        $('#template_preview').on('click', function() {
            var bulk_check = $('input[name^="name_detail"]').map(function(idx, elem) {
                return $(elem).val();
            }).get();
            console.log(bulk_check);
        });

    });
</script>


 <script>
    $(document).ready(function() {



        $('#temp_selection').on('change', function() {

            $("#category_table ").empty();
            var template = $('#temp_selection').val();
            console.log("Selected template:", template);

            $.ajax({
                url: "{{url('Crm/EmailCampaign/preview/template')}}",
                type: 'GET',
                data: {
                    template: template,
                },
                dataType: 'json',
                success: function(response) {

                    $.each(response, function(key, value) {


                        var cData = '<tr>' +




                            '<td>' + value.subject + '</td>' +

                            '<td>' + value.body_text + '</td>' +

                            '</tr>';

                        console.log("Generated HTML element:", cData);
                        $("#category_table").append(cData);
                    });
                }
            })
        });


        $('#template_preview').on('click', function() {

            var bulk_check = $('input[name^="name_detail"]').map(function(idx, elem) {
                return $(elem).val();
            }).get();

            console.log(bulk_check);


        });

    });
</script> 





<script>
    $('#temp_selection').on('change', function() {
    var selectedOption = $(this).find(':selected');
    var url = selectedOption.data('url');

    if (url) {
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                window.open(response.url);
            },
            error: function(error) {
                console.log(error);
            }
        });
    }
});
</script>


@stop