$(document).ready(function () {

    //create element for notices
    $("body").append("<div class='notifications bottom-right'></div>");
    //wait 5 sec then pull messages
    setTimeout(function () {
        pullNotices();
    }, 5000);

    setInterval(function () {

        pullNotices();

    }, 15000); //15 secs

})

function pullNotices() {
    $.ajax({
        beforeSend: function () {

            if ($(".notifications").children().length > 5) return false;


        },
        type: 'POST',
        cache: false,
        url: "?route=NoticeObs/pull",
        success: function (data) {
            data = JSON.parse(data);
            var time = 500;
            $.each(data, function (e, element) {

                setTimeout(function () {
                    $('.bottom-right').notify({
                        //type: 'bangTidy',
                        message: {html: "<h5><span class='glyphicon glyphicon-envelope'></span> Сообщение системы:</h5><p>[" + element.humanized.created_at + "]</p><p>" + element.message + "</p>"},
                        fadeOut: {enabled: false},
                        alarm: element.alarm ? true : false,
                        onClosed: function () {
                        }
                    }).show();

                }, time);
                time += 1000;
            });
        },
        complete: function () {
            $(".notifications").children().each(function(i,element){
                disappear(element, 1500);
            });
        }
    });
}