@extends('layout.master')
@section('title', ' Campagin')
@section('parentPageTitle', 'Crm')

@section('page-style')
<link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.bootstrap.min.css') }}" />
<link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.standalone.min.css') }}" />
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
{{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> --}}
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
            <form method="POST" action="{{url('Crm/EmailCampaign/createCampaign')}}">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="role">Campaign name</label>
                            <input type="text" name="campaign_name" class="form-control" required>
                        </div>
                        @error('campaign_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                
                    <div class="col-md-3" style="margin-top: 20pt;">
                        <div class="form-group">
                            <select name="bulk_selection" id="bulk_selection" class="form-control show-tick ms select2" data-placeholder="Select">
                                <option value="">Select Category</option>
                                <option value="Customer">Customer</option>
                                <option value="Vendor">Vendor</option>
                                <option value="Contacts">Contacts</option>
                                <option value="Users">Users</option>
                            </select>
                        </div>
                    </div>

                
                        <div class="col-md-4">
                            <div class="form-group">
    
                                <select name="temp_selection" id="temp_selection" class="form-control show-tick ms select2 mt-4" data-placeholder="Select" >
                                    <option value="">Select Template</option>
                                    @foreach($templateList as $tlist)
                                    <option value="{{$tlist->id}}" >{{$tlist->template_name}}</option>
    
    
                                    @endforeach
    
                                </select>
                            </div>
    
                        </div>
                     
    
                  
                </div>
                        
                <div class="row">
                </div>
                <div class="row form-group" id="h2">
                    {{-- <div class="col-md-12 mt-3 " style="text-align: center; width: 35%;height: 105%;">
                        <input type="submit" id="btn_add_to_list" value="Save Campaign" class="btn btn-primary py-2 px-4 text-white">
                    </div> --}}
                    <div class="col-md-12 " style="text-align: center; width: 35%;height: 105%; float:right">
                    <button type="button" class="btn btn-primary" id="template_preview" data-toggle="modal" data-target="#myModal">
                        Preview
                    </button>
                    
                </div>

                   
                </div>
                <table class="table" id="category_table">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" style="margin-left: 0px;" class="form-check-input mt-2" name="parent_status" id="checkAll">
                                <label class="mt-1" style="margin-left: 22px;">All</label>
                            </th>
                            <th>Name</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Your table rows with checkboxes, names, and emails -->
                    </tbody>
                </table>
            
                
                
                {{-- model for preview --}}
                <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog">
                    
                      <!-- Modal content-->
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal"></button>
                          <h4 class="modal-title">Modal Header</h4>
                        </div>
                        <div class="modal-body">
                          <p>Some text in the modal.</p>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary"  id="btn_add_to_list" value="Save Campaign">Send Now</button>
                          <button type="button" class="btn btn-primary" id="schedule_button">Schedule Campaign</button>
    
    
                        </div>
                        <div id="start_date_container" style="display: none;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role" id="start_date_label">Start Date</label>
                                    <input type="date" name="camp_date" class="form-control" id="start_date_input">
                                </div>
                            </div>
                        </div>
                        
                        
                        
                      
                                     
                      </div>               
                    </div>
                  </div>
            </form>


        </div>
    </div>
</div>
@stop
@section('page-script')
<script src="{{asset('public/assets/bundles/footable.bundle.js')}}"></script>
<script src="{{asset('public/assets/js/pages/tables/footable.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<!-- <script>
    $(document).ready(function() {

        $('#bulk_selection').on('change', function() {

            $("#email_campaign_tables").empty();
            var category = $('#bulk_selection').val();
            // alert(data);

            $.ajax({
                url: "{{url('Crm/EmailCampaign/categories')}}",
                type: 'GET',
                data: {
                    category: category,
                },
                dataType: 'json',
                success: function(response) {

                    // console.log(response);

                    $.each(response, function(key, value) {



                        var cData = '<tr>' +
                            '<td>' + value.name + '</td>' +
                            '<td>' + value.email + '</td>' +
                            '</tr>';
                        console.log(cData);
                   
                        $("#category_table tbody").append(cData);

                    });
                }
            })
        });

    });
</script> -->


<script>
    $(document).ready(function() {
    // Handle form submission
    $("#submitForm").on("click", function() {
        var bulkCheckboxes = $('.checkbox:checked');

        var data = {
            campaign_name: $("#campaign_name").val(),
            camp_date: $("#camp_date").val(),
            bulk_selection: $("#bulk_selection").val(),
            bulk_check: []
        };

        bulkCheckboxes.each(function() {
    data.bulk_check.push({
        id: $(this).val(),
        name: $(this).closest('tr').find('td:nth-child(2)').text(),
        email: $(this).closest('tr').find('td:nth-child(3)').text()
    });
});

        $.ajax({
            url: "{{url('Crm/EmailCampaign/createCampaign')}}",
            type: "POST",
            data: data,
            dataType: "json",
            success: function(response) {
                // Handle the response if needed
            },
            error: function(xhr) {
                // Handle the error if needed
            }
        });
    });

    $('#bulk_selection').on('change', function() {
        $("#category_table").empty(); // Clear the table body

        var category = $(this).val();

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
                        '<td><input class="form-check-input checkbox" type="checkbox" name="bulk_check[]" value="' + value.id + '"></td>' +
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

                // Handle row selection
                $('.checkbox').on('change', function() {
                    var name = $(this).closest('tr').find('td:nth-child(2)').text();
                    var email = $(this).closest('tr').find('td:nth-child(3)').text();

                    if ($(this).is(':checked')) {
                        // Add name and email to the data object if the row is checked
                        data.bulk_check.push({
                            id: $(this).val(),
                            name: name,
                            email: email
                        });
                    } else {
                        // Remove name and email from the data object if the row is unchecked
                        var index = data.bulk_check.findIndex(item => item.id === $(this).val());
                        if (index !== -1) {
                            data.bulk_check.splice(index, 1);
                        }
                    }
                });
            }
        });
    });
});

