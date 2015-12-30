$(document).ready(function () {

    $(document).on("click", "#search-button", function () {
        $("#cOrder,#pOrder").hide();
        var $thisButton = $(this);
        var phrase = $("#search-input").val();
        if (phrase.length > 0) {
            $.ajax({
                beforeSend: function () {
                   $thisButton.text("идет поиск...").attr("id","wait");
                },
                type: 'POST',
                cache: false,
                url: "?route=AttendanceNew/apiSearchTree/" + encodeURIComponent(phrase),
                success: function (data) {

                    render.renderSearchedElements(data);
                },
                complete: function () {
                    $thisButton.text("найти").attr("id","search-button");
                    if ($(".dropme").length == 0)
                        $(".breadcrumb").html("<li class='browse btn btn-warning dropme pointer' data-id='1'>Очистить результаты поиска</li>");
                }
            });
        }
    });

    $(document).on("click", ".dropme", function () {
        $(this).remove();
        $("#cOrder,#pOrder").show();
        $("#search-input").val("");
    })

    $("#search-input").keypress(function (e) {
        e.stopPropagation();

        var key = e.which;

        if (key == 13)  // the enter key code
        {
            $("#search-button").click();

        }
    });

});