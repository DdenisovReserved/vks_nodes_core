$(document).ready(function () {

    var modal = new Modal();

    $(document).on("mouseover", ".timerow", function (e) {
        $(this).css('background-color', '#E86C58').addClass('pointer')
    });

    $(document).on("mouseleave", ".timerow", function (e) {
        $(this).css('background-color', '').removeClass('pointer')
    });

    $(document).on("click", ".timerow", function (e) {
        //e.stopPropagation()
        var dateTime = $(this).data('datetime');
        dateTime = dateTime.split(' ');
        var data = {
            'date': dateTime[0],
            'start_time': dateTime[1]
        };
        $.ajax({
            beforeSend: function () {
                var opts = {
                    lines: 17 // The number of lines to draw
                    , length: 26 // The length of each line
                    , width: 12 // The line thickness
                    , radius: 42 // The radius of the inner circle
                    , scale: 0.2 // Scales overall size of the spinner
                    , corners: 1 // Corner roundness (0..1)
                    , color: '#000' // #rgb or #rrggbb or array of colors
                    , opacity: 0.25 // Opacity of the lines
                    , rotate: 11 // The rotation offset
                    , direction: 1 // 1: clockwise, -1: counterclockwise
                    , speed: 3.2 // Rounds per second
                    , trail: 23 // Afterglow percentage
                    , fps: 20 // Frames per second when using setTimeout() as a fallback for CSS
                    , zIndex: 2e9 // The z-index (defaults to 2000000000)
                    , className: 'spinner' // The CSS class to assign to the spinner
                    , top: '50%' // Top position relative to parent
                    , left: '50%' // Left position relative to parent
                    , shadow: false // Whether to render a shadow
                    , hwaccel: false // Whether to use hardware acceleration
                    , position: 'absolute' // Element positioning
                }
                var spinner = new Spinner(opts).spin();
                $('#center').append(spinner.el);
            },
            type: 'post',
            data: data,
            cache: false,
            url: "?route=Vks/backPackDispatcher",
            dataType: 'json',
            success: function (data) {
                if (data)
                    location.href = "?route=Vks/Create"
            },
            complete: function () {
                $('.spinner').remove();

            }
        })

    });

    $(document).on("click", "#search-vks", function (e) {
        e.preventDefault();
        modal.pull('search');
    })

    $(document).on("click", "[name='referrer']", function (e) {
        if ($(this).val() == 'Введите код приглашения')
            $(this).val('');
    });

    $("[name='test-checkbox']").bootstrapSwitch({
        'size': 'small',
        'onText': 'С поддержкой',
        'offText': 'Все',
        'labelText': "Отображение",
        'onSwitchChange': function (event, state) {
            if (state)
                $(".no-adm-Support").hide();
            else
                $(".no-adm-Support").show();
        }
    });

    //$(document).on("mouseover",".fc-content",function(e) {
    //    alert('yyy');
    //})

    $(".show-as-modal").click(function (e) {
        e.preventDefault();
        var type = $(this).data('type');
        switch (type) {
            case('ca-was'):
                getModalVksCa($(this).data('id'));
                break;
            case('ca-ns'):
                alert('not supported!');
                break;
            case('local'):
                getModalVks($(this).data('id'));
                break;

        }


    })

    $(document).on("click", ".show-more-participants", function (e) {
//        console.log($(this).parent().parent().find(".hidden"));
        $(this).parent().parent().find(".hidden").removeClass("hidden").hide().slideDown();
        $(this).html("Свернуть").addClass("show-less-participants");
    })
    $(document).on("click", ".show-less-participants", function (e) {
        $(this).parent().parent().find(".list-group-item-text:gt(4):not(:last)").slideUp().addClass("hidden");
        $(this).html("+ еще " + $(this).parent().parent().find(".list-group-item-text:gt(4):not(:last)").length).removeClass("show-less-participants");

    })
    var oldVal = '';
    $(document).on("mouseenter", ".fc-day-number", function (e) {
        oldVal = $(this).html();
        $(this).css({
            'background-color': '#ff911b',
            'cursor': 'pointer'
        }).html("<span class='show-more text-center'>Показать день</span>");
    })
    $(document).on("mouseleave", ".fc-day-number", function (e) {
        $(this).find('.show-more').remove();
        $(this).css('background-color', '');
        $(this).html(oldVal)
    })

    $(document).on('focus', '#invite-code', function () {
        $(this).select();
    });
}) //main end

function getModalVks(vksId) {
    var modal = new Modal();
    modal.showPageInModal("?route=Vks/show/" + vksId)

}

