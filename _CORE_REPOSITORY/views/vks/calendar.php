<?php
include_once(CORE_REPOSITORY_REAL_PATH . "views/main.php");
ST::setVarPhptoJS(HTTP_PATH, 'core_http_path');
ST::setUserJs("vks/calendar.js");
?>
</div>
<div class="container-fluid">
    <?php include_once(CORE_REPOSITORY_REAL_PATH . "views/pleaseEnter.php"); ?>
</div>
<div class="container">
    <div class="clearfix"></div>
    <div id='calendar' style="min-height: 640px; margin-top: 25px;"></div>
    <div style='clear:both'></div>
    <div style="margin-top: 1.9em;">
        &nbsp
    </div>
    <?php ST::deployTemplate('footer/closeContainer.inc'); ?>
    <div class="container100">
        <?php ST::deployTemplate('footer/mainFooter.inc'); ?>
    </div>


    <script>
        setInterval(function () {
            $('#calendar').fullCalendar('refetchEvents');
        }, 120000); //120 secs
    </script>
</div>
</html>

