$(document).ready(function () {

    $(document).on("keyup",'input.sp-ip-phone', function () {
        this.value = this.value.replace(/[^0-9\.]/g,'');
    });

    $(document).on("click", ".phone-inputer,.fp-ip-phone,.sp-ip-phone", function (e) {
        $(".errors-cnt").html("").hide();
    })

    $(document).on("click", "#add-ip-phone", function (e) {
        var ipPhoneFp = $("#ip-phones-container").find("select.fp-ip-phone").val();
        var ipPhoneSp = $("#ip-phones-container").find("input.sp-ip-phone").val();
        if (ipPhoneFp.length == 0 || ipPhoneSp.length == 0) {
            $(".errors-cnt")
                .show()
                .html("<p class='alert alert-warning'>Нужно заполнить оба поля</p>");
            disappear(".errors-cnt", 1000);
            return;
        } else {
            //проверить длину
            if ((ipPhoneFp + ipPhoneSp).length != 5) {
                $(".errors-cnt").html("<p class='alert alert-warning'>Длина телефона должна быть 5 символов</p> ").show()
                disappear(".errors-cnt", 1000);
                return;
            }

            stack.put("ip_phone_" + ipPhoneFp + ipPhoneSp);

            stackViewer.view("#stack-content");
            stackViewer.displayCounter("#stack-counter");
            addPointNotificator("#points-notificator", "Добавлено");

            $("#ip-phones-container").find("input.sp-ip-phone").val("");

        }
    })
    /*
     обработчик кнопки добавления телефонов на форме выбора участников
     */
    $(document).on("click", "#add-phone", function (e) {
        $(".errors-cnt").html("").hide();
        if ($(".phone-inputer").attr("disabled") == "disabled")
            return;

        var value1 = $(".phone-inputer").val();

        if (value1.length != 0) {

            stack.put("phone_"+value1);
            stackViewer.view("#stack-content");
            stackViewer.displayCounter("#stack-counter");
            $(".phone-inputer").val('');
            addPointNotificator("#points-notificator", "Добавлено");

        } else {
            $(".errors-cnt").html("<div class='alert alert-danger'>Номер должен точно соответсвовать шаблону</div> ").show();
            disappear(".errors-cnt",1000);
        }
    }); //click end






    $(document).on("click",".phone-tmpl-select-me",function(e) {
        $(".errors-cnt").html("").hide();
        $(".phone-field-hidden").removeClass("hidden");
        addActive($(this));
        var myTpl = $(this).find('span').data('phone_tpl');
        $(".phone-inputer").mask(myTpl);
        $(".phone-inputer").attr("disabled",false);
        $(".phone-inputer").focus();
    }); //click end


})

function flushcontainer(container) {
    $(container).find(".point").remove();
}
function addActive($this) {
    $($this).parent().find(".active").removeClass('active').removeClass('btn-info');
    $($this).addClass('active btn-info');

}
