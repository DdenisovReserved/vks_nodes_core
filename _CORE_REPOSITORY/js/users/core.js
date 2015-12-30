$(document).ready(function () {
    $(document).on("click", ".hold-user-vks", function (e) {
        e.preventDefault();
        if (confirm("Заявка будет отозавана с согласования, позже вы сможете сделать с неё копию, или удалить совсем")) {
            var obj = {
                'req': 'vks_store_controller/holdVksByUser',
                'data': $(this).data("vks_id")
            };
            //посылаем ajax на контроллер
            $.post("?r=controllers/ajax_router_controller", obj, function (e) {
                location.reload();
            });
        }
    })

    $(document).on("click", ".remove-user-vks", function (e) {
        e.preventDefault();
        if (confirm("Заявка будет удалена, она пропадет из этого списка")) {
            var obj = {
                'req': 'vks_store_controller/removeVksByUser',
                'data': $(this).data("vks_id")
            };
            //посылаем ajax на контроллер
            $.post("?r=controllers/ajax_router_controller", obj, function (e) {
                //            console.log($.parseJSON(e));
                location.reload();
            });
        }
    })
    $(document).on("change", ".sort-select", function (e) {
        var loc = $(this).find("option:selected").attr("href");
        if (loc.length > 0)
            location.href = loc;

    })
    $(document).on("click", "#patternField", function (e) {
        $(".resultHB").remove();
    })
    /**
     * find user by patter on users managment page
     */
    $(document).on("click", "#findUserByPattern", function (e) {
        $(".resultHB").remove();

        $.post(
            "?r=controllers/ajax_router_controller",
            {
                "req": "users_controller/searchUserByPatternJSON",
                "data": $("#patternField").val()
            },
            function (responce) {
                responce = $.parseJSON(responce)
                if (!responce.status) {

                    $("#patternField").after("<span class='alert alert-danger help-block resultHB'>" + responce.responce + "</span>")
                } else {

                    if ($("#clearSearch").length == 0)
                        $(".zebra").before("<button id='clearSearch' class='btn btn-default'>Очистить результат</button>");

                    $(".pagination").hide();

                    $(".zebra").find("td").addClass("hiddenTR").hide();
                    $.each(responce.responce, function (index, value) {

                        switch (value.role) {
                            case '1':
                                value.role = "Администратор";
                                break;
                            case '2':
                                value.role = "Пользователь";
                                break;
                            case '3':
                                value.role = "ВИП";
                                break;
                        }

                        value.status = value.status == 1 ?
                            "<span class='glyphicon glyphicon-ok-sign text-success'></span> " :
                            "<span class='glyphicon glyphicon-remove-sign text-danger'></span> ";


                        $("<tr class='searched'>" +
                            "<td>" + value.id + "</td>" +
                            "<td>" + value.login + "</td>" +
                            "<td>" + value.role + "</td>" +
                            "<td>" + value.fio + "</td>" +
                            "<td>" + value.phone + "</td>" +
                            "<td>" + value.status + "</td>" +
//                            "<td>"+value.last_visit+"</td>" +
                            "<td><span class='glyphicon glyphicon-thumbs-up'></span> make vip</td>" +
                            "</tr>")
                            .appendTo(".zebra");
                    });

                }
            })
    }) //click
    $(document).on("click", "#clearSearch", function (e) {
        $("#patternField").val("");
        $(this).remove();
        $(".zebra").find("tr.searched").remove();
        $(".zebra").find("td.hiddenTR").show();
        $(".pagination").show();

    }) //click
    $(document).on("click", "#changeRole", function (e) {
        var thisText = $(this).text();
        if (confirm("Точно изменить роль на " + thisText+ " страница будет перезагружена")) {
            var  data = {
                'uId': $(this).attr("userid"),
                'uRole': $(this).attr("role")
            };
            $.post(
                "?r=controllers/ajax_router_controller",
                {
                    "req": "users_controller/userChangeRoleJSON",
                    "data": data
                },
                function(){
                   document.location.reload();
                }) //post
        }




    }) //click



})
