$(document).ready(function () {


    var modal = new Modal();
    $(document).on("click", ".modal-event-ws", function () {
        modal.showPageInModal("?route=Vks/show/" + $(this).attr('event-id') + "/true")
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
            right: 'month'
        },
        //axisFormat: "HH:mm",
        editable: false,
//            defaultDate: '2015-07-30',
        //defaultView: 'agendaDay',
        defaultView: 'month',
        events: "?route=CalendarFeed/feedMainCount",
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
            var marker = "alert-info";
            var text = "text-primary";
            //is today?
            if ( moment().format("YYYYMMDD") == event.start.format("YYYYMMDD")) {
                marker = "alert-danger";
                text = 'text-danger';
            }
            //is this month?
            if ( moment().format("MM") != event.start.format("MM")) {
                marker = "well";
                text = 'text-muted';
            }
            //console.log(marker);
            container_counters.append(
                $("<span/>", {
                    'class': 'alert  ' + marker,
                    'html': "<span class='" + text + "'>" + event.start.format("D") + "</span>"
                })
            );

            var container_actions = $("<div/>", {
                'class': 'text-center hidden',
                'id': 'actions_container'
            });

            container_actions.append(
                $("<span/>", {
                    'id': 'vks-show-list',
                    'html': "<img title='Показать ВКС списком' data-date='" + event.php_date + "' class='icon pointer' src='../_CORE_REPOSITORY/images/list-icon-nohover.png'/>"
                })
            ).append(
                $("<span/>", {
                    'id': 'vks-show-graph',
                    'html': "<img title='Показать ВКС на графике' data-date='" + event.php_date + "' class='icon pointer' src='../_CORE_REPOSITORY/images/graph-icon-nohover.png'/>"
                })
            );

            //add counters
            if ( moment().format("YYYYMMDD") == event.start.format("YYYYMMDD")) {
                $(".fc-day-number[data-date='" +event.php_date+"']")
                    .html("<div class='pull-right'><span class='text-muted' style='font-size: 8px;'>(Сегодня) " + event.counters[1] + "/"+  event.counters[0]+"</span></div>");
            } else {
                $(".fc-day-number[data-date='" +event.php_date+"']")
                    .html("<div class='pull-right'><span class='text-muted' style='font-size: 8px;'>" + event.counters[1] + "/"+  event.counters[0]+"</span></div>");

            }

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
        $(this).find("img").attr("src",'../_CORE_REPOSITORY/images/list-icon.png' );
    });
    $(document).on("mouseleave", "#vks-show-list", function() {
        $(this).find("img").attr("src",'../_CORE_REPOSITORY/images/list-icon-nohover.png' );
    });

    $(document).on("click", "#vks-show-list", function() {
        location.href  = "?route=Vks/index/"+$(this).find("img").data("date");
    });

    $(document).on("mouseenter", "#vks-show-graph", function() {
        $(this).find("img").attr("src",'../_CORE_REPOSITORY/images/graph-icon.png' );
    });
    $(document).on("mouseleave", "#vks-show-graph", function() {
        $(this).find("img").attr("src",'../_CORE_REPOSITORY/images/graph-icon-nohover.png' );
    });

    $(document).on("click", "#vks-show-graph", function() {
        location.href  = "?route=Vks/day/"+$(this).find("img").data("date");
    });





});