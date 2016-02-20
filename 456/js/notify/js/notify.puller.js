$(document).ready(function () {

    //create element for notices
    $("body").append("<div class='notifications bottom-right'></div>");
    //wait 5 sec then pull messages
    setTimeout(function () {
        pullNotices();
    }, 5000);

    setInterval(function () {

        pullNotices();

    }, 30000); //30 secs

})

function pullNotices() {
    $.ajax({
        beforeSend: function () {
            if ($(".notifications").children().length > 100) return false;
        },
        type: 'POST',
        cache: false,
        url: "?route=NoticeObs/pull",
        success: function (data) {
            data = JSON.parse(data);
            var time = 500;
            $.each(data, function (e, element) {
                if ($(".notifications").children().length <= 5) {
                    setTimeout(function () {
                        $('.bottom-right').notify({
                            message: {html: "<h5><span class='glyphicon glyphicon-envelope'></span> Сообщение системы:</h5><p>[" + element.humanized.created_at + "]</p><p>" + element.message + "</p>"},
                            fadeOut: {enabled: false},
                            onClosed: function () {
                            }
                        }).show();
                    }, time);
                    time += 1000;
                }
            });
        },
        complete: function () {
            $(".notifications").children().each(function(i,element){
                disappear(element, 1500);
            });
        }
    });
}