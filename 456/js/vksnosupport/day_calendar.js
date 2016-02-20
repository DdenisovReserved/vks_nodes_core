$(document).ready(function () {


    var modal = new Modal();
    $(document).on("click", ".modal-event-ws", function () {
        modal.showPageInModal("?route=VksNoSupport/show/" + $(this).attr('event-id') + "/true")
        //getModalVks($(this).attr('event-id'));
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
        defaultDate: currentDate,
        //defaultView: 'agendaDay',
        defaultView: 'agendaDay',
        events: "?route=CalendarFeed/feedNs",
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
        pullServerLoadFrom: 1,

        eventRender: function (event, element) {
            element.addClass('pointer');
            if (event.fromCa) {
                element.addClass('pointer modal-event-ca');
            } else {
                element.addClass('pointer modal-event-ws');
            }


            $(element).css("margin-right", "2px");
            $(element).css("margin-bottom", "2px");


            element.attr("event-id", event.id);

            element.find(".fc-time").html("<div>" + event.titleCustom + "</div>");
        },
        eventAfterAllRender: function () {
            
            $("#current_time").remove(); 

            var now = moment().format("DD.MM.YYYY H:mm");
            var hours = moment().format("H");
            var minutes = moment().format("mm");
            var year = moment().format("YYYY");
            var day = moment().format("DD");
            var mounth = moment().format("MM")-1;
            var currentOnGoing = null;

            if (minutes < 60 && minutes>= 45) {
                currentOnGoing = moment([year, mounth, day, hours, 45]).format("DD.MM.YYYY HH:mm");
            } else if (minutes < 45 && minutes>= 30) {
                currentOnGoing = moment([year, mounth, day, hours, 30]).format("DD.MM.YYYY HH:mm");
            } else if (minutes < 30 && minutes>= 15) {
                currentOnGoing = moment([year, mounth, day, hours, 15]).format("DD.MM.YYYY HH:mm");
            } else if (minutes < 15 && minutes >=0) {
                currentOnGoing = moment([year, mounth, day, hours, 0]).format("DD.MM.YYYY HH:mm");
            }

            var s = $(".timerow[data-datetime='" + currentOnGoing + "']");

            if (s.length) {

                var o = s.offset();
                var compile = $("<div/>").prop("id", "current_time");

                compile.css({
                    'left': o.left + "px",
                    'top': o.top + "px",
                    'position': 'absolute',
                    'z-index': 9999,
                    'min-height': '20px',
                    'height': '20px',
                    'min-width': 98 + "%",
                    'width': 98 + "%",
                    'background-color': 'yellow',
                    'opacity': 0.7
                });
                compile.html("<div class='text-center'><span style='width: 100%'>Сейчас</span></div>");
                $('body').append(compile);
            } 
        }
    });


});