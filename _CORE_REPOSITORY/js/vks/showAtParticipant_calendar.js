$(document).ready(function () {
    $(document).on("click", ".modal-event-ws", function () {
        getModalVks($(this).attr('event-id'));
    });
    $(document).on("click", ".modal-event-ca", function () {
        getModalVksCa($(this).attr('event-id'));
    });
    /* initialize the calendar
     -----------------------------------------------------------------*/
//    var getVksSwitcherState = 13;
    $('#calendar').fullCalendar({

        header: {
            left: 'prev,next today',
            center: 'title',
//            right: 'month,agendaWeek,agendaDay'
            right: 'agendaDay'

        },
        //axisFormat: "HH:mm",
        editable: false,
        defaultDate: date,
        defaultView: 'agendaDay',
        events: {

            url: '?route=CalendarFeed/feedAtParticipant',
            type: 'GET',
            data: {
                date: date,
                requested_participant_id: requested_participant_id
            },
            error: function () {
                alert('there was an error while fetching events!');
            }
        },
        color: 'yellow',   // an option!
        textColor: 'black' // an option!
        ,
        eventLimit: {
            'agenda': 4,
            'default': 4
        },
        allDaySlot: false,
        slotDuration: "00:15:00",
//        weekends: false,
        lang: 'ru',
        timeFormat: {
            '': 'h(:mm)' // default
        },
        minTime: "08:00:00",
        maxTime: "20:00:00",
        slotEventOverlap: true,
        height: 650,
        pullServerLoadFrom: 0,
        eventClick: function (calEvent) {
            //get vka additional data, function in core.js
            //getModalVks(calEvent.id);
        },
        eventRender: function (event, element) {
//                    element.attr('data-toggle',"popover");
//                    element.attr('data-content',"And here's some amazing content. It's very engaging. Right?");
//                    element.attr('data-placement',"top");

            element.addClass('pointer');
            if (event.fromCa) {
                element.addClass('pointer modal-event-ca');
            } else {
                element.addClass('pointer modal-event-ws');
            }

            element.attr("event-id", event.id);

            element.find(".fc-time").html("<div style='font-size: 10px;'>" + event.titleCustom + "</div>");
//
        }
    });


});