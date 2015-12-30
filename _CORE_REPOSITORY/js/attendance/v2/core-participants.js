$(document).ready(function () {
    //$(function() {
    //    $( document ).tooltip();
    //});
    window.stack = new Stack(stackMultiName, stackMultiCapacity,".vks-points-list-display","#participants_inside");
    window.render = new Render(stack);
    window.stackViewer = new StackViewer(stack);
    //console.log(stack.read());
    render.renderPage(1);
    stackViewer.view("#stack-content");
    stackViewer.displayCounter("#stack-counter");


}) //main end