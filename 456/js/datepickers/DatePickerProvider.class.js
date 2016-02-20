function DatePickerProvider(toElement) {

    this.element = $(toElement);
    this.dates = {};
    this.disabledDays = [];
    this.enabledDays = [];
    this.holydays = [];
    this.DateLimits = {};
    //days in calendar = 60
    this.dates.min = new Date();
    this.dates.max = new Date();
    this.dates.max.setDate(this.dates.max.getDate() + 60);

    this.DateLimits.min = new Date(Date.parse(this.dates.min));
    this.DateLimits.max = new Date(Date.parse(this.dates.max));

}

DatePickerProvider.prototype.pullHolydays = function () {

    var m = this.DateLimits.min.getMonth(), d = this.DateLimits.min.getDate(), y = this.DateLimits.min.getFullYear();
    var compileStart = y + '-' + (m + 1) + '-' + d;
    var m = this.DateLimits.max.getMonth(), d = this.DateLimits.max.getDate(), y = this.DateLimits.max.getFullYear();
    var compileEnd = y + '-' + (m + 1) + '-' + d;

    $.ajax({
        async: false,
        type: 'GET',
        cache: false,
        url: "?route=Holydays/apiGetOverAllDays/" + compileStart + "/" + compileEnd,
        success: $.proxy(function (data) {

            data = $.parseJSON(data);
            this.disabledDays = data.disabledDays;
            this.enabledDays = data.enabledDays;
            this.holydays = data.holydays;
        },this)

    });

};


DatePickerProvider.prototype.filtrateDays = function (date) {
    var m = date.getMonth(), d = date.getDate(), y = date.getFullYear();
    var compileDay = moment(date).format('MM-D-YYYY');

    for (i = 0; i < this.holydays.length; i++) {
        if ($.inArray(compileDay, this.holydays) != -1) {
            return [true];
        }
    }
    for (i = 0; i < this.enabledDays.length; i++) {
        if ($.inArray(compileDay, this.enabledDays) != -1) {
            return [true];
        }
    }
    for (i = 0; i < this.disabledDays.length; i++) {
        if ($.inArray(compileDay, this.disabledDays) != -1) {
            return [false];
        }
    }
    return [true];
};

DatePickerProvider.prototype.highlightDays = function (date) {
    var m = date.getMonth(), d = date.getDate(), y = date.getFullYear(), dayInWeek = date.getDay();
    //var compileDay = (m + 1) + '-' + d + '-' + y;
    var compileDay = moment(date).format('MM-DD-YYYY');
    for (i = 0; i < this.enabledDays.length; i++) {
        if ($.inArray(compileDay, this.enabledDays) != -1) {
            return [true, ';'];
        }
    }

    if ($.inArray(dayInWeek, [6, 0]) != -1) {
        return [true, 'highlight'];
    }

    for (var i = 0; i < this.holydays.length; i++) {
        if (this.holydays[i] == compileDay) {
            return [true, 'highlight'];
        }
    }


    return [true, ''];
};

DatePickerProvider.prototype.deployObserver = function () {
    $(document).on('change', this.element.selector, $.proxy(function () {
        $.ajax({
            beforeSend: function () {
                //pass
            },
            type: 'POST',
            cache: false,
            url: "?route=Holydays/apiDayStatus/" + this.element.val(),
            success: function (data) {
                $("#error-msg").remove();
                data = $.parseJSON(data);
                if (data == 2) {
                    $('.navbar').after("<div class='alert alert-danger text-center' id='error-msg'><a class='close' data-dismiss='alert'>×</a>Обратите внимание: вы запрашиваете ВКС в выходной день, заявка будет отправлена на согласование администраторам системы.</div>")
                }
            },
            complete: function () {
                //pass
            }
        });
    }, this))
};

DatePickerProvider.prototype.setCustomDate = function (date) {
    var clazz = "";
    var arr1 = this.highlightDays(date);
    if (arr1[1] != "") clazz = arr1[1];
    var arr2 = this.filtrateDays(date);
    //return [(!arr2[0] || !arr3[0]) ? false : true, clazz];
    return [(!arr2[0]) ? false : true, clazz];
};

DatePickerProvider.prototype.setsimple = function () {
    this.element.datepicker({
        minDate: this.DateLimits.min,
        maxDate: this.DateLimits.max,
        inline: true,
        numberOfMonths: [1, 2]
    });
};

DatePickerProvider.prototype.setWithDaysCheck = function () {
    this.pullHolydays();
    //console.log(this);
    this.element.datepicker({
        minDate: this.DateLimits.min,
        maxDate: this.DateLimits.max,
        inline: true,
        numberOfMonths: [1, 2],
        beforeShowDay: $.proxy(this.setCustomDate, this) // <-----change function,

    });

    this.deployObserver();

};





