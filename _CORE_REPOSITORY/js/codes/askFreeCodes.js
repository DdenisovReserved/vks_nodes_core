function askFreeCodes(element, start, end) {

    $(element).attr("disabled", true);
    var oldVal =  $(element).html();
    $.ajax({
        beforeSend: function () {
            $(element).html('Идет анализ, пожалуйста, подождите...');
        },
        type: 'POST',
        data: {
            start_date: start,
            end_date: end
        },
        cache: false,
        url: "index.php?route=ConnectionCode/apiGetCodesInUse",
        dataType: "json",
        success: function (data) {
            var content = '<h4 class="text-muted">Таблица занятости кодов</h4><table class="table table-bordered" id="codes-free-table"><th class="col-lg-2 text-center">Код</th><th>Занятость</th>';
            for (var i = 1; i < data.length; i++) {
                var usage = Boolean(data[i].usage) ? '<span class="text-danger">Используется в { ' + data[i].usage + ' }</span>' : '<span class="text-success">free</span>';

                content += "<tr><td class='text-center'>" + data[i].code + "</td><td>" + usage + "</td></tr>";
            }

            content += '</table>';

            $.fancybox({
                'width': 720,
                'autoSize': false,
                'height': 'auto',
                'content': content,
                //closeClick: true,
                openEffect: 'none',
                openSpeed: 150,
                closeEffect: 'none',
                closeSpeed: 150,
                'iframe': true,
                'scrollOutside': true,
                helpers: {
                    overlay: true
                }

            });

        },
        complete: function () {
            $('.spinner').remove();
            $(element).attr('disabled', false).html(oldVal);
        }
    })

}