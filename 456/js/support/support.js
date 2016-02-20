$(document).ready(function () {
    $(document).on("click", ".close-me", function () {
        $(this).parent().slideUp();

    })
    $(document).on("click", ".hasErrors", function () {
        $(this).removeClass("hasErrors");

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
    $(element).delay(millis).slideUp(function () {
        $(element).remove();
    });
}

function flushMessage(insertBeforeThisElement, message) {
    $(insertBeforeThisElement).before("<div class='alert alert-danger' id='error-msg'>" + message + "</div>");
    disappear("#error-msg", 1500);
}

function showOnly(element, limit) {
    if (!$(".hide-more").length) {
        if ($(element).children().not(".show-more").length > limit) {
            var countHided = 0;
            $.each($(element).children().not(".show-more"), function (e, element) {
                if (e >= limit) {
                    $(element).hide();
                    countHided++;
                }
            }); //each end
            var moreButton = "<button class='btn btn-link btn-sm show-more'>и еще " + countHided + "</button>";
            var elemExist = $(element).find(".show-more");
            if (elemExist.length) {
                var cloned = elemExist.html("и еще " + countHided).remove().clone();
                $(element).append(cloned);
            } else {
                $(element).append(moreButton);
            }

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
        } else {
            $(".show-more").remove();
        }
    } else {
        var cloned = $(".hide-more").remove().clone();
        $(element).append(cloned);
    }


}

function requiredCapcha($formButtonId) {
    $(document).on("click", $formButtonId, function (e) {
        var obj = {
            "captcha_code": $("input[name='captcha_code']").val()
        };
        $.post("?route=Security/checkCaptcha", obj, function (e) {
            e = $.parseJSON(e);
            if (!e.cCheck) {
                $(".errors-cnt").html($(".errors-cnt").html() + "<p>Ошибка в коде проверки, попробуйте еще раз</p>").show();
                $(".refresh-captcha").click();
            } else {
                $('#form1').append("<button type='submit' id='submittrue' style='display: none;' class='hidden'></button>");
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

    if (typeofStart == "undefined" || typeofEnd == "undefined") {
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

function reloadCaptcha()
{
    jQuery('#captcha').prop('src', './securimage/securimage_show.php?sid=' + Math.random());
}







