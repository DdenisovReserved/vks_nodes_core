function StackViewer(stack) {
    this.stack = stack;
}

StackViewer.prototype.view = function (toDiv) {
    //$(toDiv).html('');

    $.ajax({
        beforeSend: function () {
            //$(toDiv).prepend("<div class='loader-stack'><br><div class='progress'><div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='45' aria-valuemin='0' aria-valuemax='100' style='width: 90%'><span class='sr-only'>75% Complete</span></div></div></div>");
        },
        type: 'POST',
        cache: false,
        url: "?route=AttendanceNew/getStackData/" + this.stack.name,
        success: function (data) {
            data = $.parseJSON(data);
            $(".empty-plank").remove();
            if (data.length == 0) {
                $(toDiv).html("<i class='empty-plank'>Ничего не выбрано</i>");
                return;
            }
            data.reverse();
            $(toDiv).find(".plank-holder").each(function () {
                var flag = false;
                for (var i = 0; i != data.length; i++) {
                    if ($(toDiv).find("div[data-id='" + data[i].id + "']").length) {
                        flag = true;
                        break;
                    }
                }
                if (!flag)
                    $(this).remove();

            });


            for (var i = 0; i != data.length; i++) {
                if (!$(toDiv).find("div[data-id='" + data[i].id + "']").length) {
                    $(toDiv).append("<div class='plank-holder' title='" + data[i].pathString + "' ><span class='glyphicon glyphicon-remove-sign plank-remove text-danger pointer' data-id='" + data[i].id + "'></span> " + data[i].pathString + "</li>");
                }
            }

        },
        complete: function () {
            //$(".loader-stack").remove();
            //showOnly(toDiv, 10);
        }

    });

};

StackViewer.prototype.viewFromVirtual = function (toDiv, stackData) {

            var data = stackData;
            $(".empty-plank").remove();
            if (data.length == 0) {
                $(toDiv).html("<i class='empty-plank'>Ничего не выбрано</i>");
                return;
            }
            data.reverse();
            $(toDiv).find(".plank-holder").each(function () {
                var flag = false;
                for (var i = 0; i != data.length; i++) {
                    if ($(toDiv).find("div[data-id='" + data[i].id + "']").length) {
                        flag = true;
                        break;
                    }
                }
                if (!flag)
                    $(this).remove();

            });

            for (var i = 0; i != data.length; i++) {
                if (!$(toDiv).find("div[data-id='" + data[i].id + "']").length) {
                    $(toDiv).append("<div class='plank-holder' title='" + data[i].pathString + "' ><span class='glyphicon glyphicon-remove-sign plank-remove-virtual text-danger pointer' data-id='" + data[i].id + "'></span> " + data[i].pathString + "</li>");
                }
            }
};
//no edit for display in user forms
StackViewer.prototype.display = function (toDiv) {
    $(toDiv).html('');
    $.post("?route=AttendanceNew/getStackData/" + this.stack.name, function (data) {
        data = $.parseJSON(data);
        if (data.length == 0) {
            $(toDiv).html("<i class='empty-plank'>Ничего не выбрано</i>");
        }

        var list = stack.capacity != 1 ? "<ol>" : "<ul>";
        for (var i = 0; i < data.length; i++) {
            list += "<li title='" + data[i].pathString + "' >" + data[i].pathString + "</li>";
        }
        list += stack.capacity != 1 ? "</ol>" : "</ul>";

        $(toDiv).append(list);
        //showOnly(toDiv, 10);
    });
}

StackViewer.prototype.displayCounter = function (toDiv, stack) {
    //$(toDiv).html('');

        if (stack.length == 0) {
            $(toDiv).html(0);
        } else {
            $(toDiv).html(stack.length);
        }
}
