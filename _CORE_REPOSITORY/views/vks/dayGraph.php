<?php
include_once(CORE_REPOSITORY_REAL_PATH . "views/main-fluid.php");
ST::setVarPhptoJS($date, 'currentDate');
ST::setUserJs("vks/day_calendar.js");
?>
<script>
    $(document).ready(function () {
        $('#btn_another_date').click(function () {
            $('#dp_another_date').datepicker({
                'dateFormat': "yy-mm-dd"
            });

            $('#dp_another_date').datepicker("show");
        });
        $(document).on('change', '#dp_another_date', function () {
            location.href = '?route=Vks/day/' + $(this).val();
        })
        $('.fc-toolbar').remove();
    })

</script>
<div class="container100">
    <h3 class="text-center">
        <input class="hidden" id="dp_another_date" disabled/>
        <button type="button" class="btn btn-info btn-sm pull-left" id="btn_another_date">Другая дата</button>
        Список ВКС на <b><?= date_create($date)->format("d.m.Y") ?></b>
        <div class="btn-group pull-right btn-group-sm">
            <a class="btn btn-default btn-sm" href="<?= ST::route('Vks/index/' . $date) ?>">
                К списку</a>
            <a class="btn btn-default btn-sm" href="<?= ST::route('Index/index') ?>">
                На главную</a>
        </div>
    </h3><hr>
</div>
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

