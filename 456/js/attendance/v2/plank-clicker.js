$(document).ready(function () {
//each click


    $(document).on("click", ".browse", function () {
        var $this = $(this);
        render.renderPage($this.data("id"))
    })

    $(document).on("click", ".plank-point", function () {
        var $this = $(this);
        var obj = {
            'id':$this.data('id'),
            'pathString': $this.data('path_string')
        };
        var t = stack.putVirtual(obj);
        if (t) {
            stackViewer.viewFromVirtual("#stack-content", t);
            $this.removeClass("plank-point").addClass('plank-point-deactivated');
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
        //addPointNotificator("#points-notificator", "Элемент удален", 'danger');
    });

    $(document).on("click", ".plank-remove-virtual", function () {
        $(this).parent().css("background-color", "red");
        var $this = $(this);
        var t = stack.removeFromVirtual($this.data('id'));
        $(".plank-point-deactivated[data-id='" + $this.data('id') + "']").removeClass("plank-point-deactivated").addClass('plank-point');
        stackViewer.viewFromVirtual("#stack-content", t);
        $this.removeClass("plank-point").addClass('plank-point-deactivated');
        //addPointNotificator("#points-notificator", "Элемент удален", 'danger');
    });

    $(document).on("click", "#clear_stack", function () {
        if (stack.getVirtual().length) {
            if (confirm("Уверены?")) {
                stack.clearVirtual();
                stackViewer.viewFromVirtual("#stack-content", stack.getVirtual());
                stackViewer.displayCounter("#stack-counter", stack.getVirtual());
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
            alert('В данном случае выбрать сразу несколько участников нельзя');
        } else {
            var $this = $(this);
            if ($this.text() != 0) {
                if (confirm("Выбрать все точки в этом контейнере?")) {
                    $.post("?route=AttendanceNew/apiGetTree/" + $this.data("id"), function (data) {
                        data = $.parseJSON(data);
                        var added = 0;
                        $.each(data.points, function (e, element) {
                            var obj = {
                                'id':element.id,
                                'pathString': element.pathString
                            };
                            if (stack.putVirtual(obj))  added++;
                        });
                        if (stack.capacity == 1) {
                            added = 1;
                        }
                        stackViewer.viewFromVirtual("#stack-content", stack.getVirtual());
                        stackViewer.displayCounter("#stack-counter", stack.getVirtual());
                        //addPointNotificator("#points-notificator", "Добавлено " + added);

                    })
                }
            }
        }
    });

    $(document).on("click", "#close-attendance-form", function (e) {


        $.when(stack.sendToRemoteStorage(stack.getVirtual()))
            .then(stackViewer.display(stack.outputElement));

        $(".fancybox-close").click();


        if (isLocationStack(stack)) {
            var stackData = stack.read();
            if (stackData.length) {
                var techMail = askTechSupport(stackData[0].id);
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
            var obj = {
                'id': $(element).data('id'),
                'pathString': $(element).data('path_string')
            };
            if (stack.putVirtual(obj))  added++;
            $(element).removeClass("plank-point").addClass("plank-point-deactivated")
        });
        if (stack.capacity == 1) {
            added = 1;
        }
        stackViewer.viewFromVirtual("#stack-content", stack.getVirtual());
        stackViewer.displayCounter("#stack-counter", stack.getVirtual());
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


