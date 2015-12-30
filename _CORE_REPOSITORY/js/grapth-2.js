$(document).ready(function () {
    //define timer
    var refreshTimer;
    $(document).on("click", ".grapth-col", function () {

        //if main timer exist do nothing
        if ($(".cancel-time-container").length > 0) return;

        var element = $(this);
        //if this is blockked or inactive element: do nothing
        if (element.hasClass('blocked') || element.hasClass('grapth-col-noavaliable')) return;
        //get time of clicked element
        var getTime = $(this).attr("data-my-time");

        if ($('#start_time').val() == getTime) return;
        //find start time point if she is exist
        if ($(".chosen-begin").length != 0) { //если точка найдена, то текущий клик расценивается как финализирующий
            //передать время в соответсвующее поле
            $('#end_time').val(getTime);
//            ------------------------------------------------
            //зарезервировать время
            var obj = {
                'start_time': $("#date-with-support").val() + " " + $("#start_time").val(),
                'end_time': $("#date-with-support").val() + " " + $("#end_time").val()
            };
            //посылаем ajax на контроллер
            var reserved_vks_id = 0;
            $.post("?route=ReservedVks/create", obj, function (e) {
                if (typeof editLogic == 'undefined') {
                    $("<div class='cancel-time-container'><div class='alert alert-success text-center'><h4><span class='glyphicon glyphicon-exclamation-sign text-info'></span> Выбранное время зарезервированно. Резерв времени будет аннулирован через <span id='timer' data-timer='15:00'>15:00</span></h4></div><div class=' col-lg-6'><button type='button' class='btn btn-default btn-lg reset-reserved pull-right' data-reserved_vks='" + e + "'><span class='glyphicon glyphicon-remove-circle text-danger'></span> Отменить резерв и выбрать другое время</button></div><div class='col-lg-6'><button type='button' class='btn btn-info btn-lg' id='batch-add' data-reserved_vks='" + e + "'><span class='glyphicon glyphicon-calendar'></span> Добавить еще время (переодическая вкс)</button></div></div>").appendTo(".here-render-timeLine");
                } else {
                    $("<div class='cancel-time-container'><div class='alert alert-success text-center'><h4><span class='glyphicon glyphicon-exclamation-sign text-info'></span> Выбранное время зарезервированно. Резерв времени будет аннулирован через <span id='timer' data-timer='15:00'>15:00</span></h4></div><div class='col-lg-12 text-center'><button type='button' class='btn btn-default btn-lg reset-reserved' data-reserved_vks='" + e + "'><span class='glyphicon glyphicon-remove-circle text-danger'></span> Отменить резерв и выбрать другое время</button></div></div>").appendTo(".here-render-timeLine");
                }



            });
            //выставить таймер обратного отсчета
            refreshTimer = setInterval(function () {
                if ($("#timer").length == 0) {
                    return;
                }
                var getStartTime = $("#timer").data("timer");
                var splitted = getStartTime.split(":");
                var min = Number(splitted[0]);
                var sec = Number(splitted[1]);
                if (sec <= 0) {
                    if (min >= 1) {
                        min--;
                        sec = 60;
                    } else { ///когда время вышло, делаем очистку, перегружаем
                        resetReserved();
                        return;
                    }
                }
                sec--;
                var result = addNull(min) + ":" + addNull(sec);
                $("#timer").data("timer", result).text(result);
            }, 1000)

//            ------------------------------------------------
            //выставить соответствующий класс
            $(this).addClass("chosen-end");
        } else { //если нет, выставляем начало
            $('#start_time').val(getTime);
            $(this).addClass("chosen-begin");
        }
        //получить номер кликнутой плашки в наборе
        var getStartedIndex = $(".grapth-col").index(element);
        //инициализируем переменную для блокировки
        var fromThisIndexSetBlock = null;
        //заблокировать все что сзади элемента
        $(".grapth-col").each(function () {
            //этот элемент, ссылка
            var $this = $(this);
            //номер перебираемого элемента в наборе
            var thisIndex = $(".grapth-col").index($this);
            //если перебираемый элмент выше в наборе и имеет класс-"занят, т.к. нет доступных администраторов(такое значение рисут php скрипт на основании выборки из бд)", то передаем переменной fromThisIndexSetBlock(блокировать все после этого элемента) номер этого элмента
            if (thisIndex > getStartedIndex && $this.hasClass('grapth-col-noavaliable')) {
                fromThisIndexSetBlock = thisIndex;
            }
            //если переменная блокировки установлена
            if (fromThisIndexSetBlock != null) {
                //и если перебираемый элемент больше или равен элементу с которого начинается блокировка и перебираемый элемент больше кликнутого элемента
                if (thisIndex >= fromThisIndexSetBlock && thisIndex > getStartedIndex) {
                    //выдаем блокирующий класс
                    $this.addClass("blocked");
                }
            }
            //блокировать все что перед этим элементом если он меньше начального (кликнутого)
            if (thisIndex < getStartedIndex) {
                $this.addClass("blocked");
            }
            //если это закрывающий клик, заблочит все что после него, и нужно перезакрасить выбранную область
            if ($(".chosen-end").length != 0) {
                if (thisIndex > getStartedIndex) {
                    $this.addClass("blocked");
                }
                var getSIndex = $(".grapth-col").index($(".chosen-begin"));
                var getEIndex = $(".grapth-col").index($(".chosen-end"));
//                console.log(getEndedIndex);
                $(".grapth-col").each(function () {
                    var thisIndex = $(".grapth-col").index($(this));
                    if (thisIndex > getSIndex && thisIndex < getEIndex) {
                        $(this).addClass("selected");
                    }
                })

            }
        });
    }); //click end

    /**
     * Наведение мышки
     */
    $(document).on("mouseenter", ".grapth-col", function () {
        var getOffset = $(this).position();
        var colHeight = $(this).height();
        $(this).css("background-color", "purple")
        var getTime = $(this).data("my-time");

        var time_tip = "<div class='time-tip-tmp'>" + getTime + "</div>";
        $(time_tip).appendTo($(this)).css({
            "top": getOffset['top'] + colHeight,
            "left": getOffset['left']
        });
    }); //click end

    $(document).on("mouseleave", ".grapth-col", function () {
        $(this).css("background-color", "");
        $(".time-tip-tmp").remove();

    }); //click end

    $(document).on("click", ".resetTime", function () {
        $(".blocked, .chosen-end, .chosen-begin, .selected")
            .each(function () {
                $(this)
                    .removeClass('blocked')
                    .removeClass('chosen-end')
                    .removeClass('chosen-begin')
                    .removeClass('selected');
                $("#start_time,#end_time").val("");
            })
    });

    $(document).on("click", ".reset-reserved", function () {
        resetReserved();

    });

    $(document).on("click", "#batch-add", function () {
        //get values
        var batchVks = {
            'reserved_vks': $(this).data('reserved_vks'),
            'date': $("#date-with-support").val(),
            'start_time': $("#start_time").val(),
            'end_time': $("#end_time").val()
        };
        //clone timer
        var timerClone = $("#timer").clone(true, true);

        //try save batch
        $.post("?route=BatchedVks/store", batchVks, function (result) {
            //if this created more than 5 items, include current, return false
            if (result == 'false') {
                alert("За один раз вы не можете создать более 5ти конференций")
            } else {

                var timerId = "timerCopy" + result + "";
                var timerCopy = "<span id='" + timerId + "' data-reserved_id='" + result + "' data-timer='" + timerClone.text() + "'>" + timerClone.text() + "</span>";
                var element = "<div class='well batched'>";
                element += batchVks.date + "<br> c " + batchVks.start_time + " до " + batchVks.end_time;
                element += " [резерв истекает через: " + timerCopy + "] <span data-href='BatchedVks/delete/" + result + "' class='delete-batch'><span class='glyphicon glyphicon-remove-sign text-danger'></span></span></div>";
                //console.log(element);
                $(element).appendTo("#selected-time-list");

                $("#selected-time-list").removeClass('hidden');
                //console.log(timerId);
                var tid = setInterval(function () {
                    var getStartTime = $("#" + timerId+ "").data("timer");
                    var getReservedId = $("#" + timerId + "").data("reserved_id");
                    //console.log(getStartTime);
                    var splitted = getStartTime.split(":");
                    var min = Number(splitted[0]);
                    var sec = Number(splitted[1]);
                    if (sec <= 0) {
                        if (min >= 1) {
                            min--;
                            sec = 60;
                        } else { ///когда время вышло, делаем очистку, перегружаем
                            //посылаем ajax на контроллер
                            $.post("?route=BatchedVks/delete/" + getReservedId);
                            $("#" + timerId).parent().slideUp();
                            clearInterval(tid);
                            return;
                        }
                    }
                    sec--;
                    var result = addNull(min) + ":" + addNull(sec);
                    $("#" + timerId).data("timer", result).text(result);
                }, 1000)

                //renew time form
                clearInterval(refreshTimer);
                $(".time-params, .here-render-timeLine").hide();
                $("#date,#date-with-support").attr("disabled", false).val('--Выберите новое значение--');
                $(".adm-support-block").show().children().show();
                $(".freeAdmins").html('');
                $('#vks_method_display,.cancel-time-container,#timer').remove();
                $("#start_time,#end_time").val('');

            }

        }); //post end
        //container

    });


    $(document).on("click", ".delete-batch", function () {
        if(confirm("Вы уверены?")) {

            var getReservedHref = $(this).data('href');

            $.post("?route=" + getReservedHref);

            $(this).parent().slideUp();
        }


    });


    $(document).on("click", "#rebuild-graph", function () {
        if ($(".reset-reserved").length != 0) resetReserved();

        $(".here-render-timeLine").children().remove();
        $(".here-render-timeLine").html("<h3 class='alert alert-danger text-center'>Обновление данных, ожидайте...</h3>")
        setTimeout(
            function () {
                $("#date-with-support").change();

            }, 2000);

    });

//----------------functions ------------------
    function resetReserved() {
        clearInterval(refreshTimer);
        var deletedId = $(".reset-reserved").data('reserved_vks');
        $(".time-params,.here-render-timeLine").hide();
        $("#date").attr("disabled", false).val('--Выберите значение--');
        $(".adm-support-block").show().children().show();
        $(".freeAdmins").html('');
        $('#vks_method_display,.cancel-time-container,#timer').remove();
        $("#vks_method").attr("value", 0);
        $("#start_time,#end_time").val('');

//посылаем ajax на контроллер
        $.post("?route=ReservedVks/clearMyReserved/" + deletedId);
    }
}); //main end


