/**
 * logic with buttons on edit-ws.php
 */
$(document).ready(function () {
    /**
     * lock datetime editing
     */

    $(".time-params-edited").find("input").attr("disabled", 'disabled');

    $(document).on("click", "#vks-edit-change-time", function () {
        //if (confirm('Уверены?')) {
        //make date field editable and flush it
        $(".time-params-edited").find("#date-with-support")
            .attr("disabled", false)
            .end()
            .find("input")
            .val('');
        $(".time-params-edited").find("#date-with-support").val("--Выберите новое значение--");

        //check if button exist
        if ($("#vks-restore-time").length == 0) {
            //make cancel button
            var button = "<button type='button' class='btn btn-default btn-sm' id='vks-restore-time'><span class='glyphicon glyphicon-remove-sign text-danger'></span>  Отмена</button>";
            //append it
            $(".edit-cancel-button").append(button);
        }

        //} //confirm end
    }); //click end

    $(document).on("click", "#vks-edit-change-time-manual", function () {

            $(".time-params-edited").find("#date-with-support")
                .attr("disabled", false)
                .end()
                .find("input")
                .attr("disabled", false)
                .val('');
            $("#date-with-support").datepicker("destroy");
            $("#date-with-support").attr("id", 'date-with-support-manual').datepicker();

            //при изменении даты строить график доступности админов
            //выставляем тайм пикеры
            $("#start_time,#end_time").timepicker({
                'minTime': '08:00',
                'maxTime': '20:00',
                'show2400': true,
                'timeFormat': "H:i",
                'step': "15",
                'forceRoundTime': true
            });
            if ($("#vks-restore-time").length == 0) {
                var button = "<button type='button' class='btn btn-default btn-sm' id='vks-restore-time'><span class='glyphicon glyphicon-remove-sign text-danger'></span>  Отмена</button>";
                //append it
                $(".edit-cancel-button").append(button);
            }
        }
    )
    //restore date/time from db
    $(document).on("click", "#vks-restore-time", function () {
        //get data
        if ($("#date-with-support-manual").length > 0) {
            $("#date-with-support-manual").datepicker("destroy");
            $("#date-with-support-manual").attr("id", 'date-with-support').datepicker({
                'minDate': new Date()
            });
        }
        $.get("?route=Vks_store/jsonGetVksById/" + $("form").data('id') + "", function (vks) {
            //parse received data
            vks = $.parseJSON(vks);
            //set it to fields
            $(".time-params-edited").find("#date-with-support").val(vks.date);
            $(".time-params-edited").find("#start_time").val(vks.start_time);
            $(".time-params-edited").find("#end_time").val(vks.end_time);
            //lock fields
            $(".time-params-edited").find("input").attr("disabled", 'disabled');

            $(".grapth-container,.cancel-time-container").remove();

        });
        //destroy button
        $(this).remove();
    }); //click end


})