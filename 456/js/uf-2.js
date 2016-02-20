/**
 * Created by tomaroviv on 29.09.14.
 * обработчики и пр. на пользовательской форме добавления вкс
 */
$(document).ready(function () {

    hideErrorMark("*.error-mark");
    $("#fileToUpload").bootstrapFileInput();
    //скрыть форму
    //console.log($("#date-with-support").val());
    if (typeof $("#date-with-support").val() !== 'undefined') {
        if ($("#date-with-support").val() == "--Выберите значение--") {
            $(".time-params, .pre-ask-3,.here-render-timeLine").hide();
        } else {
            if (typeof editLogic === 'undefined')
                askGraph($("#date-with-support"));
        }
    }

    //заплатка которая не дает писать текст в поле даты и времени
    $(document).on("keyup", "#date, #start_time, #end_time", function () {
        $(this).val("")

    });

    if ($("#date, #date-with-support").length) {
        $datePickerMainProvider = new DatePickerProvider("#date, #date-with-support");
        $datePickerMainProvider.setWithDaysCheck();
    }

    if ($("#adm-date, #date_ns").length) {
        $datePickerMainProviderSimple = new DatePickerProvider("#adm-date, #date_ns");
        $datePickerMainProviderSimple.setsimple();
    }

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
        //console.log(getSelectedDate);
        //console.log($.datepicker.formatDate("dd.mm.yy", new Date()));
        if (getSelectedDate == $.datepicker.formatDate("dd.mm.yy", new Date())) {
            //console.log("true");
            $("#start_time, #end_time").timepicker('remove');
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
    })
    //check fields
    $(document).on("click", "#submit", function (e) {
        var errors = 0;
        $(".errors-cnt").html('').hide();
        //check selected elements
        $("#title,#date-with-support,#init_head_fio,#init_customer_fio,#init_customer_phone").each(function () {
            var pe = parseElement($(this));

            errors += checkElement(pe.thisElm, "Поля обязательны для заполнения");

        });

        if ($("#selected-time-list").find('.batched').length == 0) {
            $('#batch-vks').remove();
            $("#date-with-support,#date, #start_time,#end_time").each(function () {
                var pe = parseElement($(this));
                errors += checkElement(pe.thisElm, "Поля обязательны для заполнения");
            });
        } else {
            //if there has elements, make marker for server side that tell us check batch table for this user
            if ($('#batch-vks').length == 0)
                $('#form1').append("<input type='checkbox' name='batch-vks' id='batch-vks' checked class='hidden'>");
        }
        if (errors == 0) {
                    //убираем кнопку
                    $("#submit").attr("id", "wait").text('Отправка, ожидайте...');
                    $("#date,#date-with-support, #start_time, #end_time").attr("disabled", false);
                    //создадим кнопку и нажмем её
                    $('#form1').append("<button type='submit' id='submittrue' style='display: none;' class='hidden'></button>");
                    //субмитим форму? лол что
                    $('#submittrue').click();
            //    }
            //})
        }
    });

    $(document).on("click", "#submitNoAdmin", function (e) {
        var errors = 0;
        $(".errors-cnt").html('').hide();
        $("#title,#date,#start_time,#end_time").each(function () {
            var pe = parseElement($(this));
            errors += checkElement(pe.thisElm, "Поля обязательны для заполнения");
        });
        if (errors == 0) {


                    $("#submitNoAdmin").attr("id", "wait").text('Отправка, ожидайте...');
                    $('#form1').append("<button type='submit' id='submittrue' style='display: none;' class='hidden'></button>");
                    $('#submittrue').click();
        }
    });

    $(document).on("click", "#date,#date-with-support", function (e) {
        $("#date").removeClass("error-mark");
        $(".date-error-container").html("");
    })

    $(document).on("change", "#date-with-support", function (e) {
        askGraph($(this));
    });

    /*
     кнопка отмены поддержки вкс админом

     */
    $(document).on("click", ".support-cancel", function () {
        $('.adm-support-block').show().children().show();
        $('#vks_method_display').html('').hide();
        $("#date,#start_time,#end_time").attr("disabled", false);
    });
    /*
     Кнопка открытия места проведени ВКС
     */
    $(document).on("click", "#vks_location-list-inp", function (e) {
        //console.log(appHttpPath);
        e.preventDefault();
        var fancyContent = '';
        $.ajax({
            beforeSend: function () {
                $(".loader").show();
            },
            type: 'POST',
            cache: false,
            url: appHttpPath + '?route=AttendanceNew/browseLocation',
            success: function (data) {
                fancyContent = data;
                $.fancybox({
                    'width': "95%",
                    'height': $(window).height(),
                    'autoSize': false,
                    'type': 'iframe',
                    'content': fancyContent,
                    'fitToView': false
                });
            },
            complete: function () {
                $(".loader").hide();
            }

        });
    }); //click end
//    обработчик открытия окна с выбором участников на юзерской форме
    $(document).on("click", "#participants_inside_open_popup", function (e) {
        e.preventDefault();
//        $(this).fancybox();
        var fancyContent = '';
        $.ajax({
            beforeSend: function () {
                $(".loader2").show();
            },
            type: 'POST',
            cache: false,
            url: appHttpPath + '?route=AttendanceNew/browseParticipants',
            data: {'view-type': 'default'},
            success: function (data) {
                fancyContent = data;
//                console.log($(window).height());
                $.fancybox({
                    'width': "95%",
                    'height': $(window).height(),
                    'autoSize': false,
                    'type': 'iframe',
                    'content': fancyContent,
                    'fitToView': false

                });
            },
            complete: function () {
                $(".loader2").hide();
            }
        }); // ajax end
    }); //click end
//этот обработчик не требуется
    $(document).on("click", "#nextStep", function (e) {
        $(".errors-cnt").html('').hide();
        var errors = 0;
        var PE = parseElement($(this));

        var step = PE.thisElm.data("step");
        var step2 = step + 1;

        //mini-validate

        switch (step) {
            case 1:
                $("#date,#start_time,#end_time").each(function () {
                    var pe = parseElement($(this));
                    errors += checkElement(pe.thisElm, "Поля обязательны для заполнения");
                }) // each end
//            console.log($("#vks_method_display"));
                if ($("#vks_method_display").length == 0) {
                    errors++;
                    $(".errors-cnt").html($(".errors-cnt").html() + "<p>Нужно выбрать вариант проведения ВКС (С администраторм/Без администратора)</p>").show();
                }
                break;
            case 2:
                $("#title").each(function () {
                    var pe = parseElement($(this));
                    errors += checkElement(pe.thisElm, "Поля обязательны для заполнения");
                }) // each end

                if ($("#vks_location").length == 0) {
                    errors++;
                    $(".errors-cnt").html($(".errors-cnt").html() + "<p>Нужно выбрать место проведения</p>").show();
                }

                $("#init_head_fio,#init_customer_fio,#init_customer_tel").each(function () {
                    var pe = parseElement($(this));
                    errors += checkElement(pe.thisElm, "Поля обязательны для заполнения");
                }) // each end

                break;
            case 3:
                $("#participants_inside").each(function () {
                    var pe = parseElement($(this));
                    errors += checkElement(pe.thisElm, "Нужно заполнить список участников");
                }) // each end
                break;

        }


//    console.log(step);
        if (errors == 0) {

            $(PE.thisElm).hide("normal");
            $(".form-step-container:visible").hide().next(".form-step-container").show();
            $(".this-marked").removeClass("this-marked");
            $("li[data-step='" + step2 + "']").addClass("this-marked");
        }
    }); //click end
    /**
     * обработчик добавления форм внешних участников
     */
    $(document).on('change', '#fileToUpload', function (e) {

        $(".loadOuterFile").click();
    })

    $(document).on('click', '.loadOuterFile', function (e) {
        var check = $("#fileToUpload").val();
        if (check.length == 0) {
            flushMessage(".file-input-name", 'Нельзя передавать пустое значение, выберите файл для загрузки');
        } else {
            var getExt = check.split(".");
            getExt = getExt[$(getExt).length - 1];
            if ($.inArray(getExt.toLowerCase(), ["docx", "doc", "rtf", "txt"]) >= 0) {
                $("#loading").show();
                return ajaxFileUpload();
            } else {
                $("#fileToUpload").val("");
                $(".file-input-name").html("");
                flushMessage(".file-input-name", 'Нельзя передавать файлы такого формата, допускается только docx, doc, rtf, txt');

            }
        }
    });

    $(document).on('click', '.remove', function (e) {
        var $this = $(this);
        var sendObj = {'filename': $(this).parent().find("a").text()};
        $.post("?route=Vks/dropFile", sendObj, function () {
            $this.parent().remove();
            $(".participants_outside").html('');
            $(".remove").each(function () {
                var gethref = $(this).parent().find("a").attr('href');
                gethref = gethref + ",";
                $(".participants_outside").html($(".participants_outside").html() + gethref);
            });
        });
    });

    $(document).on('click', '.all-tb-select', function (e) {
        if ($(this).attr('data-clicked') == 0) {
            $(".tb-list").find("input").click();
            $(this).attr('data-clicked', 1);
            $(this).text('Отменить все');
        } else {
            $(".tb-list").find("input").removeAttr("checked");
            $(this).attr('data-clicked', 0);
            $(this).text('Выбрать все');
        }

    });


    function ajaxFileUpload() {

        $.ajaxFileUpload
        (
            {
                url: '?route=Vks/ajaxFileUpload',
                secureuri: false,
                fileElementId: 'fileToUpload',
                dataType: 'json',
                data: {name: 'logan', id: 'id'},
                beforeSend: function () {
                    $("#loading").show();
                },
                success: function (data, status) {
                    //console.log(status);
                    if (typeof(data.error) != 'undefined') {
                        if (data.error != '') {
                            alert(data.error);
                        } else {
                            //рендерим результат
                            $("<div></div>").html("<span class='remove'>[х] </span>  <a href='" + data.link + "' target='_blank'>" + data.filename + "</a> (" + data.size + "kb)").appendTo(".for_files_info");
                            data.link = data.link + "@@DELIM@@";
                            $('textarea.participants_outside').html($('textarea.participants_outside').html() + data.link);
                            $("#loading").hide();
                        }
                    }
                },
                error: function (data, status, e) {

                    alert(e);
                },
                complete: function (e) {
                    //console.log(e);
                    $("#loading").hide();
                }
            }
        );
        $("#fileToUpload").val("");
        $(".file-input-name").html("");
        return false;
    }


/////////////////////////////////////stepper Page Manipulator////////////////////
//    $("*[type='submit']").hide();
//    $(".form-step-container").not(":first").hide();

////////////////////////////////////!stepper Page Manipulator////////////////////
    /**
     * обработчик вывода подсказок на юзерской форме, потом допилить ччтоб подсказки брались из БД
     */
    $(document).on("click", ".tip", function (e) {
        e.preventDefault();
//        $(this).fancybox();
        var fancyContent = 'Что значит поддержка админом или без поддержки, тут описать как что';
        $.fancybox({
            'width': 960,
            'height': 960,
            'autoSize': true,
//                'type':'iframe',
            'content': fancyContent

        });
    }); //click end
    $(document).on("click", ".hide-me", function (e) {
        $(this).parent().parent().slideUp();
    }); //click end
}); // ready main end


