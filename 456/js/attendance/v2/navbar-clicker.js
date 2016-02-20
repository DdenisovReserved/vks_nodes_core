$(document).ready(function () {

    $(document).on("click", "#nav-points", function (e) {
        addActive($(this));
        $("#main-container").show();
    });


    $(document).on("click", "#nav-phone-nums", function (e) {
        addActive($(this));
        $("#phone-nums-container").show();
    });


    $(document).on("click", "#nav-ip-phones", function (e) {
        addActive($(this));
        $("#ip-phones-container").show();
    })


    function addActive($this) {
        $($this).parent().find(".active").removeClass('active').removeClass('btn-success');
        $($this).addClass('active btn-success');
        $(".nav-container").each(function() {
            $(this).hide();
        })
    }
});