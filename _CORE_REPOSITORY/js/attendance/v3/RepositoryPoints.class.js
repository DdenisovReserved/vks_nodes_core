function RepositoryPoints() {

}

RepositoryPoints.prototype.pullPoints = function (id, except, dateTimeforCheck) {

    id = typeof id === 'undefined' ? 1 : id;
    except = typeof except === 'undefined' ? [0] : except;
    dateTimeforCheck = typeof dateTimeforCheck === 'undefined' ? [] : dateTimeforCheck;
    //console.log(except);
    var data = {
        'id': id,
        'except': except,
        'dateTimeforCheck': dateTimeforCheck
    };
    var result = null;
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
        type: 'POST',
        async: false,
        cache: false,
        data: data,
        url: "?route=AttendanceNew/apiGetFastTree",
        dataType: "json",
        success: function (repo) {
            result = repo;

        },
        complete: function () {
            $('.spinner').remove();
        }
    });
    return result;

};

RepositoryPoints.prototype.busyAt = function (id, dateTimeforCheck, except) {
    var data = {
        'id': id,
        'dateTimeforCheck': dateTimeforCheck,
        'except': except
    };
    var result = null;
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
        type: 'POST',
        async: false,
        cache: false,
        data: data,
        url: "?route=AttendanceNew/apiGetPointBusyAt/render",
        dataType: "json",
        success: function (repo) {
            result = repo;
            //console.log(repo);
            $.fancybox({
                'width': 720,
                'autoSize': false,
                'height': 'auto',
                'content': result
            });

        },
        complete: function () {
            $('.spinner').remove();
        }
    });
    return result;
};

RepositoryPoints.prototype.search = function (phrase, except) {
    except = typeof except === 'undefined' ? [0] : except;
    //console.log(except);
    var result = null;
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
        async: false,
        cache: false,
        url: "?route=AttendanceNew/apiSearchFastTree/" + phrase + "/" + except,
        dataType: "json",
        success: function (repo) {
            result = repo;
        },
        complete: function () {
            $('.spinner').remove();
        }
    });
    return result;

};

RepositoryPoints.prototype.pullChilds = function (id, except) {
    id = typeof id === 'undefined' ? 1 : id;
    except = typeof except === 'undefined' ? [0] : except;
    var result = null;
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
        async: false,
        cache: false,
        url: "?route=AttendanceNew/apiGetFastChilds/" + id + "/" + except,
        dataType: "json",
        success: function (repo) {
            result = repo;
        },
        complete: function () {
            $('.spinner').remove();
        }
    });
    return result;


};

RepositoryPoints.prototype.storeAtStorage = function (key, value) {
    value = JSON.stringify(value);

    var sendedObj = {
        "key": key,
        "value": value
    };
    var result = null;
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
        type: 'POST',
        async: false,
        cache: false,
        url: "?route=LocalStorage/set",
        dataType: "json",
        data: sendedObj,
        success: function (data) {
            result = data
        },
        complete: function () {
            $('.spinner').remove();
        }
    });
    return result;
};

RepositoryPoints.prototype.getFromStorage = function (key) {
    var result = null;
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
        async: false,
        url: "?route=LocalStorage/get/" + key + "",
        dataType: "json",
        success: function (data) {
            result = [];

            for (var key in data) {
                // skip loop if the property is from prototype
                if (!data.hasOwnProperty(key)) continue;
                var obj = data[key];
                result.push(obj);
            }
            //console.log(result);

        },
        complete: function () {
            $('.spinner').remove();
        }
    });
    return result;
};

RepositoryPoints.prototype.removeFromStorage = function (key) {
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
        url: "?route=LocalStorage/remove/" + key + "",
        dataType: "json",
        success: function (vks) {
        },
        complete: function () {
            $('.spinner').remove();
        }
    })
};


RepositoryPoints.prototype.isExistInStorage = function (key) {
    var result = null;
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
        async: false,
        cache: false,
        url: "?route=LocalStorage/isExist/" + key + "",
        dataType: "json",
        success: function (data) {
            result = Boolean(data)
        },
        complete: function () {
            $('.spinner').remove();
        }
    })
    return result;
};
