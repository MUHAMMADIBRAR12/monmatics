<!-- Asset Detail Start -->
<div class="px-1" id="assetfrag">
    <div class="d-flex justify-content-between">
        <div>
            {{--            <h5>Personal info</h5>--}}
        </div>
        <div class="">
            <div class="p-1 px-2 rounded pe-auto" style="background-color: #0C7CE6; color:white; cursor: pointer;" title="Add Asset" data-toggle="modal" data-target="#addAsset">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                    <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                </svg>
            </div>
        </div>

    </div>

    <div>
        <div class="row col-12 d-flex gap-2">
            @foreach($assets as $asset)
                <div class="col-2  p-3 rounded" style="background-color: #F5F5F5;">

                    <div class="d-flex justify-content-end gap-1">
                        <p class="mb-0 text-primary delete-btn" style="cursor:pointer;" title="edit Asset" data-toggle="modal" data-target="#editAsset_{{ $asset->id  }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                            </svg>
                        </p>
                        <div>
                            <form action="{{ route('asset-detail.delete' , $asset->id) }}" method="POST" id="deleteForm_{{ $asset->id }}">
                                @csrf
                                @method('DELETE')
                                <button class="text-danger border-0 delete-btn" type="button" style="cursor:pointer;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-x-square" viewBox="0 0 16 16">
                                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    <h5 class="font-bold">{{ $asset->name }}</h5>
                </div>

                <div class="modal fade" id="editAsset_{{ $asset->id  }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Edit Asset</h5>
                                <p style="cursor:pointer;" class="close text-primary" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                        </svg>
                    </span>
                                </p>
                            </div>
                            <form action="{{ route('asset-detail.update' , $asset->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <input type="hidden" name="employee_id" value="{{ $employee->id }}" />
                                    <div class="row col-lg-12">
                                        <div class="mb-2 col-lg-12">
                                            <label for="asset_name" class="form-label mb-0">Asset Name</label>
                                            <input type="text" class="form-control" id="asset_name" value="{{ $asset->name }}" name="asset_name"/>
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
</div>
<!-- Asset Detail End -->


<div class="modal fade" id="addAsset" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Asset</h5>
                <p style="cursor:pointer;" class="close text-primary" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                        </svg>
                    </span>
                </p>
            </div>
            <form action="{{ route('asset-detail.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="employee_id" value="{{ $employee->id }}" />
                    <div class="row col-lg-12">
                        <div class="mb-2 col-lg-12">
                            <label for="asset_name" class="form-label mb-0">Asset Name</label>
                            <input type="text" class="form-control" id="asset_name" name="asset_name"/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>





<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                const confirmation = confirm('Are you sure you want to delete this asset?');
                if (confirmation) {
                    const formId = this.closest('form').getAttribute('id');
                    document.getElementById(formId).submit();
                }
            });
        });
    });
</script>
