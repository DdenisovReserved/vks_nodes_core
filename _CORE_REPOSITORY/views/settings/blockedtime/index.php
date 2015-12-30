<?php include_once(CORE_REPOSITORY_REAL_PATH . "views/main.php");
ST::setUserJs("settings/blockedtime_calendar.js");
?>
<p>
<a class="btn btn-success" href="<?= ST::route('BlockedTime/create') ?>">Добавить блокировку</a>
</p>
<div class="alert alert-danger">Блокировка создания упрощенных ВКС</div>
<div id="calendar"></div>

</div>
<?php
//ST::deployTemplate('footer/mainFooter.inc'); ?>


