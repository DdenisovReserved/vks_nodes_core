<?php
include_once(CORE_REPOSITORY_REAL_PATH . "views/main-fluid.php");
//timed_array
ST::setVarPhptoJS($date, 'date');
ST::setVarPhptoJS($attendance->id, 'requested_participant_id');
ST::setUserJs("vks/showAtParticipant_calendar.js");
ST::setUserJs("attendance/tree.js");
ST::setUserCss("attendance/tree.css");
$resuested_att = $attendance;
?>
<script>
    $(document).ready(function () {
        $(".date-pick").datepicker({
            defaultDate: date,
            dateFormat: "yy-mm-dd",
            onSelect: function (date) {
                location.href = "<?php echo App::$instance->opt->appHttpPath; ?>?route=AttendanceNew/showSchedule/0/" + date + "/" + requested_participant_id;
            },
        });
        //autocomplete handler
        $(document).on('click', '#founded_results_attendance li', function () {
            var $this = $(this);
            location.href = "<?php echo App::$instance->opt->appHttpPath; ?>?route=AttendanceNew/showSchedule/0/" + date + "/" + $this.data('id');
        })


    })
</script>
<div class="pull-left">
    <h3 class="text-muted">ВКС походящие <b><?= date_create($date)->format("d.m.Y") ?></b> в переговорной: <span
            class="text-success"><?= strlen(AttendanceNew_controller::makeFullPath($attendance->id)) ? AttendanceNew_controller::makeFullPath($attendance->id) : 'Корневой контейнер'; ?></span>
    </h3>
</div>
<div class="pull-right">
    <h3>
            <span class="btn-group">
                <button type="button" class="btn btn-default disabled">Список</button>
                <a href="<?= ST::route('AttendanceNew/showSchedule/1/' . $date . "/" . $attendance->id) ?>"
                   class="btn btn-default">График</a>
            </span>
    </h3>
