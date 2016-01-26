$(document).ready(function () {
    $(document).on("click", ".close-me", function () {
        $(this).parent().slideUp();

    })
    $(document).on("click", ".hasErrors", function () {
        $(this).removeClass("hasErrors");

    })

    $(document).on("click", ".modalled", function (event) {
        var $this = $(this);
        $this.attr("disabled", true);
        event.preventDefault();
        var modal = new Modal();
        modal.showPageInModal($this.prop("href"));

        setTimeout(function () {
            $this.attr("disabled", false);
        }, 1000);

    })


    $(document).on("click", ".confirmation", function (event) {
        event.preventDefault();
        if (confirm('Вы уверены?')) {
            window.location = $(this).attr("href");
        }
    })
})
function parseElement($element) {
    var PE = {};
    PE['thisElm'] = $element;
    PE['id'] = $element.attr('id');
    PE['name'] = $element.attr('name');
    PE['val'] = $element.val();

    return PE;
}
function checkElement($element, $message) {
    var pe = parseElement($element);
    var errors = 0;
    //console.log(pe.val.length);
    if (!$element.hasClass("no-check")) {
        if (pe.val.length == 0 || pe.val == '--Выберите значение--') {
            pe.thisElm.addClass('error-mark');
            errors++;
            $(".errors-cnt").html("<p>" + $message + "</p>").show();
        }
    }

    return errors;
}
function hideErrorMark($elementlist) {
    $(document).on("click", $elementlist, function () {
        $(this).removeClass("error-mark");
        $(".errors-cnt").hide();
    })
}


function validateElements(elements) {
    var errors = 0;
    $(elements).each(function () {

        var $this = $(this);
        if (!$this.hasClass("no-check")) {
            var pE = parseElement($this);
            if (pE.val == '') {
                $this.addClass("hasErrors");
                errors++;
            }
        }
    })
    if (errors != 0) {
        $(".errors-cnt").html("Поля не могут быть пустыми").show();
        return false;
    } else {
        return true;
    }
}

function createPages(elementsSelector, limit) {

//    var countElements = elementList.length;
    $(elementsSelector + ":gt(" + limit + ")").hide();
    var getHided = $("" + elementsSelector + "");
//    var getfirstHided = $(""+elementsSelector+":hidden:first");
    console.log(getfirstHided);
    counter = 1;
    do {
        var button = "<div class='btn btn-success' data-show-begin>got</div> "
        $(".pager-container").append(button);
        counter = counter + limit;
    } while (counter < getHided.length)

}
function addNull(inp) {
    if (inp < 10 && inp >= 0)
        return "0" + inp;
    else
        return inp;
}
function roundMinutes(mins) {
    mins = Number(mins);
//    console.log (mins);
    if (mins < 15) {
        mins = '15';
    } else if (mins < 30) {
        mins = '30';
    } else if (mins < 45) {
        mins = '45';
    } else if (mins < 60) {
        mins = '45';
    }
    return mins;
}

function checkSymbols(checkedElement, displayCounterElement, limit) {
    var getVal = $(checkedElement).val();
    if (getVal.length <= limit) {
        if (getVal.length > 0) {
            $(displayCounterElement).text(limit - getVal.length);
        }
    } else {
        $(checkedElement).val(getVal.substring(0, getVal.length - 1));
    }
}

function disappear(element, millis) {
    $(element).delay(millis).slideUp();
}

function showOnly(element, limit) {

    if ($(element).children().length > limit) {
        var countHided = 0;
        $.each($(element).children(), function (e, element) {
            if (e >= limit) {
                $(element).hide();
                countHided++;
            }
        }); //each end
        var moreButton = "<button class='btn btn-link btn-sm show-more'>и еще " + countHided + "</button>";
        $(element).append(moreButton);
        $(document).on("click", ".show-more", function (e) {
            var element = $(this).parent();

            $(this).remove();
            //console.log($(element).children());
            $.each($(element).children(), function (e, element) {
                if (e >= limit) {
                    $(element).slideDown();
                }
            }); //each end

            var hideButton = "<button class='btn btn-link btn-sm hide-more' >Скрыть</button>";
            $(element).append(hideButton);
        });

        $(document).on("click", ".hide-more", function (e) {
            var element = $(this).parent();
            var countHided = 0;
            $(this).remove();
            $.each($(element).children(), function (e, element) {
                if (e >= limit) {
                    $(element).slideUp();
                    countHided++;
                }
            }); //each end


            var moreButton = "<button class='btn btn-link btn-sm show-more'>и еще " + countHided + "</button>";
            $(element).append(moreButton);

        });
    }


}

function requiredCapcha($formButtonId) {
    $(document).on("click", $formButtonId, function (e) {
        var obj = {
            "checkCaptcha": true,
            "captcha_code": $("input[name='captcha_code']").val()
        };
        $.post("?r=controllers/controller_ajax", obj, function (e) {
            e = $.parseJSON(e);
            if (!e.cCheck) {
                $(".errors-cnt").html($(".errors-cnt").html() + "<p>Ошибка в коде проверки, попробуйте еще раз</p>").show();
                $(".refresh-captcha").click();

            } else {
                $('#form1').append("<button type='submit' id='submittrue' style='display: none;' class='hidden'></button>");
                //субмитим форму? лол что
                $('#submittrue').click();
            }
        }) //post end
    }); // click end
}

function forEach(data, callback) {
    for (var key in data) {
        if (data.hasOwnProperty(key)) {
            callback(key, data[key]);
        }
    }
}

function range(start, end, step) {

    var range = [];
    var typeofStart = typeof start;
    var typeofEnd = typeof end;

    if (step === 0) {
        throw TypeError("Step cannot be zero.");
    }

    if (typeof start == "undefined" || typeof end == "undefined") {
        throw TypeError("Must pass start and end arguments.");
    } else if (typeofStart != typeofEnd) {
        throw TypeError("Start and end arguments must be of same type.");
    }

    typeof step == "undefined" && (step = 1);

    if (end < start) {
        step = -step;
    }

    if (typeofStart == "number") {

        while (step > 0 ? end >= start : end <= start) {
            range.push(start);
            start += step;
        }

    } else if (typeofStart == "string") {

        if (start.length != 1 || end.length != 1) {
            throw TypeError("Only strings with one character are supported.");
        }

        start = start.charCodeAt(0);
        end = end.charCodeAt(0);

        while (step > 0 ? end >= start : end <= start) {
            range.push(String.fromCharCode(start));
            start += step;
        }

    } else {
        throw TypeError("Only string and number types are supported");
    }

    return range;

}

function ajaxWrapper(address, data, method) {
    data = typeof data === "undefined" ? {} : data;
    method = typeof method === "undefined" ? 'get' : method;
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
        type: method,
        async: false,
        cache: false,
        data: data,
        url: address,
        dataType: "json",
        success: function (repo) {
            result = repo;
        },
        complete: function() {
            $('.spinner').remove();
        }
    });
    return result;
}

var delay = (function(){
    var timer = 0;
    return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
    };
})();
//how use
/*
 delay(function(){
 alert('Time elapsed!');
 }, 1000 );

 */

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};




