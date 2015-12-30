<?php
ST::deployTemplate('heads/ui_timepicker.inc');
RenderEngine::MenuChanger();
//foreach($vks->participants as $parp) {
//    dump($parp->toArray());
//};
?>
    <div class="container">
        <div class="col-lg-2">
        </div>
        <div class="col-lg-9 left-border">
            <div class='action-buttons'>
                <div class="col-lg-6 no-left-padding">
                    <h4>Данные ВКС</h4>
                </div>
                <div class="col-lg-6 no-right-padding">
                    <div class="text-right">
                        <div class="btn-group" role="group" aria-label="...">

                            <?php if ($vks->status == VKS_STATUS_PENDING && Auth::isAdmin(App::$instance)): ?>
                                <a class='btn btn-warning btn-sm' href='?route=Vks/showNaVks/<?= $vks->id ?>'
                                   title='Согласовать'><span class='glyphicon glyphicon-ok-sign'></span></a>
                            <?php endif ?>
                            <?php if ($vks->humanized->isOutlookable): ?>
                                <a class='btn btn-info btn-sm'
                                   href='?route=OutlookCalendarRequest/pushToStack/<?= $vks->id ?>'
                                   title='Отправить приглашение в мой календарь Outlook'><span
                                        class='glyphicon glyphicon-calendar'></span></a>
                                <?php else: ?>
                                <span class='btn btn-default btn-sm' href='' disabled title='Отправить приглашение в мой календарь Outlook'><span
                                        class='glyphicon glyphicon-calendar'></span></span>
                            <?php endif ?>

                            <?php if ($vks->humanized->isCloneable): ?>
                                <a class='btn btn-default btn-sm' href='?route=Vks/makeClone/<?= $vks->id ?>'
                                   title='Клонировать'><span class='glyphicon glyphicon-duplicate'></span></a>
                            <?php else: ?>
                                <span class='btn btn-default btn-sm' href='' disabled title='Клонировать'><span
                                        class='glyphicon glyphicon-duplicate'></span></span>
                            <?php endif; ?>

                            <?php if ($vks->humanized->isCodePublicable): ?>
                                <a class='btn btn-default btn-sm' href='?route=Vks/publicStatusChange/<?= $vks->id ?>'
                                   title='Изменить видимость кода'><span
                                        class='glyphicon glyphicon-eye-open'></span></a>
                            <?php else: ?>
                                <span class='btn btn-default btn-sm' href='' disabled
                                      title='Изменить видимость кода'><span class='glyphicon glyphicon-eye-open'></span></span>
                            <?php endif; ?>

                            <?php if ($vks->humanized->isEditable): ?>
                                <a class='btn btn-info btn-sm' href='?route=Vks/edit/<?= $vks->id ?>'
                                   title='Редактировать'><span class='glyphicon glyphicon-edit'></span></a>
                            <?php else: ?>
                                <span class='btn btn-default btn-sm' href='' disabled title='Редактировать'><span
                                        class='glyphicon glyphicon-edit'></span></span>
                            <?php endif; ?>

                            <?php if ($vks->humanized->isDeletable): ?>
                                <?php if (Auth::isLogged(App::$instance) && Auth::isAdmin(App::$instance)): ?>
                                    <a class='btn btn-danger btn-sm' href='?route=Vks/annulate/<?= $vks->id ?>'
                                       title='Аннулировать'><span class='glyphicon glyphicon-remove-sign'></span></a>
                                <?php elseif (Auth::isLogged(App::$instance)): ?>
                                    <a class='btn btn-danger btn-sm confirmation'
                                       href='?route=Vks/cancel/<?= $vks->id ?>' title='Аннулировать'><span
                                            class='glyphicon glyphicon-remove-sign'></span></a>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class='btn btn-default btn-sm' href='' disabled title='Аннулировать'><span
                                        class='glyphicon glyphicon-remove-sign'></span></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-hover table-striped">
                <tr>
                    <td width="30%">id</td>
                    <td>
                        <?= $vks->id ?>
                        <!-- try link to CA-->
                        <?php
                        if (isset($vks->ca_linked_vks)) {
                            if ($vks->link_ca_vks_type == 0)
                                echo "-> в ЦА: " . ST::linkToCaVksPage($vks->ca_linked_vks->id);
                            else
                                echo "-> в ЦА: " . ST::linkToCaNsVksPage($vks->ca_linked_vks->id);
                            echo $vks->link_ca_vks_type == VKS_WAS ? ' (С поддержкой администратора)' : ' (Без поддержки администратора)';
                            if (isset($vks->tb_parp)) {
                                echo " (Транспортная)";
                            }
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Статус:</td>
                    <td><?= $vks->humanized->status_label ?></span></td>
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
                            <span class="connection-code-highlighter">Код подключения не выдан</span>
                        <?php endif ?>


                    </td>
                </tr>
                <tr>

                    <td>Дата</td>
                    <td><?= $vks->humanized->date ?></td>
                </tr>
                <tr>
                    <td>Время</td>
                    <td>
                        <?= $vks->humanized->startTime ?> - <?= $vks->humanized->endTime ?>
                        <?php if ($vks->ca_linked_vks): ?>
                            (Мск: <?= $vks->ca_linked_vks->startTime ?> - <?= $vks->ca_linked_vks->endTime ?>)
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td>Подразделение</td>
                    <td><?= $vks->department_rel->prefix ?>. <?= $vks->department_rel->name ?></td>
                </tr>
                <!--                <tr>-->
                <!--                    <td>Инициатор</td>-->
                <!--                    <td>--><?php //  echo $vks->initiator_rel->name ?><!--</td>-->
                <!--                </tr>-->
                <tr>
                    <td>Заказчик</td>
                    <td><?= $vks->init_customer_fio ?>, тел. <?= $vks->init_customer_phone ?> </td>
                </tr>
                <tr>
                    <td>Подключать другой ТБ/ЦА</td>
                    <td><?= $vks->other_tb_required ? 'Да' : 'Нет' ?> </td>
                </tr>
                <!--                <tr>-->
                <!--                    <td>Код ЦА</td>-->
                <!--                    <td>--><?php // echo  $vks->ca_code ? $vks->ca_code : '-' ?><!-- </td>-->
                <!--                </tr>-->
                <tr>
                    <td>Согласовал</td>
                    <td>
                        <?php if (isset($vks->approver)): ?>
                            <?= $vks->approver->login ?>, тел.<?= $vks->approver->phone ?>
                        <?php else: ?>
                            <span class="text-danger">Адмнистратор не согласовал эту ВКС</span>
                        <?php endif ?>
                    </td>
                </tr>
                <!--                <tr>-->
                <!--                    <td>Комментарий для администратора</td>-->
                <!--                    <td>--><?php //echo $vks->comment_for_admin ?><!-- </td>-->
                <!--                </tr>-->
                <tr>
                    <td>Комментарий для пользователя</td>
                    <td><?= strlen($vks->comment_for_user) ? $vks->comment_for_user : '-' ?> </td>
                </tr>
                <tr>
                    <td>Список участников</td>
                    <td>
                        <div class="inside_parp">
                            <ol class="">

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

                        <!--                        <script>showOnly(".list-unstyled", 10)</script>-->
                    </td>
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
                <?php if ($vks->humanized->im_owner && isset($vks->tb_parp)): ?>

                    <tr>
                        <td>Приглашение для других ТБ<sup><i title='Видно только вам'>*</i></sup></td>
                        <td>


                            <b><span
                                    class='referral-code-highlighter'><?= ST::makeInviteLink($vks->ca_linked_vks) ?></span></b>
                        </td>
                    </tr>
                <?php endif ?>
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