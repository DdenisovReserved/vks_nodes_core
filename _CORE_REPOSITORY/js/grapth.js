$(document).ready(function () {
    $(document).on("click", ".grapth-col", function () {
        var element = $(this);
        //если кликается элемент с блокировкой, ничего не делать
        if (element.hasClass('blocked') || element.hasClass('grapth-col-noavaliable')) return;
        //получить время кликнутого элемента
        var getTime = $(this).attr("data-my-time");
        //найти стартовую точку если она существует
        if ($(".chosen-begin").length != 0) { //если точка найдена, то текущий клик расценивается как финализирующий


            //передать время в соответсвующее поле
            $('#end_time').val(getTime);
//            ------------------------------------------------
            //зарезервировать время
            var obj = {
                'req': 'vks_store_controller/createReserved',
                'date': $("#date-with-support").val(),
                'start_time': $("#start_time").val(),
                'end_time': $("#end_time").val()
            };
            //посылаем ajax на контроллер
            $.post("?r=controllers/ajax_router_controller", obj);
            //выставить таймер обратного отсчета
            $("<br><div class='alert alert-danger cancel-time-container'> Резерв времени будет аннулирован через <span id='timer' data-timer='15:00'>15:00</span> <button type='button' class='btn btn-default resetAll'>Отменить прямо сейчас</button></div>").appendTo(".here-render-timeLine");
            setInterval(function () {
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
                        sec = 59;
                    } else { ///когда время вышло, делаем очистку, перегружаем
                        var obj = {
                            'req': 'vks_store_controller/clearMyReserved'
                        };
                        //посылаем ajax на контроллер
                        $.post("?r=controllers/ajax_router_controller", obj);
                        document.location.href = "?r=views/userforms/add";
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
    $(document).on("click", ".resetAll", function () {
        $(".time-params, .pre-ask-3,.here-render-timeLine").hide();
        $("#date").attr("disabled", false).val('--Выберите значение--');
        $(".adm-support-block").show().children().show();
        $(".freeAdmins").html('');
        $('#vks_method_display,.cancel-time-container').remove();
        $("#vks_method").attr("value", 0);
        $("#start_time,#end_time").val('');
        //аннулируем все резервы созданные с этого ip
        var obj = {
            'req': 'vks_store_controller/clearMyReserved'
        };
        //посылаем ajax на контроллер
        $.post("?r=controllers/ajax_router_controller", obj);

    });


}); //main end