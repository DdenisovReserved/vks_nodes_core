function RenderEngine() {

}

RenderEngine.prototype.render = function (repository, toContainer, append, withPath, onlypoints) {
    if (repository == null) {
        alert('Ошибка получения данных по точкам ВКС');
        return;
    }
    var compiled = '';
    //console.log(repository);
    this.buildBreadcrumbs(repository.path, '.breadcrumbs');

    if (typeof repository.containers !== 'undefined' && typeof onlypoints === 'undefined') {
        $.each(repository.containers, function (i, container) {
            //console.log(container);
            compiled += "<li class='point-pick'>" +
                "<div class='checkbox'>" +
                "<label>" +
                "<input class='point_checkbox' type='checkbox' id='" + container.id + "' data-type='1' data-parent_id='" + container.parent_id + "' data-path='" + container.path + "' data-name='" + container.name + "'/>" +
                "<span class='glyphicon glyphicon-folder-open text-info'></span>" +
                "&nbsp;&nbsp;" +
                "<span class='btn-group small-text'>" +
                "<button class='btn btn-default btn-sm add-one-child' type='button'>Кто-то</button>" +
                "<button class='btn btn-default btn-sm add-all-childs' type='button'>Все</button>" +
                "<button class='btn btn-default btn-sm show-container-button' type='button'>Выбрать из</button>" +
                "</span>&nbsp" +
                "<span class='point_name show_container' id='" + container.id + "'>" + container.name + "</span>" +
                "</label></div>" +
                "</li>";
        });
    }

    if (typeof repository.points !== 'undefined') {

        $.each(repository.points, function (i, point) {

            if (!point.selectable && typeof onlypoints !== 'undefined') {
                return false;
            }

            var name = typeof withPath !== 'undefined' ? point.path : point.name;
            compiled += "<li class='point-pick";
            if (!point.free) {
                compiled += " disabled";
            }
            if (!point.selectable) {
                compiled += " dropped";
            }
                compiled += "'>" +
                "<div class='checkbox'><label>" +
                "<input class='point_checkbox'";
            if (!point.selectable) {
                compiled += " disabled ";
            }
            compiled += "type='checkbox' data-free='"+point.free+"' data-selectable='"+point.selectable+"' id='" + point.id + "' data-type='0' data-parent_id='" + point.parent_id + "' data-path='" + point.path + "' data-name='" + point.name + "'/>" +
                "<span class='glyphicon glyphicon-camera text-success'></span>" +
                "&nbsp;&nbsp;<span class='point_name canBeMoved'>" + name + "</span>";
                if (!point.free) {
                    compiled += " <span class='glyphicon glyphicon-warning-sign text-danger points_check_where' id='" + point.id + "' title='Точка ВКС занята в другой конференции'></span>"
                }
                compiled += "</label></div></li>";

        });
    }

    //console.log((typeof repository.points == 'undefined' || repository.points.length == 0));

    if (typeof onlypoints === 'undefined'
        &&(typeof repository.points == 'undefined' || repository.points.length == 0)
        && (typeof repository.containers == 'undefined' || repository.containers.length == 0)
    ) {
        compiled = "<div class='text-center empty_text' style='margin-top: 5em;'><i>Список пуст</i></div>";
    }

    if (append) {
        $(toContainer).html($(toContainer).html() + compiled);
    } else {
        $(toContainer).html(compiled);
    }

};

RenderEngine.prototype.renderSelected = function (repositoryFromCookies, toContainer) {


    if (repositoryFromCookies == null) {
        toContainer.html('<i>Ошибка получения данных</i>');
        return;
    }
    if (!$(repositoryFromCookies).length) {
        return;
    }

    var compiled = '';

        $.each(repositoryFromCookies, function (i, element) {
             if (element.type == 1) {
                 compiled += "<li class='point-pick'>" +
                     "<div class='checkbox'>" +
                     "<label>" +
                     "<input class='point_checkbox' type='checkbox' id='" + element.id + "' data-type='1' data-parent_id='" + element.parent_id + "' data-path='" + element.path + "' data-name='" + element.name + "'/>" +
                     "<span class='glyphicon glyphicon-folder-open text-info'></span>&nbsp;&nbsp;" +
                     "<span class='btn-group small-text'><button class='btn btn-sm add-one-child' type='button' disabled>Кто-то</button></span> " +
                     "<span class='point_name show_container' id='" + element.id + "'>" + element.name + "</span>" +
                     "</label></div></li>";
             } else if(element.type == 0)   {
                 var name =  element.path;
                 compiled += "<li class='point-pick'>" +
                     "<div class='checkbox'>" +
                     "<label>" +
                     "<input class='point_checkbox' type='checkbox' id='" + element.id + "' data-type='0' data-parent_id='" + element.parent_id + "' data-path='" + element.path + "' data-name='" + element.name + "'/>" +
                     "<span class='glyphicon glyphicon-camera text-success'></span>&nbsp;&nbsp;" +
                     "<span class='point_name canBeMoved'>" + name + "</span>";
                 if (typeof element.free !== 'undefined' && !element.free) {
                     compiled += " <span class='glyphicon glyphicon-warning-sign text-danger points_check_where' id='" + element.id + "' title='Точка ВКС занята в другой конференции'></span>";
                 }
                 compiled += "</label></div></li>";
             } else if(element.type == 3)   {
                 compiled += "<li class='point-pick inplace'>" +
                     "<div class='checkbox'>" +
                     "<label>" +
                     "<input class='point_checkbox' type='checkbox' id='0' data-parent_id='0'/>" +
                     "<span class='point_name'>С рабочих (IP телефоны, Lynс, CMA Desktop и т.д.):" +
                     " <span class='label label-as-badge label-default'>" +
                     "<span class='inplace_counter'> " + element.counter + "</span>" +
                     "</span></span>" +
                     "</label></div></li>";
             }


        });

    $(toContainer).html(compiled);

    this.countSelected("#selected_points_container", "#selected_counter");


};

