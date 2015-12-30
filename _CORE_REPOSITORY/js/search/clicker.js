$(document).ready(function() {

    searchRender = new SearchRender();

    $(document).on("click", "#search-button", function () {
        var $thisButton = $(this);
        var phrase = $("#search-input").val();
        if (phrase.length > 0) {
            $.ajax({
                beforeSend: function () {
                    $thisButton.text("идет поиск...").attr("id","wait");
                    $("#search-results").html("");
                },
                type: 'POST',
                cache: false,
                url: "?route=Search/search/" + + encodeURIComponent(phrase),
                success: function (data) {
                    data = $.parseJSON(data);
                    if (!data.ws.length) $("#search-results").append("<h5 class='alert alert-warning'>По вашему запросу ничего не найдено</h5>");

                    $.each(data.ws, function (e, element) {
                        var element = searchRender.renderVks(element);
                        $("#search-results").append(element);
                    });

                },
                complete: function () {
                    $thisButton.text("найти").attr("id","search-button");
                }
            });
        }
    })
})