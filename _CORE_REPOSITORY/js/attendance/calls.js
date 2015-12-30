$(document).ready(function () {

    $(document).on("click", "#show-branch-tree", function (e) {
        e.preventDefault();
//        $(this).fancybox();
        var fancyContent = '';

        $.ajax({
            type: 'POST',
            cache: false,
            url: appHttpPath + '?r=views/attendance/view',
            data: {'view-type': 'only-containers'},
            success: function (data) {

                fancyContent = data;
                $.fancybox({
                    'width': 960,
                    'height': 960,
                    'autoSize': false,
                    'type': 'iframe',
                    'content': fancyContent

                });

            }
        });
    });

    $(document).on("click", "#add_point", function (e) {
        var findChosen = $("input:checked").attr('data-my_id');
        if (findChosen) {
            location.href = appHttpPath + "?r=views/attendance/add-point&id=" + findChosen + "";
        } else {
            location.href = appHttpPath + "?r=views/attendance/add-point";
        }
    })

    $(document).on("click", "#add_container", function (e) {
        var findChosen = $("input:checked").attr('data-my_id');
        if (findChosen) {
            location.href = appHttpPath + "?r=views/attendance/add-container&id=" + findChosen + "";
        } else {
            location.href = appHttpPath + "?r=views/attendance/add-container";
        }
    })

    $(document).on("click", "#edit_point", function (e) {
        var findChosen = $("input:checked").attr('data-my_id');
        if (findChosen) {
            location.href = appHttpPath + "?r=views/attendance/edit&id=" + findChosen + "";
        } else {
            $(".errors-cnt").html("Выберите точку или контейнер").show();
        }
    })

    $(document).on("click", "#del_point", function (e) {
        var findChosen = $("input:checked").attr('data-my_id');
        if (findChosen) {
            if (confirm("Удалить элемент? если это контейнер все вложенные элементы будут удалены")) {
                location.href = appHttpPath + "?r=views/attendance/delete&id=" + findChosen + "";
            }
        } else {
            $(".errors-cnt").html("Выберите точку или контейнер").show();
        }
    })
    $(document).on("click", ".tr-trigger", function (e) {
        var thisId = $(this).attr("my_id");
        var parentId = $(this).attr("parent_id");
        var myName = $(this).attr("my_name");
        var countRows = $("tr[parent_id='" + thisId + "']");
        if (countRows.length != 0) {
            $("tr.tr-trigger").hide();
            $(countRows).show().removeClass("hidden");
            $("<span class='point-breadcrumb' show='" + parentId + "'>" + myName + "</span>").appendTo(".breadcrumbs");
        }
    })
    $(document).on("click", ".point-breadcrumb", function (e) {

        var thisId = $(this).attr("show");
        var countRows = $("tr[parent_id='" + thisId + "']");
        if (countRows.length != 0) {
            $("tr.tr-trigger").hide();
            $(countRows).show().removeClass("hidden");
            var thisIndex = $(".point-breadcrumb").index(this);
            $(".point-breadcrumb:gt("+thisIndex+")").remove();
            $(this).remove();
        }
    })

//    select-container



})