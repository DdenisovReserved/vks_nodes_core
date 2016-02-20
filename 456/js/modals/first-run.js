$(document).ready(function () {

    var modal = new Modal();

    var refuseButton = "<button type='button' class='btn btn-default' id='no-more-help' data-dismiss='modal'>Закрыть и больше не показывать подсказок</button>";

    //$.removeCookie('vks-first-start-pass');
    //first run
    if (typeof $.cookie('vks-first-start-pass') == 'undefined') {
        var content = "Для продолжения, выберите, пожалуйста, дату проведения вкс";
        modal.generateAndPull("Система помощи (первый запуск)", content, refuseButton);

    }


    $(document).on("change", "#date-with-support", function () {
        if (typeof $.cookie('vks-first-start-pass') == 'undefined') {
            var content = "<h4>Перед вами шкала занятости администраторов системы.</h4>" +
                "<ul><li><span class='text-success'>Зеленый</span> цвет означает - время доступно для выбора, <span class='text-danger'>красный</span> - все доступные администраторы заняты и провести ВКС в это время невозможно.</li>" +
                "<li>Первый клик в шкале выбирает время начала ВКС, второй время окончания</li>" +
                "<li>Как только вы выбирете время, оно будет зарезервировано за вами в течении 15 минут.</li>" +
                "<li>Вы можете создать за один раз 5 ВКС (с разным временем проведения, но остальные параметры будет одинаковы)</li>"
                "</ul>";
            modal.generateAndPull("Система помощи (первый запуск)", content, refuseButton);
        }
    })






    $(document).on("click", "#no-more-help", function () {
        //$.cookie('vks-first-start-pass',1,60*60*24*365*10);
    })

})