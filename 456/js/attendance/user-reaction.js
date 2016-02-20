$(document).ready(function () {

    $(document).on("click", ".remove-userPlank", function (e) {
        var makeStr = "";
        $(this).parent().parent().parent().find('input').attr("value", "");
        $(this).parent().remove();
    }) //click end


    if ($("#verifiable").prop("checked")) {
        $(".checked_by-block").show()
        $("#container").attr("disabled", "disabled");
    } else {
        $(".checked_by-block").hide();
    }
    if ($("#container").prop("checked")) {
        $("#verifiable").attr("disabled", "disabled");
    } else {
        $("#verifiable").attr("disabled", false);
    }


    $(document).on("click", "#root", function (e) {
        $("#showTree,#show-me-who-parent").toggle();
        $("#parent_id").attr('value', "0");

    });
    /*
     Обработчик внопки добавить к выбранным
     */
    $(document).on("click", ".elem-cam", function (e) {
        var makeName = makeFullName($(this), " - ");

        //проверит существование плашки в контейнере
        if (!isExistPoint(makeName, $(".selected-points")))
        //переместить плашку в контейнер
            $("<div></div>")
                .addClass('point')
                .html(makeName + "<sup> <span class='del-point'>[X]</span></sup> ")
                .attr("data-my_name", makeName)
                .attr("data-my_id", $(this).attr("data-my_id"))
                .appendTo(".selected-points");
    });
    //конец обработчика

    /*
     Обработчик кнопки добавить (для одинарного выбора при выборе места проведения ВКС)
     */
    $(document).on("click", ".elem-cam", function (e) {
        //сначала очистить правую часть, там не может быть более 1 элемента
        $(".selected-point-vks_location").find(".point").remove();
        var makeName = makeFullName($(this), " - ");
        $("<div></div>")
            .addClass('point')
            .html(makeName + "<sup> <span class='del-point'>[X]</span></sup> ")
            .attr("data-my_name", makeName)
            .attr("data-my_id", $(this).attr("data-my_id"))
            .appendTo(".selected-point-vks_location");
    });
    //конец обработчика
    //функция построения полного имени плашки, на выходе строка
    function makeFullName(elem, delim) {
        //массив для построения имени плашки
        var makeName = new Array();
        //пускаем рекурсивную функцию
        getAllParents($(elem), makeName);
        //имя кликнутого элемента вставляем в конец массив
        makeName.push($(elem).attr('data-my_name'));
        //разобрать массив
        makeName = makeName.join(delim);
        return makeName;
    }

    //функция по поиску всех элементов родителей плашки
    function getAllParents(selectedElem, fillArr) {
        var getParent = $(selectedElem).attr('data-parent_id')
        if (getParent != 1) {
            getAllParents($(".elem[data-my_id='" + getParent + "']"), fillArr);
            fillArr.push($(".elem[data-my_id='" + getParent + "']").attr('data-my_name'));
//            console.log(fillStr);
        }
    } //function end

    function isExistPoint(pointName, inContainer) {
        //получить имя точки
        if (!pointName)
            return;
        //поискать такую точку в контейнере
        var findPoint = $(inContainer).find("*[data-my_name='" + pointName + "']");
        //разбираем поиск
        if (findPoint.length == 0) {
            return false
        } else {
            return true;
        }
    } //function end


    $(document).on("click", "#three-expand", function (e) {
        $(".closed-image").click();
    });

    $(document).on("click", ".del-point", function (e) {
        $(this).parent().parent().remove();
    });
    $(document).on("click", ".checked_by_show", function (e) {
        var fancyContent = '';
        $.ajax({
            type: 'POST',
            cache: false,
            url: appHttpPath + '?r=views/settings/users/view_group',
            data: {'group': 3},
            success: function (data) {
                fancyContent = data;
                $.fancybox({
                    'width': 960,
                    'height': 960,
                    'autoSize': false,
                    'content': fancyContent
                });

            }
        });
    }); // click end

    $(document).on("click", " #select-parent", function (e) {
        e.stopPropagation();
        var findChosen = $(this).parent().find("input:checked");
        if (findChosen.length == 0) {
            $(".errors-cnt").html("Нужно выбрать контейнер").show();
        } else {
            var userData = "<div class='checked-by-userData' data-container-id='" + findChosen.attr('data-my_id') + "' >" + findChosen.attr('data-my_name') + "</div>";
            $(".show-branch-container").html("");
            $(userData).appendTo(".show-branch-container");
            $(".show-branch-container-input").attr("value", findChosen.attr('data-my_id'))
            $(".fancybox-overlay").remove();
        }
    });


    $(document).on("click", "#verifiable", function (e) {
        if ($(this).prop("checked")) {
            $(".checked_by-block").slideDown();
            $(".show-inner-points").slideDown();
            $("#container").attr("checked", false).attr("disabled", "disabled");

        } else {
            $(".checked_by-block").slideUp();
            $("#container").attr("disabled", false);
        }
    })
    $(document).on("click", "#container", function (e) {
        if ($(this).prop("checked")) {
            $("#verifiable").attr("checked", false).attr("disabled", "disabled");

        } else {

            $("#verifiable").attr("disabled", false);
        }
    })


    $(document).on("click", ".findUser-button", function (e) {
        $(".findUser-result").html("");
        var getText = $('.findUser-text').val();
//        console.log(getText);
        var ajaxDataObject = {"requestString": getText};
        $.ajax({
            beforeSend: function () {
                $(".findUser-result").html("Идет поиск, ожидайте..")
            },
            type: 'POST',
            cache: false,
            url: appHttpPath + '?r=controllers/ajax_router_controller',
            data: {'req': "users_controller/findUser", 'data': ajaxDataObject},
            success: function (data) {
                data = $.parseJSON(data);
                if (typeof(data) == 'string')
                    $(".findUser-result").html(data);
                else {
                    var castStr = "<table class='table table-bordered'>";
                    $.each(data, function (i, item) {
                        castStr += "<tr>";
                        castStr += "<td style='text-align: center;'><button type='button' class='btn btn-sm btn-default checked-by-add-user-button' data-user-id='" + item.id + "' data-user-login='" + item.login + "'>Добавить</button></td>";
                        castStr += "<td>" + item.login + "</td>";
                        castStr += "<td>" + item.fio + "</td>";
                        castStr += "<td>" + item.phone + "</td>";
                        castStr += "<td data-checked-by-add-user-add-result='" + item.id + "'>&nbsp&nbsp</td>";
                    })
                    $(".findUser-result").html(castStr);
                }
            }


        });
    }) //click end
    $(document).on("click", ".findUser-close-button", function (e) {
        $(".fancybox-overlay").click();
    }) //click end
    $(document).on("click", ".checked-by-add-user-button", function (e) {

        var userData = "<div class='checked-by-userData' data-user-id='"
            + $(this).attr("data-user-id") + "' >"
            + $(this).attr("data-user-login") + ""
            + "<span class='remove-userPlank' > [X]</span></div>";

        var makeStr = $(this).attr("data-user-id") + "|";

        $(".checked_by_container").find(".checked-by-userData").each(function () {
            makeStr += $(this).attr("data-user-id") + "|";
        })


        $(userData).appendTo(".checked_by_container");

        $("*[data-checked-by-add-user-add-result='" + $(this).attr("data-user-id") + "']").html("ок")

        $(".checked_by_container-input").attr("value", makeStr);

    }) //click end
    //click destroy on edit page
    $(document).on("click", ".remove-userPlank", function (e) {
        var makeStr = "";
        $(this).parent().remove();
        $(".checked_by_container").find(".checked-by-userData").each(function () {
            makeStr += $(this).attr("data-user-id") + "|";
        })
        $(".checked_by_container-input").attr("value", makeStr);

    }) //click end

    $(document).on("click", ".closed-trigger", function (e) {
//          alert("t");
        e.stopPropagation();
        var $this = $(this);
        var $this_id = $this.data('my_id');
        //проверить закрыт или открыт элемент
//        if ($this.attr('data-closed')==1 ) { //если закрыт, найти детей и показать
        $(".elem").hide();
        //если из корневого
        if ($this.data('parent_id') == 1) {
            //make bread container
            if ($(".breadcrumb-container").length == 0)
                $("<span class='breadcrumb-container'>Навигатор: </span> ").prependTo(".tree-container");
//                $this.clone().html("Главная").addClass("go-back-to-tree").appendTo(".breadcrumb-container").removeClass("closed-trigger");
            $this.clone().addClass("go-back-to-tree").appendTo(".breadcrumb-container").removeClass("closed-trigger");
        } else {
            $this.clone().addClass("go-back-to-tree").appendTo(".breadcrumb-container").removeClass("closed-trigger");

        }
        $(".elem[data-parent_id='" + $this_id + "']").show();


        //установить элемент открыт
//            $this.attr("data-closed",0).attr("src","images/icons/minus.png");
//        } else {

//            $this.attr("data-closed",1).attr("src","images/icons/plus.png");
//            $this.parent().parent().find(".elem").not($this.parent()).hide().attr("data-closed",1);
//        }
    });

    $(document).on("click", ".go-back-to-tree", function (e) {
//          alert("t");
        e.stopPropagation();
        var $this = $(this);
        var getParent = $this.data('parent_id');
        $(".go-back-to-tree:gt(" + $this.index() + ")").remove();
//        console.log($("*[data-parent_id='"+getParent+"']"));
        $(".elem").hide();
        $("*[data-parent_id='" + getParent + "']").show();
//        console.log($this);
        $this.remove();
        if ($this.data('parent_id') == 0) {
            $(".breadcrumb-container").remove();
        }

    });


    $(document).on("click", ".add-inner-att", function (e) {
        var fancyContent = '';

        $.ajax({
            type: 'GET',
            cache: false,
            url: appHttpPath + '?r=views/attendance/attendanceinner/_add',
            data: {'id': id},
            success: function (data) {
                fancyContent = data;
                $.fancybox({
                    'width': 960,
                    'height': 960,
                    'autoSize': false,
                    'content': data
                });
            }
        });
    }); // click end
    $(document).on("click", ".inner-add", function (e) {
        var fancyContent = '';

        $.ajax({
            type: 'post',
            cache: false,
            url: appHttpPath + '?r=views/controllers/ajax_router_controller',
            data: {'id': id},
            success: function (data) {
                fancyContent = data;
                $.fancybox({
                    'width': 960,
                    'height': 960,
                    'autoSize': false,
                    'content': data
                });
            }
        });
    }); // click end


}) //main end

