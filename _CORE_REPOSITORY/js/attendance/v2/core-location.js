$(document).ready(function () {
    //$(function() {
    //    $( document ).tooltip();
    //});
    window.stack = new Stack(stackName, stackCapacity,".vks_location-display","#vks_location");
    window.render = new Render(stack);
    window.stackViewer = new StackViewer(stack);

    //console.log(stack.read());
    render.renderPage(1);
    stackViewer.view("#stack-content");

}) //main end