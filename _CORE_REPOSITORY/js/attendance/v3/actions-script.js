$(document).ready(function () {

    $(document).on('click', '.sort', function () {
        render.sort($(this).data('sort_target'), Number($(this).data('sort_desc')));
        var changed = Number($(this).data('sort_desc')) === 0 ? '1' : '0';
        $(this).data('sort_desc', changed);

    });

    $(document).on('dblclick', '.show_container', function (e) {
        e.stopPropagation();
        $("#points_container").children().remove();
        currentContainer = $(this).attr("id");
        //console.log(currentContainer);
        render.render(repo.pullPoints(currentContainer, selectedPoints, dateTimeforCheck), "#points_container");
    });

    $(document).on('click', '.show-container-button', function (e) {
        e.stopPropagation();
        $("#points_container").children().remove();
        currentContainer = $(this).parent().parent().find("input").attr("id");
        //console.log(currentContainer);
        render.render(repo.pullPoints(currentContainer, selectedPoints, dateTimeforCheck), "#points_container");
    });

    $(document).on('click', '.bread', function (e) {
        e.stopPropagation();
        $("#points_container").children().remove();
        currentContainer = $(this).attr("id");
        render.render(repo.pullPoints(currentContainer, selectedPoints, dateTimeforCheck), "#points_container");
    });

    $(document).on('click', '.add_points_button', function () {

        $("#selected_points_container").find(".empty_text").remove();

        $("#points_container > .point-pick > .checkbox > label > .point_checkbox:checked").each(function () {
            var element = $(this).attr('checked', false).parent().parent().parent().remove().clone();

            if ($(element).find('.btn-group').length) {
                $(element).find('.btn-group').html("<button type='button' class='btn btn-sm' disabled>Кто-то</button>")
            }
            $(element).find(".point_name").text($(element).find(".point_checkbox").data('path')).end().appendTo("#selected_points_container");
        });
        if ($("#points_container > .point-pick > .checkbox > label > .point_checkbox:checked").length > 0) {
            $(".add_points_button").attr("disabled", false);
        } else {
            $(".add_points_button").attr("disabled", 'disabled');
        }

        render.countSelected("#selected_points_container", "#selected_counter");
        selectedPoints = render.pullSelected("#selected_points_container");

        $(this).parent().find(".all").attr('data-selected', 0).removeClass("btn-danger")
    });

    $(document).on('click', '.remove_points_button', function () {

        $("#points_container").find(".empty_text").remove();

        $("#selected_points_container > .point-pick > .checkbox > label > .point_checkbox:checked").each(function () {
            var element = $(this).attr('checked', false).parent().parent().parent().remove().clone();
            if ($(element).find('.btn-group').length) {
                $(element).find('.btn-group').html("<button class='btn btn-default btn-sm add-one-child' type='button'>Кто-то</button><button class='btn btn-default btn-sm add-all-childs' type='button'>Все</button><button class='btn btn-default btn-sm show-container-button' type='button'>Выбрать из</button>")
            }
//                console.log($(element));
            if ($(element).find(".point_checkbox").data("parent_id") == currentContainer) {
                $(element).find(".point_name").text($(element).find(".point_checkbox").data('name')).end().appendTo("#selected_points_container");
                $(element).appendTo("#points_container");
            } else {
                $(element).remove();
            }
            selectedPoints = render.pullSelected("#selected_points_container");

        });

        if ($("#selected_points_container > .point-pick > .checkbox > label > .point_checkbox:checked").length > 0) {
            $(".remove_points_button").attr("disabled", false);
        } else {
            $(".remove_points_button").attr("disabled", 'disabled');
        }

        render.countSelected("#selected_points_container", "#selected_counter");
        $(this).parent().find(".all").attr('data-selected', 0).removeClass("btn-danger")

    });
    //checkbox click enable batch button
    $(document).on('click', '#points_container > .point-pick > .checkbox > label > .point_checkbox', function () {
        if ($("#points_container > .point-pick > .checkbox > label > .point_checkbox:checked").length > 0) {
            $(".add_points_button").attr("disabled", false);
        } else {
            $(".add_points_button").attr("disabled", 'disabled');
        }
    });
    //checkbox click disable batch button
    $(document).on('click', '#selected_points_container > .point-pick > .checkbox > label > .point_checkbox', function () {


        if ($('#selected_points_container > .point-pick > .checkbox > label > .point_checkbox:checked').length > 0) {
            $(".remove_points_button").attr("disabled", false);
        } else {
            $(".remove_points_button").attr("disabled", 'disabled');
        }
    });

    $(document).on('click', '.add-all-childs', function (e) {
        e.preventDefault();
        $("#selected_points_container").find(".empty_text").remove();

        var id = $(this).parent().parent().parent().find(".point_checkbox").attr("id");

        if (jQuery.inArray(id, allSelectedContainer) >= 0) {
            alert("Ошибка: Вы уже добавляли данный контейнер целиком, повторно добавлять запрещено, извините");
        } else {
            if (confirm("Добавить все точки в этом контейнере?")) {
                allSelectedContainer.push(id);
                var childs = repo.pullPoints(id, selectedPoints, dateTimeforCheck);
                if (childs.points == 0 || typeof childs.points === 'undefined') {
                    alert("Точек внутри контейнера не найдено");
                    return false;
                } else {
                    var enyone = false;
                    $.each(childs.points, function (i, elem) {
                        if (elem.free) {
                            enyone = true
                        }
                    });
                    if (!enyone) {
                        alert("Точек для добавления внутри контейнера не найдено");
                    }

                }

                render.render(childs, '#selected_points_container', true, true, true);
                render.countSelected("#selected_points_container", "#selected_counter");
                selectedPoints = render.pullSelected("#selected_points_container");
            }
        }
    });

    $(document).on('click', '.add-one-child', function () {
        $(this).parent().parent().find("input").click();
        $(".add_points_button").click();
        selectedPoints = render.pullSelected("#selected_points_container");
    });

    $(document).on('click', '#points_add_inplace', function () {
        var getVal = $("#points_add_inplace_field").val();

        getVal = getVal.replace(/[^0-9.]/g, "");
        $("#points_add_inplace_field").val("");

        if (getVal.length && getVal != 0) {
            $("#selected_points_container").find(".empty_text").remove();
            if (!$("#selected_points_container").find(".inplace_counter").length) {
                var inplace = "<li class='point-pick inplace'>" +
                    "<div class='checkbox'><label>" +
                    "<input class='point_checkbox' type='checkbox' id='0' data-parent_id='0'/>" +
                    "<span class='point_name'>С рабочих (IP телефоны, Lynс, CMA Desktop и т.д.): " +
                    "<span class='label label-as-badge label-default'>" +
                    "<span class='inplace_counter'> " + getVal + "</span>" +
                    "</span></span></label></div></li>";

                $("#selected_points_container").html(inplace + $("#selected_points_container").html());
            } else {
                $("#selected_points_container").find(".inplace_counter").html(getVal);
            }

        }
        render.countSelected("#selected_points_container", "#selected_counter");
    });

    $(document).on('click', '.all', function () {

        var target = "#" + $(this).data('all_target');
        if ($(target).children().length > 0) {
            if ($(this).attr('data-selected') == 1) {
                $(target).find("input:enabled").prop("checked", false);
                $(this).attr('data-selected', 0).removeClass('btn-danger');
                if (target == '#points_container') {
                    $(".add_points_button").attr("disabled", true);
                } else {
                    $(".remove_points_button").attr("disabled", true);
                }
            } else {
                $(this).attr('data-selected', 1).addClass("btn-danger");
                $(target).find("input:enabled").prop("checked", true);
                if (target == '#points_container') {
                    $(".add_points_button").attr("disabled", false);
                } else {
                    $(".remove_points_button").attr("disabled", false);
                }
            }
        }

        selectedPoints = render.pullSelected("#selected_points_container");
    });

    $(document).on('click', '.point-back-button', function () {
        var brLen = $(".breadcrumbs").children().length;

        if (brLen > 1) {
            currentContainer = $(".breadcrumbs").children().eq(brLen - 2).attr("id");
            render.render(repo.pullPoints(currentContainer, selectedPoints, dateTimeforCheck), "#points_container");
        }
    });

    $(document).on('click', '#point-search-button', function () {
        var q = $("#point-search-field").val();
        if (q.length > 0) {
            render.render(repo.search(q, selectedPoints), "#points_container", false, true);
            if ($('#point-search-refuse').length == 0) {
                $(".points-buttons-block").append("<button type='button' class='btn btn-warning btn-sm' title='обнулить поиск' id='point-search-refuse'><span class='glyphicon glyphicon-remove-sign'></span></button>")
            }

        }
    });

    $(document).on('click', '#point-search-refuse', function () {
        currentContainer = 1;
        render.render(repo.pullPoints(currentContainer, selectedPoints, dateTimeforCheck), "#points_container");
        $("#point-search-field").val('')
        $(this).remove();

    });


    $(document).on('click', '.points_check_where', function () {
        var repo = new RepositoryPoints();
        repo.busyAt(Number($(this).prop("id")), dateTimeforCheck);

    });

    $(document).on('click', '#point_save_and_exit', function () {
        $(this).text("Сохраняем участников, подождите...").attr("disabled", 'disabled');

        $(".points-warn").remove();
        var result = [];
        var softWarning = false;
        var strictWarning = false;
        var elements = $("#selected_points_container").children();
        if ($(elements).length > 0) {
            $(elements).each(function (i, element) {

                var elem = {};
                var dataElem = $(element).find(".point_checkbox");
                //console.log(String($(dataElem).data("free")) === 'false');
                if (String($(dataElem).data("free")) === 'false') {
                    softWarning = true;
                }

                if (String($(dataElem).data("selectable")) === 'false') {
                    strictWarning = true;
                }
                if (!$(element).hasClass('inplace')) {
                    elem.type = $(dataElem).data('type');
                    elem.id = $(dataElem).prop('id');
                    elem.name = $(dataElem).data('name');
                    elem.parent_id = $(dataElem).data('parent_id');
                    elem.path = $(dataElem).data('path');
                    elem.free = $(dataElem).data('free');

                } else {
                    elem.type = 3;
                    elem.counter = Number($(element).find('.inplace_counter').text());
                }
                result.push(elem);
            });

            if (Boolean(Number(ajaxWrapper("?route=Settings/getOther/attendance_check_enable/true")[0]))
                && Boolean(Number(ajaxWrapper("?route=Settings/getOther/attendance_strict/true")[0]))) {
                $(".add_time").prop("disabled", true);

                $(".time_container").find("input").each(function (i, elem) {
                    $(elem).prop("disabled", true);
                });

                if ($(".time_reblock").length == 0) {
                    $(".add_time").before("<button type='button' class='btn btn-danger pull-right btn-sm time_reblock'>Изменить время</button>");
                }
            }
        }


        if (softWarning) {
            $(".vks-points-list-display").before("<div class='points-warn alert alert-danger'>Предупреждение: Среди выбранных вами участников, есть такие, которые в заданное время уже участвуют в другой ВКС</div>");
        }
        if (strictWarning) {
            $(".vks-points-list-display").before("<div class='points-warn alert alert-danger'>Предупреждение: Администратор установил строгий режим работы, а среди ваших участников есть такие, которые уже участвуют в другой ВКС</div>");
        }
        $(".close").click();
        repo.storeAtStorage(cookieName, result);
        render.showParpList(".vks-points-list-display");


    });

});