//нажав на этот элемент будет выскакивать попап с поиском юзеров
function setUpUserChooserPopUpInvoker(containerEl, inputElStorage, multiple) {

    $.ajax({
        type: 'POST',
        cache: false,
        url: appHttpPath + '?r=views/settings/users/view_group',
        data: {'group': 3},
        success: function (data) {

            $.fancybox({
                'width': 960,
                'height': 960,
                'autoSize': false,
                'content': data
            });

            $(document).off("click", ".checked-by-add-user-button");
            $(document).on("click", ".checked-by-add-user-button", function (e) {
//                    console.log(containerEl);
                var userData = "<div class='checked-by-userData' data-user-id='"
                    + $(this).attr("data-user-id") + "' >"
                    + $(this).attr("data-user-login") + ""
                    + "<span class='remove-userPlank' > [X]</span></div>";
                var makeStr = $(this).attr("data-user-id");
                if (multiple) {
                    $(containerEl).find(".checked-by-userData").each(function () {
                        makeStr += $(this).attr("data-user-id") + "|";
                    })
                } else {
                    $(containerEl).html("");
                }
                $(userData).appendTo(containerEl);
                $("*[data-checked-by-add-user-add-result='" + $(this).attr("data-user-id") + "']").html("ок")
                $(inputElStorage).attr("value", makeStr);
                if (!multiple) {
                    $(".findUser-close-button").click();
                }
            });
        } //success end
    }); //ajax end


} //func end

