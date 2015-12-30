$(document).ready(function() {

    $(document).on("click",".getAdmin",function() {
        setUpUserChooserPopUpInvoker(".admin-selected-container",".approved_by",false);
    })
    $(document).on("click",".getUsers",function() {
        setUpUserChooserPopUpInvoker(".user-selected-container",".owner",false);
    })

//    setUpUserChooserPopUpInvoker(".getAdmin",".admin-selected-container",".approved_by",false);

//    $("[name='annihilateLinks']").bootstrapSwitch({
//        'size': 'small',
//        'onText': 'Да',
//        'offText': 'Нет'
//    });
    if($('#vks_method').val()==1) {
        $('#submit_admin').parent().show();
    }





    $(document).on('click','#checkFreeAdminsInTime',function(){
        var qObj = {'checkFreeAdmins':true};
//        qObj.date = $("#date").val();
        qObj.start = $("#date").val()+" "+$("#start_time").val();
        qObj.end = $("#date").val()+" "+$("#end_time").val();
        $.ajax({
            beforeSend: function () {
                $(".checkAdminResult").removeClass('hidden').find(".alert").html("<p>Загрузка...</p>");
            },
            type: 'POST',
            cache: false,
            url: "?r=controllers/controller_ajax",
            data: qObj,
            success: function (data) {
                //если расписание работы админов заполнено, рендерим!, иначе не рендерим и дальше форму не показываем
                if (data != 'null') {

                    if (data == 'true') {

                        $(".checkAdminResult").removeClass('hidden').html("<div class='alert alert-success '>Есть свободные админы</div>");
                    } else {
                        $(".checkAdminResult").removeClass('hidden').html("<div class='alert alert-warning '>Нет свободных админов <button id='checkFreeAdmins' type='button' class='btn  btn-info btn-sm'>Показать график </button></div>");
//                       $(".here-render-timeLine").append("<button type='button' class='btn btn-default resetAll'>Отмена</button>");

                }

                } else {
                    $(".checkAdminResult").removeClass('hidden').html("<div class='alert alert-danger '>ОШИБКА: Расписание работы дежурной смены на запрашиваемую дату не заполнено, анализ не возможен</div>");


                }
            }

        });
    }) //change end

    $(document).on('change','#vks_method',function(){
        if($(this).val()==1) {
            $('#submit_admin').parent().show();
        } else {
            $('#submit_admin').val('').parent().hide();
        }
    }) //change end



    /**
     * Created by tomaroviv on 29.09.14.
     */
    $(document).ready(function () {
        hideErrorMark("*.error-mark");
        $(document).on("keyup", "#date, #start_time, #end_time", function () {
            $(this).val("")
        });

        $("#date").datepicker();

        $("#start_time, #end_time").timepicker({"timeFormat":'HH:mm',
            'minTime':'08:00',
        'maxTime':'20:00',
            'show2400': true,
            'timeFormat': "H:i",
            'step': "15",
            'forceRoundTime': true
        });

        //check fields
        $(document).on("change","#method",function(e) {

            var getVAl = $(this).val();
            if (getVAl==1) {
                e.preventDefault();
                if(confirm("Изменение метода проведения ВКС откатит её в статус - 'на согласовании', её придется заново согласовать, уверены что хотите изменить это поле?")) {
                    $(this).val(1);
                    $("#approved").val(0).attr("disabled","disabled");
                } else {
                    $(this).val(0);
                }
            }

        });



    }); // ready main end


}); // main end