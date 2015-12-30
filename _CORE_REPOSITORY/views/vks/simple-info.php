<?php
ST::deployTemplate('heads/ui_timepicker.inc');
RenderEngine::MenuChanger();
//dump($vks);
//dump($vks->owner);
?>

    <div class="container">
        <div class="col-lg-2">
        </div>
        <div class="col-lg-7 left-border">
            <div class='action-buttons'>
                <div class="col-lg-6 no-left-padding">
                    <h4>Данные ВКС</h4>
                </div>
                <div class="col-lg-6 no-right-padding">
                    <div class="text-right">
                        <div class="btn-group" role="group" aria-label="...">

                            <?php if($vks->humanized->isCloneable): ?>
                                <a class='btn btn-default btn-sm' href='?route=Vks/makeClone/<?= $vks->id ?>'  title='Клонировать'><span class='glyphicon glyphicon-duplicate'></span></a>
                            <?php else: ?>
                                <span class='btn btn-default btn-sm' href='' disabled title='Клонировать'><span class='glyphicon glyphicon-duplicate'></span></span>
                            <?php endif; ?>

                            <?php if($vks->humanized->isCodePublicable): ?>
                                <a class='btn btn-default btn-sm' href='?route=Vks/publicStatusChange/<?= $vks->id ?>'  title='Изменить видимость кода'><span class='glyphicon glyphicon-eye-open'></span></a>
                            <?php else: ?>
                                <span class='btn btn-default btn-sm' href='' disabled title='Изменить видимость кода'><span class='glyphicon glyphicon-eye-open'></span></span>
                            <?php endif; ?>

                            <?php if($vks->humanized->isEditable): ?>
                                <a class='btn btn-info btn-sm' href='?route=Vks/edit/<?= $vks->id ?>'  title='Редактировать'><span class='glyphicon glyphicon-edit'></span></a>
                            <?php else: ?>
                                <span class='btn btn-default btn-sm' href='' disabled title='Редактировать'><span class='glyphicon glyphicon-edit'></span></span>
                            <?php endif; ?>

                            <?php if($vks->humanized->isDeletable): ?>
                                <?php if(Auth::isLogged(App::$instance) && Auth::isAdmin(App::$instance)): ?>
                                    <a class='btn btn-danger btn-sm' href='?route=Vks/annulate/<?= $vks->id ?>'  title='Аннулировать'><span class='glyphicon glyphicon-remove-sign'></span></a>
                                <?php elseif(Auth::isLogged(App::$instance)): ?>
                                    <a class='btn btn-danger btn-sm confirmation' href='?route=Vks/cancel/<?= $vks->id ?>'  title='Аннулировать'><span class='glyphicon glyphicon-remove-sign'></span></a>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class='btn btn-default btn-sm' href='' disabled title='Аннулировать'><span class='glyphicon glyphicon-remove-sign'></span></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-hover table-striped">
                <tr>
                    <td>id</td>
                    <td><?= $vks->id ?></td>
                </tr>
                <tr>
                    <td>Статус:</td>
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
                Ссылка на эту страницу
                <textarea
                    class="form-control"><?= ST::route("Vks/show") . "/" . $vks->id ?></textarea>
            </div>

        </div>

    </div>
    <br><br>
<?php //ST::deployTemplate('footer/mainFooter.inc'); ?>