
$(function () {
    attendanceDataPuller(1, "#tree_holder");
});

$(document).on('click', '.point-simple-list', function (e) {
    e.stopPropagation();
    var url = getUrlParameter('route').split("/")
    url.pop();
    url.push($(this).data("id"));
    url = "?route=" + url.join("/");
    location.href = url;

});

$(document).on('click', '.container-simple-list-opened', function (e) {
    e.stopPropagation();
    var $this = $(this);
    $this.removeClass('container-simple-list-opened')
        .addClass('container-simple-list-closed')
        .find("span")
        .removeClass('glyphicon-plus').removeClass('text-success')
        .addClass('glyphicon-minus').addClass('text-info');
    attendanceDataPuller($this.data('id'), $this);
});

$(document).on('click', '.container-simple-list-closed', function (e) {
    e.stopPropagation();
    var $this = $(this);

    $this.find('ul').slideUp();
    $this.find('ul').remove();

    $this.removeClass('container-simple-list-closed')
        .addClass('container-simple-list-opened')
        .find("span")
        .removeClass('glyphicon-minus').removeClass('text-info')
        .addClass('glyphicon-plus').addClass('text-success');
});

function draw(repository, toList) {
    var result = '<ul class="list-unstyled tree">';
    var toElement = $(toList);
    if (!repository.containers && !repository.points) {
        result += "<li><i class='text-danger'>Список пуст</i></li>";
    }

    if (repository.containers)
        for (var i = 0; i < repository.containers.length; i++) {
            var current = repository.containers[i];
            var element = $("<li/>", {
                'html': "<span class='pointer glyphicon glyphicon-plus text-success'></span> " + current.name,
                'class': 'container-simple-list-opened',
                'data-id': current.id
            });
            result += element.prop('outerHTML');
        }
    if (repository.points)
        for (var i = 0; i < repository.points.length; i++) {
            var current = repository.points[i];
            var name = current.name.length ? current.name : current.id;
            var element = $("<li/>", {
                'html': "<span class='glyphicon glyphicon-link text-info'></span> " + name,
                'class': 'pointer point-simple-list',
                'data-id': current.id
            });
            result += element.prop('outerHTML');
        }
    result += "</ul>";
    $(result).appendTo(toElement).slideDown();
}

function attendanceDataPuller(id, element) {

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
            };
            var spinner = new Spinner(opts).spin();
            $(element).append(spinner.el);
        },
        type: 'POST',
        cache: false,
        data: {'id': id},
        url: "?route=AttendanceNew/apiGetFastTree",
        dataType: "json",
        success: function (data) {
            if (id == 1) {
                draw(data, element)
            } else {
                draw(data, element)
            }
        },
        complete: function () {
            $('.spinner').remove();
        }
    });
}
