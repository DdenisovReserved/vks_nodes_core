$(document).ready(function () {
    $(document).on("click", ".modal-event-ws", function () {
        getModalVks($(this).attr('event-id'));
    });
    /* initialize the calendar
     -----------------------------------------------------------------*/
//    var getVksSwitcherState = 13;
    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
//            right: 'month,agendaWeek,agendaDay'
            right: 'month'

        },
        //axisFormat: "HH:mm",
        editable: false,
//            defaultDate: '2015-07-30',
        eventLimit: true, // allow "more" link when too many events
        //defaultView: 'agendaDay',
        defaultView: 'month',
        events: "?route=BlockedTime/feed",
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

            //console.log(calEvent);
            location.href = '?route=BlockedTime/edit/'+calEvent.id;
        },

        dayClick: function (date, jsEvent, view) {
            if (view.name == 'month' || view.name == 'basicWeek') {
                var dateLocal = date.toDate();

                dateLocal = dateLocal.getFullYear()+ "-" +(dateLocal.getMonth() + 1) + "-" + dateLocal.getDate();

                location.href = '?route=BlockedTime/create/'+dateLocal
            }
        },
        eventRender: function (event, element) {

            element.addClass('pointer');

            element.attr("block-id", event.id);

            element.find(".fc-time").html(
                "<div>" + event.start_hours + "-" + event.end_hours + "<br>"+event.description+"</div>"
            );

        }
    });


});