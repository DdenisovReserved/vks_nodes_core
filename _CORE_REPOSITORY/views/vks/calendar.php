<?php
include_once(CORE_REPOSITORY_REAL_PATH . "views/main-fluid.php");
ST::setVarPhptoJS(HTTP_PATH, 'core_http_path');
ST::setUserJs("vks/calendar.js");
?>
<?php include_once(CORE_REPOSITORY_REAL_PATH . "views/pleaseEnter.php"); ?>
<div class="clearfix"></div>
<div id='calendar' style="min-height: 640px;"></div>
<div style='clear:both'></div>
<div style="margin-top: 0.6em;">
    <a class="btn btn-default pull-right" href="<?= ST::route('Vks/index/' . date_create()->format("d.m.Y")) ?>"
       type="button"><span class="glyphicon glyphicon-list-alt"></span> Показать события списком</a>
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

</html>

