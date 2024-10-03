function searchCoa(objId , urls){    

    alert('i m n');
    
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


function autoFill(textId, token=null)
{
    $( "#"+textId ).autocomplete({
 
        source: function(request, response) 
                {
                    $.ajax({
                    url: "{{url('autocomplete')}}",
                    data: {
                            name : request.term,
                            _token : token
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
 