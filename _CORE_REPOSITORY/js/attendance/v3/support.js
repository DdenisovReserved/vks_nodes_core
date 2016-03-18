$(document).ready(function(){
    render = new RenderEngine();

    $(document).on('click', '.time_reblock', function () {
        cookieName = typeof formCookieParticipantsName !== 'undefined' ? formCookieParticipantsName : 'vks_participants_create';

        if (confirm("При изменении времени, список участников ВКС будет обнулен (нужно перевыбрать участников), уверены что хотите изменить время?")) {
            $(this).remove();

            if (Boolean(Number(ajaxWrapper("?route=Settings/getOther/attendance_check_enable/true")[0]))
                && Boolean(Number(ajaxWrapper("?route=Settings/getOther/attendance_strict/true")[0]))) {
                $(".add_time").prop("disabled", false);
                $(".time_container").find("input").each(function (i, elem) {
                    $(elem).prop("disabled", false);
                });
                Cookies.remove(cookieName);
                render.showParpList(".vks-points-list-display");
            }
        }
    });
});
