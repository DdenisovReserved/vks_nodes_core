$(document).ready(function () {


    $(document).on("click",".modal-event", function() {
        getModalVksNs($(this).attr('event-id'));
    });
    /* initialize the calendar
     -----------------------------------------------------------------*/
//    var getVksSwitcherState = 13;
    $('#calendar').fullCalendar({

        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month'
        },
        editable: false,
        //defaultDate: currentDate,
        eventLimit: true, // allow "more" link when too many events
        //defaultView: 'agendaDay',
        defaultView: 'month',
        events: "?route=CalendarFeed/feedNsCount",
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

            element.removeClass("fc-day-grid-event fc-event fc-start fc-end");

            element.css({
                'background-color': 'white !important',
                'display': 'block',
                'height': '50px'
            });
            var container_main = $(element.find(".fc-content"));

            container_main.css({
                'margin-top': '35px'
            });

            $(container_main.find(".fc-title")).remove();
            var container_counters = $("<div/>", {
                'class': 'text-center',
                'id': 'counters_container'
            });
            container_counters.append(
                $("<span/>", {
                    'class': 'alert alert-info',
                    'style': 'background-color: #EFEFEF; border-color: #EFEFEF;',
                    'html': "<span class='text-primary'><b>" + event.counters[1] + "</b></span>/<span class='text-muted'>" + event.counters[0] + "</span>"
                })
            );

            var container_actions = $("<div/>", {
                'class': 'text-center hidden',
                'id': 'actions_container'
            });

            container_actions.append(
                $("<span/>", {
                    'id': 'vks-show-list',
                    'html': "<img title='Показать ВКС списком' data-date='" + event.php_date + "' class='icon pointer' src='images/list-icon-nohover.png'/>"
                })
            ).append(
                $("<span/>", {
                    'id': 'vks-show-graph',
                    'html': "<img title='Показать ВКС на графике' data-date='" + event.php_date + "' class='icon pointer' src='images/graph-icon-nohover.png'/>"
                })
            );
            element.append(container_main.append(container_counters).append(container_actions));

        }
    });

    $(document).on("mouseenter", ".fc-event-container", function() {

        $(this)
            .find("#counters_container").addClass("hidden")
            .end()
            .find("#actions_container").removeClass("hidden");
        $(this).find(".fc-content").css('margin-top', '24px')
    });
    $(document).on("mouseleave", ".fc-event-container", function() {
        $(this)
            .find("#actions_container").addClass("hidden")
            .end()
            .find("#counters_container").removeClass("hidden");
        $(this).find(".fc-content").css('margin-top', '35px')
    });

    $(document).on("mouseenter", "#vks-show-list", function() {
        $(this).find("img").attr("src",'images/list-icon.png' );
    });
    $(document).on("mouseleave", "#vks-show-list", function() {
        $(this).find("img").attr("src",'images/list-icon-nohover.png' );
    });

    $(document).on("click", "#vks-show-list", function() {
        location.href  = "?route=VksNoSupport/index/"+$(this).find("img").data("date");
    });

    $(document).on("mouseenter", "#vks-show-graph", function() {
        $(this).find("img").attr("src",'images/graph-icon.png' );
    });
    $(document).on("mouseleave", "#vks-show-graph", function() {
        $(this).find("img").attr("src",'images/graph-icon-nohover.png' );
    });

    $(document).on("click", "#vks-show-graph", function() {
        location.href  = "?route=VksNoSupport/day/"+$(this).find("img").data("date");
    });
});