function Render(stack,blockageStack) {
    this.stack = stack;
    blockageStack = typeof blockageStack !== 'undefined' ? blockageStack : new BlockageStack('blockage', '1');

    this.blockageStack = blockageStack;
}

Render.prototype.renderPage = function (id, cOrder, pOrder, pathHead) {
    cOrder = typeof cOrder !== 'undefined' ? cOrder : 'name_to_asc';
    pOrder = typeof pOrder !== 'undefined' ? pOrder : 'name_to_asc';

    //clear block
    $("#containers-container, #points-container").html("");
    var $this = this;

    $.ajax({
        beforeSend: function () {
            //$("#main-container").prepend("<div class='loader'><br><div class='progress'><div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='45' aria-valuemin='0' aria-valuemax='100' style='width: 45%'><span class='sr-only'>45% Complete</span></div></div></div>");
            //$("#containers-container, #points-container").html("<h4 class='roller'>Обновление данных...</h4>")
        },
        type: 'POST',
        cache: false,
        url: "?route=AttendanceNew/apiGetTree/" + id + "/" + cOrder + "/" + pOrder,
        dataType: 'json',
        success: function (data) {
            //data = $.parseJSON(data);

            $this.renderPath(data, pathHead);

            $this.renderOrdersSimple(data);

            if (!data.containers.length) $("#containers-container").append("<div class='text-center'> <h5 class='text-muted'><i>Список пуст</i></h5></div>");
            if ($("#path").children().length > 1) {
                $("#cOrder").append("<span class='pull-left btn-link pull_me_up pointer text-primary'><span class='glyphicon glyphicon glyphicon-arrow-left'></span> Назад</span>");
            } else {
                $("#cOrder").append("<span class='pull-left text-muted'><span class='glyphicon glyphicon glyphicon-arrow-left'></span> Назад</span>");
            }


            $.each(data.containers, function (e, element) {
                $("#containers-container").append($this.renderPlank(element));
            });

            if (!data.points.length) {
                $("#points-container").append("<div class='text-center'><h5 class='text-muted'><i>Список пуст</i></h5></div>");
                $("#pOrder").append("<span class='pull-left  text-muted'>Добавить все <span class='glyphicon glyphicon-indent-left'></span></span>");
            } else {
                $("#pOrder").append("<span class='pull-left btn-link select_all_points pointer text-primary'>Добавить все <span class='glyphicon glyphicon-indent-left'></span></span>");
            }

            $.each(data.points, function (e, element) {
                $("#points-container").append($this.renderPlank(element));
            });
            $('[data-toggle="tooltip"]').tooltip()
        },
        complete: function () {
            $(".loader, .roller").remove();
        }

    });


}

Render.prototype.renderPlank = function (element) {
    //console.log(element);
    var result = '<div';

    if (this.isContainer(element)) {
        result += " class='browse plank plank-container'";
    } else {

        if (this.stack.exist(element.id) ||  this.blockageStack.exist(element.id))
            result += " class='points plank plank-point-deactivated' data-toggle='tooltip' data-placement='left' title='Точка уже выбрана'";
        else
            result += " class='points plank plank-point' ";
    }


    result += " data-id=" + element.id + ">";
    if (this.isContainer(element)) {

        result += "<div class='col-sm-2 text-right '><span title='Выбрать все точки в этом контейнере' data-toggle='tooltip' data-placement='top' class='plank-select-all btn-link badge' data-id='" + element.id + "'>" + element.childs.length + "</span></div> ";
    } else {
        //result += "<div class='col-sm-2 text-right'><span class='glyphicon glyphicon-camera text-muted'></span></div>";
    }
    result += "<div class='col-sm-10'><span class='container-name'>" + element.name + "</span></div> ";
    result += "</div>";
    return result;

}

Render.prototype.renderOrders = function (data) {

    var pointsOE = $("#pOrder");

    var containersOE = $("#cOrder");

    //clear containers
    pointsOE.html('');
    containersOE.html('');

    var elementPOE = this.generateOrderElement('POE', data.pOrder);

    var elementCOE = this.generateOrderElement('COE', data.cOrder);

    pointsOE.append(elementPOE);

    containersOE.append(elementCOE);

}

Render.prototype.renderOrdersSimple = function (data) {

    var pointsOE = $("#pOrder");

    var containersOE = $("#cOrder");

    //clear containers
    pointsOE.html('');
    containersOE.html('');

    var elementPOE = this.generateOrderElementSimple(data.pOrder);

    var elementCOE = this.generateOrderElementSimple(data.cOrder);

    pointsOE.append(elementPOE).show();

    containersOE.append(elementCOE).show();


}

