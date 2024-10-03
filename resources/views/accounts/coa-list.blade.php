
@extends('layout.master')
@section('title', 'Accounts')
@section('parentPageTitle', 'Chart of Accounts')
@section('page-style')
<?php  use App\Libraries\appLib;
       use App\Libraries\dbLib; ?>

<link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.bootstrap.min.css') }}" />
<link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.standalone.min.css') }}" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    .table td {
        padding: 0.10rem;
    }

    ul,
    #myUL {
        list-style-type: none;
        color: blue;
    }

    .fut:hover {
        color: blue;
    }

    #myUL {
        margin-top: 20px;
        padding: 0;
    }

    .ten {
        background-color: #800080;
    }

    .caret {

        cursor: pointer;
        -webkit-user-select: none;
        /* Safari 3.1+ */
        -moz-user-select: none;
        /* Firefox 2+ */
        -ms-user-select: none;
        /* IE 10+ */
        user-select: none;
    }

    .caret::before {

        color: black;
        display: inline-block;
        margin-right: 6px;
    }

    .caret-down::before {
        -ms-transform: rotate(90deg);
        /* IE 9 */
        -webkit-transform: rotate(90deg);
        /* Safari */
        transform: rotate(90deg);
    }

    .nested {
        display: none;
    }

    .active {
        display: block;
    }

    .modal {
        z-index: 20;
    }

    .modal-backdrop {
        z-index: 10;
    }

    ​

    /* The popup form - hidden by default */
    .form-popup {
        display: none;
        position: fixed;
        margin-top: 2%;
        right: 10%;
        border: 3px solid #f1f1f1;
        z-index: 9;
        background-color: #0275d8;
    }

    /* Add styles to the form container */
    .form-container {
        max-width: 360px;
        padding: 10px;
        background-color: white;
    }

    /* Full-width input fields */
    .form-container input[type=text],
    .form-container input[type=password] {
        width: 100%;
        padding: 0px;
        margin: 5px 0 22px 0;
        border: none;
        background: #f1f1f1;
    }
</style>
<script>
    var searchCoaURL = "{{ url('coaSearch') }}";
        var token = "{{ csrf_token() }}";
</script>
@stop
@section('content')
@php
function displayTree($dataTree, $space = '')
{
foreach ($dataTree as $element) {
$space++;
if (is_array($element)) {
echo '<ul class="nested">';
    displayTree($element, $space);
    echo '</ul>';
echo '</li>';
} else {
if ($element->trans_group == 0) {
echo '<li>
    <button type="button" class="btn btn-primary btn-sm" onclick="newAccount();selectParent(' . $element->id . ')"
        data-toggle="modal" data-target="#account_detail">
        <i class="fa fa-plus-circle" aria-hidden="true"></i>
    </button>';

    if ($element->editable == 1) {
    echo '<button type="button" class="btn btn-success btn-sm" onclick="editParent(' . $element->id . ')"
        data-toggle="modal" data-target="#account_detail">
        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
    </button>';
    }
    echo '<span class="caret"> ' . $element->name . '</span>';
    } else {
    echo '
<li>';
    if ($element->editable == 1) {
    echo '<button type="button" class="btn btn-success btn-sm" onclick="editAccount(' . $element->id . ')"
        data-toggle="modal" data-target="#account_detail">
        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
    </button>
    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
        data-target="#myModalNew' . $element->id . '">
        <i class="fa fa-trash" aria-hidden="true"></i>
    </button>
    <div class="modal fade" id="myModalNew' . $element->id . '">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    Are You Sure to Delete This Account
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success"
                        onclick="deleteAccount(' . $element->id . ')">Yes</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>';
    }
    echo '<span class="caret"> ' . $element->name . '</span>
</li>';
}
}
}
}
@endphp

