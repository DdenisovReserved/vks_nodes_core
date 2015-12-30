$(document).ready(function () {
//each click


    $(document).on("click", ".browse", function () {
        var $this = $(this);
        render.renderPage($this.data("id"))
    })

    $(document).on("click", ".plank-point", function () {
        var $this = $(this);
        var put = stack.put($this.data('id'));
        if (put) {
            stackViewer.view("#stack-content");
            stackViewer.displayCounter("#stack-counter");
            $this.removeClass("plank-point").addClass('plank-point-deactivated');
            addPointNotificator("#points-notificator", "Элемент добавлен");
        }
    })

    $(document).on("click", ".plank-remove", function () {
        $(this).parent().css("background-color", "red");
        var $this = $(this);
        stack.remove($this.data('id'));
        $(".plank-point-deactivated[data-id='" + $this.data('id') + "']").removeClass("plank-point-deactivated").addClass('plank-point');
        $this.removeClass("plank-point").addClass('plank-point-deactivated');

        stackViewer.view("#stack-content");
        stackViewer.displayCounter("#stack-counter");
        addPointNotificator("#points-notificator", "Элемент удален", 'danger');


    });

    $(document).on("click", "#clear_stack", function () {
        var stackData = stack.read();


        if (stackData.length) {
            if (confirm("Уверены?")) {
                $(stackData).each(function (i, element) {
                    $(".plank-point-deactivated[data-id='" + element + "']").removeClass("plank-point-deactivated").addClass('plank-point');
                });
                stack.clear();
                stackViewer.view("#stack-content");
                stackViewer.displayCounter("#stack-counter");
                addPointNotificator("#points-notificator", "Элементы удалены", 'danger');
            }
        }
    });


    //first version of sorter
    $(document).on("change", ".order", function () {
        var cOrder = $("#cOrder").find("#field").val() + "_to_" + $("#cOrder").find("#dir").val();
        var pOrder = $("#pOrder").find("#field").val() + "_to_" + $("#pOrder").find("#dir").val();
        render.renderPage(render.getCurrentElement(), cOrder, pOrder);
    });
    //second simple version of sorter
    $(document).on("click", ".oDirect", function () {
        //switch current element
        var currentOrder = $(this).data('direct');
        var newOrder = (currentOrder == 'asc') ? 'desc' : 'asc';

        $(this).data('direct', newOrder);

        var cOrder = "name_to_" + $("#cOrder").find(".oDirect").data('direct');
        var pOrder = "name_to_" + $("#pOrder").find(".oDirect").data('direct');
        render.renderPage(render.getCurrentElement(), cOrder, pOrder);
    });

    $(document).on("click", ".plank-select-all", function (e) {
        e.stopPropagation();
        if (stack.capacity == 1) {
            alert('В данном случае выбрать сразу несколько участников нельзя')
        } else {
            var $this = $(this);
            if ($this.text() != 0) {
                if (confirm("Выбрать все точки в этом контейнере?")) {
                    $.post("?route=AttendanceNew/apiGetTree/" + $this.data("id"), function (data) {
                        data = $.parseJSON(data);
                        var added = 0;
                        $.each(data.points, function (e, element) {
                            if (stack.put(element.id))  added++;
                        });
                        if (stack.capacity == 1) {
                            added = 1;
                        }
                        stackViewer.view("#stack-content");
                        stackViewer.displayCounter("#stack-counter");
                        addPointNotificator("#points-notificator", "Добавлено " + added);

                    })
                }
            }
        }
    });

    $(document).on("click", "#close-attendance-form", function (e) {
        in_place = $("[name='in_place_participants_count']").attr('value',$("[name='in_place_participants_count_modal']").val());
        $(".fancybox-close").click();
        stackViewer.display(stack.outputElement);
        if (isLocationStack(stack)) {
            var stackData = stack.read();
            if (stackData.length) {
                var techMail = askTechSupport(stackData[0]);
                if (techMail) {
                    allowTechSupport($("#needTPSupport"))
                } else {
                    blockTechSupport($("#needTPSupport"))
                }
            } else {
                blockTechSupport($("#needTPSupport"))
            }
        }

    });

    $(document).on("click", ".select_all_points", function (e) {
        if (stack.capacity == 1) {
            alert('В данном случае выбрать сразу несколько участников нельзя')
            return;
        }
        var added = 0;
        var points = $("#points-container").find(".plank-point");
        $.each(points, function (e, element) {

            if (stack.put($(element).data('id')))  added++;
            $(element).removeClass("plank-point").addClass("plank-point-deactivated")
        });
        if (stack.capacity == 1) {
            added = 1;
        }
        stackViewer.view("#stack-content");
        stackViewer.displayCounter("#stack-counter");
        addPointNotificator("#points-notificator", "Добавлено " + added);
        //var cOrder = "name_to_" + $("#cOrder").find(".oDirect").data('direct');
        //var pOrder = "name_to_" + $("#pOrder").find(".oDirect").data('direct');
        //render.renderPage(render.getCurrentElement(), cOrder, pOrder);

    });

    $(document).on("click", ".pull_me_up", function (e) {
        var last = $("#path").children().last();
        //console.log(last);
        render.renderPage($(last).find(".pointer").data("parent_id"));

    });


});

function addPointNotificator(element, message, type) {
    //if (typeof type == 'undefined') type = 'success';
    //$("#point-notice").remove();
    //var notice = "<span id='point-notice' class='label label-"+type+" style='display: none;'>" + message + "</span>";
    //$(notice).appendTo(element).show('fast');
    //disappear("#point-notice", 1000);
}


