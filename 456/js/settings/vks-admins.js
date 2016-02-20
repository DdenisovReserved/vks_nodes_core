$(document).ready(function() {
    setTimepickers()

    $(document).on("keyup",("*[name*='start_wt'],*[name*='end_wt']"),function () {
        $(this).val('');
    })

    $(document).on("click",".add-sector",function () {
        //получить номер
        var getNum = $(".phone-code-block").last().attr('data-id');
        //клонируем последнее правило
        $(".phone-code-block").last().clone().prependTo($("button[type='submit']").parent().parent()).attr("data-id",parseInt(getNum)+1);

        $(".phone-code-block").last().find("input").each(function() {
            $(this).val('');
//            console.log($(this).attr(getNum))
            $(this).attr('name',$(this).attr('name').replace(getNum,parseInt(getNum)+1));
        })
    }) //click end
    //обработчик нажатия кнопки удалить
    $(document).on("click",".remove-sector",function () {
                if (confirm("Точно удалить? если не сохраните страницу, то эта запись не пропадет")) {
                   $(this).parent().remove();
                }

    })
    //добавить еще смену
    $(document).on("click",".add-more-schedule",function () {
        var getNum = $(".g-block").last().clone().insertAfter($(".g-block").last());
        var currentNum = $(getNum).find("*[name*='start_wt']").attr('name').replace ( /[^\d.]/g, '' );
        currentNum++
        $(getNum).find(".shift-num").html("Смена "+currentNum);
        $(getNum).find("*[name*='workers']").attr('name',"shift["+ currentNum +"][workers]").val('');
        $(getNum).find("*[name*='start_wt']").attr('name',"shift["+ currentNum +"][start_wt]").val('');
        $(getNum).find("*[name*='end_wt']").attr('name',"shift["+ currentNum +"][end_wt]").val('');
        $(getNum).find("*[name*='admins_ids']").attr('name',"shift["+ currentNum +"][admins_ids][]");

        setTimepickers();
    }) //click end
    $(document).on("click",".this-delete",function () {
        if(confirm("Точно?")) {
            $(this).parent().remove();
        }

    }) //click end
    //оичистим поля в новом правиле


}) //main end

//установка тайм пикеров return void
function setTimepickers() {
    $("*[name*='start_wt'],*[name*='end_wt']").timepicker({
        'minTime':'08:00',
        'maxTime':'20:00',
        'show2400': true,
        'timeFormat': "H:i",
        'step': "15",
        'forceRoundTime': true
    });
}
