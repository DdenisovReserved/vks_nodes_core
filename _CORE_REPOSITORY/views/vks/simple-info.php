<?php
if (!$partial) {
    ST::deployTemplate('heads/ui_timepicker.inc');
    RenderEngine::MenuChanger();
}
//dump($vks);
//dump($vks->owner);
?>

    <div style="width: 960px; margin: 0 auto;">
        <div class="col-lg-12">
            <div class='action-buttons'>
                <div class="col-lg-6 no-left-padding">
                    <h4>Данные ВКС</h4>
                </div>
                <div class="col-lg-6 no-right-padding">
                    <div class="text-right">
                        <div class="text-right">
                            <?php include_once(CORE_REPOSITORY_REAL_PATH . "views/vks/tpl/_action_menu.php") ?>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-hover table-striped table-bordered">
                <tr>
                    <td>id</td>
                    <td><?= $vks->id ?></td>
                </tr>
                <tr>
                    <td>Статус</td>
                    <td><?= $vks->humanized->status_label ?></td>
                </tr>

                <tr>
                    <td>Название</td>
                    <td><?= $vks->title ?></td>
                </tr>
                <tr>
                    <td>Код подключения</td>
                    <td>
                        <?php if (count($vks->connection_codes)): ?>
                            <?php foreach ($vks->connection_codes as $code) : ?>
                                <p>
                                <span class="connection-code-highlighter">
                                    <?= $code->value ?> <?= strlen($code->tip) ? "<sup>({$code->tip})</sup>" : "" ?>
                                </span>
                                </p>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span class='connection-code-highlighter'>Код подключения не выдан</span>
                        <?php endif ?>


                    </td>
                </tr>
                <tr>

                    <td>Дата</td>
                    <td><?= $vks->humanized->date ?></td>
                </tr>
                <tr>
                    <td>Время</td>
                    <td><?= $vks->humanized->startTime ?> - <?= $vks->humanized->endTime ?> </td>
                </tr>
                <tr>

                <tr>
                    <td>Список участников</td>
                    <td>
                        <ul class="list-unstyled">
                            <li class="list-group-item-text"><span
                                    class="glyphicon glyphicon-phone"></span> C рабочих мест (IP телефон, Lynс, CMA
                                Desktop и т.д.): <span class="label label-as-badge label-default"><?= $vks->in_place_participants_count ?>

                                </span></li>
                            <?php if ($vks->participants): ?>

                                <?php foreach ($vks->participants as $parp) : ?>
                                    <li class="list-group-item-text"><span
                                            class="glyphicon glyphicon-camera"></span> <?= $parp->full_path ?></li>
                                <?php endforeach; ?>

                            <?php else: ?>

                            <?php endif ?>

                        </ul>
                </tr>
                <tr>
                    <td>Владелец ВКС</td>
                    <td>
                        <?php if ($vks->owner): ?>
                            <?= $vks->owner->fio ?><br> (<?= $vks->owner->login ?>, т.<?= $vks->owner->phone ?>)
                        <?php else: ?>
                            <span class="text-danger">Владелец не найден</span>
                        <?php endif ?>

                    </td>
                </tr>
                <tr>
                    <td>Запись ВКС</td>
                    <td><?= $vks->record_required ? "Да" : 'Нет' ?></td>
                </tr>

            </table>
            <hr>
            <div class="text-muted">
                <a href="<?= ST::route("Vks/show") . "/" . $vks->id ?>">Ссылка на эту страницу</a>
                <textarea
                    class="form-control"><?= ST::route("Vks/show") . "/" . $vks->id ?></textarea>
            </div>

        </div>

    </div>
    <br><br>
<?php //ST::deployTemplate('footer/mainFooter.inc'); ?>