$(document).ready(function () {
    /* initialize the calendarвапыпап
     -----------------------------------------------------------------*/

    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
//            right: 'month,agendaWeek,agendaDay'
            right: 'month'
        },

        editable: false,
        eventLimit: true, // allow "more" link when too many events
//        events: evtall,
        events: "?route=CalendarFeed/holydays",
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
        eventClick: function (calEvent, jsEvent, view) {

            location.href = "?route=Holydays/change/" + calEvent.date;
        },
        dayClick: function (element, view) {
            //console.log(element._d);
            var m = element._d.getMonth(), d = element._d.getDate(), y = element._d.getFullYear(), dayInWeek = element._d.getDay();
            var compileDay = y + '-' + (m + 1) + '-' + d;
            //console.log(compileDay);
            location.href = "?route=Holydays/change/" + compileDay;
        },
        eventRender: function (event, element) {

//                $(element).addClass("round-mark");

            //var def = '';
            //if (event.date) {
            //    def = 'По-умолчанию';
            //} else {
            //    def = 'Изменено';
            //}
//                if (event.editable) {
            $(element).find(".fc-time").html("");
//                } else {
//                    $(element).find(".fc-time").html("<div class='text-center'>Смены закрыты</div>");
//                }

//            event.backgroundColor = '#00ff00';

//
        }
    });
//    var d = $('#calendar').fullCalendar("getView");
//    console.log(d.start._d);
//    console.log(d.end._d);
//    var call = {"start":d.start._d, "end":d.end._d };
//    $.post("?r=views/view-calendar-event-feeder",call);
//    console.log( $('#calendar').fullCalendar('getView'));
    function dayClicked(date) {
//                console.log(date);
        location.href = '?r=views/settings/admins_schedule/setAdmins&d=' + date;
//            $('#calendar').fullCalendar('gotoDate', date);
//            $('#calendar').fullCalendar('changeView', 'agendaDay')
    }

//

})
;

