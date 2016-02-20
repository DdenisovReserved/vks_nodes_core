$(document).ready(function() {
//    $(document).on('keyup',".issue",function() {
//        alert('r');
//    })
    $(document).on('keyup',".issue",function() {
        checkSymbols($(this),$(this).parent().find(".sym-least"),1600)
    });
    $(document).on('keyup',".author",function() {
        checkSymbols($(this),$(this).parent().find(".sym-least"),45)
    });
    $(document).on('keyup',".dev_answer",function() {
        checkSymbols($(this),$(this).parent().find(".sym-least"),1600)
    });
})

function checkSymbols(checkedElement, displayCounterElement, limit) {
    var getVal = $(checkedElement).val();
    if (getVal.length<=limit) {
        if (getVal.length>0) {
            $(displayCounterElement).text(limit-getVal.length);
        }
    } else {
        $(checkedElement).val(getVal.substring(0,getVal.length-1));
    }
 }