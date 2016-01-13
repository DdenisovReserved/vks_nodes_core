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
    //console.log('local');
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
        type: 'POST',
        cache: false,
        dataType: 'json',
        url: "?route=Vks/apiGet/" + vksId,
        success: function (vks) {
            //prepare modal content

            var fancyContent = '';
            //console.log(vks.is_simple);
            if (vks.is_simple) {
                fancyContent += '<div class="alert alert-warning text-center">ВКС создана по упрощенной схеме</div>';
            }

            fancyContent += "<div class='action-buttons'>";
            fancyContent += "<div class='col-lg-6 no-left-padding'><a class='text-primary' href='?route=Vks/show/" + vks.id + "'><span class='glyphicon glyphicon-link'></span> Перейти на страницу Вкс</a></div>";
            fancyContent += "<div class='col-lg-6 no-right-padding'><div class='text-right'>";
            //admin section
            if (typeof Auth !== 'undefined' && (Auth == 1 || Auth == 5)) {
                if (vks.status == 0) {
                    fancyContent += "<a class='btn btn-warning btn-sm' title='Согласование' href='?route=Vks/showNaVks/" + vks.id + "'><span class='glyphicon glyphicon-ok-sign'></span> </a>";
                }


                if (vks.flag) {
                    fancyContent += "<a class='btn btn-default btn-sm' title='Снять флаг' href='?route=Vks/unmark/" + vks.id + "'><span class='glyphicon glyphicon-warning-sign'></span></a>";
                } else {
                    fancyContent += "<a class='btn btn-info btn-sm' title='Выдать флаг' href='?route=Vks/mark/" + vks.id + "'><span class='glyphicon glyphicon-alert'></span></a>";
                }

            }

            //!admin section
            if (Boolean(vks.humanized.isOutlookable)) {
                fancyContent += "<a class='btn btn-info btn-sm' href='?route=OutlookCalendarRequest/pushToStack/" + vks.id + "'  title='Отправить приглашение в мой календарь Outlook'><span class='glyphicon glyphicon-calendar'></span></a>";
            } else {
                fancyContent += "<span class='btn btn-default btn-sm' href='' disabled title='Отправить приглашение в мой календарь Outlook'><span class='glyphicon glyphicon-calendar'></span></span>";
            }
            if (Boolean(vks.humanized.isCloneable)) {
                fancyContent += "<a class='btn btn-default btn-sm' href='?route=Vks/makeClone/" + vks.id + "'  title='Клонировать'><span class='glyphicon glyphicon-duplicate'></span></a>";
            } else {
                fancyContent += "<span class='btn btn-default btn-sm' href='' disabled title='Клонировать'><span class='glyphicon glyphicon-duplicate'></span></span>";
            }
            if (Boolean(vks.humanized.isCodePublicable)) {
                    fancyContent += "<a class='btn btn-default btn-sm' href='?route=Vks/publicStatusChange/" + vks.id + "'  title='Изменить видимость кода'><span class='glyphicon glyphicon-eye-open'></span></a>";
            } else {
                fancyContent += "<span class='btn btn-default btn-sm' href='' disabled title='Изменить видимость кода'><span class='glyphicon glyphicon-eye-open'></span></span>";
            }
            if (Boolean(vks.humanized.isEditable)) {
                fancyContent += "<a class='btn btn-info btn-sm' href='?route=Vks/edit/" + vks.id + "'  title='Редактировать'><span class='glyphicon glyphicon-edit'></span></a>";
            } else {
                fancyContent += "<span class='btn btn-default btn-sm' href='' disabled title='Редактировать'><span class='glyphicon glyphicon-edit'></span></span>";
            }
            if (Boolean(vks.humanized.isDeletable)) {
                if (typeof Auth !== 'undefined' && (Auth == 1 || Auth == 5)) {
                    fancyContent += "<a class='btn btn-danger btn-sm' href='?route=Vks/annulate/" + vks.id + "'  title='Аннулировать'><span class='glyphicon glyphicon-remove-sign'></span></a>";
                } else {
                    fancyContent += "<a class='btn btn-danger btn-sm confirmation' href='?route=Vks/cancel/" + vks.id + "'  title='Аннулировать'><span class='glyphicon glyphicon-remove-sign'></span></a>";
                }
            } else {
                fancyContent += "<span class='btn btn-default btn-sm' href='' disabled title='Аннулировать'><span class='glyphicon glyphicon-remove-sign'></span></span>";
            }


            fancyContent += "</div></div>";
            fancyContent += "</div>";

            fancyContent +=
                "<table class='table table-bordered table-striped>' " +
                "<tr><td class='col-md-3'>id</td><td>" + vks.id;
            if (typeof vks.ca_linked_vks !== 'undefined') {
                fancyContent += " + ВКС ЦА #" + vks.ca_linked_vks.id;
                fancyContent += vks.link_ca_vks_type == 0 ? ' (С поддержкой администратора)' : ' (Без поддержки администратора)';
                if (typeof vks.tb_parp !== 'undefined') {
                    fancyContent += " + транспорт (ТБ для ТБ)";
                }
            }

            fancyContent +=
                "</th>" +
                "<tr><td>Тема</td><td>" + vks.title + "</td>" +
                "<tr><td>Статус</td><td>" + vks.humanized.status_label + "</td>" +
                "<tr><td>Дата</td><td>" + vks.humanized.date + "</td>" +
                "<tr><td>Время</td><td>" + vks.humanized.startTime + " - " + vks.humanized.endTime;
            if (typeof vks.ca_linked_vks !== 'undefined') {
                fancyContent += " (Мск: " + vks.ca_linked_vks.startTime + " - " + vks.ca_linked_vks.endTime + ")";
            }
            fancyContent += "</td>";
            fancyContent +=
                "<tr><td>Код подключения </td>";
            if (vks.connection_codes  && vks.connection_codes.length && vks.status == 1) {
                fancyContent += "<td>";
                $(vks.connection_codes).each(function (i, code) {
                    fancyContent += "<p><span class='connection-code-highlighter'>" + code.value;

                    if (code.tip && code.tip.length)
                        fancyContent += " (" + code.tip + ")";

                    fancyContent += "</span></p>";
                });
                fancyContent += "</td>";
            } else {
                if (vks.status == 0) {
                    fancyContent += "<td><span class='connection-code-highlighter-wait'>Заявка находится на согласовании администратором ВКС, пожалуйста, подождите</span></td>";
                } else {
                    fancyContent += "<td><span class='connection-code-highlighter'>Код подключения не выдан</span></td>";
                }

            }

            if (!vks.is_simple) {
                fancyContent += "</td>" +
                    "<tr><td>Заказчик </td><td>" + vks.init_customer_fio + ", " + vks.init_customer_phone + "</td>";
            }

            fancyContent += "<tr><td>Список участников</td><td><ol class= 'inside_parp'>";

            if (typeof vks.tb_parp !== 'undefined') {
                fancyContent += "<li class=''>Кол-во участников в ЦА: <span class='label label-warning label-as-badge'>" + vks.ca_linked_vks.ca_participants + "</span></li>";
                $.each(vks.tb_parp, function (i, parp) {
                    fancyContent += "<li class=''>" + parp.full_path + " <span class='label label-warning label-as-badge'>TB</span></li>";
                });
            }
            fancyContent += "<li><span class='glyphicon glyphicon-phone'></span> C рабочих мест (IP телефон, Lynс, CMA Desktop и т.д.): <span class='label label-as-badge label-default'>" + vks.in_place_participants_count + "</span></li>";
            $.each(vks.participants, function (i, parp) {
                fancyContent += "<li class='list-group-item-text'>";

                if (parp.container) {
                    fancyContent += "<span class='text-success glyphicon glyphicon-folder-open' title='Кто-то из контейнера'></span>&nbsp";
                } else {

                    fancyContent += "<span class='text-info glyphicon glyphicon-camera' title='Точка'></span>&nbsp";
                    if (typeof Auth !== 'undefined' && (Auth == 1 || Auth == 5)) {
                        if (parp.ip.length) {
                            fancyContent += "<span class='text-primary'>[" + parp.ip + "]</span> ";
                        }

                    }
                }

                fancyContent += parp.full_path + "</li>";
            });

            fancyContent += "</ul></tr>";
            fancyContent += "<tr><td>Утвердил администратор</td>";

            if (vks.approver) {
                fancyContent += "<td>" + vks.approver.login + ", тел." + vks.approver.phone + "</td>";
            } else {
                if (!vks.is_simple) {
                    fancyContent += "<td>Нет данных</td>";
                } else {
                    fancyContent += "<td>Утверждена в автоматическом режиме</td>";
                }
            }


            if (vks.humanized.im_owner && typeof vks.ca_linked_vks !== 'undefined' && vks.other_tb_required) {
                //fancyContent += "<tr><td>Код подключения в ЦА<sup><i title='Видно только вам'>*</i></sup></td><td><b><span class='text-primary'>" + vks.ca_linked_vks.v_room_num + "</span></b></td></tr>";
                var linkCode = "<input value='" + base_http_path + "i.php?r=" + vks.ca_linked_vks.referral + "' id='invite-code'/> <a title='перейти по ссылке' class='btn btn-sm btn-default' href='" + base_http_path + "i.php?r=" + vks.ca_linked_vks.referral + "'><span class='glyphicon glyphicon-link'></span></a>";

                if (vks.ca_linked_vks.referral) {
                    fancyContent += "<tr><td>Ссылка-приглашение на ВКС<sup><i title='Видно только вам'>*</i></sup></td><td><b><span class='referral-code-highlighter'>" + linkCode + "</span></b></td></tr>";
                } else {
                    fancyContent += "<tr><td>Ссылка-приглашение на ВКС</td>" +
                        "<td><div class='text-center text-danger'><h3>Не найден код приглашения, обратитесь к администратору</h3></div></tr>";

                }
            }
            var recordNeed = vks.record_required ? "Да" : "Нет";
            fancyContent += "<tr><td>Запись ВКС</td><td>"+recordNeed+"</td></tr>";

            fancyContent += "</table>";


            $.fancybox({
                'width': 720,
                'autoSize': false,
                'height': 'auto',
                'content': fancyContent,
                //closeClick: true,
                openEffect: 'none',
                openSpeed: 150,
                closeEffect: 'none',
                closeSpeed: 150,
                'iframe': true,
                'scrollOutside': true,
                helpers: {
                    overlay: true
                }

            });

            return false;
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
                                'width': 720,
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


