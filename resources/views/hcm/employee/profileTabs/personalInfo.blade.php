<style>
    .customGrid {
        width: 40%;
    }
    @media only screen and (max-width: 1024px) {
        .customGrid {
            width: 48%; /* Adjust width for screens less than 1024px */
        }
    }

    @media only screen and (max-width: 600px) {
        .customGrid {
            width: 100%; /* Adjust width for screens less than 600px */
        }
    }
</style>




@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<!-- Personal Information Start -->
<div class="px-1">
    <div class="d-flex justify-content-between">
        <div>
{{--            <h5>Personal info</h5>--}}
        </div>
        <div class="">
            <div class="p-1 px-2 rounded pe-auto" style="background-color: #0C7CE6; color:white; cursor: pointer;" title="Edit Personal Info" data-toggle="modal" data-target="#exampleModal">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                </svg>
            </div>
        </div>

    </div>
    <div class="p-3 mt-2 rounded" style="background-color: #F5F5F5;">
        <div class="row col-12">
            <div class="col-3 gap-2 d-flex">
                <p class="font-bold">First Name:</p>
                <p class="text-primary">{{ $employee->f_name }}</p>
            </div>
            <div class="col-3 gap-2 d-flex">
                <p class="font-bold">Last Name:</p>
                <p class="text-primary">{{ $employee->l_name }}</p>
            </div>
            <div class="col-3 gap-2 d-flex">
                <p class="font-bold">National ID:</p>
                <p class="text-primary">{{ $employee->national_id }}</p>
            </div>
            <div class="col-3 gap-2 d-flex">
                <p class="font-bold">Phone:</p>
                <p class="text-primary">{{ $employee->phone }}</p>
            </div>
            <div class="col-3 gap-2 d-flex">
                <p class="font-bold">Email:</p>
                <p class="text-primary">{{ $employee->email }}</p>
            </div>
            <div class="col-3 gap-2 d-flex">
                <p class="font-bold">DOB:</p>
                <p class="text-primary">{{ $employee->dob }}</p>
            </div>
            <div class="col-3 gap-2 d-flex">
                <p class="font-bold">Gender:</p>
                <p class="text-primary">{{ $employee->gender }}</p>
            </div>
            <div class="col-3 gap-2 d-flex">
                <p class="font-bold">Marital Status:</p>
                <p class="text-primary">{{ $employee->martial_status }}</p>
            </div>
            <div class="col-12 gap-2 d-flex">
                <p class="font-bold">Current Address:</p>
                <p class="text-primary">{{ $employee->current_address }} , {{ $employee->current_city }} , {{ $employee->current_state }} , {{ $employee->current_country }}</p>
            </div>
            <div class="col-12 gap-2 d-flex">
                <p class="font-bold">Permanent Address:</p>
                <p class="text-primary">{{ $employee->permanent_address }} , {{ $employee->permanent_city }} , {{ $employee->permanent_state }} , {{ $employee->permanent_country }}</p>

            </div>
        </div>
    </div>
</div>
<!-- Personal Information End -->


