function BlockageStack(name, capacity) {

    this.name = name;
    this.delim = '@@DELIM@@';
    this.capacity = typeof capacity !== 'undefined' ? capacity : 1; //1 item is default capacity
    //this.clear();

}

BlockageStack.prototype.read = function () {
    var stack = typeof Cookies.get(this.name) !== 'undefined' ? Cookies.get(this.name) : [];
    if (stack.length > 0) {
        stack = stack.split(this.delim);
    } else {
        stack = Array();
    }
    return stack;
};

BlockageStack.prototype.put = function (value) {
    value = String(value);
    var result = false;
    var stack = this.read();
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
    Cookies.set(this.name, stack);
    return result;
};


BlockageStack.prototype.exist = function(value) {
    value = String(value);

    var stack = this.read();

    var index = $.inArray(value, stack);

    if (index > -1)
        return true;
    else
        return false;

}



