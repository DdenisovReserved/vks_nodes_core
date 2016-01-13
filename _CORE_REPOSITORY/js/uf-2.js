$(document).ready(function () {
    hideErrorMark("*.error-mark");


    if ($("*[name='needTB']").data('checked') == 1) {
        $("#tbs,#ca_participants").removeClass('hidden')
    }


    //заплатка которая не дает писать текст в поле даты и времени
    $(document).on("keyup", "#date, #start_time, #end_time", function () {
        $(this).val("")
    });
    //выставляем датапикер
    $("#date,#date-with-support").datepicker({
        'minDate': new Date()
    });
    //при изменении даты строить график доступности админов
    //выставляем тайм пикеры
    $("#start_time, #end_time").timepicker({
        'minTime': '08:00',
        'maxTime': '19:45',
        'show2400': true,
        'timeFormat': "H:i",
        'step': "15",
        'forceRoundTime': true
    });


    $(document).on("change", "#date, #date-with-support", function () {
        $("#start_time, #end_time").attr("disabled", false);
        var getSelectedDate = $(this).val();

        if (getSelectedDate == $.datepicker.formatDate("dd.mm.yy", new Date())) {
            $("#start_time, #end_time").timepicker('remove').val('');
            var current = new Date();
            var mins = current.getMinutes();
            mins = roundMinutes(mins);

            if ($.inArray(current.getHours(), range(8, 20)) >= 0) {
                $("#start_time, #end_time").timepicker({
                    'minTime': current.getHours() + ':' + mins,
                    'maxTime': '19:45',
                    'show2400': true,
                    'timeFormat': "H:i",
                    'step': "15",
                    'forceRoundTime': true
                });
            } else {
                $("#start_time, #end_time").attr("disabled", 'disabled');
                $("#start_time,#end_time").timepicker('remove').val('');
                alert("К сожалению в выбранную дату создать ВКС невозможно, режим работы системы с 08:00 до 20:00");
            }

        } else {
            $("#start_time,#end_time").timepicker('remove');
            $("#start_time,#end_time").timepicker({
                'minTime': '08:00',
                'maxTime': '19:45',
                'show2400': true,
                'timeFormat': "H:i",
                'step': "15",
                'forceRoundTime': true
            });
        }
    });

    $(document).on("change", "#start_time", function () {
        $("#end_time").timepicker('remove');
        $("#end_time").timepicker({
            "minTime": $("#start_time").val(),
            "timeFormat": "H:i",
            'maxTime': '20:00',
            'show2400': true,
            'timeFormat': "H:i",
            'step': "15",
            'forceRoundTime': true
        });
    });
    //check fields
    $(document).on("click", "#submit", function (e) {
        var repo = new RepositoryPoints();
        var $this = $(this);
        var errors = 0;
        $(".errors-cnt").html('').hide();
        //check selected elements
        $("#title,#date-with-support, #start_time, #end_time, #init_customer_mail,#init_customer_fio,#init_customer_phone,*[name='department'],*[name='initiator'],*[name='title'],*[name='in_place_participants_count']").each(function () {
            var pe = parseElement($(this));
            errors += checkElement(pe.thisElm, "Поля обязательны для заполнения");
        });
        var cookieName = typeof formCookieParticipantsName !== 'undefined' ? formCookieParticipantsName : "vks_participants_create";
        var participants = repo.isExistInStorage(cookieName) ? repo.getFromStorage(cookieName) : [];
        if (typeof participants === 'object') {
            participants = Array(participants);
        }
        ;
        if ($("*[name='in_place_participants_count']").length && $("*[name='in_place_participants_count']").val().length) {
            participants.push('in_place_participants_count');
        }


        if (participants.length == 0) {
            errors++;
            $(".errors-cnt").html($(".errors-cnt").html() + "<p>Вы не выбрали ни одного участника вкс из справочника и нет участников с рабочих мест</p>").show();
        }

        if ($("*[name='initiator']").val() != 1) {
            if ($("*[name='other_tb_required']").val() == 1) {

                if ($("*[name='participants[]']:checked").length == 0) {
                    errors++;
                    $(".errors-cnt").html($(".errors-cnt").html() + "<p>Вы хотите подключить другие ТБ, но не указали какие</p>").show();
                }
            }
        }
        if (errors == 0) {
            //проверим капчу
            //var obj = {
            //    "checkCaptcha": true,
            //    "captcha_code": $("input[name='captcha_code']").val()
            //};
            //$.post("?r=controllers/controller_ajax", obj, function (e) {
            //    e = $.parseJSON(e);
            //    if (!e.cCheck) {
            //        $(".errors-cnt").html($(".errors-cnt").html() + "<p>Ошибка в коде проверки, попробуйте еще раз</p>").show();
            //        $(".refresh-captcha").click();
            //} else {
            //убираем кнопку
            $("#submit").attr("id", "wait").removeClass('btn-success').addClass('btn-default').text('Отправка, ожидайте...');
            $(".time_container").find("input,select").each(function (i, element) {
                $(element).attr("disabled", false);
            });

            //создадим кнопку и нажмем её
            var newSubmitButton = $this.clone().removeAttr('id').removeAttr('type');
            $(newSubmitButton).attr('id', 'submittrue').attr('type', 'submit').addClass('hidden');
            $('#form1').append(newSubmitButton);
            //субмитим форму? лол что
            $('#submittrue').click();
            //}
            //})
        }
    });


    $(document).on("click", "#date,#date-with-support", function (e) {
        $("#date").removeClass("error-mark");
        $(".date-error-container").html("");
    })

//    обработчик открытия окна с выбором участников на юзерской форме
    $(document).on("click", "#participants_inside_open_popup", function (e) {
        var $thisButton = $(this);
        //alert("this");
        e.preventDefault();
        $thisButton.attr("disabled", true);
        var modal = new Modal();
        dateTimeforCheck = [];

        //core data/time
        if (Boolean(Number(ajaxWrapper("?route=Settings/getOther/attendance_check_enable/true")[0]))) {

            var coreDT = {
                'date': $("#date-with-support").val(),
                'start_time': $("#start_time").val(),
                'end_time': $("#end_time").val()
            };
            //must be filled
            var res = true;
            $.each(coreDT, function (i, elem) {
                if (!elem.length) {
                    alert("Перед выбором участников основные значения даты и времени должны быть обязательно заполнены");
                    res = false;
                    return false;
                }
            });
            if (res)
                dateTimeforCheck.push(coreDT);

            //additional date/times
            if ($(".time_container:gt(0)").length > 0) {
                $(".time_container:gt(0)").each(function (i, element) {
                    var res = true;
                    var additional = {
                        'date': $(element).find(".clonedDP").val(),
                        'start_time': $(element).find(".start_time").val(),
                        'end_time': $(element).find(".end_time").val()
                    };
                    //console.log(additional);
                    $.each(additional, function (i, elem) {
                        if (!elem.length) {
                            alert("Перед выбором участников дополнительные значения даты и времени должны быть заполнены или удалены");
                            res = false;
                            return false;
                        }
                    });
                    if (res)
                        dateTimeforCheck.push(additional);
                });
            }

            if (dateTimeforCheck.length)
                $.when(modal.pull("participants")).then(
                    setTimeout(function () {
                        $thisButton.attr("disabled", false);
                    }, 4000)
                );

        } else {
            $.when(modal.pull("participants")).then(
                setTimeout(function () {
                    $thisButton.attr("disabled", false);
                }, 4000)
            );
        }


    }); //click end


    $(document).on('click', '.remove', function (e) {
        var $this = $(this);
        var sendObj = {'dropFile': true};
        sendObj['filename'] = $(this).parent().find("a").text();
        $.post("?r=controllers/controller_ajax", sendObj, function () {
            $this.parent().remove();
            $(".participants_outside").html('');
            $(".remove").each(function () {

                var gethref = $(this).parent().find("a").attr('href');
                gethref = gethref + ",";
                $(".participants_outside").html($(".participants_outside").html() + gethref);

            })
        })

    })

    $(document).on("click", "*[name='initiator']", function () {
        $("#initiator-variator").html('');
        if ($(this).val() == 1) { //if CA
            $("#initiator-variator").html("<label>Укажите код ЦА (это поле не обязательно, если код не известен, оставьте пустым)</label><input name='ca_code' class='form-control'/>").removeClass('hidden');
        } else if ($(this).val() != 1 && $(this).val().length > 0) { //if other
            $("#initiator-variator").html("<label>Подключить другой ТБ</label><select name='other_tb_required' class='form-control'><option value='0'>Нет</option><option value='1'>Да</option></select>").removeClass('hidden');

        }
    });


    $(document).on("click", "*[name='needTB']", function () {
        if ($(this).data('checked') == 1) {
            $(this).data('checked', 0)
            $("#tbs,#ca_participants").addClass('hidden');
        } else {
            $(this).data('checked', 1)
            $("#tbs,#ca_participants").removeClass('hidden')
        }
    });

    $(document).on("click", ".add_time", function () {
        if ($(".time_container").length >= 5) {
            alert("За 1 раз можно добавлять не более 5ти заявок");
            return;
        }
        var current = $(this).attr('data-number');
        $(".time_container:first").clone()
            .addClass("well")
            .find("input").each(function () {
                if ($(this).hasClass('hasDatepicker')) {
                    $(this).addClass('clonedDP');
                    $(this).data('vks_blocked_type', '0')
                }
                $(this).attr("id", "").attr("name", $(this).attr("name").replace(/(\d+)/g, Number(current) + 1))
                    .removeClass('hasDatepicker').removeClass("ui-timepicker-input")
            }).end()
            .find(".col-md-4").each(function () {
                $(this).removeClass("col-md-4").addClass("col-md-3");
            })
            .end()
            .insertAfter($(".time_container:last"));

        $(".time-params:last").append("<div class='col-md-3'><label><h4>&nbsp</h4></label><button type='button' class='form-control btn btn-danger btn-sm dates_remove'>Удалить</button></div>");

        $(".clonedDP:last").datepicker({
            'minDate': new Date()
        })
        $(".start_time:last, .end_time:last").timepicker({
            'minTime': '08:00',
            'maxTime': '19:45',
            'show2400': true,
            'timeFormat': "H:i",
            'step': "15",
            'forceRoundTime': true
        });
        $(this).attr('data-number', Number(current) + 1);
    });

    $(document).on("click", ".dates_remove", function () {
        $(this).parent().parent().parent().parent().remove();
    });


    $(document).on("change", ".clonedDP", function () {
        var timers = $(this).parent().parent().find(".start_time, .end_time");
        timers.attr("disabled", false);
        var getSelectedDate = $(this).val();
        //console.log(getSelectedDate);

        if (getSelectedDate == $.datepicker.formatDate("dd.mm.yy", new Date())) {
            //console.log("true");
            timers.timepicker('remove').val('');
            var current = new Date();
            var mins = current.getMinutes();
            mins = roundMinutes(mins);

            if ($.inArray(current.getHours(), range(8, 20)) >= 0) {
                timers.timepicker({
                    'minTime': current.getHours() + ':' + mins,
                    'maxTime': '19:45',
                    'show2400': true,
                    'timeFormat': "H:i",
                    'step': "15",
                    'forceRoundTime': true
                });
            } else {
                timers.attr("disabled", 'disabled');
                timers.timepicker('remove').val('');
                alert("К сожалению в выбранную дату создать ВКС невозможно, режим работы системы с 08:00 до 20:00");
            }

        } else {
            timers.timepicker('remove');
            timers.timepicker({
                'minTime': '08:00',
                'maxTime': '19:45',
                'show2400': true,
                'timeFormat': "H:i",
                'step': "15",
                'forceRoundTime': true
            });
        }
        //alert("gg");
    });


    $(document).on('click', '.get_help_button', function () {

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
            type: 'GET',
            cache: false,
            url: "?route=help/ask/" + $(this).data('file') + "/" + $(this).data('element'),
            dataType: "json",
            success: function (vks) {
                var modal = new Modal();
                modal.generateAndPull('Подсказка', vks[0]);

            },
            complete: function () {
                $('.spinner').remove();
            }
        })

    })


}); // ready main end

function isLocationStack(stack) {
    return stack instanceof Stack && stack.capacity == 1 ? true : false;
}
