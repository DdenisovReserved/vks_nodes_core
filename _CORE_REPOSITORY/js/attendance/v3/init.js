$(document).ready(function () {
    repo = new RepositoryPoints();
    render = new RenderEngine();
    currentContainer = 1;
    cookieName = typeof formCookieParticipantsName !== 'undefined' ? formCookieParticipantsName : 'vks_participants_create';

    askCookies = repo.isExistInStorage(cookieName) ? repo.getFromStorage(cookieName) : [];

    allSelectedContainer = [];
    selectedPoints = [];

    //console.log(askCookies);


    $(askCookies).each(function (i, elem) {
        if (typeof elem.id !== 'undefined') //filtrate in place participant record
            selectedPoints.push(elem.id)
    });
    render.renderSelected(askCookies, "#selected_points_container");
    render.render(repo.pullPoints(currentContainer, selectedPoints, dateTimeforCheck), "#points_container");

    $("#points_container, #selected_points_container").sortable({
        connectWith: ".connectedSortable",
        placeholder: "ui-state-highlight",
        revert: true,
//                handle: ".point_name",
        delay: 100,
        receive: function (e, ui) {

            $(e.target).find(".empty_text").remove();

            if ($(e.target).attr('id') == 'selected_points_container') {

                if ($(ui.item).hasClass('dropped')) {
                    //alert("При строгом режиме, выбор занятых точке невозможен, извините");
                    $("#points_container").append($(ui.item));
                }

                if ($(ui.item).text().toLowerCase() == 'список пуст') {
                    $(ui.item).remove();
                }

                if ($(ui.item).hasClass("point-pick")) {
                    $(ui.item).find(".btn-group").html("<button type='button' class='btn btn-sm' disabled>Кто-то</button>");
                    $(ui.item).find("input").attr('checked', false);
                }


                $(ui.item).find(".point_name").text($(ui.item).find(".point_checkbox").data('path'));

                if ($("#points_container > .point-pick > .checkbox > label > .point_checkbox:checked").length > 0) {
                    $(".add_points_button").attr("disabled", false);
                } else {
                    $(".add_points_button").attr("disabled", 'disabled');
                }


            } else {


                $(ui.item).find(".btn-group").html("<button class='btn btn-default btn-sm add-one-child' type='button'>Кто-то</button><button class='btn btn-default btn-sm add-all-childs' type='button'>Все</button><button class='btn btn-default btn-sm show-container-button' type='button'>Выбрать</button>");


                if ($(ui.item).text().toLowerCase() == 'список пуст') {
                    $(ui.item).remove();
                }

                if ($(ui.item).find(".point_checkbox").data("parent_id") != currentContainer) {
                    $(ui.item).remove();
                }

                $(ui.item).find(".point_name").text($(ui.item).find(".point_checkbox").data('name'));


                if ($("#selected_points_container > .point > .checkbox > label > .point_checkbox:checked").length > 0) {
                    $(".remove_points_button").attr("disabled", false);
                } else {
                    $(".remove_points_button").attr("disabled", 'disabled');
                }

                $(ui.item).find("input").attr('checked', false);
            }
            render.countSelected("#selected_points_container", "#selected_counter");
            selectedPoints = render.pullSelected("#selected_points_container");
        }
    }).disableSelection();
});
