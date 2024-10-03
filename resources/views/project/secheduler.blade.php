@extends('layout.master')
@section('title','Sechedule')
@section('parentPageTitle','Project')
@section('page-style')
<?php  use App\Libraries\appLib; ?>
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/datatables.min.css"/>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="{{asset('public/assets/css/sw.css')}}">
<link rel="stylesheet" href="{{asset('public/assets/css/list.css')}}">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<style>

.nav-link{
    padding:2px !important;
   padding-left:5px;

}
.nav .nav-item .active{
    background-color:#6495ED !important ;
    color:white !important;
    box-shadow: 5px 10px #f5f5f5;
    border:none !important;
    padding:2px 5px !important;
}

.nav .nav-item .project-list{
    background-color:#ffc40c !important ;
    color:white !important;
    box-shadow: 5px 10px #f5f5f5;
    border:none !important;
    padding:2px 5px !important;
}
.tab-list .emp-li{
    background-color:#6495ED !important ;
    color:white !important;
}
.tab-list .prj-li{
    background-color:#ffc40c !important ;
    color:white !important;
}
.emp-color li{
    background-color:#6495ED !important ;
    color:white !important;
}
.bg-r-project{
    background-color:#ffc40c;
}

</style>
<script>
  $( function() {
    $( "#tabs" ).tabs();
  } );
  </script>
