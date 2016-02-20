$(document).ready(function () {
    $(document).on('click', "#vks_modal_bg", function (e) {
        $(".close_modal").click();
    });

    $(document).on('click', '#vks_modal', function (e) {
        e.stopPropagation();
    });

    $(document).on('click', ".close_modal", function (e) {
        $('#vks_modal').remove();
        $("#vks_modal_bg").remove();
        jQuery.enableScroll();

    });
});

function Modal() {

    Modal.element_bg = $("<div/>", {
        'id': 'vks_modal_bg'
    });
    Modal.closeElement = $("<div/>", {
        'class': 'text-right close_modal',
        'style': 'margin-bottom: 20px; padding-bottom: 10px;',
        'html': "<span class='pull-right pointer text-primary' >Закрыть</span>"
    });

    Modal.element = $("<div/>", {
        'id': 'vks_modal'
    });

}

Modal.prototype.generateAndPull = function (title, content, more_buttons) {
    $.ajax({
        beforeSend: function () {
            $(".modal").remove();
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
        },
        type: 'POST',
        cache: false,
        data: {
            title: title,
            content: content,
            more_buttons: more_buttons
        },
        url: "?route=ModalWindow/generate",
        success: function (data) {
            $("body").append(data);
        },
        complete: function () {
            $(".modal").modal('show');
            $('.spinner').remove();
        }
    });
};

Modal.prototype.pull = function (name, params) {
    $.ajax({
        beforeSend: function () {
            $(".modal").remove();
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
            $('#center').append(spinner.el);
        },
        type: 'POST',
        cache: false,
        dataType: "html",
        data: {
            name: name,
            param: params
        },
        url: "?route=ModalWindow/pull",
        success: function (data) {

            $("body").append(data);
        },
        complete: function () {
            //alert("complete");
            $(".modal").modal('show');
            $('.spinner').remove();
        }
    });


};

Modal.prototype.showPageInModal = function (url) {


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
        },
        type: 'GET',
        cache: false,
        url: url,
        dataType: "html",
        success: function (data) {
            var tempEl = $("<div/>");


            jQuery.asModal($(tempEl).html(data));


            //fix icon show in ie 8
            var $style;
            $style = $('<style type="text/css">:before,:after{content:none !important}</style>');
            $('head').append($style);
            return setTimeout((function () {
                return $style.remove();
            }), 0);


        },
        complete: function () {
            $('.spinner').remove();
        }
    });


}

jQuery.fn.center = function () {
    //console.log($(window).scrollTop());
    this.css('position', 'fixed');
    this.css("top", ($(window).height() / 2) - (this.outerHeight() / 2)) + 10;
    this.css("left", ($(window).width() / 2) - (this.outerWidth() / 2));

    return this;
};

jQuery.asModal = function (modalContent) {


    jQuery.disableScroll();


    Modal.element.remove();
    Modal.element_bg.remove();


    $("<div/>", {
        'id': 'vks_modal_head',
    }).appendTo(Modal.element);

    $("<div/>", {
        'id': 'vks_modal_content'
    }).appendTo(Modal.element);

    $("<div/>", {
        'id': 'vks_modal_bottom',
    }).appendTo(Modal.element);

    Modal.element_bg.css({
        'min-height': $(window).height(),
        'height': $(window).height(),
        'overflow-y': 'auto',
        'width': '100%',
        'padding': '0',
        'background-color': '#c6c6c6',
        'opacity': '0',
        'border': '1px solid #ccc',
        'z-index': 9997
    });

    Modal.element.css({
        'min-height': $(window).height() - 30,
        'height': $(window).height() - 30,
        'overflow-y': 'auto',
        'width': '1000px',
        'padding': '20px',
        'background-color': 'white',
        'border': '1px solid #ccc',
        'z-index': 9999
    });

    $('body').append(Modal.element);

    $('body').append(Modal.element_bg);


    $("#vks_modal_head").html(Modal.closeElement);
    $("#vks_modal_content").html(modalContent);

    $(Modal.element_bg).center();
    $(Modal.element).center();

    return this;

};

jQuery.disableScroll = function () {
    var body = $('body');
    body.attr('scrolled', $(window).scrollTop());
    body.addClass('noscroll');
    body.css('top', -body.attr('scrolled'));
};

jQuery.enableScroll = function () {

    var body = $('body');
    body.removeClass('noscroll');
    $(document).scrollTop(body.attr('scrolled'));
    body.removeAttr('scrolled');

};