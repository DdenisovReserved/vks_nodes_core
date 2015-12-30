<?php
ST::deployTemplate('heads/ui_timepicker.inc');
RenderEngine::MenuChanger();
?>
<script>
    $(document).ready(function() {
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
                   url: "http://localhost:8088/videoconf-ca-dev/api/?m=getTbId/Среднерусский",
                   dataType: "json",
                   success: function (data) {

                   },
                   complete: function() {
                       $('.spinner').remove();
                   }
               })
        })
</script>