Render.prototype.generateOrderElement = function (mark, startPack) {
    var result = '';
    var optionsFields = [];
    var optionsDirs = [];
    startPack = startPack.split("_to_");
    optionsFields.push(startPack[0]);
    optionsDirs.push(startPack[1]);
    var orderFieldsSet = ['id', 'name', 'created_at'];
    var orderDirsSet = ['asc', 'desc'];

    for (var i = 0; i < orderFieldsSet.length; i++) {
        if ($.inArray(orderFieldsSet[i], optionsFields) == "-1")
            optionsFields.push(orderFieldsSet[i]);
    }
    for (var i = 0; i < orderDirsSet.length; i++) {
        if ($.inArray(orderDirsSet[i], optionsDirs) == "-1")
            optionsDirs.push(orderDirsSet[i]);
    }

    result += "<div class='col-lg-2 nopadding'><select id='field' class='order form-control'>";
    for (var i = 0; i < optionsFields.length; i++) {

        result += "<option value='" + optionsFields[i] + "'>" + this.humanizeField(optionsFields[i]) + "</option>";
        //console.log(humanizeDirection(e[1]));
    }
    result += "</select></div>";

    result += "<div class='col-lg-2 nopadding'><select id='dir' class='order form-control'>";
    for (var i = 0; i < orderDirsSet.length; i++) {

        result += "<option value='" + optionsDirs[i] + "'>" + this.humanizeDirection(optionsDirs[i]) + "</option>";
        //console.log(humanizeDirection(e[1]));
    }
    result += "</select></div>";
    return result
}

Render.prototype.generateOrderElementSimple = function (startPack) {
    var result = '';

    var currentdirection;

    currentdirection = startPack.split("_to_")[1];
    result += "<span class='h6'><span class='oDirect btn-link pointer' data-direct='" + currentdirection + "'>" + this.humanizeDirection(currentdirection) + "</span></span>";

    return result
}

Render.prototype.renderPath = function (data, head) {

    //init head
    head = typeof head !== 'undefined' ? head : '1';
    var result = '';
    var pathElements = [];
    var head = {'dataId': head, 'name': ''};
    pathElements.push(head);
    if (data.path)
        for (var i = 0; i < data.path.length; i++) {
            point = {'dataId': data.path[i].id, 'name': data.path[i].name, 'parent_id':data.path[i].parent_id};
            if (head.dataId != point.dataId)
                pathElements.push(point);
        }

    for (var i = 0; i < pathElements.length; i++) {

        if (pathElements[i].dataId == head.dataId)
            result += "<li><a class='browse' data-id='" + pathElements[i].dataId + "'><span class='glyphicon glyphicon-home text-success pointer'></span></a></li>";
        else
            result += "<li><a  class='browse pointer' data-id='" + pathElements[i].dataId + "' data-parent_id='" + pathElements[i].parent_id + "'>" + pathElements[i].name + "</a></li>";
    }

    $("#path").html(result);


}

Render.prototype.isContainer = function (element) {
    return Number(element.container) ? true : false;
}

Render.prototype.humanizeField = function (field) {
    var result = '';
    switch (field) {
        case("id"):
            result = 'Сист. номер';
            break;
        case("name"):
            result = 'Имя';
            break;
        case("created_at"):
            result = 'Дата создания';
            break;
        default:
            result = 'поле не определено';
            break;
    }
    return result;

}

Render.prototype.humanizeDirection = function (dir) {
    var result = '';
    switch (dir) {
        case("asc"):
            result = "по-возрастанию <span class='glyphicon glyphicon-sort-by-alphabet'></span>";
            break;
        case("desc"):
            result = "по-убыванию <span class='glyphicon glyphicon-sort-by-alphabet-alt'></span>";
            break;
        default:
            result = 'порядок не определен';
            break;

    }
    return result;
}

Render.prototype.getCurrentElement = function () {
    return $('#path').find('.browse').last().data('id');
}

Render.prototype.renderSearchedElements = function (data) {

    data = $.parseJSON(data);

    $("#containers-container, #points-container").html("<br>");

    var $this = this;

    if (!data.containers.length) $("#containers-container").append("<h5>Ничего не найдено</h5>");

    $.each(data.containers, function (e, element) {
        $("#containers-container").append($this.renderPlank(element));
    });

    if (!data.points.length) $("#points-container").append("<h5>Ничего не найдено</h5>");

    $.each(data.points, function (e, element) {
        $("#points-container").append($this.renderPlank(element));
    });

}