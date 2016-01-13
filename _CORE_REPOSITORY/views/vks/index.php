<?php
include_once(CORE_REPOSITORY_REAL_PATH . "views/main-fluid.php");
ST::setVarPhptoJS($date, 'currentDate');
//dump($vkses);
?>
<script>
    $(document).ready(function () {
        $(".date-pick").datepicker({
            onSelect: function (date) {
                location.href = "<?php echo App::$instance->opt->appHttpPath; ?>?route=Vks/index/" + date;
            },
            defaultDate: currentDate
        });
    })
</script>
<div class="col-lg-12">
    <h3 class="text-center">Список ВКС на <?= $date ?><a class="pull-right btn btn-default"
                                                         href="<?= ST::route('Index/index') ?>">Вернуться
            в
            календарь</a></h3>
    <hr>
</div>
<div class="col-md-9 right-border">
    <?php if (!count($vkses)): ?>
        <div class="alert alert-info text-center">Список пуст</div>
    <?php else: ?>
        <table class="table table-striped table-hover small">
            <th class="text-center"><a href="<?= RenderEngine::makeOrderLink('id') ?>">id</a></th>
            <th class="text-center"><a href="<?= RenderEngine::makeOrderLink('title') ?>">Название</a></th>
            <th class="text-center"><a href="<?= RenderEngine::makeOrderLink('date') ?>">Дата</a></th>
            <th class="text-center"><a href="<?= RenderEngine::makeOrderLink('start_date_time') ?>">Время</a></th>
            <th class="text-center">Код Вк</th>
            <th class="text-center">Участники</th>
            <th class="text-center"><span class="glyphicon glyphicon-info-sign" title="Тип ВКС"></span></th>
            <th class="text-center"><span class="glyphicon glyphicon-facetime-video" title="Запись ВКС"></span></th>
            <?php foreach ($vkses as $vks): ?>
                <tr>
                    <td class="text-center">
                        <?= ST::linkToVksPage($vks->id, true) ?>
                    </td>
                    <td class="text-center"><?= $vks->title ?></td>
                    <td class="text-center"><?= $vks->humanized->date ?></td>
                    <td class="text-center">
                        <?= $vks->humanized->startTime ?> - <?= $vks->humanized->endTime ?>
                    </td>
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
<div class="col-md-3">
    <div class="date-pick"></div>
</div>
</div>