function getModalVksCa(vksId) {
    //console.log('ca');

    var vks = {};

    function ajaxQueue(step) {
        switch (step) {
            case 0:
                $.ajax({
                    //async: false,
                    type: 'get',
                    //cache: false,
                    url: base_http_path + "api/?m=getVksWasById/" + vksId + "/" + encodeURIComponent(time_zone),
                    //url: core_http_path + "?route=Vks/apiGet/" + vksId + "/" + encodeURIComponent(time_zone),
                    beforeSend: function () {
                        var opts = {
                            lines: 17 // The number of lines to draw
                            , length: 26 // The length of each line
                            , width: 12 // The line thickness
                            , radius: 42 // The radius of the inner circle
                            , scale: 0.2 // Scales overall size of the spinner
                            , corners: 1 // Corner roundness (0..1)
                            , color: '#000' // #rgb or #rrggbb or array of colors
                            , opacity: 0.25 // Opacity of the lines
                            , rotate: 11 // The rotation offset
                            , direction: 1 // 1: clockwise, -1: counterclockwise
                            , speed: 3.2 // Rounds per second
                            , trail: 23 // Afterglow percentage
                            , fps: 20 // Frames per second when using setTimeout() as a fallback for CSS
                            , zIndex: 2e9 // The z-index (defaults to 2000000000)
                            , className: 'spinner' // The CSS class to assign to the spinner
                            , top: '50%' // Top position relative to parent
                            , left: '50%' // Left position relative to parent
                            , shadow: false // Whether to render a shadow
                            , hwaccel: false // Whether to use hardware acceleration
                            , position: 'absolute' // Element positioning
                        }
                        var spinner = new Spinner(opts).spin();
                        $('#center').append(spinner.el);
                    },
                    success: function (data) {
                        vks = $.parseJSON(data);
                        if (vks.status == 200) {
                            vks = vks.data
                            vks.tb_flag = false;
                        } else {
                            vks = false;
                        }

                    },
                    complete: function () {
                        //$('.spinner').remove();
                        ajaxQueue(1);
                    }
                });
                break;
            case 1:
                $.ajax({
                    //async: false,
                    beforeSend: function () {

                    },
                    type: 'get',
                    cache: false,
                    url: "?route=NotesCa/apiCheckFlag/" + vksId,
                    success: function (check) {
                        if (vks) {
                            check = $.parseJSON(check);
                            vks.tb_flag = check.response == 1 ? true : false;
                        }

                    },
                    complete: function () {
                        //$('.spinner').remove();
                        ajaxQueue(2);

                    }
                });
                break;
            case 2:
                $.ajax({
                    //async: false,
                    beforeSend: function () {

                    },
                    type: 'get',
                    cache: false,
                    url: "?route=Vks/hasLocalVks/" + vksId,
                    success: function (check) {
                        if (vks) {
                            check = $.parseJSON(check);
                            vks.has_local = check.response > 0 ? true : false;

                            //prepare modal content
                            var fancyContent = "<div><b><span class='text-muted'>#" + vks.id + " </span></div></b>";
                            var mskDate = vks.humanized.date != vks.local_date ? " (Мск:" + vks.local_date + ")" : "";
                            var fancyContent = '';
                            //console.log(vks.is_simple);
                            if (vks.flag) {
                                fancyContent += '<div class="alert alert-danger text-center">ВКС помечена как "важная" в ЦА</div>';
                            }
                            fancyContent += "<div class='action-buttons'>";
                            fancyContent += "<div class='col-lg-6 no-left-padding'><a class='text-primary' href=" + core_http_path + "?route=Vks/show/" + vks.id + "><span class='glyphicon glyphicon-link'></span> перейти на страницу Вкс в ЦА</a></div>";
                            fancyContent += "<div class='col-lg-6 no-right-padding'><div class='text-right'>";
                            //admin section
                            //console.log(vks);
                            if (typeof Auth !== 'undefined' && (Auth == 1 || Auth == 5)) {
                                if (!Boolean(vks.tb_flag)) {
                                    fancyContent += "<a class='btn btn-info btn-sm' title='Выдать флаг' href='?route=NotesCa/mark/" + vks.id + "'><span class='glyphicon glyphicon-alert'></span></a>";
                                } else {
                                    fancyContent += "<a class='btn btn-default btn-sm' title='Снять флаг' href='?route=NotesCa/unmark/" + vks.id + "'><span class='glyphicon glyphicon-warning-sign'></span></a>";
                                }

                                if (Boolean(vks.has_local)) {
                                    fancyContent += "<a class='btn btn-default btn-sm' title='Показать связные ВКС' href='?route=Vks/showLocalVks/" + vks.id + "'><span class='glyphicon glyphicon-list-alt'></span> </a>";
                                } else {
                                    fancyContent += "<a class='btn btn-default btn-sm' title='Нет связных ВКС' href='' disabled><span class='glyphicon glyphicon-list-alt'></span> </a>";
                                }
                            }

                            fancyContent += "</div></div>";
                            fancyContent += "</div>";


                            fancyContent +=
                                "<table class='table table-bordered table-striped>' " +
                                "<tr><th class='col-md-2'>Параметр</th><th>Значение</th>" +
                                "<tr><td>Тема</td><td>" + vks.title + "</td>" +
                                "<tr><td>Статус</td><td>" + vks.humanized.status_label + "</td>" +
                                "<tr><td>Дата</td><td>" + vks.humanized.date + " " + mskDate + " </td>" +
                                "<tr><td>Время</td><td>" + vks.local_start_time + " - " + vks.local_end_time + " (Мск:" + vks.humanized.startTime + " - " + vks.humanized.endTime + ")" + "</td>" +
                                "<tr><td>Место проведения</td><td> " + vks.humanized.location + "</td>";
                            fancyContent +=
                                "<tr><td>Номер Вк </td>";
                            if (vks.connection_code)
                                fancyContent += "<td><span class='connection-code-highlighter'>" + vks.connection_code.value + "</span></td>";
                            else {
                                fancyContent += "<td><span class='connection-code-highlighter'>Код подключения не выдан</span></td>";
                            }
                            fancyContent += " </tr>";
                            if (typeof Auth !== 'undefined' && (Auth == 1 || Auth == 5)) {

                                if (vks.referral) {
                                    fancyContent += "<tr><td>Ссылка-приглашение на ВКС</td>" +
                                        "<td><span class='referral-code-highlighter'>" + vks.humanized.invite_link + "</span></td></tr>";
                                } else {
                                    fancyContent += "<tr><td>Ссылка-приглашение на ВКС</td>" +
                                        "<td><div class='text-center text-danger'><h3>Не найден код приглашения, обратитесь к администратору</h3></div></tr>";

                                }

                            }
                            fancyContent +=
                                "<tr><td>ФИО председательсвующего</td><td>" + vks.init_head_fio + "</td>" +
                                "<tr><td>Заказчик </td><td>" + vks.init_customer_fio + ", " + vks.init_customer_phone + "</td>";

                            fancyContent += "<tr><td>Список участников Сбербанка </td><td><ul class= 'list-unstyled inside_parp'>";

                            $.each(vks.inside_parp, function (i, parp) {

                                fancyContent += "<li class='list-group-item-text-text'><span class='glyphicon glyphicon-camera'></span> " + parp.full_path + "</li>";
                            });
                            $.each(vks.phone_parp, function (i, parp) {
                                fancyContent += "<li class='list-group-item-text'><span class= 'glyphicon glyphicon-phone'></span> " + parp.phone_num + "</li>";
                            });
                            var c = 1;
                            fancyContent += "</td>";
                            fancyContent += "<tr><td>Внешние участники</td><td><ul class= 'list-unstyled'>";
                            if (!vks.outside_parp.length) fancyContent += "Нет";
                            $.each(vks.outside_parp, function (i, parp) {
                                fancyContent += "<li class='list-group-item-text'><a class='btn btn-default btn-sm' href='" + parp.attendance_value + "'><span class='glyphicon glyphicon-file'></span> Ф." + c + "</a></li>";
                                c++;
                            });

                            fancyContent += "</ul></tr>";

                            fancyContent +=
                                "<tr><td>Администратор ВКС </td>";

                            if (vks.admin) {

                                fancyContent += "<td>" + vks.admin.login + ", тел." + vks.admin.phone + "</td>";

                            } else {
                                fancyContent += "<td><span class='text-danger'>Адмнистратор не назначен</span></td>";
                            }


                            fancyContent += "</table>";


                            $.fancybox({
                                'width': 960,
                                'autoSize': false,
                                'height': 'auto',
                                'content': fancyContent,
                                //closeClick: true,
                                openEffect: 'none',
                                openSpeed: 20,
                                closeEffect: 'none',
                                closeSpeed: 20,

                                'scrollOutside': true,
                                helpers: {
                                    overlay: true
                                }
                            })
                        } else {
                            $.fancybox({
                                'width': 720,
                                'autoSize': false,
                                'height': 'auto',
                                'content': '<h4 class="text-center">Ошибка: не удалось получить данные, возможно api центрального сервера перегружено запросами. Попробуйте еще раз через некоторое время.</h4>',
                                //closeClick: true,
                                openEffect: 'none',
                                openSpeed: 20,
                                closeEffect: 'none',
                                closeSpeed: 20,

                                'scrollOutside': true,
                                helpers: {
                                    overlay: true
                                }
                            })
                        }
                    },
                    complete: function () {
                        $('.spinner').remove();
                        var $style;
                        $style = $('<style type="text/css">:before,:after{content:none !important}</style>');
                        $('head').append($style);
                        return setTimeout((function () {
                            return $style.remove();
                        }), 0);
                    }
                });
                break;
        }
    }

    ajaxQueue(0);


}

$(window).bind("load", function () {

    var footerHeight = 0,
        footerTop = 0,
        $footer = $("#footer");

    positionFooter();

    function positionFooter() {

        footerHeight = $footer.height();
        footerTop = ($(window).scrollTop() + $(window).height() - footerHeight) + "px";

        if (($(document.body).height() + footerHeight) < $(window).height()) {
            $footer.css({
                position: "absolute"
            }).css({ // can be animate, just type animate
                top: footerTop
            })
        } else {
            $footer.css({
                position: "static"
            })
        }

    }

    $(window)
        //.scroll(positionFooter)
        .resize(positionFooter)

});


