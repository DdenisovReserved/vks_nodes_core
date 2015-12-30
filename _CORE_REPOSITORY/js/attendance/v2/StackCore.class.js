//define new stack
function Stack(name, capacity, outputElement,parsedElement) {

    this.name = name;
    this.delim = '@@DELIM@@';
    this.capacity = typeof capacity !== 'undefined' ? capacity : 1; //1 item is default capacity
    //this.clear();
    this.outputElement = outputElement;
    this.parsedElement = parsedElement;

}


Stack.prototype.clear = function () {
    $.cookie(this.name, '');
    return true;
};

Stack.prototype.read = function () {
    var stack = typeof $.cookie(this.name) !== 'undefined' ? $.cookie(this.name) : [];
    if (stack.length > 0) {
        stack = stack.split(this.delim);
    } else {
        stack = Array();
    }
    return stack;
};

Stack.prototype.put = function (value) {
    value = String(value);
    var result = false;
    var stack = this.read();
    if (stack.length == this.capacity) {
        alert('Больше значений добавить нельзя, максимально: '+this.capacity);
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

Stack.prototype.remove = function(value) {
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
}

Stack.prototype.exist = function(value) {
    value = String(value);

    var stack = this.read();

    var index = $.inArray(value, stack);

    if (index > -1)
        return true;
    else
        return false;

}