function isLocationStack(stack) {
    return stack instanceof Stack && stack.capacity == 1 ? true : false;
}

function askTechSupport(attId) {
    var result = false;
    if (!attId.length || typeof attId !== 'undefined') {
        $.ajax({
            beforeSend: function () {

            },
            type: 'POST',
            cache: false,
            async: false,
            url: "?route=TechSupportMail/apiGetTechMail/" + attId,
            success: function (data) {
                data = $.parseJSON(data);
                result = data;
                //console.log(result);
            },
            //if no mails do this
            complete: function () {

            }
        });
    }
    return result;
}

function allowTechSupport(element) {
    element.removeAttr('disabled');
    $(".tp_lock_helper").remove();
    $(element).parent().append("<div class='tp_lock_helper text-success'><i>*Техническая поддержка может быть предоставлена</i></div>");
}

function blockTechSupport(element) {
    $(".tp_lock_helper").remove();
    element.attr('disabled', 'disabled');
    $(element).attr('checked', false);
    $(element).parent().after("<div class='tp_lock_helper text-danger'><i>*Для выбранного места проведения техническая поддержка не предоставляется</i></div>");
}

function askGraph(callelement) {
    var getDate = $(callelement).val();
    $('#start_time, #end_time').val('');
    $(".time-params").hide();
    $(".pre-ask-3").hide();
    if (getDate == '') {
        $("#date-with-support").addClass("error-mark");
        $(".date-error-container").html("<div class='alert alert-danger'>Нужно выбрать дату</div> ")
        return;
    }
    $(".here-render-timeLine").show();
    $.ajax({
        beforeSend: function () {
            $(".here-render-timeLine").html("<h3>Загрузка...</h3>");
        },
        type: 'get',
        cache: false,
        url: "?route=vks/buildGraph/" + getDate,
        success: function (data) {
            //если расписание работы админов заполнено, рендерим!, иначе не рендерим и дальше форму не показываем
            if (data != 'null') {
                $('#vks_method').attr('value', '1');
                $(".adm-support-block").hide();
//                    $(".freeAdmins").html("<div id='vks_method_display' class='alert alert-success'>Вы заказали для ВКС поддержку Администратора <button type='button' class='btn btn-default resetAll'>Отмена</button></div>");

                $('#start_time, #end_time,#date').attr("disabled", "disabled");
                $(".time-params").show();
                $(".pre-ask-3").slideDown("normal");
                $(".here-render-timeLine").html(data).show();
                $('#form1,.grapth-container').show(function () {
                    $("*[data-view-time='1']").each(function () {
                        var $this = $(this);
                        var getOffset = $this.position();
                        var getTime = $this.data("my-time");
                        var time_tip = "<div class='time-tip'>" + getTime + "</div>";
                        $(time_tip).css({
                            "top": getOffset['top'] - 20,
                            "left": getOffset['left']
                        })
                            .insertAfter($this);
                    });
                });
                //if only one free point fix
                if ($(".grapth-col-active").length == 1)
                    $(".grapth-col-active")
                        .removeClass('grapth-col-active')
                        .addClass("grapth-col-noavaliable");
            }
        }
    });
}