/*FullCalendar Init*/
$(document).ready(function() {
	'use strict';

    var date = new Date();
    var day = date.getDate();
    var month = date.getMonth();
    var year = date.getFullYear();
	
	var evenements = [];
	
	$.ajax({
		url : '/api/evenements',
		type: 'GET',
	}).done(function(response){ //
		if(response.evenements){
			evenements = response.evenements;

            $('#calendar').fullCalendar({
        
                //defaultView: 'agendaWeek',
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                editable: true,
                droppable: true, // this allows things to be dropped onto the calendar
                eventLimit: true, // allow "more" link when too many events
                    
                eventMouseover: function (data, event, view) {
                    var tooltip = '<div class="tooltiptopicevent tooltip tooltip-inner" style="width:auto;height:auto;position:absolute;z-index:10001;">'+data.heureDebut+' - ' + data.title + '</div>';
                    $("body").append(tooltip);
                    $(this).mouseover(function (e) {
                        $(this).css('z-index', 10000);
                        $('.tooltiptopicevent').fadeIn('500');
                        $('.tooltiptopicevent').fadeTo('10', 1.9);
                    }).mousemove(function (e) {
                        $('.tooltiptopicevent').css('top', e.pageY + 10);
                        $('.tooltiptopicevent').css('left', e.pageX + 20);
                    });
                },
                eventMouseout: function (data, event, view) {
                    $(this).css('z-index', 8);
                    $('.tooltiptopicevent').remove();
                },
                dayClick: function () {
                    tooltip.hide()
                },
                eventResizeStart: function () {
                    tooltip.hide()
                },
                eventDragStart: function () {
                    tooltip.hide()
                },
                viewDisplay: function () {
                    tooltip.hide()
                },
                eventClick: function(event) {
					$("#titre").text(event.title);
					$("#lieu").text(event.place);
					$("#ID").text(event.ID);
					$("#description").text(event.description);
					$("#debut").text(moment(event.start).format('DD/MM/YYYY HH:mm'));
					$("#fin").text(moment(event.end).format('DD/MM/YYYY HH:mm'));
					$('#modalDetail').modal('show');
                    //alert('Event: ' + event.place);
                    // alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
                    // alert('View: ' + view.name);

                    // // change the border color just for fun
                    // $(this).css('border-color', 'red');

                },
                events: evenements,
                timeFormat: 'H:mm' // uppercase H for 24-hour clock
            });
    
		}else{
			console.log(0)
		}

	}).fail(function(xhr, status, error){
		console.log(error);
	});
	
    var drag =  function() {
        $('.calendar-event').each(function() {

        // store data so the calendar knows to render an event upon drop
        $(this).data('event', {
            title: $.trim($(this).text()), // use the element's text as the event title
            stick: true // maintain when user navigates (see docs on the renderEvent method)
        });

        // make the event draggable using jQuery UI
        $(this).draggable({
            zIndex: 1111999,
            revert: true,      // will cause the event to go back to its
            revertDuration: 0  //  original position after the drag
        });
    });
    };
    
    var removeEvent =  function() {
		$(document).on('click','.remove-calendar-event',function(e) {
			$(this).closest('.calendar-event').fadeOut();
        return false;
    });
    };
    
    $(".add-event").keypress(function (e) {
        if ((e.which == 13)&&(!$(this).val().length == 0)) {
            $('<div class="btn btn-success calendar-event">' + $(this).val() + '<a href="javascript:void(0);" class="remove-calendar-event"><i class="ti-close"></i></a></div>').insertBefore(".add-event");
            $(this).val('');
        } else if(e.which == 13) {
            alert('Please enter event name');
        }
        drag();
        removeEvent();
    });
    
    
    drag();
    removeEvent();
    
});