<!-- Model For Add Brand -->
<div class="modal fade" id="account_detail" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content ">
            <!--<form action="{{ url('Accounts/CoaSave') }}" method="post" id="frmCoas">-->
            <form id="frmCoas" onsubmit="addAccount(); return false" target="_blank">
                @csrf
                <input type="hidden" name="id" id="khan">
                <div class="modal-header d-block ">
                    <h5 class="modal-title text-center  text-primary" id="exampleModalLabel">Account Details</h5>
                </div>
                <div class="modal-header d-block ">
                    <label id="result"></label>
                </div>

                <div class="modal-body d-block mx-5">
                    <label for="fiscal_year">Parent Account</label>
                    <div>
                        <select id="select" name="parent_id" class="form-control show-tick ms select2 mb-3"
                            data-placeholder="Select" required>
                            <option value="-1">Select Account</option>
                            @foreach ($coa as $account)
                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label for="code">Account Code</label>
                    <div>
                        <input type="autofill" name="code" id="code" class="form-control mb-3"
                            value="{{ $data->code ?? '' }}" required>
                    </div>
                    <label for="name">Account Name</label>
                    <div>
                        <input type="text" name="name" id="name" onkeyup="autoFill(this.id, searchCoaURL, token)"
                            value="{{ $data->name ?? '' }}" class="form-control" required>
                    </div>
                    <div>
                        <input type="radio" class="trans_group" name="trans_group" required id="radio1" value="0" <?php
                            if(isset($data) && $data->trans_group == 0) echo 'checked'; ?> required>
                        <label for="trans_group">Group Account</label><br>
                        <input type="radio" class="trans_group" name="trans_group" id="radio2" value="1" <?php
                            if(isset($data) && $data->trans_group == 1) echo 'checked'; ?> required>
                        <label for="trans_group">Transaction Account</label>
                    </div>

                </div>
                <div class="modal-footer d-block">
                    <div class="text-center">
                        <input type="checkbox" id="keepme" name="keepme" value="on" checked>Keep me
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Model For Add Brand -->
<div class="row clearfix">

    <!-- form code starts from here
                <div class="form-popup " id="myForm">
                  <form action="{{ url('Accounts/CoaSave') }}"  method="POST"  class="form-container bg-primary">
                  {{ csrf_field() }}
                  <input type="hidden" name="id" id="khan">
                    <h3>Account Details</h3>
                    <label for="fiscal_year">Parent Account</label>
                        <div >
                            <select id="select" name="parent_id" class="form-control show-tick ms select2 mb-3" data-placeholder="Select" required>
                                <option value="-1">Select Account</option>
                                @foreach ($coa as $account)
    <option value="{{ $account->id }}">{{ $account->name }}</option>
    @endforeach
                            </select>
                        </div>
                        <label for="code">Account Code</label>
                        <div >
                            <input type="autofill" name="code" id="code" class="form-control mb-3" value="{{ $data->code ?? '' }}"  required>
                        </div>
                        <label for="name">Account Name</label>
                        <div>
                            <input type="text" name="name" id="name1"  onkeyup="autoFill(this.id, searchCoaURL, token)" value="{{ $data->name ?? '' }}" class="form-control" required>
                        </div>
                        <div>
                            <input type="radio" class="trans_group" name="trans_group" id="radio1" value="0" @if ($data ?? '') {{ $data->trans_group == 0 ? 'checked' : '' }} @endif  required>
                            <label for="trans_group">Group Account</label><br>
                             <input type="radio" class="trans_group" name="trans_group" id="radio2" value="1" @if ($data ?? '') {{ $data->trans_group == 1 ? 'checked' : '' }} @endif  required>
                            <label for="trans_group">Transaction Account </label>
                        </div>
                    <button type="submit" class="btn">Ok</button>
                    <button type="button" class="btn cancel" onclick="closeForm()">Cancel</button>
                  </form>
                </div>
                   form code ends here
                 -->

    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="body">
                <button class="btn btn-primary" style="align:right"
                    onclick="window.location.href = '{{ url('Accounts/Coa') }}';">New Account</button>
                <div class="table-responsive">
                    <ul id="myUL">
                        @php
                        displayTree($coaTree);
                        @endphp
                    </ul>

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

<script>
    var toggler = document.getElementsByClassName("caret");
        var i;
        console.log(toggler.length);
        for (i = 0; i < toggler.length; i++) {
            toggler[i].addEventListener("click", function() {
                this.parentElement.querySelector(".nested").classList.toggle("active");
                this.classList.toggle("caret-down");
            });
        }
</script>

<script>
    var token = "{{ csrf_token() }}";

    function addAccount() {
        $('#result').removeClass();
        $('#result').text('');

        $.post("{{ url('Accounts/CoaSave') }}", $('#frmCoas').serialize())
        .done(function(data) {
            if (data.result === "success") {
                // Account created/updated successfully
                $('#result').addClass('alert alert-success');
                $('#result').text(data.message);
                $('#code').val('');
                $('#name').val('');
                $('#khan').val(null);
            } else {
                // Display the error message received from the server
                $('#result').addClass('alert alert-danger');
                $('#result').text(data.message);
            }
        })
        .fail(function(xhr) {
            // Error while updating account info
            var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error while updating account info.';
            $('#result').addClass('alert alert-danger');
            $('#result').text(errorMessage);
        });


    }



        function editAccount(id) {

            // Call Ajax Function
            $.post("{{ url('Accounts/CoaListEdit') }}", {
                id: id,
                _token: token
            }, function(data) {
                $('#code').val(data.code);
                $('#name').val(data.name);
                $('#khan').val(data.id);
                $('#select').val(data.coa_id);

                if (data.trans_group == 0)
                    $("#radio1").prop("checked", true);
                else
                    $("#radio2").prop("checked", true);
            });
        }

        function closeForm() {
            document.getElementById("myForm").style.display = "none";
        }

        function newAccount() {

            $('#code').val('');
            $('#name').val('');
            $("input:radio").removeAttr("checked");
        }

        function selectParent(id) {
            console.log(id);
            $('option[value=' + id + ']').prop("selected", true);
        }
        //this fucntion eidt parent accounts
        function editParent(id) {
            //console.log(id);
            //$('option[value='+id+']').prop("selected", true);
            var token = "{{ csrf_token() }}";
            var url = "{{ url('Accounts/EditParentAccount') }}";
            $.post(url, {
                id: id,
                _token: token
            }, function(data) {
                $('#khan').val(data[0].id);
                $('option[value=' + data[0].coa_id + ']').prop("selected", true);
                $('#code').val(data[0].code);
                $('#name').val(data[0].name);
                if (data[0].trans_group == 0)
                    $("#radio1").prop("checked", true);
                else
                    $("#radio2").prop("checked", true);

            });
        }

        // this function delete child account
        function deleteAccount(id) {
            var token = "{{ csrf_token() }}";
            var url = "{{ url('Accounts/DelChildAccount') }}";

            $.post(url, {
                id: id,
                _token: token
            }, function(data) {
                alert(data);
                window.location.href = "{{ url('Accounts/CoaList') }}"; // Redirect to Accounts/CoaList route
            });
        }
</script>

@stop