</div>
<div class="clearfix"></div>
<hr>
<div class="col-lg-9">
    <?php include_once(CORE_REPOSITORY_REAL_PATH . "views/attendance/tpl/_search_form.php") ?>
    <?php if (count($filtered_vkses)): ?>
        <table class="table table-striped table-hover small">
            <th class="text-left  col-lg-1">id</th>
            <th class="text-left  col-lg-2">Время</th>
            <th class="text-left  col-lg-2">Название</th>
            <th class="text-left  col-lg-2">Код Вк</th>
            <th class="text-left  col-lg-2">Участники</th>
            <th class="text-left col-lg-1"><span class="glyphicon glyphicon-info-sign" title="Тип ВКС"></span></th>
            <th class="text-left col-lg-1"><span class="glyphicon glyphicon-facetime-video" title="Запись ВКС"></span>
            </th>
            <?php foreach ($filtered_vkses as $vks): ?>
                <tr>
                    <td class="text-left">
                        <?= ST::linkToVksPage($vks->id, true) ?>
                    </td>
                    <td class="text-left">
                        <?= $vks->humanized->startTime ?> - <?= $vks->humanized->endTime ?>
                    </td>
                    <td class="text-left"><?= $vks->title ?></td>

                    <td class="text-left">
                        <?php if (count($vks->connection_codes)): ?>
                            <?php foreach ($vks->connection_codes as $code) : ?>
                                <p>
                                <span class="connection-code-highlighter">
                                    <?= $code->value ?> <?= strlen($code->tip) ? "<sup>({$code->tip})</sup>" : "" ?>
                                </span>
                                </p>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <?php if ($vks->status == VKS_STATUS_PENDING): ?>
                                <span class="connection-code-highlighter-wait">Заявка находится на согласовании администратором ВКС, пожалуйста, подождите</span>
                            <?php else: ?>
                                <span class="text-muted">Код подключения не выдан</span>
                            <?php endif ?>
                        <?php endif ?></td>
                    <td class="text-left">
                        <div class="inside_parp">
                            <ol>

                                <?php if (isset($vks->tb_parp)): ?>
                                    <li class="list-group-item-text">Кол-во участников в ЦА: <span
                                            class="label label-as-badge label-warning"><?= $vks->ca_linked_vks->ca_participants ?></span>
                                    </li>
                                    <?php foreach ($vks->tb_parp as $parp) : ?>
                                        <li class="list-group-item-text"><?= $parp->full_path ?> <span
                                                class="label label-warning label-as-badge">TB</span></li>
                                    <?php endforeach; ?>
                                <?php endif ?>


                                <li class="list-group-item-text"><span
                                        class="glyphicon glyphicon-phone"></span> C рабочих мест (IP телефон, Lynс, CMA
                                    Desktop и т.д.):<span
                                        class="label label-as-badge label-default"><?= $vks->in_place_participants_count ?></span>
                                </li>
                                <?php if ($vks->participants): ?>

                                    <?php foreach ($vks->participants as $parp) : ?>
                                        <li class="list-group-item-text">
                                            <?php if ($parp->container): ?>
                                                <span class="text-success glyphicon glyphicon-folder-open"
                                                      title="Кто-то из контейнера"></span>&nbsp
                                            <?php else: ?>
                                                <span class="text-info glyphicon glyphicon-camera"
                                                      title="Точка"></span>&nbsp
                                            <?php endif; ?><?= Auth::isAdmin(App::$instance) && strlen($parp->ip) ? "<span class='text-primary'>[{$parp->ip}]</span> " : "" ?><?= $parp->full_path ?>
                                        </li>
                                    <?php endforeach; ?>

                                <?php else: ?>

                                <?php endif ?>
                            </ol>
                        </div>
                    </td>
                    <td class="text-left">
                        <?php if ($vks->link_ca_vks_id): ?>
                            <?php if ($vks->other_tb_required): ?>
                                <span class="glyphicon glyphicon-certificate text-primary"
                                      title="ВКС с другим ТБ/ЦА"></span>
                            <?php else: ?>
                                <span class="glyphicon glyphicon-magnet text-primary"
                                      title="ВКС по приглашению ЦА"></span>
                            <?php endif; ?>
                        <?php else: ?>
                            <?= $vks->is_simple ? '<span class="glyphicon glyphicon-sunglasses text-muted" title="Упрощенная ВКС"></span>' : '<span class="glyphicon glyphicon-blackboard text-primary" title="Стандартная ВКС"></span>' ?>
                        <?php endif; ?>

                    </td>
                    <td class="text-left">
                        <?= $vks->record_required ? "<span title='Да, запись заказана' class='glyphicon glyphicon-ok text-success'></span>" : "<span title='Нет, без записи' class='glyphicon glyphicon-remove text-danger'></span>" ?>
                    </td>
                </tr>
            <?php endforeach; ?>

        </table>
    <?php else: ?>
        <?php if ($attendance->id == 1): ?>
            <div class="col-md-6">
                <h4>Выберите точку из каталога</h4>

                <div id="tree_holder">
                </div>
            </div>
            <div class="col-md-6">
                <?php include_once(CORE_REPOSITORY_REAL_PATH . "views/attendance/tpl/_last_seen_attendance.php") ?>
            </div>
            <div class="clearfix"></div>
            <br>
        <?php endif ?>
        <div class="clearfix"></div>
        <?php if ($resuested_att->id != 1): ?>
            <div class="text-center">
                <div class="alert alert-success"><i>Вкс не найдены, переговорная свободна</i></div>
            </div>
        <?php endif ?>
    <?php endif ?>

</div>
<div class="col-md-3">
    <div class="text-center">
        <div class="date-pick"></div>
    </div>
    <?php if ($resuested_att->id != 1): ?>

        <hr>
        <h4>Каталог точек</h4>

        <div id="tree_holder">
        </div>
        <hr>
        <?php include_once(CORE_REPOSITORY_REAL_PATH . "views/attendance/tpl/_last_seen_attendance.php") ?>
    <?php endif ?>
</div>
<div class="clearfix"></div>
<div style="margin-top: 7.0em;">
    &nbsp
</div>
<?php ST::deployTemplate('footer/closeContainer.inc'); ?>
<div class="container100">
    <?php ST::deployTemplate('footer/mainFooter.inc'); ?>
</div>

