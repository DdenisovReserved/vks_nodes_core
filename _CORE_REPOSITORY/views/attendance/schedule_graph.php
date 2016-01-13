<?php
include_once(CORE_REPOSITORY_REAL_PATH . "views/main-fluid.php");
//timed_array
ST::setVarPhptoJS($date, 'date');
ST::setVarPhptoJS($attendance->id, 'requested_participant_id');
ST::setUserJs("vks/showAtParticipant_calendar.js");
ST::setUserJs("attendance/tree.js");
ST::setUserCss("attendance/tree.css");

?>
<script>
    $(document).ready(function () {
        $('#calendar').fullCalendar('gotoDate', date);

        $(".date-pick").datepicker({
            defaultDate: date,
            dateFormat: "yy-mm-dd",
            onSelect: function (date) {
                location.href = "<?php echo App::$instance->opt->appHttpPath; ?>?route=AttendanceNew/showSchedule/1/" + date + "/" + requested_participant_id;
            }
        });

        //autocomplete handler
        $(document).on('click', '#founded_results_attendance li', function () {
            var $this = $(this);
            location.href = "<?php echo App::$instance->opt->appHttpPath; ?>?route=AttendanceNew/showSchedule/1/" + date + "/" + $this.data('id');
        })

    })
</script>

    <div class="pull-left">
        <h3 class="text-muted">ВКС проходящие <?= date_create($date)->format("d.m.Y") ?> в переговорной: <span
                class="text-success"><?= strlen(AttendanceNew_controller::makeFullPath($attendance->id)) ? AttendanceNew_controller::makeFullPath($attendance->id) : 'Корневой контейнер' ; ?></span>
            </h3>
    </div>
    <div class="pull-right">
        <h3>
        <span class="btn-group">
            <a href="<?= ST::route('AttendanceNew/showSchedule/0/'.$date."/".$attendance->id) ?>" class="btn btn-default" >Список</a>
            <button type="button" class="btn btn-default" disabled>График</button>
        </span>
        </h3>
    </div>
    <div class="clearfix"></div>
    <hr>
    <div class="col-lg-9">
        <?php include_once(CORE_REPOSITORY_REAL_PATH."views/attendance/tpl/_search_form.php") ?>
        <div id='calendar' style="min-height: 640px;"></div>
        <div style='clear:both'></div>
    </div>
    <div class="col-md-3">
        <div class="date-pick"></div>
        <hr>
        <h4>Каталог точек</h4>
        <div id="tree_holder">
        </div>
        <?php include_once(CORE_REPOSITORY_REAL_PATH . "views/attendance/tpl/_last_seen_attendance.php") ?>
    </div>


    <script>
        setInterval(function () {
            $('#calendar').fullCalendar('refetchEvents');
        }, 120000); //120 secs
    </script>

<div class="clearfix"></div>
<div style="margin-top: 7.0em;">
    &nbsp
</div>
<?php ST::deployTemplate('footer/closeContainer.inc'); ?>
<div class="container100">
    <?php ST::deployTemplate('footer/mainFooter.inc'); ?>
</div>

