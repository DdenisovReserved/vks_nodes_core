//define new stack
function Stack(name, capacity, outputElement, parsedElement) {

    this.name = name;
    this.capacity = typeof capacity !== 'undefined' ? capacity : 1; //1 item is default capacity
    this.outputElement = outputElement;
    this.virtualStack = [];

}


Stack.prototype.clear = function () {
    $.cookie(this.name, '');
    return true;
};

Stack.prototype.read = function () {
    return this.getVirtual();
};

Stack.prototype.putVirtual = function (value) {


    if (this.virtualStack.length == this.capacity) {

        if (!$("#error_msg").length) {
            $(".fancybox-skin").prepend("<div class='alert alert-danger' id='error_msg'>Ошибка: Больше значений добавить нельзя, максимум: " + this.capacity + "</div>");
            disappear("#error_msg", 2000);
        }
        return false;
    }


    if (!this.existVirtual(value)) {
        this.virtualStack.push(value);
    }

    return this.virtualStack;
};
Stack.prototype.getVirtual = function () {
    return this.virtualStack;
};
Stack.prototype.clearVirtual = function () {
    this.virtualStack = [];
    return this.virtualStack;
};
Stack.prototype.removeFromVirtual = function (value) {
    var newStack = [];
    for (var i = 0; i < this.virtualStack.length; i++) {
        if (this.virtualStack[i].id != value) {
            newStack.push(this.virtualStack[i]);
        }
    }
    this.virtualStack = newStack;
    return this.virtualStack
};


Stack.prototype.sendToRemoteStorage = function(vstack) {

    var stackData = [];
    if (vstack.length)
        $.each(vstack, function(i, val) {
            stackData.push(val.id)
        });

    var data = {
        key: this.name,
        value: JSON.stringify(stackData)
    };
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
             type: 'post',
             cache: false,
             data: data,
             async: false,
             url: "index.php?route=LocalStorage/set/",
             success: function (data) {
             },
             complete: function() {
                 $('.spinner').remove();
             }
         })
};

Stack.prototype.populateFromRemoveServer = function() {
    var stack = this;
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
             url: "index.php?route=AttendanceNew/getStackData/" + this.name,
             dataType: "json",
             success: function (data) {
                 stack.virtualStack = data;
             },
             complete: function() {
                 $('.spinner').remove();
             }
         })
};

Stack.prototype.put = function (value) {

    value = String(value);
    var result = false;
    var stack = this.read();

    if (stack.length == this.capacity) {

        if (!$("#error_msg").length) {
            $(".fancybox-skin").prepend("<div class='alert alert-danger' id='error_msg'>Ошибка: Больше значений добавить нельзя, максимум: " + this.capacity + "</div>");
            disappear("#error_msg", 2000);
        }
        return false;
    }
    if (this.capacity == 1) { //if capacity is 1, clear stack and put it
        //console.log(this);
        this.clear();
        stack = this.read();
        $(".plank-point-deactivated").removeClass("plank-point-deactivated").addClass('plank-point');
        stack.push(value);
        result = true;
    } else {
        if ($.inArray(value, stack) == "-1") {
            stack.push(value);
            result = true;
        }
        //check length of stack, cut it off if more
        if (stack.length > this.capacity) {
            stack = stack.slice(stack.length - this.capacity);
        }
    }

    stack = stack.join(this.delim);
    $.cookie(this.name, stack);
    return result;
};

Stack.prototype.remove = function (value) {
    var result = false;
    value = String(value);
    var stack = this.read();

    var index = $.inArray(value, stack);

    if (index > -1) {
        stack.splice(index, 1);
        $(".plank-point-deactivated[data-id='" + value + "']").removeClass("plank-point-deactivated").addClass('plank-point');
        result = true;
    }

    stack = stack.join(this.delim);

    $.cookie(this.name, stack);

    return result;
};

Stack.prototype.exist = function (value) {

    var stack = this.getVirtual();
    //console.log(stack);
    for(var i = 0; i < stack.length; i++) {
        if (stack[i].id == value) {
            return true;
        }
    }
    return false;
}
Stack.prototype.existVirtual = function (value) {

    var stack = this.getVirtual();
    for(var i = 0; i < stack.length; i++) {
        if (stack[i].id == value.id) {
            return true;
        }
    }
    return false;
}




