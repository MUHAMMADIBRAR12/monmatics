@extends('layout.master')
@section('title', 'Employee Detail')
@section('parentPageTitle', 'Hcm')

@section('page-style')
    <link rel="stylesheet" href="{{asset('public/assets/css/sw.css')}}">
    <link rel="stylesheet" href="{{asset('public/assets/css/list.css')}}">
    <link rel="stylesheet" href="{{asset('public/assets/plugins/dropify/css/dropify.min.css')}}" />
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>


@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session('success') }}</strong>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session('error') }}</strong>
        </div>
    @endif
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body body">


                    <ul class="nav nav-tabs p-0 mb-3">
                        <li class="nav-item"><p class="nav-link active" style="cursor:pointer;" data-toggle="tab" id="personal_info_tab_button">Personal Information</p></li>
                        <li class="nav-item"><p class="nav-link pe-auto" style="cursor:pointer;" data-toggle="tab" id="employee_detail_tab_button">Employment Detail</p></li>
                        <li class="nav-item"><p class="nav-link pe-auto" style="cursor:pointer;" data-toggle="tab" id="asset_tab_button">Assets</p></li>
                    </ul>

                    <div id="personal_info_tab" class="tab-content mt-3">
                        @include('hcm.employee.profileTabs.personalInfo')

                    </div>

                    <div class="tab-content" id="employee_detail_tab">
                        @include('hcm.employee.profileTabs.employementDetail')

                    </div>


                    <div class="tab-content"  id="assetTabs">
                        @include('hcm.employee.profileTabs.assets')
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('page-script')
<script>
        $(document).ready(function(){
        // Initially show the first tab and set its button as active
        $('#personal_info_tab').show();
        $('#employee_detail_tab').hide();
        $('#salary_detail_tab').hide();
        $('#asset_tab').hide();

        $('#personal_info_tab_button').addClass('active-tab');

        // Add click event listeners to the buttons
        $('#personal_info_tab_button').click(function(){
        showTab('personal_info_tab');
        activateButton('personal_info_tab_button');
    });

        $('#employee_detail_tab_button').click(function(){
        showTab('employee_detail_tab');
        activateButton('employee_detail_tab_button');
    });

        $('#salary_detail_tab_button').click(function(){
        showTab('salary_detail_tab');
        activateButton('salary_detail_tab_button');
    });

        $('#asset_tab_button').click(function(){
            console.log('hgello from asset');
        showTab('assetTabs');
        activateButton('asset_tab_button');
    });

        // Function to show a tab and hide others
        function showTab(tabId) {
            console.log('hello from show tab func');
        $('.tab-content').hide();
        $('#' + tabId).show();
    }

        // Function to activate a button and deactivate others
        function activateButton(buttonId) {
        $('.btn').removeClass('active-tab');
        $('#' + buttonId).addClass('active-tab');
    }

            $(".toggleVisibility").click(function(){
                $(".contentHideShow").toggle();
            });
            $(".educationToggleVisibility").click(function(){
                $(".educationContentHideShow").toggle();
            });

            $(".EmployementToggleVisibility").click(function(){
                $(".employementContentHideShow").toggle();
            });

            $(".emergencyToggleVisibility").click(function(){
                $(".emergencyContentHideShow").toggle();
            });

            $(".bankToggleVisibility").click(function(){
                $(".bankContentHideShow").toggle();
            });

            $(".socialMediaToggleVisibility").click(function(){
                $(".socialMediaContentHideShow").toggle();
            });


            $(".documentToggleVisibility").click(function(){
                $(".documentContentHideShow").toggle();
            });

            $(".leaveToggleVisibility").click(function(){
                $(".leaveContentHideShow").toggle();
            });

            $(".salaryToggleVisibility").click(function(){
                $(".salaryContentHideShow").toggle();
            });
    });






</script>
@endsection