</script>
    



{{-- for template --}}


{{-- <script>
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
</script> --}}


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


{{-- modal show --}}
<script>
    $(document).ready(function() {
        // ... Your existing code ...

        $('#template_preview').on('click', function() {
        var campaignName = $('input[name="campaign_name"]').val();
        var vendorSelection = $('#bulk_selection').val();
        var templateSelection = $('#temp_selection').val();
        var email = $('#email').val();
        var templateName = $('#temp_selection option:selected').text(); // Get the selected template name

        // Get the selected checkboxes
        var selectedCheckboxes = $('input[name="bulk_check[]"]:checked');

        // Prepare arrays to hold the names and emails
        var selectedNames = [];
        var selectedEmails = [];

        // Loop through the selected checkboxes and extract the name and email from the associated row
        selectedCheckboxes.each(function() {
            var row = $(this).closest('tr');
            var name = row.find('td:nth-child(2)').text();
            var email = row.find('td:nth-child(3)').text();
            selectedNames.push(name);
            selectedEmails.push(email);
        });

            $.ajax({
                url: "{{url('Crm/EmailCampaign/preview/template')}}",
                type: 'GET',
                data: {
                    template: templateSelection,
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);

                    // Assuming the response is an array with a single template object
                

                    // Assuming the response is an array with a single template object
                    var template = response[0];
                    console.log("temp");
                    console.log(template);
                    var bodyText = template.body_text;

                    // Now, let's display the values in the modal
                    $('#myModal .modal-title').text('Preview');

                    // Handle the case when body_text is undefined
                    var bodyTextContent = bodyText ? stripHtmlTags(bodyText) : 'N/A';
                    var selectedNamesStr = selectedNames.join(', '); // Join names with a comma
                var selectedEmailsStr = selectedEmails.join(', ');

                var modalBodyContent = '<p>Campaign Name: ' + campaignName + '</p>' +
                    '<p>Selected Vendor: ' + vendorSelection + '</p>' +
                    '<p>Selected Template: ' + templateName + '</p>' +
                    '<p>Selected Names: ' + selectedNamesStr + '</p>' +
                    '<p>Selected Emails: ' + selectedEmailsStr + '</p>' +
                    '<p>Template Body Text: <textarea disabled style="width: 300px; height: 150px;">' + bodyTextContent + '</textarea></p>';

                    $('#myModal .modal-body').html(modalBodyContent);

                    // You can add additional information or formatting as needed.

                    // Finally, show the modal
                    $('#myModal').modal('show');
                },
                error: function() {
                    console.log('Error occurred while fetching data.');
                }
            });
        });
    });

    // Helper function to strip HTML tags from the text
    function stripHtmlTags(html) {
        var tmpElement = document.createElement('div');
        tmpElement.innerHTML = html;
        return tmpElement.textContent || tmpElement.innerText || '';
    }
</script>

{{-- code for hide input date  --}}

<script>
$(document).ready(function() {
    // Handle button click to toggle start date container
    $("#schedule_button").on("click", function() {
        $("#start_date_container").toggle();
    });
});


</script>








@stop