@stop
@section('content')
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
           <!-- <div class="header">
              <button class="btn btn-primary" style="align:right" onclick="window.location.href = '{{ url('Inventory/IGP/Create') }}';" >New Inward Gate Pass</button>
            </div> -->
            <div class="body">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Date</label>
                            <input type="date" name="date" id="date" class="form-control" value="{{date('Y-m-d')}}">
                        </div>
                        <div class="col-md-3 mt-4">
                            <h3 id="day" class="font-weight-bold"><em>Monday</em></h3>
                        </div>
                        <div class="col-md-3  offset-md-3">
                            <label>Project Manager</label>
                            <select name="manager" id="manager" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                <option value="-1">All</option>
                                @foreach($project_manager as $manager)
                                <option value="{{$manager->project_manager}}">{{$manager->project_manager_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-3 border" style="background-color:#f5f5f5;">
                    
                            <ul class="nav nav-tabs p-0 mb-3">
                                <li class="nav-item ml-3"><a class="nav-link  tab active" data-toggle="tab" href="#home">Employees</a></li>
                                <li class="nav-item ml-3"><a class="nav-link tab" data-toggle="tab" href="#attachments" id="project-tab">Projects</a></li>
                            </ul>
                            
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane in active" id="home">  
                                    <ul id="employees" class="sortable_list team_list connectedSortable list-unstyled tab-list" style="min-height: 20px;">
                                        
                                    </ul>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="attachments">  
                                    <ul id="project_tab" class="projects_list  list-unstyled tab-list">
                                    </ul>
                                </div>
                            </div>   
                        </div>
                        <div class="col-md-9 text-center" >
                            <div class="table-responsive table-bordered" id="projectList">
                            </div>
                        </div>
                    </div>
                 </div>    
            </div>
    </div>
</div>
@stop
@section('page-script')
<script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
<script>
$('.tab').on('click',function(){
    if($(this).html()==='Projects')
        $("#project-tab").addClass("project-list");
    else
        $("#project-tab").removeClass("project-list");
})



$(document).ready(function(){
    getProjctData ();

    });
$('#date').on('change',function(){
    getProjctData ();
});
$('#manager').on('change',function(){
    
    getProjctData ();
});

// Function will fetch data
function getProjctData ()
{

    var url = "{{ url('Project/Secheduler/GetbyDate')}}";
    var token =  "{{ csrf_token()}}";
    var date=$('#date').val();
    var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    var d = new Date(date);
    var dayName = days[d.getDay()];
    $('#day').html(dayName);
    var manager=$("#manager option:selected").val();

    // get Active project data
    $.post(url,{date:date,manager:manager, _token:token},function(data){
        freeEmployee=data.freeEmployee;
        ProjectsByDate=data.ProjectsByDate;
        activeProjects=data.projects;
        $("#employees").html('');
        $("#project_tab").html('');
        $("#projectList").html('');
        
        freeEmployee.map(function(val,i){
            $("#employees").append('<li class="border emp-li" id="'+val.id+'">'+val.employee+'</li>');
        });
        
        $.each(activeProjects, function(index, value) 
        {
                todayProjectId = index;
                todayProject = value;
                
                projectData = '<div class="row border-top border-right border-left" style="background-color:#f5f5f5;white-space: nowrap;">'+
                                '<div class="col-md-2 bg-r-project text-white">'+
                                '<span class="font-weight-bold" id="'+todayProjectId+'-p"></span>'+
                                '</div>'+
                                '<div class="col-md-8">'+
                                '<ul id="'+todayProjectId+'" class="sortable_list project_list connectedSortable emp-color  list-unstyled d-sm-inline-flex w-100" style="min-height: 20px;">';
                                                                  
                projectData += '</ul>'+
                            '</div>'+ 
                            '</div>';
                $("#projectList").append(projectData);
                $.each(todayProject, function(index, value) 
                {
                    if(value.employee_id !==null)
                    {
                        $("#"+todayProjectId).append('<li class="border ml-1 p-1" ml-sm-3 ui-sortable-handle" id="'+value.employee_id+'">'+value.employee_name+'</li>');
                    }
                   
                    $('#'+todayProjectId+'-p').text(value.project_name);

                });
        });
        ProjectsByDate.map(function(val,i){
            $("#project_tab").append('<li class="border prj-li" id="'+val.id+'">'+val.name+'</li>');
        });
          
        $( ".project_list" ).sortable({

            appendTo: document.body,
            connectWith: ".connectedSortable",
            receive: function(event, ui) 
            {
                /* alert("dropped on = "+this.id); // Where the item is dropped
                alert("sender = "+ui.sender[0].id); // Where it came from
                alert("item = "+ui.item[0].innerHTML); //Which item (or ui.item[0].id)
                */
                
                project_id=this.id;
                emp_id=ui.item[0].id;
                date=$('#date').val();
                
                var url = "{{ url('Project/Secheduler/addSechduler')}}";
                console.log(url);
                    $.ajax({
                    data:{emp_id:emp_id,project_id:project_id,date:date,_token:token},
                    type: 'POST',
                    url: url,
                });
            }, 
            
        });
           
            $( ".team_list" ).sortable({
                appendTo: document.body,
                connectWith: ".connectedSortable",
                receive: function(event, ui) 
                {
                    /* alert("dropped on = "+this.id); // Where the item is dropped
                    alert("sender = "+ui.sender[0].id); // Where it came from
                    alert("item = "+ui.item[0].innerHTML); //Which item (or ui.item[0].id)
                    */
                    project_id= $(ui.sender).attr('id');
                    emp_id=ui.item[0].id;
                    date=$('#date').val();
                    var token =  "{{ csrf_token()}}";
                    var url = "{{ url('Project/Secheduler/removeSechduler')}}";
                    console.log(url);
                        $.ajax({
                        data:{emp_id:emp_id,project_id:project_id,date:date,_token:token},
                        type: 'POST',
                        url: url,
                    });
                },  
            });
        });


}

</script>

<script>
(function ($) {
    // Detect touch support
    $.support.touch = 'ontouchend' in document;
    // Ignore browsers without touch support
    if (!$.support.touch) {
    return;
    }
    var mouseProto = $.ui.mouse.prototype,
        _mouseInit = mouseProto._mouseInit,
        touchHandled;

    function simulateMouseEvent (event, simulatedType) { //use this function to simulate mouse event
    // Ignore multi-touch events
        if (event.originalEvent.touches.length > 1) {
        return;
        }
    event.preventDefault(); //use this to prevent scrolling during ui use

    var touch = event.originalEvent.changedTouches[0],
        simulatedEvent = document.createEvent('MouseEvents');
    // Initialize the simulated mouse event using the touch event's coordinates
    simulatedEvent.initMouseEvent(
        simulatedType,    // type
        true,             // bubbles                    
        true,             // cancelable                 
        window,           // view                       
        1,                // detail                     
        touch.screenX,    // screenX                    
        touch.screenY,    // screenY                    
        touch.clientX,    // clientX                    
        touch.clientY,    // clientY                    
        false,            // ctrlKey                    
        false,            // altKey                     
        false,            // shiftKey                   
        false,            // metaKey                    
        0,                // button                     
        null              // relatedTarget              
        );

    // Dispatch the simulated event to the target element
    event.target.dispatchEvent(simulatedEvent);
    }
    mouseProto._touchStart = function (event) {
    var self = this;
    // Ignore the event if another widget is already being handled
    if (touchHandled || !self._mouseCapture(event.originalEvent.changedTouches[0])) {
        return;
        }
    // Set the flag to prevent other widgets from inheriting the touch event
    touchHandled = true;
    // Track movement to determine if interaction was a click
    self._touchMoved = false;
    // Simulate the mouseover event
    simulateMouseEvent(event, 'mouseover');
    // Simulate the mousemove event
    simulateMouseEvent(event, 'mousemove');
    // Simulate the mousedown event
    simulateMouseEvent(event, 'mousedown');
    };

    mouseProto._touchMove = function (event) {
    // Ignore event if not handled
    if (!touchHandled) {
        return;
        }
    // Interaction was not a click
    this._touchMoved = true;
    // Simulate the mousemove event
    simulateMouseEvent(event, 'mousemove');
    };
    mouseProto._touchEnd = function (event) {
    // Ignore event if not handled
    if (!touchHandled) {
        return;
    }
    // Simulate the mouseup event
    simulateMouseEvent(event, 'mouseup');
    // Simulate the mouseout event
    simulateMouseEvent(event, 'mouseout');
    // If the touch interaction did not move, it should trigger a click
    if (!this._touchMoved) {
      // Simulate the click event
      simulateMouseEvent(event, 'click');
    }
    // Unset the flag to allow other widgets to inherit the touch event
    touchHandled = false;
    };
    mouseProto._mouseInit = function () {
    var self = this;
    // Delegate the touch handlers to the widget's element
    self.element
        .on('touchstart', $.proxy(self, '_touchStart'))
        .on('touchmove', $.proxy(self, '_touchMove'))
        .on('touchend', $.proxy(self, '_touchEnd'));

    // Call the original $.ui.mouse init method
    _mouseInit.call(self);
    };
})(jQuery);
$(function() {
$( ".project_list" ).sortable({
  connectWith: ".connectedSortable",
  /*stop: function(event, ui) {
      var item_sortable_list_id = $(this).attr('id');
      console.log(ui);
      alert($(ui.sender).attr('id'))
  },*/
  receive: function(event, ui) 
  {
      /* alert("dropped on = "+this.id); // Where the item is dropped
      alert("sender = "+ui.sender[0].id); // Where it came from
      alert("item = "+ui.item[0].innerHTML); //Which item (or ui.item[0].id)
      */

     project_id=this.id;
     emp_id=ui.item[0].id;
     date=$('#date').val();
      
      var url = "{{ url('Project/Secheduler/addSechduler')}}";
      console.log(url);
        $.ajax({
           data:{emp_id:emp_id,project_id:project_id,date:date,_token:token},
           type: 'POST',
           url: url,
       });
    
    // var url='Project/addSechduler';
    // $.post(url,{name:'Ali', _token:token},function(data){
    // });
  
      
  },  
      
}).disableSelection();
  


$( ".team_list" ).sortable({
  connectWith: ".connectedSortable",
  /*stop: function(event, ui) {
      var item_sortable_list_id = $(this).attr('id');
      console.log(ui);
      alert($(ui.sender).attr('id'))
  },*/
  receive: function(event, ui) 
  {
      /* alert("dropped on = "+this.id); // Where the item is dropped
      alert("sender = "+ui.sender[0].id); // Where it came from
      alert("item = "+ui.item[0].innerHTML); //Which item (or ui.item[0].id)
      */

     project_id= $(ui.sender).attr('id');
     emp_id=ui.item[0].id;
     date=$('#date').val();
      var token =  "{{ csrf_token()}}";
      var url = "{{ url('Project/Secheduler/removeSechduler')}}";
      console.log(url);
        $.ajax({
           data:{emp_id:emp_id,project_id:project_id,date:date,_token:token},
           type: 'POST',
           url: url,
       });
    
    // var url='Project/addSechduler';
    // $.post(url,{name:'Ali', _token:token},function(data){
    // });
  
      
  },  
      
}).disableSelection();

});

</script>






@stop