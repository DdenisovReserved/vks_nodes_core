$(document).ready(function () {

    $(document).on("keyup",'input.sp-ip-phone', function () {
        this.value = this.value.replace(/[^0-9\.]/g,'');
    });

    $(document).on("click", ".phone-inputer,.fp-ip-phone,.sp-ip-phone", function (e) {
        $(".errors-cnt-simple").html("").hide();
    })

    $(document).on("click", "#add-ip-phone", function (e) {
        var ipPhoneFp = $("#ip-phones-container").find("select.fp-ip-phone").val();
        var ipPhoneSp = $("#ip-phones-container").find("input.sp-ip-phone").val();
        if (ipPhoneFp.length == 0 || ipPhoneSp.length == 0) {
            $(".errors-cnt-simple")
                .html("<p class='alert alert-warning'>Нужно заполнить оба поля</p>")
                .show();
            disappear(".errors-cnt-simple", 1000);
            return;
        } else {
            //проверить длину
            if ((ipPhoneFp + ipPhoneSp).length != 5) {
                $(".errors-cnt-simple").html("<p class='alert alert-warning'>Длина телефона должна быть 5 символов</p> ").show()
                disappear(".errors-cnt-simple", 1000);
                return;
            }

            var obj = {
                'id': "ip_phone_" + ipPhoneFp + ipPhoneSp,
                'pathString': "ip_phone_" + ipPhoneFp + ipPhoneSp
            };
            var t = stack.putVirtual(obj);
            if (t) {
                stackViewer.viewFromVirtual("#stack-content", t);
            }

            $("#ip-phones-container").find("input.sp-ip-phone").val("");

        }
    })
    /*
     обработчик кнопки добавления телефонов на форме выбора участников
     */
    $(document).on("click", "#add-phone", function (e) {
        $(".errors-cnt-simple").html("").hide();
        if ($(".phone-inputer").attr("disabled") == "disabled")
            return;

        var value1 = $(".phone-inputer").val();

        if (value1.length != 0) {

            var obj = {
                'id': "phone_" + value1,
                'pathString': "phone_" + value1
            };
            var t = stack.putVirtual(obj);
            if (t) {
                stackViewer.viewFromVirtual("#stack-content", t);
            }

            $(".phone-inputer").val('');
            addPointNotificator("#points-notificator", "Добавлено");

        } else {
            $(".errors-cnt-simple").html("<div class='alert alert-danger'>Номер должен точно соответсвовать шаблону</div> ").show();
            disappear(".errors-cnt-simple",1000);
        }
    }); //click end






    $(document).on("click",".phone-tmpl-select-me",function(e) {
        $(".errors-cnt-simple").html("").hide();
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
