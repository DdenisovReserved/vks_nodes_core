$(document).ready(function () {
    //deploy existing verificators
    $("#selected").find('option').each(function () {
        $("#user-tokens")
            .append("<li data-uid='" + $(this).attr('value') + "'>" + $(this).text() + " <span class='unselect-user glyphicon glyphicon-remove'></span></li>");
    });

    //init query stack
    var stack = 0;
    $(document).on("keyup", "#name", function () {
        $("#founded").children().remove();
        if (stack > 0) return;
        var $this = $(this);
        if ($this.val().length > 2) {
            var timer = setTimeout(
                function () {

                    $.ajax({
                        beforeSend: function () {
                            $("#founded").append("<span class='roller'><h4>Идет поиск..</h4></span>");
                        },
                        type: 'POST',
                        cache: false,
                        url: "?route=User/find/" + $this.val(),
                        success: function (data) {


                            $("#founded").children().remove();
                            data = $.parseJSON(data);
                            console.log(data.length);
                            if (data.length > 0) {
                                //create element
                                $(data).each(function (i, element) {
                                    $("#founded").append("<li class='select-user' data-uid='" + element.id + "'>" + element.login + "</li>");
                                });
                            } else {
                                $("#founded").append("<span class=''><h4>Ничего не найдено</h4></span>");
                            }

                        },
                        complete: function () {
                            $(".roller").remove();
                        }
                    });
                    stack--;
                }, 200);
            stack++;

        } else {
            $("#founded").append("<span class='roller'><h4>Минимум нужно ввести 3 символа</h4></span>");
        }
    })

    $(document).on("click", ".select-user", function () {

        if ($("#selected").find("[value='" + $(this).data('uid') + "']").length == 0) {
            $("<option value='" + $(this).data('uid') + "'>" + $(this).text() + "</option>").attr('selected', true).appendTo("#selected");
            $("#user-tokens")
                .append("<li data-uid='" + $(this).data('uid') + "'>" + $(this).text() + " <span class='unselect-user glyphicon glyphicon-remove'></span></li>");
        }

        $(this).remove();

    })
    $(document).on("click", ".unselect-user", function (e) {

        $("#selected").find("[value='" + $(this).parent().data('uid') + "']").remove();
        $(this).parent().remove();
    })
}); //main end