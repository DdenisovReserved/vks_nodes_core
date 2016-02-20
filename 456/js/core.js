$(document).ready(function () {

    //var scale = 'scale(1)';
    //document.body.style.webkitTransform =  scale;    // Chrome, Opera, Safari
    //document.body.style.msTransform =   scale;       // IE 9
    //document.body.style.transform = scale;     // General
    //document.body.style.zoom = screen.logicalXDPI / screen.deviceXDPI;

    var modal = new Modal();

    $(document).on("click", "#search-vks", function (e) {
        e.preventDefault();
        modal.pull('search');
    });

    $(document).on("click", "#show-referral-code-ws", function (e) {
        e.preventDefault();
        $.get('?route=Vks/apiGet/' + $(this).data("vks_id"), function (data) {
            data = $.parseJSON(data);
            if (data.referral) {
                var content = '<div class="text-center" ><input id="invite-code" style="width:100%;" value="' + data.humanized.invite_link_raw + '"/></div>';
            } else {
                var content = '<div class="text-center text-danger"><h3>Приглашение не найдено, обратитесь к администрации</h3></div>';
            }
            modal.generateAndPull('Ссылка-приглашение на ВКСП #' + data.id, content);
        });
    });

    $(document).on("click", "#show-referral-code-ns", function (e) {
        e.preventDefault();
        $.get('?route=VksNoSupport/apiGet/' + $(this).data("vks_id"), function (data) {
            data = $.parseJSON(data);
            if (data.referral) {
                var content = '<div class="text-center" ><input id="invite-code" style="width:100%;" value="' + data.humanized.invite_link_raw + '"/></div>';
            } else {
                var content = '<div class="text-center text-danger"><h3>Не найден код приглашения, обратитесь к администратору</h3></div>';
            }

            modal.generateAndPull('Ссылка-приглашение на ВКСвК #' + data.id, content);
        });
    });

    $("[name='test-checkbox']").bootstrapSwitch({
        'size': 'small',
        'onText': 'С поддержкой',
        'offText': 'Все',
        'labelText': "Отображение",
        'onSwitchChange': function (event, state) {
            if (state)
                $(".no-adm-Support").hide();
            else
                $(".no-adm-Support").show();
        }
    });

    //$(document).on("mouseover",".fc-content",function(e) {
    //    alert('yyy');
    //})


    $(".show-as-modal").click(function (e) {
        e.preventDefault();
        getModalVks($(this).data('id'));

    })
    $(".show-as-modal-ns").click(function (e) {
        e.preventDefault();
        getModalVksNs($(this).data('id'));

    })


    $(document).on("click", ".show-more-participants", function (e) {
//        console.log($(this).parent().parent().find(".hidden"));
        $(this).parent().parent().find(".hidden").removeClass("hidden").hide().slideDown();
        $(this).html("Свернуть").addClass("show-less-participants");
    })
    $(document).on("click", ".show-less-participants", function (e) {
        $(this).parent().parent().find(".list-group-item-text:gt(4):not(:last)").slideUp().addClass("hidden");
        $(this).html("+ еще " + $(this).parent().parent().find(".list-group-item-text:gt(4):not(:last)").length).removeClass("show-less-participants");

    })
    var oldVal = '';
    //$(document).on("mouseenter", ".fc-day-number", function (e) {
    //    oldVal = $(this).html();
    //    $(this).css({
    //        'background-color': '#ff911b',
    //        'cursor': 'pointer'
    //    }).html("<span class='show-more text-center'>Показать</span>");
    //})
    //$(document).on("mouseleave", ".fc-day-number", function (e) {
    //    $(this).find('.show-more').remove();
    //    $(this).css('background-color', '');
    //    $(this).html(oldVal)
    //})

    $(document).on('focus', '#invite-code', function () {
        $(this).select();
    });

}) //main end

function getModalVks(vksId) {
    var modal = new Modal();
    modal.showPageInModal("?route=Vks/show/" + vksId + "/true")
}

function getModalVksNs(vksId) {
    var modal = new Modal();
    modal.showPageInModal("?route=VksNoSupport/show/" + vksId + "/true")
}

$(window).bind("load", function () {

    var footerHeight = 0,
        footerTop = 0,
        $footer = $("#footer");

    positionFooter();

    function positionFooter() {

        footerHeight = $footer.height();
        footerTop = ($(window).scrollTop() + $(window).height() - footerHeight) + "px";

        if (($(document.body).height() + footerHeight) < $(window).height()) {
            $footer.css({
                position: "absolute"
            }).css({ // can be animate, just type animate
                top: footerTop
            })
        } else {
            $footer.css({
                position: "static"
            })
        }

    }

    $(window)
        //.scroll(positionFooter)
        .resize(positionFooter)

});

