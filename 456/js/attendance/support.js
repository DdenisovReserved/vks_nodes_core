$(document).ready(function(){
    var getContainerH = $(".fancybox-inner").attr("height");
//    alert(getContainerH);
    $(".fancybox-inner").css("overflow","hidden");
    $(".tree-container").height($(window).height()-200);

})