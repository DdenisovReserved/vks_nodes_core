<?php
if (!$partial) {
    ST::deployTemplate('heads/ui_timepicker.inc');
    RenderEngine::MenuChanger();
}
?>
    <div style="width: 960px; margin: 0 auto;">
        <div class="col-lg-12">
            <div class='action-buttons'>
                <div class="col-lg-6 no-left-padding">
                    <h4>Данные по ВКС
                        </h4>
                </div>
                <div class="col-lg-6 no-right-padding">
                    <div class="text-right">
                        <?php include_once(CORE_REPOSITORY_REAL_PATH . "views/vks/tpl/_action_menu.php") ?>
                    </div>
                </div>
            </div>
            <table class="table table-hover table-striped table-bordered">
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
                    <td>Статуc</td>
                    <td><?= $vks->humanized->status_label ?></span></td>
                </tr>

                <tr>
                    <td>Название</td>
                    <td>
                        <?= $vks->is_global ? "<span class='label label-danger'>Global</span>" : '' ?>
                        <?= $vks->flag ? "<span class='label label-danger'>Важная!</span>" : '' ?>
                        <?= $vks->title ?></td>
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
                            <?php if ($vks->status == VKS_STATUS_PENDING): ?>
                                <span class="connection-code-highlighter-wait">Заявка находится на согласовании администратором ВКС, пожалуйста, подождите</span>
                            <?php else: ?>
                                <span class="connection-code-highlighter">Код подключения не выдан</span>
                            <?php endif ?>
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
                    <td>Согласовал</td>
                    <td>
                        <?php if (isset($vks->approver)): ?>
                            <?= $vks->approver->login ?>, тел.<?= $vks->approver->phone ?>
                        <?php else: ?>
                            <span class="text-danger">Адмнистратор не согласовал эту ВКС</span>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td>Комментарий для пользователя</td>
                    <td><?= strlen($vks->comment_for_user) ? $vks->comment_for_user : '-' ?> </td>
                </tr>
                <tr>
                    <td>Список участников <span
                            class="label label-as-badge label-default"><?= $vks->participants_count ?></span></td>
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
                    <td>Подключать другой ТБ/ЦА</td>
                    <td><?= $vks->other_tb_required ? 'Да' : 'Нет' ?> </td>
                </tr>
                <?php if($vks->other_tb_required && !is_numeric($vks->link_ca_vks_id) && $vks->status == VKS_STATUS_APPROVED): ?>
                    <tr>
                        <td>Участники в ЦА и других ТБ (Без транспортной ВКС)</td>
                        <td>
                            <?php if(isset($vks->CaIdParticipants) && count($vks->CaIdParticipants)): ?>
                            <p>
                            С рабочих мест в ЦА: <span
                                class="label label-as-badge label-default"><?= $vks->CaInPlaceParticipantCount->participants_count ?></span>
                        </p>
                        <p>
                            Заявленные ТБ:
                        <ol>
                            <?php foreach ($vks->CaIdParticipants as $ca_id): ?>
                                <li><span
                                        class="glyphicon glyphicon-facetime-video text-success"></span> <?= CAAttendance::find($ca_id->ca_att_id)->name ?>
                                </li>
                            <?php endforeach ?>
                        </ol>
                        </p>
                        <?php else: ?>
                        <p>Не определено</p>
                        <?php endif ?>
                        </td>
                    </tr>
                <?php endif ?>
                <tr class="hidden">
                    <td>Тех. поддержка в точках</td>
                    <td>
                        <ol class="">
                            <?php if (count($vks->tech_support_requests)): ?>
                                <?php foreach ($vks->tech_support_requests as $request): ?>
                                    <?php if($request->status != TechSupportRequest::STATUS_USER_REFUSE): ?>
                                        <li>
                                        <span class="text-info glyphicon glyphicon-camera"
                                              title="Точка">&nbsp</span><?= $request->attendance->full_path ?>
                                        </li>
                                    <?php endif ?>

                                <?php endforeach ?>
                            <?php else: ?>
                                <i>-</i>
                            <?php endif ?>
                        </ol>
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
                <a href="<?= ST::route("Vks/show") . "/" . $vks->id ?>">Ссылка на эту страницу</a>
                <textarea
                    class="form-control"><?= ST::route("Vks/show") . "/" . $vks->id ?></textarea>
            </div>

        </div>

    </div>
    <br><br>
<?php //ST::deployTemplate('footer/mainFooter.inc'); ?>