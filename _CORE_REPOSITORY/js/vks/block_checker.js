$(function () {
    $(document).on('change', '#date-with-support, .clonedDP', function () {
        var vks_blocked_type = $(this).data('vks_blocked_type');
        if (typeof vks_blocked_type !== 'undefined') {
            $.ajax({
                beforeSend: function () {
                    var opts = {
                        lines: 17 // The number of lines to draw
                        , length: 26 // The length of each line
                        , width: 12 // The line thickness
                        , radius: 42 // The radius of the inner circle
                        , scale: 0.2 // Scales overall size of the spinner
                        , corners: 1 // Corner roundness (0..1)
                        , color: '#000' // #rgb or #rrggbb or array of colors
                        , opacity: 0.25 // Opacity of the lines
                        , rotate: 11 // The rotation offset
                        , direction: 1 // 1: clockwise, -1: counterclockwise
                        , speed: 3.2 // Rounds per second
                        , trail: 23 // Afterglow percentage
                        , fps: 20 // Frames per second when using setTimeout() as a fallback for CSS
                        , zIndex: 2e9 // The z-index (defaults to 2000000000)
                        , className: 'spinner' // The CSS class to assign to the spinner
                        , top: '50%' // Top position relative to parent
                        , left: '50%' // Left position relative to parent
                        , shadow: false // Whether to render a shadow
                        , hwaccel: false // Whether to use hardware acceleration
                        , position: 'absolute' // Element positioning
                    }
                    var spinner = new Spinner(opts).spin();
                    $('#center').append(spinner.el);
                    $("#blocked-time-list")
                        .html("<h4 class='text-muted'>Запрос блокировок...</h4>")
                        .removeClass('hidden');
                },
                type: 'GET',
                cache: false,
                url: "?route=BlockedTime/askAtDate/" + $(this).val() + "/"+vks_blocked_type,
                dataType: "json",
                success: function (data) {
                    var content = '<div class="panel panel-danger"><div class="panel-heading"><h5>Обнаружены блокировки </h5></div><div class="panel-body"><h4 class="text-danger">Заявки в указанное время не принимаются</h4><hr><ul class="list-group list-unstyled">';
                    if (data.length) {
                        $.each(data, function (i, elem) {
                            content += '<li>';
                            content += elem.start_at + ' до <br>' + elem.end_at +'<br>';
                            content += 'Основание: '+ elem.description;
                            content += '</li><hr>';
                        });
                        content += '</ul></div> </div>';

                        $("#blocked-time-list")
                            .html(content)
                            .removeClass('hidden');
                    } else {
                        $("#blocked-time-list")
                            .html('<div class="panel panel-success"><div class="panel-heading"><h5>Блокировок нет</h5></div><div class="panel-body">Можно создавать ВКС на любое время</h5></div></div>')
                            .removeClass('hidden');
                    }
                },
                complete: function () {
                    $('.spinner').remove();
                }
            })
        }

    })
})