<?php
include_once(CORE_REPOSITORY_REAL_PATH . "views/main-fluid.php");
ST::setVarPhptoJS($date, 'currentDate');
//dump($vkses);
?>
<script>
    $(document).ready(function () {
        $('#btn_another_date').click(function ()
        {
            $('#dp_another_date').datepicker();
            $('#dp_another_date').datepicker("show");
        });
        $(document).on('change','#dp_another_date', function() {
            location.href = '?route=Vks/index/'+$(this).val();
        })

    })
</script>
<div class="col-lg-12">
    <h3 class="text-center"><input class="hidden" id="dp_another_date" disabled/><button type="button" class="btn btn-info btn-sm pull-left" id="btn_another_date">Другая дата</button>Список ВКС на <b><?= $date ?></b><a class="pull-right btn btn-default btn-sm"
                                                         href="<?= ST::route('Index/index') ?>">Вернуться
            в
            календарь</a></h3>
    <hr>
</div>
<div class="col-md-12">
    <?php if (!count($vkses)): ?>
        <div class="alert alert-info text-center">Список пуст</div>
    <?php else: ?>
        <table class="table table-striped table-hover small">
            <th class="text-left col-lg-1"><a href="<?= RenderEngine::makeOrderLink('id') ?>">id</a></th>
            <th class="text-left col-lg-2"><a href="<?= RenderEngine::makeOrderLink('start_date_time') ?>">Время</a></th>
            <th class="text-left col-lg-3"><a href="<?= RenderEngine::makeOrderLink('title') ?>">Название</a></th>

            <th class="text-left col-lg-2">Код Вк</th>
            <th class="text-left col-lg-2">Участники</th>
            <th class="text-left col-lg-1"><span class="glyphicon glyphicon-info-sign" title="Тип ВКС"></span></th>
            <th class="text-left col-lg-1"><span class="glyphicon glyphicon-facetime-video" title="Запись ВКС"></span></th>
            <?php foreach ($vkses as $vks): ?>
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
                            <?php if($vks->status == VKS_STATUS_PENDING): ?>
                                <span class="connection-code-highlighter-wait">Заявка находится на согласовании администратором ВКС, пожалуйста, подождите</span>
                            <?php else: ?>
                                <span class="text-muted">Код подключения не выдан</span>
                            <?php endif ?>
                        <?php endif ?></td>
                    <td class="text-left">
                        <div class="inside_parp">
                            <ol class="no-left-padding">

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
                    <td>
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
                    <td>
                        <?= $vks->record_required ? "<span title='Да, запись заказана' class='glyphicon glyphicon-ok text-success'></span>" : "<span title='Нет, без записи' class='glyphicon glyphicon-remove text-danger'></span>"?>
                    </td>
                </tr>
            <?php endforeach; ?>

        </table>
        <?= $pages ?>
    <?php endif; ?>
</div>
</div>