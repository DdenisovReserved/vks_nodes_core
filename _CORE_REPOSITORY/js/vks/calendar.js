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
            right: 'month,agendaDay'

        },
        //axisFormat: "HH:mm",
        editable: false,
//            defaultDate: '2015-07-30',
        eventLimit: true, // allow "more" link when too many events
        //defaultView: 'agendaDay',
        defaultView: 'month',
        events: "?route=CalendarFeed/feedMain",
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
        slotEventOverlap: false,
        height: 'auto',
        pullServerLoadFrom: 0,
        eventClick: function (calEvent) {
            //get vka additional data, function in core.js
            //getModalVks(calEvent.id);
        },

        dayClick: function (date, jsEvent, view) {

            if (view.name == 'month' || view.name == 'basicWeek') {
//
                $('#calendar').fullCalendar('gotoDate', date);
                $('#calendar').fullCalendar('changeView', 'agendaDay');
            }
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

            element.find(".fc-time").html("<div>" + event.titleCustom + "</div>");
//
        }
    });


});