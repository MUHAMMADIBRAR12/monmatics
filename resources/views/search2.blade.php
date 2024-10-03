@extends('layout.master')
@section('content')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>


  <style>
    .container{
    padding: 10%;
    text-align: center;
   } 
 </style>
<div class="container">
    <div class="row">
        <div class="col-12"><h2>laravel 6 Auto Complete Search Using Jquery UI</h2></div>
        <div class="col-12">
            
                    <input id="search1" name="search1" type="text" class="form-control" placeholder="Search" onkeyup="autoFill(this.id)"/>
                    <input id="search1_ID" name="search1_ID" type="hidden" class="form-control" placeholder="Search" "/>
               
        </div>
    </div>
    <div class="row">
        <div class="col-12"><h2>laravel 6 Auto Complete Search Using Jquery UI</h2></div>
        <div class="col-12">
            
                    <input id="search2" name="search2" type="text" class="form-control" placeholder="Search" onkeyup="autoFill(this.id)"/>
                    <input id="search2_ID" name="search2_ID" type="hidden" class="form-control" placeholder="Search" "/>
                
        </div>
    </div>
</div>
<script>
function autoFill(textId)
{
    $( "#"+textId ).autocomplete({
 
        source: function(request, response) 
                {
                    $.ajax({
                    url: "{{url('autocomplete')}}",
                    data: {
                            name : request.term,
                            _token : ''
                     },
                    dataType: "json",
                    success: function( data ) {
                        response( data );
                     }
                });
            },
        select: function (event, ui) {
           // Set selection
           $('#'+textId).val(ui.item.label); // display the selected text
           $('#'+textId+'_ID').val(ui.item.value); // save selected id to input
           return false;
        }
    });
}
 
</script>   
@stop