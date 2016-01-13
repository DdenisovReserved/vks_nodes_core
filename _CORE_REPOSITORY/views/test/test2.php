<?php
include_once(CORE_REPOSITORY_REAL_PATH . "views/main-fluid.php");
RenderEngine::MenuChanger();
//timed_array
ST::setVarPhptoJS($date, 'date');
ST::setVarPhptoJS($attendance->id, 'requested_participant_id');
ST::setUserJs("vks/showAtParticipant_calendar.js");
?>
<script>
    $(document).ready(function () {
        $(".date-pick").datepicker({
            defaultDate: date,
            dateFormat: "yy-mm-dd",
            onSelect: function (date) {
                location.href = "<?php echo App::$instance->opt->appHttpPath; ?>?route=test/test2/" + date + "/" + requested_participant_id;
            },
        });
    })
</script>

    <div class="pull-left">
        <h3 class="text-muted">ВКС в <?= AttendanceNew_controller::makeFullPath($attendance->id); ?>
            на <?= date_create($date)->format("d.m.Y") ?></h3>
    </div>
        <div class="pull-right">
            <h3>
            <span class="btn-group">
                <button type="button" class="btn btn-default disabled">Список</button>
                <a href="<?= ST::route('test/test/'.$date."/".$attendance->id) ?>" class="btn btn-default">График</a>
            </span>
            </h3>
        </div>
    <div class="clearfix"></div>
    <hr>
    <div class="col-lg-9">
        <?php if (count($filtered_vkses)): ?>
            <table class="table table-striped table-hover small">
                <th class="text-center  col-lg-1">id</th>
                <th class="text-center  col-lg-2">Время</th>
                <th class="text-center  col-lg-2">Название</th>
                <th class="text-center  col-lg-2">Код Вк</th>
                <th class="text-center  col-lg-2">Участники</th>
                <th class="text-center col-lg-1"><span class="glyphicon glyphicon-info-sign" title="Тип ВКС"></span></th>
                <th class="text-center col-lg-1"><span class="glyphicon glyphicon-facetime-video" title="Запись ВКС"></span></th>
                <?php foreach ($filtered_vkses as $vks): ?>
                    <tr>
                        <td class="text-center">
                            <?= ST::linkToVksPage($vks->id, true) ?>
                        </td>
                        <td class="text-center">
                            <?= $vks->humanized->startTime ?> - <?= $vks->humanized->endTime ?>
                        </td>
                        <td class="text-center"><?= $vks->title ?></td>

                        <td class="text-center">
                            <?php if (count($vks->connection_codes)): ?>
                                <?php foreach ($vks->connection_codes as $code) : ?>
                                    <p>
                                <span class="connection-code-highlighter">
                                    <?= $code->value ?> <?= strlen($code->tip) ? "<sup>({$code->tip})</sup>" : "" ?>
                                </span>
                                    </p>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <?php if($vks->status == VKS_STATUS_PENDING): ?>
                                    <span class="connection-code-highlighter-wait">Заявка находится на согласовании администратором ВКС, пожалуйста, подождите</span>
                                <?php else: ?>
                                    <span class="connection-code-highlighter">Код подключения не выдан</span>
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
                                                <?php endif; ?><?= Auth::isAdmin(App::$instance) && strlen($parp->ip) ? "<span class='text-primary'>[{$parp->ip}]</span> " : "" ?><?=  $parp->full_path ?>
                                            </li>
                                        <?php endforeach; ?>

                                    <?php else: ?>

                                    <?php endif ?>
                                </ol>
                            </div>
                        </td>
                        <td class="text-center">
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
                        <td class="text-center">
                            <?= $vks->record_required ? "<span title='Да, запись заказана' class='glyphicon glyphicon-ok text-success'></span>" : "<span title='Нет, без записи' class='glyphicon glyphicon-remove text-danger'></span>"?>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </table>
        <?php else: ?>
            <div class="text-center"><i>Список пуст</i></div>
        <?php endif ?>

    </div>
    <div class="col-md-3">
        <div class="date-pick"></div>
    </div>


<div style="margin-top: 10.6em;">
    &nbsp
</div>

