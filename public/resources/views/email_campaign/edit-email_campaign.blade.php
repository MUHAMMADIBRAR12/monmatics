@extends('layout.master')
@section('title', ' Campagin')
@section('parentPageTitle', 'Crm')

@section('page-style')
<link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.bootstrap.min.css') }}" />
<link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.standalone.min.css') }}" />
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <style>
        ul {
            list-style-type: none;
        }
        #category_table {
        width: 100%;
        border-collapse: collapse;
        }

        #category_table th,
        #category_table td {
        padding: 8px;
        text-align: left;
        border: 1px solid #ddd;
        }

        #category_table th {
        font-weight: bold;
        }

        #category_table input[type="checkbox"] {
        margin: 0;
        }
    </style>
@stop
@section('content')

<div class="row clearfix">
    <div class="card col-lg-12">
        <div class="header">
            <div class="row">
                <div class="col-md-9">
                    <h2><strong>Email</strong> Campaign</h2>
                </div>

            </div>
        </div>
        @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
        @endif
        <div class="body">
            <form method="POST" action="{{url('Crm/EmailCampaign/update')}}">
                {{ csrf_field() }}
                <input type="hidden" name="edit_campaign_id" value="{{$campaignList->id}}">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="role">Campaign name</label>
                            <input type="text" name="camp_name" class="form-control" required>
                        </div>
                        @error('camp_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="form-group">
                                <label for="role">Start Date</label>
                                <input type="date" name="camp_date" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" style="margin-top: 20pt;"> <!-- Modified col-md-3 instead of col-md-4 -->
                        <div class="form-group">
                            <select name="bulk_selection" id="bulk_selection" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                <option value="">Select Category</option>
                                <option value="Customer">Customer</option>
                                <option value="Vendor">Vendor</option>
                                <option value="Contacts">Contacts</option>
                                <option value="Users">Users</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row form-group" id="h2">
                    <div class="col-md-12 mt-3" style="text-align: center; width: 35%;height: 105%;">
                        <input type="submit" value="Add to List" class="btn btn-primary py-2 px-4 text-white">
                    </div>
                </div>
                <table class="table" id="category_table">
                    <thead>
                        <tr>
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
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

  <script>
    $(document).ready(function() {
      $('#bulk_selection').on('change', function() {
        $("#category_table").empty(); // Clear the table body

        var category = $('#bulk_selection').val();

        $.ajax({
          url: "{{url('Crm/EmailCampaign/categories')}}",
          type: 'GET',
          data: {
            category: category,
          },
          dataType: 'json',
          success: function(response) {
            // Add table head row
            var tableHead = '<tr>' +
              '<th><input class="form-check-input" type="checkbox" id="select_all"></th>' +
              '<th>Name</th>' +
              '<th>Email</th>' +
              '</tr>';
            $("#category_table").append(tableHead);

            // Add table data rows
            $.each(response, function(key, value) {
              var cData = '<tr>' +
                '<td><input class="form-check-input checkbox" type="checkbox" name="bulk_check[]" value="' + value.name + '"></td>' +
                '<td>' + value.name + '</td>' +
                '<td>' + value.email + '</td>' +
                '</tr>';

              $("#category_table").append(cData);
            });

            // Handle select all functionality
            $('#select_all').on('change', function() {
              var checkboxes = $('.checkbox');
              checkboxes.prop('checked', this.checked);
            });
          }
        });
      });
    });
  </script>




@stop
