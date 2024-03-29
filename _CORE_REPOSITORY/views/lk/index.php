<?php
ST::deployTemplate('heads/ui_timepicker.inc');

RenderEngine::MenuChanger();
ST::setUserCss('render-vks.css');
ST::setUserJs('users/core.js');
ST::setUserCss('users/lk.css');
ST::setUserJs('users/core.js');
//dump($vksList);
ST::setVarPhptoJS(HTTP_PATH, 'core_http_path');
ST::setVarPhptoJS(HTTP_BASE_PATH, 'base_http_path');
?>
<div class="container-fluid">
    <div class="col-md-12">
        <h3 class="text-success">Мои видеоконференции</h3>

        <div class="lk-menu">
            <h4>
                <span class="text-muted">Показать:</span>
                <?php if ($filter != 'all'): ?>
                    <a href="<?= ST::route("Lk/show/all") ?>" class="lk-menu-item menu-span like-href">Все</a>
                <?php else: ?>
                    <span class="lk-menu-item menu-span text-warning">Все</span>
                <?php endif; ?>

                <?php if ($filter != 'meaning'): ?>
                    <a href="<?= ST::route("Lk/show/meaning") ?>" class="lk-menu-item menu-span like-href">Без
                        удаленных</a>
                <?php else: ?>
                    <span class="lk-menu-item menu-span text-warning">Без удаленных</span>
                <?php endif; ?>

                <?php if ($filter != 'status'): ?>
                    <a href="<?= ST::route("Lk/show/status") ?>"
                       class="lk-menu-item menu-span like-href">Утвержденные</a>
                <?php else: ?>
                    <span class="lk-menu-item menu-span text-warning">Утвержденные</span>
                <?php endif; ?>

                <?php if ($filter != 'pending'): ?>
                    <a href="<?= ST::route("Lk/show/pending") ?>" class="lk-menu-item menu-span like-href">На
                        согласовании</a>
                <?php else: ?>
                    <span class="lk-menu-item menu-span text-warning">На согласовании</span>
                <?php endif; ?>

                <?php if ($filter != 'deleted'): ?>
                    <a href="<?= ST::route("Lk/show/deleted") ?>" class="lk-menu-item menu-span like-href">Удаленные и
                        аннулированные</a>
                <?php else: ?>
                    <span class="lk-menu-item menu-span text-warning">Удаленные и
                        аннулированные</span>
                <?php endif; ?>


            </h4>
        </div>
        <hr>
        <?php if (count($vksList) == 0) : ?>
            <div class="alert alert-info"><i>Список пуст</i></div>
            <?php die(); endif; ?>
        <table class="table table-bordered">
            <th><a href="<?= RenderEngine::makeOrderLink('id') ?>">id</a></th>
            <th class="col-lg-1"><a href="<?= RenderEngine::makeOrderLink('status') ?>">Статус</a></th>
            <th class="col-lg-1"><a href="<?= RenderEngine::makeOrderLink('date') ?>">Дата</a></th>
            <th class="col-lg-1"><a href="<?= RenderEngine::makeOrderLink('start_date_time') ?>">Время</a></th>
            <th class="col-lg-3"><a href="<?= RenderEngine::makeOrderLink('title') ?>">Название</a></th>
            <th class="col-lg-2">Код подключения</th>
            <th class="text-center"><span class="glyphicon glyphicon-info-sign" title="Тип ВКС"></span></th>
            <th class="text-center"><span class="glyphicon glyphicon-facetime-video" title="Запись ВКС"></span></th>
            <th class="text-center"><span class="glyphicon glyphicon-cog" title="Действия"></span></th>

            <?php foreach ($vksList as $vks) : ?>
                <tr class="<?php
                if ($vks['status'] == VKS_STATUS_PENDING)
                    echo 'well';
                else if (!in_array($vks['status'], [VKS_STATUS_PENDING, VKS_STATUS_APPROVED ]))
                    echo 'alert alert-danger disabled';
                ?>">
                    <td><?= ST::linkToVksPage($vks['id'], true) ?></td>
                    <td class="">
                        <?= $vks->humanized->status_label ?>
                    </td>
                    <td><?= @$vks['humanized']->date ?></td>
                    <td><?= @$vks['humanized']->startTime ?> - <?= $vks['humanized']->endTime ?></td>
                    <td><?= @$vks['title'] ?></td>
                    <td>
                        <?php if (count($vks->connection_codes)): ?>
                            <?php foreach ($vks->connection_codes as $code) : ?>
                                <p>
                                <span class="connection-code-highlighter-compact">
                                    <?= $code->value ?> <?= strlen($code->tip) ? "<sup>({$code->tip})</sup>" : "" ?>
                                </span>
                                </p>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <?php if($vks->status == VKS_STATUS_PENDING): ?>
                                <span class="connection-code-highlighter-compact-wait">Заявка находится на согласовании администратором ВКС, пожалуйста, подождите</span>
                            <?php else: ?>
                                <span class="connection-code-highlighter-compact">Код подключения не выдан</span>
                            <?php endif ?>
                        <?php endif ?>


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
                    <td class="text-center">
                        <?php include_once(CORE_REPOSITORY_REAL_PATH . "views/vks/tpl/_action_menu.php") ?>


                    </td>
                </tr>
            <?php endforeach; ?>

        </table>
        <?= $data['pages'] ?>
    </div>
</div>