<!-- Skill Info Start -->
<div class="px-1 mt-5">
    <div class="d-flex justify-content-between align-items-center rounded p-1 text-white toggleVisibility" style="background-color: #0c7ce6">
        <div>
            <h5 class="mb-0">Skills</h5>
        </div>
        <div class="">
            <div class="p-1 px-2 rounded pe-auto bg-white text-primary" style="cursor: pointer;" title="Add New Skill" data-toggle="modal" data-target="#skillAddModel">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                    <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                </svg>
            </div>
        </div>

    </div>

    <div class="contentHideShow">
        <div class="p-3 d-flex gap-2  mb-2 ">
            @foreach($skillsByType as $type => $skills)
                @if($skills->isNotEmpty())
                    <div class="col-lg-3 col-md-5 p-3 rounded " style="background-color: #F5F5F5; ">
                        <div class="d-flex justify-content-between mb-2 p-2 rounded text-white" style="background-color: #0c7ce6;">
                            <h5 class="font-bold mb-0" >{{ $type }}</h5>
                            {{--                        <div class="d-flex gap-1 mb-0">--}}
                            {{--                            <div>--}}
                            {{--                                <p class="mb-0" style="cursor:pointer;" title="edit skills" data-toggle="modal" data-target="#editGroupSkill">--}}
                            {{--                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">--}}
                            {{--                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>--}}
                            {{--                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>--}}
                            {{--                                    </svg>--}}
                            {{--                                </p>--}}
                            {{--                            </div>--}}
                            {{--                        </div>--}}
                        </div>
                        <div class="">
                            @foreach($skills as $skill)
                                <div class="w-100 mb-1">
                                    <div class="d-flex justify-content-between">
                                        <p class="font-bold mb-0">{{ $skill->skill_name }}</p>
                                        <div class="d-flex gap-1">
                                            <p class="mb-0" style="cursor:pointer;" title="edit skills" data-toggle="modal" data-target="#editGroupSkill_{{ $skill->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                                </svg>
                                            </p>
                                            <div>
                                                <form action="{{ route('skill-detail.delete' , $skill->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button class="text-danger border-0" type="submit" style="cursor:pointer;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-x-square" viewBox="0 0 16 16">
                                                            <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                                                        </svg>
                                                    </button>

                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <progress id="file" value="{{ $skill->skill_level }}" max="100" class="w-100"> {{ $skill->skill_level }}% </progress>
                                </div>








                                <!-- Group Skill Edit Modal -->
                                <div class="modal fade" id="editGroupSkill_{{ $skill->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-md" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Skill Edit</h5>
                                                <p style="cursor:pointer;" class="close text-primary" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                        </svg>
                    </span>
                                                </p>
                                            </div>
                                            <form action="{{ route('skill-detail.update' , $skill->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="row col-lg-12">
                                                        <div class="mb-2 col-lg-6">
                                                            <label for="skill_name" class="form-label mb-0">Skill Name</label>
                                                            <input type="text" class="form-control" id="skill_name" name="skill_name" value="{{ $skill->skill_name }}"/>
                                                        </div>
                                                        <div class="mb-2 col-lg-6">
                                                            <label for="skill_level" class="form-label mb-0">Expertise</label>
                                                            <input type="number" class="form-control" value="{{ $skill->skill_level }}" id="skill_level"
                                                                   name="skill_level"/>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    </div>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>


                            @endforeach

                        </div>

                    </div>

                @endif
            @endforeach

        </div>

    </div>
</div>
<!-- Skill Info End -->


<!-- Educational Info Start -->
<div class="px-1 mt-5">
    <div class="d-flex justify-content-between align-items-center rounded p-1 text-white educationToggleVisibility" style="background-color: #0c7ce6">
        <div>
            <h5 class="mb-0">Educational Info</h5>
        </div>
        <div class="">
            <div class="p-1 px-2 rounded pe-auto bg-white text-primary" style="cursor: pointer;" title="Add New Education"  data-toggle="modal" data-target="#educationAddModel">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                    <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                </svg>
            </div>
        </div>

    </div>

        @foreach($educationDetail as $education)
        <div class="p-3 rounded mb-2 mt-3 educationContentHideShow" style="background-color: #F5F5F5; ">

            <div class="row col-12">
                <div class="col-lg-10">
                    <h5 class="font-bold">{{ $education->university_name }}
                        <span class="text-muted">({{ $education->degree }})</span>
                        <span class="text-primary">{{ \Carbon\Carbon::parse($education->start_date)->format('F, Y') }} - {{ \Carbon\Carbon::parse($education->end_date)->format('F, Y') }}</span>
                        <span class="">CGPA: <span class="text-primary">{{ $education->cgpa }}</span></span>
                    </h5>

                    <div>
                        <a href="{{ route('education-detail.download' , $education->id) }}" class="btn-sm btn-primary">Download file</a>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="d-flex justify-content-end gap-2">
                        <div>
                            <p class="text-primary" style="cursor:pointer;"
                               title="Edit Education"  data-toggle="modal" data-target="#educationEditModel_{{ $education->id }}"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                     class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                </svg>
                            </p>
                        </div>
                        <div>
                            <form action="{{ route('education-detail.delete' , $education->id) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button class="text-danger border-0" type="submit" style="cursor:pointer;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-square" viewBox="0 0 16 16">
                                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                                    </svg>
                                </button>

                            </form>
                        </div>
                    </div>

                </div>

            </div>

            <!-- Education Edit Modal -->
            <div class="modal fade" id="educationEditModel_{{ $education->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit Education Info</h5>
                            <p style="cursor:pointer;" class="close text-primary" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                        </svg>
                    </span>
                            </p>
                        </div>
                        <form action="{{ route('education-detail.update' , $education->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <input type="hidden" value="{{ $employee->id }}" name="employee_id"/>
                                <div class="row col-lg-12">
                                    <div class="mb-2 col-lg-4">
                                        <label for="uniName" class="form-label mb-0">University Name</label>
                                        <input type="text" class="form-control" value="{{ $education->university_name }}" id="uniName" name="uni_name"/>
                                    </div>
                                    <div class="mb-2 col-lg-2">
                                        <label for="degree" class="form-label mb-0">Degree</label>
                                        <input type="text" class="form-control" value="{{ $education->degree }}" id="degree" name="degree_name"/>
                                    </div>
                                    <div class="mb-2 col-lg-3">
                                        <label for="from_date" class="form-label mb-0">From</label>
                                        <input type="date" class="form-control" value="{{ $education->start_date }}" id="from_date" name="from_date"/>
                                    </div>
                                    <div class="mb-2 col-lg-3">
                                        <label for="to_date" class="form-label mb-0">To</label>
                                        <input type="date" class="form-control" value="{{ $education->end_date }}" id="to_date" name="to_date"/>
                                    </div>
                                    <div class="mb-2 col-lg-3">
                                        <label for="cgpa" class="form-label mb-0">CGPA</label>
                                        <input type="text" class="form-control" value="{{ $education->cgpa }}" id="cgpa" name="cgpa"/>
                                    </div>

                                    <div class="mb-2 col-lg-6">
                                        <label for="degree_doc" class="form-label mb-0">Upload Degeee</label>
                                        <input type="file" class="form-control  dropify"  id="degree_doc" name="degree_doc"/>
                                    </div>

                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>
        @endforeach





</div>
<!-- Educational Info End -->



<!-- Employment History  Start -->
<div class="px-1 mt-5">
    <div class="d-flex justify-content-between align-items-center rounded p-1 text-white EmployementToggleVisibility" style="background-color: #0c7ce6">
        <div>
            <h5 class="mb-0">Employment History</h5>
        </div>
        <div class="">
            <div class="p-1 px-2 rounded pe-auto bg-white text-primary" style="cursor: pointer;" title="Add New Experience" data-toggle="modal" data-target="#experiencenAddModel">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                    <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                </svg>
            </div>
        </div>

    </div>

    <div class="p-3 row col-12 gap-1 mb-2 employementContentHideShow">
        @foreach($empHistory as $history)
            <div class="col-lg-5 col-md-8 p-3 rounded customGrid" style="background-color: #F5F5F5; ">
                <div class="row col-12">
                    <div class="col-10">
                        <h5 class="font-bold mb-1">{{ $history->company_name }}
                            <span class="text-muted font-15">( {{ date('M, Y', strtotime($history->joining_date)) }} - {{ date('M, Y', strtotime($history->leaving_date)) }})</span>
                        </h5>
                    </div>

                    <div class="col-2 d-flex gap-2">
                        <p class="text-primary" style="cursor:pointer;" title="Edit Experience" data-toggle="modal" data-target="#experienceEditModel_{{ $history->id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                            </svg>
                        </p>
                        <div>
                            <form action="{{ route('employee.delete-employement-history' , $history->id) }}"
                                  method="POST"
                                  class="delete-form"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-danger border-0 delete-Employment-history-form">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-square" viewBox="0 0 16 16">
                                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                </div>

                <div>
                    <span class="text-muted">Joining Designation: <span class="text-primary">{{ $history->joining_designation }}</span></span>
                </div>
                <div>
                    <span class="text-muted">Leave Designation: <span class="text-primary">{{ $history->leaving_designation }}</span></span>
                </div>
                <div class="mt-2">

                        <a href="{{ route('employee.download-experience-letter' , $history->id) }}" target="_blank" class="btn btn-primary">Download Doc</a>

                </div>
            </div>






            <!-- Experience Edit Modal -->
            <div class="modal fade" id="experienceEditModel_{{ $history->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Experience Info</h5>
                            <p style="cursor:pointer;" class="close text-primary" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                        </svg>
                    </span>
                            </p>
                        </div>
                        <form action="{{ route('employee.update-employement-history' , $history->id) }}" enctype="multipart/form-data" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="row col-lg-12">
                                    <input type="hidden" value="{{ $employee->id }}" name="employee_id">
                                    <div class="mb-2 col-lg-6">
                                        <label for="company_name" class="form-label mb-0">Company Name</label>
                                        <input type="text" class="form-control" id="company_name"
                                               value="{{ $history->company_name }}"
                                               name="company_name"/>
                                        @error('company_name')
                                        <p class="text-danger">
                                            {{ $message }}
                                        </p>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-lg-3">
                                        <label for="joining_date" class="form-label mb-0">Joining Date</label>
                                        <input type="date" class="form-control" id="joining_date"
                                               value="{{ $history->joining_date }}"
                                               name="joining_date"/>
                                    </div>
                                    <div class="mb-2 col-lg-3">
                                        <label for="joining_designation" class="form-label mb-0">Joining Desigation</label>
                                        <input type="text" class="form-control"  id="joining_designation"
                                               value="{{ $history->joining_designation }}"
                                               name="joining_designation"/>
                                    </div>
                                    <div class="mb-2 col-lg-3">
                                        <label for="leave_date" class="form-label mb-0">Leave Date</label>
                                        <input type="date" class="form-control"
                                               value="{{ $history->leaving_date }}"
                                               id="leave_date" name="leave_date"/>
                                    </div>

                                    <div class="mb-2 col-lg-3">
                                        <label for="leave_designation" class="form-label mb-0">Leave Designation</label>
                                        <input type="text" class="form-control"  id="leave_designation"
                                               value="{{ $history->leaving_designation }}"
                                               name="leave_designation"/>
                                    </div>
                                    <div class="mb-2 col-lg-6">
                                        <label for="experience_letter" class="form-label mb-0">Experience Letter</label>
                                        <input type="file" class="form-control  dropify"  id="experience_letter" name="experience_letter"/>
                                    </div>

                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update Experience</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>




        @endforeach


    </div>
</div>
<!-- Employment History  End -->


<!-- Emergency Contact Start -->
<div class="px-1 mt-5">
    <div class="d-flex justify-content-between align-items-center rounded p-1 text-white emergencyToggleVisibility" style="background-color: #0c7ce6">
        <div>
            <h5 class="mb-0">Emergency Contact</h5>
        </div>
        <div class="">
            <div class="p-1 px-2 rounded pe-auto bg-white text-primary" style="cursor: pointer;" title="Add New Education" data-toggle="modal" data-target="#emergencyContactAddModel">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                    <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                </svg>
            </div>
        </div>

    </div>

    <div class="p-3 row col-12 gap-1 mb-2 emergencyContentHideShow">
        @foreach($emergencyContact as $emergency)
            <div class="col-lg-4 col-md-8 p-3 rounded customGrid" style="background-color: #F5F5F5; ">
                <div class="row col-12">
                    <div class="col-10">
                        <h5 class="font-bold mb-1">{{ $emergency->name }}
                            @if($emergency->relationship)
                                <span class="text-muted">(<span class="text-primary">{{ $emergency->relationship }}</span>)</span>
                            @endif
                        </h5>
                    </div>

                    <div class="col-2 d-flex gap-2">
                        <p class="text-primary" style="cursor:pointer;" title="Edit Emergency Contact" data-toggle="modal" data-target="#emergencyContactEditModel_{{ $emergency->id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                            </svg>
                        </p>
                        <div>
                            <form action="{{ route('emergency-contact.delete' , $emergency->id) }}"
                                  method="POST"
                                  class="delete-form"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-danger border-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-square" viewBox="0 0 16 16">
                                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="d-flex gap-3">
                    <span class="text-muted">Phone: <span class="text-primary">{{ $emergency->number }}</span></span>
                </div>
            </div>


            <div class="modal fade" id="emergencyContactEditModel_{{ $emergency->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Emergency Contact</h5>
                            <p style="cursor:pointer;" class="close text-primary" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                        </svg>
                    </span>
                            </p>
                        </div>
                        <form action="{{ route('emergency-contact.update' , $emergency->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" value="{{ $employee->id }}" name="employee_id"/>
                            <div class="modal-body">
                                <div class="row col-lg-12">
                                    <div class="mb-2 col-lg-4">
                                        <label for="emergency_name" class="form-label mb-0">Person Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="emergency_name" value="{{ $emergency->name }}" name="emergency_name"/>
                                    </div>
                                    <div class="mb-2 col-lg-4">
                                        <label for="emergency_relationship" class="form-label mb-0">Relationship</label>
                                        <input type="text" class="form-control" id="emergency_relationship" value="{{ $emergency->relationship }}" name="emergency_relationship"/>
                                    </div>
                                    <div class="mb-2 col-lg-4">
                                        <label for="emergency_phone" class="form-label mb-0">Phone <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="{{ $emergency->number }}" id="emergency_phone" name="emergency_phone"/>
                                    </div>

                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        @endforeach
    </div>
</div>
<!-- Emergency Contact End -->


<!-- Bank Details Start -->
<div class="px-1 mt-5">
    <div class="d-flex justify-content-between align-items-center rounded p-1 text-white bankToggleVisibility" style="background-color: #0c7ce6">
        <div>
            <h5 class="mb-0">Bank Details</h5>
        </div>
        <div class="">
            <div class="p-1 px-2 rounded pe-auto bg-white text-primary" style="cursor: pointer;" title="Add New Bank" data-toggle="modal" data-target="#bankDetailAddModel">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                    <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                </svg>
            </div>
        </div>

    </div>

    <div class="p-3 row col-12 gap-1 mb-2 bankContentHideShow">
        @foreach($bankDetail as $bank)
            <div class="col-lg-5 col-md-8 p-3 rounded " style="background-color: #F5F5F5; ">
                <div class="row col-12">
                    <div class="col-10">
                        <h5 class="font-bold mb-1">{{ $bank->bank_name }}
                            <span class="text-muted">ACC Title: <span class="text-primary">{{ $bank->account_title }}</span></span>
                        </h5>
                    </div>

                    <div class="col-2 d-flex gap-2">
                        <p class="text-primary" style="cursor:pointer;" title="Edit Bank Detail" data-toggle="modal" data-target="#bankDetailEditModel_{{ $bank->id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                            </svg>
                        </p>
                        <div>
                            <form action="{{ route('bank-detail.delete' , $bank->id) }}"
                                  method="POST"
                                  class="delete-form"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-danger border-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-square" viewBox="0 0 16 16">
                                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-3">
                    <span class="text-muted">ACC No: <span class="text-primary">{{ $bank->account_no }}</span></span>
                    <span class="text-muted">IBAN No: <span class="text-primary">{{ $bank->iban_no }}</span></span>
                </div>
            </div>


            <!-- Bank Detail edit Modal -->
            <div class="modal fade" id="bankDetailEditModel_{{ $bank->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add New Bank</h5>
                            <p style="cursor:pointer;" class="close text-primary" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                        </svg>
                    </span>
                            </p>
                        </div>
                        <form action="{{ route('bank-detail.update' , $bank->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" value="{{ $employee->id }}" name="employee_id"/>
                            <div class="modal-body">
                                <div class="row col-lg-12">
                                    <div class="mb-2 col-lg-6">
                                        <label for="bank_name" class="form-label mb-0">Bank Name</label>
                                        <input type="text" class="form-control" value="{{ $bank->bank_name }}" id="bank_name" name="bank_name"/>
                                    </div>
                                    <div class="mb-2 col-lg-6">
                                        <label for="acc_title" class="form-label mb-0">Acc Title</label>
                                        <input type="text" class="form-control" value="{{ $bank->account_title }}" id="acc_title" name="acc_title"/>
                                    </div>
                                    <div class="mb-2 col-lg-6">
                                        <label for="acc_no" class="form-label mb-0">Acc No</label>
                                        <input type="text" class="form-control" value="{{ $bank->account_no }}"  id="acc_no" name="acc_no"/>
                                    </div>
                                    <div class="mb-2 col-lg-6">
                                        <label for="iban_no" class="form-label mb-0">Iban No</label>
                                        <input type="text" class="form-control" value="{{ $bank->iban_no }}"  id="iban_no" name="iban_no"/>
                                    </div>

                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>



        @endforeach


    </div>
</div>
<!-- Bank Details End -->



<!-- Social Media Start -->
<div class="px-1 mt-5">
    <div class="d-flex justify-content-between align-items-center rounded p-1 text-white socialMediaToggleVisibility" style="background-color: #0c7ce6">
        <div>
            <h5 class="mb-0">Social Media</h5>
        </div>
        <div class="">
            <div class="p-1 px-2 rounded pe-auto bg-white text-primary" style="cursor: pointer;" title="Add Social Media" data-toggle="modal" data-target="#scoialMediaAddModel">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                    <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                </svg>
            </div>
        </div>

    </div>

    <div class="p-3 row col-12 gap-1 mb-2 socialMediaContentHideShow">
        <div class="col-lg-12 p-3 rounded " style="background-color: #F5F5F5; ">
           <div class="d-flex gap-3">
               @foreach($socialMediaDetail as $social)
                   <a href="{{ $social->url }}" target="_blank" >
                       <p class="text-primary text-decoration-underline">{{ $social->platform_name }}</p>
                   </a>
               @endforeach
           </div>
        </div>
    </div>
</div>
<!-- Social Media End -->


<!-- Documents  Start -->
<div class="px-1 mt-5">
    <div class="d-flex justify-content-between align-items-center rounded p-1 text-white documentToggleVisibility" style="background-color: #0c7ce6">
        <div>
            <h5 class="mb-0">Documents</h5>
        </div>
        <div class="">
            <div class="p-1 px-2 rounded pe-auto bg-white text-primary" style="cursor: pointer;" title="Add New Document" data-toggle="modal" data-target="#documentAddModel">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                    <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                </svg>
            </div>
        </div>

    </div>

    <div class="p-3 row col-12 gap-1 mb-2 documentContentHideShow">
        @foreach($documents as $document)
            <div class="col-lg-4 col-md-8 p-3 rounded customGrid" style="background-color: #F5F5F5; ">
                <div class="row col-12">
                    <div class="col-10">
                        <h5 class="font-bold mb-1">{{ $document->document_type }}</h5>

                        <h6>{{ $document->title }} <span class="text-muted">(<a href="{{ route('emp-documents.download' , $document->id) }}">download</a>)</span></h6>
                    </div>

                    <div class="col-lg-2">
                            <div class="d-flex justify-content-end gap-2">
                                <div>
                                    <p class="text-primary" style="cursor:pointer;"
                                       title="Edit document"  data-toggle="modal" data-target="#documentEditModel_{{ $document->id }}"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                             class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                        </svg>
                                    </p>
                                </div>
                                <div>
                                    <form action="{{ route('emp-documents.delete' , $document->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button class="text-danger border-0" type="submit" style="cursor:pointer;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-square" viewBox="0 0 16 16">
                                                <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                                            </svg>
                                        </button>

                                    </form>
                                </div>
                            </div>

                        </div>

                </div>
            </div>



            <div class="modal fade" id="documentEditModel_{{ $document->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit Documents</h5>
                            <p style="cursor:pointer;" class="close text-primary" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                        </svg>
                    </span>
                            </p>
                        </div>
                        <form action="{{ route('emp-documents.update', $document->id) }}" enctype="multipart/form-data" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <input type="hidden" value="{{ $employee->id }}" name="employee_id"/>
                                <div class="row col-lg-12">
                                    <div class="mb-2 col-lg-6">
                                        <label for="document_type" class="form-label mb-0">Document Type</label>
                                        <select name="document_type" id="document_type">
                                            <option value="">Select</option>
                                            @foreach($documentTypes as $documentType)
                                                <option value="{{ $documentType->document_type }}" {{ $document->document_type == $documentType->document_type ? 'selected' : '' }}>{{ $documentType->document_type }}</option>
                                            @endforeach
                                            <option value="Others" {{ $document->document_type == 'Others' ? 'selected' : '' }}>Others</option>
                                        </select>
                                    </div>
                                    <div class="mb-2 col-lg-6" id="document_type_name_field" style="{{ $document->document_type == 'Others' ? '' : 'display: none;' }}">
                                        <label for="document_type_name" class="form-label mb-0">Document Type Name</label>
                                        <input type="text" class="form-control" id="document_type_name" name="document_type_name" value="{{ $document->document_type }}"/>
                                    </div>
                                    <div class="mb-2 col-lg-6">
                                        <label for="document_title" class="form-label mb-0">Title</label>
                                        <input type="text" class="form-control" id="document_title" name="document_title" value="{{ $document->title }}"/>
                                    </div>
                                    <div class="mb-2 col-lg-6">
                                        <label for="document_file" class="form-label mb-0">Upload File</label>
                                        <input type="file" class="form-control" id="document_file" name="document_file"/>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update Document</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach


    </div>
</div>
<!-- Documents  End -->








<!-- Personal Info edit Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Personal Info</h5>
                <p style="cursor:pointer;" class="close text-primary" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                        </svg>
                    </span>
                </p>
            </div>
            <form action="{{ route('employee.personal-info' , $employee->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row col-lg-12">
                        <div class="mb-2 col-lg-3">
                            <label for="fname" class="form-label mb-0">First Name</label>
                            <input type="text" class="form-control" value="{{ $employee->f_name }}" id="fname" name="fname"/>
                        </div>
                        <div class="mb-2 col-lg-3">
                            <label for="lname" class="form-label mb-0">Last Name</label>
                            <input type="text" class="form-control" value="{{ $employee->l_name }}" id="lname" name="lname"/>
                        </div>
                        <div class="mb-2 col-lg-3">
                            <label for="nationalID" class="form-label mb-0">National ID:</label>
                            <input type="text" class="form-control" value="{{ $employee->national_id }}" id="nationalID" name="nationalID"/>
                        </div>
                        <div class="mb-2 col-lg-3">
                            <label for="phone" class="form-label mb-0">Phone</label>
                            <input type="text" class="form-control" value="{{ $employee->phone }}" id="phone" name="phone"/>
                        </div>
                        <div class="mb-2 col-lg-3">
                            <label for="email" class="form-label mb-0">Email</label>
                            <input type="email" class="form-control" value="{{ $employee->email }}" id="email" name="email"/>
                        </div>
                        <div class="mb-2 col-lg-3">
                            <label for="dob" class="form-label mb-0">DOB</label>
                            <input type="date" class="form-control" value="{{ $employee->dob }}" id="dob" name="dob"/>
                        </div>
                        <div class="mb-2 col-lg-3">
                            <label for="gender" class="form-label mb-0">Gender:</label>
                            <select class="form-control" name="gender" id="gender">
                                <option value="">Select</option>
                                <option value="male" {{ $employee->gender == 'male' ? 'selected' : ''}} >Male</option>
                                <option value="female" {{ $employee->gender == 'female' ? 'selected' : ''}} >Female</option>
                                <option value="perfer_not_to_say" {{ $employee->gender == 'perfer_not_to_say' ? 'selected' : ''}} >Prefer Not To Say</option>
                            </select>
                        </div>
                        <div class="mb-2 col-lg-3">
                            <label for="marital_status" class="form-label mb-0">Marital Status:</label>
                            <select class="form-select" id="marital_status" name="marital_status">
                                <option value="">   Select</option>
                                <option value="single" {{ $employee->martial_status == 'single' ? 'selected' : '' }}>Single</option>
                                <option value="married" {{ $employee->martial_status == 'married' ? 'selected' : '' }} >Married</option>
                                <option value="divorced" {{ $employee->martial_status == 'divorced' ? 'selected' : '' }} >Divorced</option>
                                <option value="widowed" {{ $employee->martial_status == 'widowed' ? 'selected' : '' }} >Widowed</option>
                            </select>
                        </div>
                        <h6 class="text-primary font-bold mb-0 mt-3">Current Address</h6>
                        <div class="mb-2 col-lg-6">
                            <label for="current_address" class="form-label mb-0">Adress:</label>
                            <input type="text" class="form-control" value="{{ $employee->current_address }}" id="current_address" name="current_address"/>
                        </div>
                        <div class="mb-2 col-lg-2">
                            <label for="current_city" class="form-label mb-0">City:</label>
                            <input type="text" class="form-control" value="{{ $employee->current_city }}" id="current_city" name="current_city"/>
                        </div>
                        <div class="mb-2 col-lg-2">
                            <label for="current_state" class="form-label mb-0">State:</label>
                            <input type="text" class="form-control" value="{{ $employee->current_state }}" id="current_state" name="current_state"/>
                        </div>
                        <div class="mb-2 col-lg-2">
                            <label for="current_country" class="form-label mb-0">Country:</label>
                            <input type="text" class="form-control" value="{{ $employee->current_country }}" id="current_country" name="current_country"/>
                        </div>

                        <h6 class="text-primary font-bold mb-0 mt-3">Permanent Address</h6>
                        <div class="mb-2 col-lg-6">
                            <label for="permanent_address" class="form-label mb-0">Address:</label>
                            <input type="text" class="form-control" value="{{ $employee->permanent_address }}" id="permanent_address" name="permanent_address"/>
                        </div>
                        <div class="mb-2 col-lg-2">
                            <label for="permanent_city" class="form-label mb-0">City:</label>
                            <input type="text" class="form-control" value="{{ $employee->permanent_city }}" id="permanent_city" name="permanent_city"/>
                        </div>
                        <div class="mb-2 col-lg-2">
                            <label for="permanent_state" class="form-label mb-0">State:</label>
                            <input type="text" class="form-control" value="{{ $employee->permanent_state }}" id="permanent_state" name="permanent_state"/>
                        </div>
                        <div class="mb-2 col-lg-2">
                            <label for="permanent_country" class="form-label mb-0">Country:</label>
                            <input type="text" class="form-control" value="{{ $employee->permanent_country }}" id="permanent_country" name="permanent_country"/>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Skill add Modal -->
<div class="modal fade" id="skillAddModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Skill Info</h5>
                <p style="cursor:pointer;" class="close text-primary" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                        </svg>
                    </span>
                </p>
            </div>
            <form action="{{ route('skill-detail.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" value="{{ $employee->id }}" name="employee_id"/>
                    <div class="row col-lg-12">
                        <div class="mb-2 col-lg-6">
                            <label for="skill_type" class="form-label mb-0">Skill Type</label>
                            <select name="skill_type" id="skill_type" class="form-control">
                                <option value="">Select</option>
                                @foreach($skillType as $skill)
                                    <option value="{{ $skill }}">{{ $skill }}</option>
                                @endforeach
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="mb-2 col-lg-6" id="customSkillType" style="display: none">
                            <label for="skill_type_name" class="form-label mb-0">Skill Type</label>
                            <input type="text" class="form-control" id="skill_type_name" name="skill_type_name"/>
                        </div>
                        <div class="mb-2 col-lg-6">
                            <label for="skill_name" class="form-label mb-0">Skill Name</label>
                            <input type="text" class="form-control" id="skill_name" name="skill_name"/>
                        </div>
                        <div class="mb-2 col-lg-6">
                            <label for="skill_level" class="form-label mb-0">Skill Level (1-100 %)</label>
                            <input type="number" class="form-control" min="1" max="100" id="skill_level" name="skill_level"/>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Education add Modal -->
<div class="modal fade" id="educationAddModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Education Info</h5>
                <p style="cursor:pointer;" class="close text-primary" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                        </svg>
                    </span>
                </p>
            </div>
            <form action="{{ route('education-detail.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" value="{{ $employee->id }}" name="employee_id"/>
                    <div class="row col-lg-12">
                        <div class="mb-2 col-lg-4">
                            <label for="uniName" class="form-label mb-0">University Name</label>
                            <input type="text" class="form-control" id="uniName" name="uni_name"/>
                        </div>
                        <div class="mb-2 col-lg-2">
                            <label for="degree" class="form-label mb-0">Degree</label>
                            <input type="text" class="form-control" id="degree" name="degree_name"/>
                        </div>
                        <div class="mb-2 col-lg-3">
                            <label for="from_date" class="form-label mb-0">From</label>
                            <input type="date" class="form-control"  id="from_date" name="from_date"/>
                        </div>
                        <div class="mb-2 col-lg-3">
                            <label for="to_date" class="form-label mb-0">To</label>
                            <input type="date" class="form-control"  id="to_date" name="to_date"/>
                        </div>
                        <div class="mb-2 col-lg-3">
                            <label for="cgpa" class="form-label mb-0">CGPA</label>
                            <input type="text" class="form-control"  id="cgpa" name="cgpa"/>
                        </div>

                        <div class="mb-2 col-lg-6">
                            <label for="degree_doc" class="form-label mb-0">Upload Degree</label>
                            <input type="file" class="form-control  dropify"  id="degree_doc" name="degree_doc"/>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Education</button>
                </div>
            </form>
        </div>
    </div>
</div>




<!-- Experience add Modal -->
<div class="modal fade" id="experiencenAddModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Experience Info</h5>
                <p style="cursor:pointer;" class="close text-primary" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                        </svg>
                    </span>
                </p>
            </div>
            <form action="{{ route('employee.employement-history') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row col-lg-12">
                        <input type="hidden" name="employee_id" value="{{ $employee->id }}"/>
                        <div class="mb-2 col-lg-6">
                            <label for="company_name" class="form-label mb-0">Company Name</label>
                            <input type="text" class="form-control" id="company_name" name="company_name"/>
                            @error('company_name')
                            <p class="text-danger">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                        <div class="mb-2 col-lg-3">
                            <label for="joining_date" class="form-label mb-0">Joining Date</label>
                            <input type="date" class="form-control" id="joining_date" name="joining_date"/>
                        </div>
                        <div class="mb-2 col-lg-3">
                            <label for="joining_designation" class="form-label mb-0">Joining Desigation</label>
                            <input type="text" class="form-control"  id="joining_designation" name="joining_designation"/>
                        </div>
                        <div class="mb-2 col-lg-3">
                            <label for="leave_date" class="form-label mb-0">Leave Date</label>
                            <input type="date" class="form-control"  id="leave_date" name="leave_date"/>
                        </div>

                        <div class="mb-2 col-lg-3">
                            <label for="leave_designation" class="form-label mb-0">Leave Designation</label>
                            <input type="text" class="form-control"  id="leave_designation" name="leave_designation"/>
                        </div>

                        <div class="mb-2 col-lg-6">
                            <label for="experience_letter" class="form-label mb-0">Experience Letter</label>
                            <input type="file" class="form-control  dropify"  id="experience_letter" name="experience_letter"/>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Experience</button>
                </div>
            </form>
        </div>
    </div>
</div>








<!-- Emergency Contact add Modal -->
<div class="modal fade" id="emergencyContactAddModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Emergency Contact</h5>
                <p style="cursor:pointer;" class="close text-primary" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                        </svg>
                    </span>
                </p>
            </div>
            <form action="{{ route('emergency-contact.create') }}" method="POST">
                @csrf
                <input type="hidden" value="{{ $employee->id }}" name="employee_id"/>
                <div class="modal-body">
                    <div class="row col-lg-12">
                        <div class="mb-2 col-lg-4">
                            <label for="emergency_name" class="form-label mb-0">Person Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="emergency_name" name="emergency_name"/>
                        </div>
                        <div class="mb-2 col-lg-4">
                            <label for="emergency_relationship" class="form-label mb-0">Relationship</label>
                            <input type="text" class="form-control" id="emergency_relationship" name="emergency_relationship"/>
                        </div>
                        <div class="mb-2 col-lg-4">
                            <label for="emergency_phone" class="form-label mb-0">Phone <span class="text-danger">*</span></label>
                            <input type="text" class="form-control"  id="emergency_phone" name="emergency_phone"/>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>




<!-- Bank Detail add Modal -->
<div class="modal fade" id="bankDetailAddModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Bank</h5>
                <p style="cursor:pointer;" class="close text-primary" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                        </svg>
                    </span>
                </p>
            </div>
            <form action="{{ route('bank-detail.store') }}" method="POST">
                @csrf
                <input type="hidden" value="{{ $employee->id }}" name="employee_id"/>
                <div class="modal-body">
                    <div class="row col-lg-12">
                        <div class="mb-2 col-lg-6">
                            <label for="bank_name" class="form-label mb-0">Bank Name</label>
                            <input type="text" class="form-control" id="bank_name" name="bank_name"/>
                        </div>
                        <div class="mb-2 col-lg-6">
                            <label for="acc_title" class="form-label mb-0">Acc Title</label>
                            <input type="text" class="form-control" id="acc_title" name="acc_title"/>
                        </div>
                        <div class="mb-2 col-lg-6">
                            <label for="acc_no" class="form-label mb-0">Acc No</label>
                            <input type="text" class="form-control"  id="acc_no" name="acc_no"/>
                        </div>
                        <div class="mb-2 col-lg-6">
                            <label for="iban_no" class="form-label mb-0">Iban No</label>
                            <input type="text" class="form-control"  id="iban_no" name="iban_no"/>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add </button>
                </div>
            </form>
        </div>
    </div>
</div>




<!-- Social media add Modal -->
<div class="modal fade" id="scoialMediaAddModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Social Media Links</h5>
                <p style="cursor:pointer;" class="close text-primary" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                        </svg>
                    </span>
                </p>
            </div>
            <form action="{{ route('social-media-detail.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" class="form-control" name="employee_id" value="{{ $employee->id }}">
                    <div class="row col-lg-12">
                        <div class="mb-2 col-lg-6">
                            <label for="social_platform" class="form-label mb-0">Platform</label>
                            <select name="social_platform" id="social_platform" class="form-control">
                                <option value="">Select</option>
                                @foreach($socialMediaPlatform as $plateform)
                                    <option value="{{ $plateform->platform_name }}">{{ $plateform->platform_name }}</option>
                                @endforeach
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="mb-2 col-lg-6" id="platformNameContainer" style="display: none">
                            <label for="socialPlatform"  class="form-label mb-0">Platform Name</label>
                            <input type="text" class="form-control" id="socialPlatform" name="social_platform_name"/>
                        </div>
                        <div class="mb-2 col-lg-6">
                            <label for="profile_url" class="form-label mb-0">Url</label>
                            <input type="text" class="form-control" id="profile_url" name="profile_url"/>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Social Media</button>
                </div>
            </form>
        </div>
    </div>
</div>






<!-- Documents add Modal -->
<div class="modal fade" id="documentAddModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Documents</h5>
                <p style="cursor:pointer;" class="close text-primary" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                        </svg>
                    </span>
                </p>
            </div>
            <form action="{{ route('emp-documents.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" value="{{ $employee->id }}" name="employee_id"/>
                    <div class="row col-lg-12">
                        <div class="mb-2 col-lg-6">
                            <label for="document_type" class="form-label mb-0">Document Type</label>
                            <select name="document_type" id="document_type_add">
                                <option value="">Select</option>
                                @foreach($documentTypes as $document)
                                    <option value="{{ $document->document_type }}">{{ $document->document_type }}</option>
                                @endforeach
                                <option value="Others">Others</option>
                            </select>
                        </div>
                        <div class="mb-2 col-lg-6" id="document_type_name_field_add" style="display: none;">
                            <label for="document_type_name" class="form-label mb-0">Document Type Name</label>
                            <input type="text" class="form-control" id="document_type_name" name="document_type_name"/>
                        </div>
                        <div class="mb-2 col-lg-6">
                            <label for="document_title" class="form-label mb-0">Title</label>
                            <input type="text" class="form-control" id="document_title" name="document_title"/>
                        </div>
                        <div class="mb-2 col-lg-6">
                            <label for="document_file" class="form-label mb-0">Upload File</label>
                            <input type="file" class="form-control" id="document_file" name="document_file"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Document</button>
                </div>
            </form>


        </div>
    </div>
</div>











<script>
    document.querySelectorAll('.delete-Employment-history-form').forEach(form => {
        form.addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent form submission
            const confirmation = confirm('Are you sure you want to delete this Employment History?'); // Display confirmation dialog
            if (confirmation) {
                this.submit(); // If confirmed, submit the form
            }
        });
    });



    document.getElementById("social_platform").addEventListener("change", function() {
        let platform = this.value;
        let platformNameContainer = document.getElementById("platformNameContainer");
        if (platform === "Other") {
            platformNameContainer.style.display = "block";
        } else {
            platformNameContainer.style.display = "none";
        }
    });
    document.getElementById("skill_type").addEventListener("change", function() {
        let skillType = this.value;
        let skillTypeName = document.getElementById("customSkillType");
        if (skillType === "Other") {
            skillTypeName.style.display = "block";
        } else {
            skillTypeName.style.display = "none";
        }
    });

        document.getElementById('document_type_add').addEventListener('change', function () {
            let documentTypeNameField = document.getElementById('document_type_name_field_add');
            if (this.value === 'Others') {
                documentTypeNameField.style.display = 'block';
            } else {
                documentTypeNameField.style.display = 'none';
            }
        });

    document.getElementById('document_type').addEventListener('change', function () {
        let documentTypeNameField = document.getElementById('document_type_name_field');
        if (this.value === 'Others') {
            documentTypeNameField.style.display = 'block';
        } else {
            documentTypeNameField.style.display = 'none';
        }
    });
</script>