RenderEngine.prototype.buildBreadcrumbs = function (pathData, toContainer) {
    var compiled = "<span class='bread btn btn-link' id='1' data-parent_id='1'><span class='glyphicon glyphicon-home text-success'></span></span>";
    if (!pathData) {
        $(toContainer).html(compiled);
    } else {
        $.each(pathData, function (i, element) {
            compiled += "/<span class='bread btn btn-link' id='" + element.id + "' data-parent_id='" + element.parent_id + "'>" + element.name + "</span>";
        });
        $(toContainer).html(compiled);
    }
};

RenderEngine.prototype.sort = function (target, sortDescending) {
    //console.log(sortDescending);
    if (typeof target == "string")
        target = document.getElementById(target);
    // Idiot-proof, remove if you want
    if (!target) {
        return;
    }
    // Get the list items and setup an array for sorting
    var lis = []
    $(target).find("li").each(function (i, elem) {
        if (!$(elem).hasClass('inplace')) {
            lis.push(elem);
        }
    });
    var vals = [];
    // Populate the array
    for (var i = 0, l = lis.length; i < l; i++)
        vals.push(lis[i].innerHTML);


    //buble!
    for (var i = 0, l = vals.length; i < l; i++) {
        var current_name = $(vals[i]).find(".point_name").text();
        for (var j = 0, k = vals.length; j < k; j++) {
            var compared_name = $(vals[j]).find(".point_name").text();
            var tmp = null;
            if (current_name < compared_name) {
                tmp = vals[j];
                vals[j] = vals[i];
                vals[i] = tmp;
            }
        }
    }
    // Sometimes you gotta DESC
    if (sortDescending) {
        vals.reverse()
    }

    // Change the list on the page
    for (var i = 0, l = vals.length; i < l; i++)
        lis[i].innerHTML = vals[i];
};

RenderEngine.prototype.countSelected = function (whereCount, resultToContainer) {
    var result = 0;

    result = $(whereCount).children().length;

    if ($(whereCount).find(".inplace").length) {
        result -= 1;
        result += Number($(whereCount).find(".inplace").find(".inplace_counter").text());
    }
    //console.log(result);
    $(resultToContainer).text(result);

}

RenderEngine.prototype.pullSelected = function (wherecount) {
    var result = [];
    $(wherecount).children().each(function (i, elem) {
        result.push(Number($(elem).find(".point_checkbox").prop("id")));
    })
    //console.log(result);
    return result;
}

RenderEngine.prototype.showParpList = function (toElem) {
    //$(".vks-points-list-display")
    var elements = repo.getFromStorage(cookieName);
    var inplaceCounter = 0;
    var pointsCounter = 0;
    //console.log(elements);
    if(typeof elements === 'undefined') {
        $(toElem).html("<i class='text-danger'>Во время сохранения списка, произошла ошибка</i>");
        return false;
    }

    if ($(elements).length > 0) {
        $.each(elements, function (i, element) {
            if (element.type == 3)
                inplaceCounter = element.counter;
            else
                pointsCounter++;

            $(toElem).html("<ul class='list-unstyled'><li>Количество участников с рабочих мест: <span class='label label-as-badge label-default'>" + inplaceCounter + "</span></li><li>Количество участников из справочника точек: <span class='label label-as-badge label-default'>" + pointsCounter + "</span></li></ul>");

        })
    } else {
        $(toElem).html("<i>Список участников пуст</i>")
    }
}

