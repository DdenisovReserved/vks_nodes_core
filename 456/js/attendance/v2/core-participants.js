$(document).ready(function () {

    window.stack = new Stack(stackMultiName, stackMultiCapacity,".vks-points-list-display","#participants_inside");
    window.render = new Render(stack);
    window.stackViewer = new StackViewer(stack);
    stack.populateFromRemoveServer();

    render.renderPage(1);
    stackViewer.viewFromVirtual("#stack-content", stack.getVirtual());




}) //main end