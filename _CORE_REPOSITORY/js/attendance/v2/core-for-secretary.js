$(document).ready(function () {
    window.stack = new Stack(stackMultiName, stackMultiCapacity, ".vks-points-list-display", "#participants_inside");
    window.blockagestack = new BlockageStack(stackBlockageName, stackBlockageCapacity);
    window.render = new Render(stack,blockagestack);
    window.stackViewer = new StackViewer(stack);
    //console.log(stack.read());
    render.renderPage(containerId, 'name_to_asc', 'name_to_asc', containerId);
    stackViewer.view("#stack-content");
    stackViewer.displayCounter("#stack-counter");

}) //main end