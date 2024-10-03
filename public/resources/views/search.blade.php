@extends('layout.master')
@section('content')

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Auto Complete Search Using Jquery UI - Tutsmake.com</title>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
   <style>
    .container{
    padding: 10%;
    text-align: center;
   }
 </style>
 <style>

.autocomplete {
  /*the container must be positioned relative:*/
  position: relative;
  display: inline-block;
}

.autocomplete-items {
  position: absolute;
  border: 1px solid #d4d4d4;
  border-bottom: none;
  border-top: none;
  z-index: 99;
  /*position the autocomplete items to be the same width as the container:*/
  top: 100%;
  left: 0;
  right: 0;
}
.autocomplete-items div {
  padding: 10px;
  cursor: pointer;
  background-color: #fff;
  border-bottom: 1px solid #d4d4d4;
}
.autocomplete-items div:hover {
  /*when hovering an item:*/
  background-color: #e9e9e9;
}
.autocomplete-active {
  /*when navigating through the items using the arrow keys:*/
  background-color: DodgerBlue !important;
  color: #ffffff;
}

</style>
<div class="row">
        <div class="col-12">
            <form autocomplete="off" action="/action_page.php">
                    <div class="autocomplete" style="width:300px;">
                      <input id="searchs" type="text" name="searchs" onkeyup="searchCoa('searchs', 'coaSearch')" placeholder="Country">
                    </div>
                    <input type="submit">
                  </form>
<!--            <div id="custom-search-input">
                <div class="input-group">
                    <input id="searchs" name="searchs" type="text" class="form-control autocomplete" onkeyup="searchCoa('searchs', 'coaSearch')" placeholder="Search" onb />
                    <input type="text" name='id' id='id'>
                </div>
            </div>        -->
    </div>
</div>

<script>
function searchCoa(objId , urls){
// $(document).ready(function() {
    $('#'+objId).autocomplete({

        source: function(request, response) {
            $.ajax({
            url: "{{ url('coaSearch') }}",
            data: {
                    name : request.term
             },
            dataType: "json",
            success: function(data){
               var resp = $.map(data,function(obj){
                   // console.log(obj);
                    $('#id').val(obj.id);
                     return obj.name;
               });

                response(resp);
            }

        });
    },
    minLength: 1
 });
}

</script>
@stop
