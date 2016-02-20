$(document).ready(function () {
    window.stack = new Stack(stackName, stackCapacity,".vks_location-display","#vks_location");
    window.render = new Render(stack);
    window.stackViewer = new StackViewer(stack);

    stack.populateFromRemoveServer();

    render.renderPage(1);

    stackViewer.viewFromVirtual("#stack-content", stack.getVirtual());

}) //main end