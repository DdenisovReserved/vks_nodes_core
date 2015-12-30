$(document).ready(function() {

//    setUpUserChooserPopUpInvoker(".getUsers",".user-selected-container",".owner",false);
//    setUpUserChooserPopUpInvoker(".getAdmin",".admin-selected-container",".approved_by",false);
    $("#date").datepicker('destroy');

    $("#start_time_adm,#end_time_adm").timepicker({
        'minTime':'08:00',
        'maxTime':'20:00',
        'show2400': true,
        'timeFormat': "H:i",
        'step': "15",
        'forceRoundTime': true
    });
    $(document).on("change", "#start_time_adm", function () {
        $("#end_time_adm").timepicker('remove');
        $("#end_time_adm").timepicker({"minTime": $("#start_time_adm").timepicker('getTime'),
            "timeFormat": "H:i",
            'maxTime':'20:00',
            'show2400': true,
            'timeFormat': "H:i",
            'step': "15",
            'forceRoundTime': true
        });
    })

    $(document).on("change","#approved",function(){
        if ($(this).val()==1) {
            $(".createLinks-group").removeClass("hide");
        } else {
            $(".createLinks-group").addClass("hide");
        }
    })
})