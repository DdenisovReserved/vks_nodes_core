$(document).ready(function () {

    var modal = new Modal();

    $(document).on("change", ".template-select", function () {
        var $this = $(this);
        var thisNum = $this.parent().parent().attr("data-code-num");
        if ($this.val().length)
            $.ajax({
                beforeSend: function () {
                },
                type: 'POST',
                cache: false,
                url: "?route=Settings/getCodeDelivery/build/" + $this.val(),
                success: function (data) {
                    data = $.parseJSON(data);

                    $this.parent().parent().find('.prefix,.postfix,.serve,.tip').html('');

                    $this.parent().parent().find('.prefix').append("<select name='code[" + thisNum + "][prefix]'><option>" + data.core + "</option></select>");

                    $this.parent().parent().find('.postfix').append("<select name='code[" + thisNum + "][postfix]'></select>");

                    $this.parent().parent().find('.tip').append("<textarea name='code[" + thisNum + "][tip]'  maxlength='255'>" + data.name + "</textarea>");


                    $this.parent().parent().find('.serve').append("<button type='button' class='btn btn-info btn-sm check'><span class='glyphicon glyphicon-search'></span></button>");

                    $(data.tail_codes).each(function (i, postcode) {

                        $("*[name='code[" + thisNum + "][postfix]']").append("<option>" + postcode + "</option>");
                    })

                },
                complete: function () {

                }
            });
        else
            $this.parent().parent().find('.prefix,.postfix,.serve,.tip').html('');
    })

    $(document).on("click", "*[name='no-codes']", function () {
        if (Number($(this).attr('data-checked')) == 0) {
            $(".code-table").find("select, input,textarea").each(function () {
                $(this).attr("disabled", 'disabled');
            })
            $(this).attr('data-checked', 1);
        } else {
            $(".code-table").find("select, input,textarea").each(function () {
                $(this).attr("disabled", false);

            })
            $(this).attr('data-checked', 0);
        }

    })

    $(document).on("click", "*[name='add']", function () {
        $(".emptyly").remove();
        if ($('.code-table').find("tr[data-code-num]").length < 5) {
            var $template = '<tr data-code-num="1">';
            $template += '<td class="clear"></td>';
            $template += '<td class="template">';
            $template += '<select name="template-select" class="template-select">';
            $template += '<option value="">--Выберите шаблон--</option>';
            //console.log($template);
            $.ajax({
                beforeSend: function () {

                },
                type: 'POST',
                cache: false,
                url: "?route=Settings/getCodeDelivery/build",
                async: false,
                success: function (data) {
                    data = $.parseJSON(data);
                    $(data).each(function (i, element) {
                        //console.log(element.uid);
                        $template += '<option value="' + element.uid + '">' + element.name + '</option>';
                    });
                    //console.log($template);
                },
                complete: function () {

                }
            });
            //console.log($template);

            $template += '</select></td>';
            $template += '<td class="prefix"></td>';
            $template += '<td class="postfix"></td>';
            $template += '<td class="tip"></td>';
            $template += '<td class="serve"></td>';
            $template += '</tr>';
            $($template).find('.prefix,.postfix,.serve,.tip').html('').end()
                .attr('data-code-num', Number($('.code-table').data('rows')) + 1)
                .appendTo('.code-table')
                .find(".clear").html('')
                .append("<span class='glyphicon glyphicon-remove-circle remove text-danger'></span>");

            $('.code-table').data('rows', Number($('.code-table').data('rows')) + 1);

        } else {
            alert('Кодов слишком много, максимум - 5');
        }
    })


    $(document).on("click", "*[name='manual']", function () {
        $(".emptyly").remove();
        if ($('.code-table').find("tr[data-code-num]").length < 5) {
            var num = Number($('.code-table').data('rows')) + 1;
            var $template = '<tr data-code-num="' + num + '">';
            $template += '<td class="clear"></td>';
            $template += '<td class="template">';
            $template += '<input value="Код без шаблона" disabled/></td>';
            $template += '<td class="prefix"><select name="code[' + num + '][prefix]">';
            $.ajax({
                beforeSend: function () {

                },
                type: 'POST',
                cache: false,
                url: "?route=Settings/getCodesCores",
                async: false,
                success: function (data) {
                    data = $.parseJSON(data);
                    $(data).each(function (i, element) {
                        $template += '<option>' + element + '</option>';
                    });
                },
                complete: function () {
                }
            });

            $template += '</select></td>';
            $template += '<td class="postfix"><input name="code[' + num + '][postfix]" maxlength="4" style="width:45px;"/></td>';
            $template += '<td class="tip"><textarea name="code[' + num + '][tip]"  maxlength="255"></textarea></td>';
            $template += '<td class="serve"><button type="button" class="btn btn-info btn-sm check"><span class="glyphicon glyphicon-search"></span></button></td>';
            $template += '</tr>';

            $($template)
                .appendTo('.code-table')
                .find(".clear").html('')
                .append("<span class='glyphicon glyphicon-remove-circle remove text-danger'></span>");
            $('.code-table').data('rows', num);
        } else {
            alert('Кодов слишком много, максимум - 5');
        }
    })


    $(document).on("click", ".remove", function () {
        //$(".here-code").html('');
        $(this).parent().parent().remove();

    })

    $(document).on("click", "*[name='mCode']", function () {
        if ($(this).attr('data-init') == 0) {
            $(this).val('').attr('data-init', 1);
        }
    })

    $(document).on("click", ".check", function () {
        var codeVal = $(this).parent().parent().find('.postfix').find('select').length ? $(this).parent().parent().find('.postfix').find('select').val() : $(this).parent().parent().find('.postfix').find('input').val();

        if (codeVal.length == 0) {
            alert('Нельзя проверить пустое значение кода');
            return;
        }

        $.get('?route=Vks/apiGet/' + vksId, function (data) {
            data = $.parseJSON(data);
            $.get('?route=ConnectionCode/isCodeInUse/' + codeVal + "/" + data.start_date_time + "/" + data.end_date_time, function (data) {
                data = $.parseJSON(data);
                if (data) {
                    data = $.parseJSON(data)
                    var content = '<div class="text-center text-danger"><h1>Код ' + codeVal + ' используется в вкс <a href="?route=Vks/show/' + data.id + '" class="show-as-modal" target="_blank" data-id="' + data.id + '">#' + data.id + '</a></h1>' +
                        '<h3>Тема: ' + data.title + '</h3><h3>Время: ' + data.humanized.startTime + ' - ' + data.humanized.endTime + '</h3></div>';
                } else {
                    var content = '<div class="text-center text-success"><h1>Код ' + codeVal + ' свободен </h1></div>';
                }

                modal.generateAndPull('Проверка кода подключения ' + codeVal, content);
            });
        });
    })


    $(document).on("click", ".submit", function () {
        $(".error-mark").removeClass("error-mark");
        $(".errors-cnt").hide();
        var errors = 0;
        //no codes?
        if ($("*[name='no-codes']").attr('data-checked') == 1) {
            $(".code-table").children().remove();
        } else {

            if (!$(".code-table").find("tr[data-code-num]").length) {
                $(".errors-cnt").html("Нужно указать код подключения, но можно согласовать и без них (выбрав соответсвующую опцию)").show();
                errors++;
            }

            $('.template-select').each(function () {
                if (!$(this).val().length) {
                    $(this).addClass('error-mark');
                    $(".errors-cnt").html("Все шаблоны должны быть использованы или удалены из формы").show();
                    errors++;
                }
            });

            $('.postfix').each(function () {
                if ($(this).find('input').length)
                    if (!$(this).find('input').val().length) {
                        $(this).addClass('error-mark');
                        $(".errors-cnt").html("Все шаблоны должны быть использованы или удалены из формы").show();
                        errors++;
                    }
            })

        }

        if (!errors) {
            $("form").append("<input class='hidden' name='status' value='1'/>");
            $("form").submit();
        }


    });

})