<!DOCTYPE html>
<html>
<head>
    <title>Laravel Fullcalender By Mohammad Ali Khan</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
  
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
</head>
<body>
  
    <div class="container">
        <h1>Appointment Calendar</h1>
        <div id='calendar'></div>
    </div>

   
<div class="modal fade" id="exampleModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Add Your Schedule</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label>Patient Name:</label>
                        <input type="text" id="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Title:</label>
                        <input type="text" id="title" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Note:</label>
                        <input type="text" id="note" class="form-control">
                    </div>
                </form>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                {{-- <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> --}}
                <button type="button" id="save" class="btn btn-success" data-dismiss="modal">Save Your Schedule</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
   
var SITEURL = "{{ url('/') }}";
  
$.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


var calendar = $('#calendar').fullCalendar({
                    header: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'agendaWeek,agendaDay'
                            },
                    defaultView: 'agendaWeek',
                    editable: true,
                    events: SITEURL + "/fullcalender",
                    displayEventTime: false,
                    editable: true,
                    eventRender: function (event, element, view) {
                        if (event.allDay === 'true') {
                                event.allDay = true;
                        } else {
                                event.allDay = false;
                        }
                    },
                    selectable: true,
                    selectHelper: true,
                    // select: function (arg) {
                        
                    //     alert(arg);
                    // },
                    select: function (start, end, allDay) {
                        
                        $("#exampleModal").modal('show');
                        
                        
                        // var title = prompt('Event Title:');
                        $("#save").on("click", function() {
                            var title = $('#title').val();

                            // alert(title);
                        //     var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD");
                        //    var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD");
                        //    alert(start+'--'+end);
                            // if (title) {
                            // var start = $.fullCalendar.formatDate(start, "Y-MM-DD");
                         
                            // var end = $.fullCalendar.formatDate(end, "Y-MM-DD");
                            
                            // alert(start.toISOString().split('T')[1]+'--'+end);
                            var start1 = moment(new Date(start)).format("YYYY-MM-DD HH:mm:ss");
                          
                            var end1 = moment(new Date(end)).format("YYYY-MM-DD HH:mm:ss");

                            $.ajax({
                                url: SITEURL + "/fullcalenderAjax",
                                data: {
                                    title: title,
                                    start: start1,
                                    end: end1,
                                    type: 'add'
                                },
                                type: "POST",
                                success: function (data) {
                                    displayMessage("Event Created Successfully");
  
                                    calendar.fullCalendar('renderEvent',
                                        {
                                            id: data.id,
                                            title: title,
                                            start: start,
                                            end: end,
                                            allDay: allDay
                                        },true);
  
                                    // calendar.fullCalendar('unselect');
                                }
                            });
                        //   }
                        });
                        
                        
                    },
                    // select: function (start, end, allDay) {
                    //     var title = prompt('Event Title:');
                    //     if (title) {
                    //         var start = $.fullCalendar.formatDate(start, "Y-MM-DD");
                    //         var end = $.fullCalendar.formatDate(end, "Y-MM-DD");
                    //         $.ajax({
                    //             url: SITEURL + "/fullcalenderAjax",
                    //             data: {
                    //                 title: title,
                    //                 start: start,
                    //                 end: end,
                    //                 type: 'add'
                    //             },
                    //             type: "POST",
                    //             success: function (data) {
                    //                 displayMessage("Event Created Successfully");
  
                    //                 calendar.fullCalendar('renderEvent',
                    //                     {
                    //                         id: data.id,
                    //                         title: title,
                    //                         start: start,
                    //                         end: end,
                    //                         allDay: allDay
                    //                     },true);
  
                    //                 calendar.fullCalendar('unselect');
                    //             }
                    //         });
                    //     }
                    // },
                    eventDrop: function (event, delta) {
                        // var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD");
                        // var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD");
                        var start1 = moment(new Date(event.start)).format("YYYY-MM-DD HH:mm:ss");
                          
                        var end1 = moment(new Date(event.end)).format("YYYY-MM-DD HH:mm:ss");

                        $.ajax({
                            url: SITEURL + '/fullcalenderAjax',
                            data: {
                                title: event.title,
                                start: start1,
                                end: end1,
                                id: event.id,
                                type: 'update'
                            },
                            type: "POST",
                            success: function (response) {
                                displayMessage("Event Updated Successfully");
                            }
                        });
                    },
                   
                    eventClick: function (event) {
                        var deleteMsg = confirm("Do you really want to delete?");
                        if (deleteMsg) {
                            $.ajax({
                                type: "POST",
                                url: SITEURL + '/fullcalenderAjax',
                                data: {
                                        id: event.id,
                                        type: 'delete'
                                },
                                success: function (response) {
                                    calendar.fullCalendar('removeEvents', event.id);
                                    displayMessage("Event Deleted Successfully");
                                }
                            });
                        }
                    }
 
                });
 
});
 
function displayMessage(message) {
    toastr.success(message, 'Event');
} 
  
</script>
  
</body>
</html>