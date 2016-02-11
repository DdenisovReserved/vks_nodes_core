<?php
include_once(CORE_REPOSITORY_REAL_PATH . "views/main-fluid.php");

$vks = (object)$data['vks'];
ST::setUserJs('vks/approve/core.js');
ST::setVarPhptoJS($vks->id, 'vksId');
$p = new ParticipationsV3Assert();
$p->init();
$asrt = new FlotGraphAssert();
$asrt->init();
ST::setUserJs('codes/askFreeCodes.js');
?>
<script type='text/javascript'>
    <?php
    echo "var plotTicks = ". json_encode($graph->plotTicks) . ";\n";
    echo "var randomData = ". json_encode($graph->data) . ";\n";
    echo "var thresholdCou = ". json_encode($graph->threshold) . ";\n";
    ?>
</script>

<script>
    $(document).ready(function () {
        $(document).on('click', '.points_check_where_admin_page', function () {
            dateTimeforCheck = [];
            var coreDT = {
                'date': $(this).data("date"),
                'start_time': $(this).data("start"),
                'end_time': $(this).data("end")
            };

            dateTimeforCheck.push(coreDT);
            var vks_id = [$(this).data("vks_id")];

            var repo = new RepositoryPoints();
            repo.busyAt(Number($(this).prop("id")), dateTimeforCheck, vks_id);
        })
    })
</script>
<div class="container-fluid">
    <div class="col-lg-12">
        <h4>Информация по заявке<span class="pull-right"><a class="btn btn-default btn-sm"
                                                            href="<?= ST::route('vks/edit/' . $vks->id) ?>">
                    Редактировать</a></span></h4>
        <hr>
    </div>
    <div class="col-lg-6">
        <?php if ($vks->link_ca_vks_id): ?>
            <div class="alert alert-danger">Обратите внимание: эта заявка связана с ВКС в ЦА</div>
            <?php if (is_null($caVks)): ?>
                <div class="alert alert-danger">Нам не удалось найти связную ВКС в ЦА, возможно это ошибка системы</div>
            <?php endif ?>

            <?php if (!is_null($caVks)) : ?>
                <?php $flag = false;
                foreach (isset($caVks->inside_parp) ? $caVks->inside_parp : $caVks->participants as $parp): ?>
                    <?php if ($parp->attendance_id == App::$instance->tbId): ?>
                        <?php $flag = True; ?>
                    <?php endif ?>
                <?php endforeach; ?>
                <?php if (!$flag): ?>
                    <div class="alert alert-danger">Обратите внимание: ваш ТБ не заявлен на эту ВКС</div>
                <?php endif ?>
            <?php endif ?>
        <?php endif ?>
        <?php if ($vks->other_tb_required): ?>
            <div class="alert alert-info">В этой ВКС заявлены участники из других ТБ, ВКС в ЦА создана автоматически
                из нашего пула адресов
            </div>
        <?php endif ?>
        <table class="table table-hover table-bordered" id="vks-info-table">
            <?php if ($caVks): ?>
                <th class="compare-td col-lg-1 "></th>
                <th class="compare-td ">В нашей заявке</th>
                <th class="compare-td alert-warning">В ЦА</th>
            <?php endif ?>
            <tr>
                <td class="col-lg-1">id</td>
                <td class="col-lg-5"><?= $vks->id ?></td>
                <?php if ($caVks): ?>
                    <td class="compare-td alert-warning col-lg-5"><?= $caVks->id ?><?= $vks->link_ca_vks_type == VKS_WAS ? ' (С поддержкой администратора)' : ' (Без поддержки администратора)' ?></td>
                <?php endif ?>

            </tr>
            <tr>
                <td>Наименование</td>
                <td><?= $vks->title ?></td>
                <?php if ($caVks): ?>
                    <td class="compare-td alert-warning"><?= $caVks->title ?></td>
                <?php endif ?>
            </tr>
            <tr>
                <td>Дата</td>
                <td><?= $vks->humanized->date ?></td>
                <?php if ($caVks): ?>
                    <td class="compare-td alert-warning"><?= date_create($caVks->date)->format("d.m.Y") ?></td>
                <?php endif ?>
            </tr>
            <tr>
                <td>Время</td>
                <td><?= $vks->humanized->startTime ?> - <?= $vks->humanized->endTime ?></td>
                <?php if ($caVks): ?>
                    <td class="compare-td alert-warning"><?= date_create($caVks->start_date_time)->format("H:i") ?>
                        - <?= date_create($caVks->end_date_time)->format("H:i") ?></td>
                <?php endif ?>
            </tr>
            <tr>
                <td>Подразделение</td>
                <td><?= $vks->department_rel->prefix ?>. <?= $vks->department_rel->name ?></td>
                <?php if ($caVks): ?>
                    <td class="compare-td alert-warning"> -</td>
                <?php endif ?>
            </tr>
            <!--            <tr>-->
            <!--                <td>Инициатор</td>-->
            <!--                <td>--><?php // echo $vks->initiator_rel->name ?><!--</td>-->
            <!--                --><?php //if ($caVks): ?>
            <!--                    <td class="compare-td alert-warning"> -</td>-->
            <!--                --><?php //endif ?>
            <!--            </tr>-->
            <tr>
                <td>Заказчик</td>
                <td><?= $vks->init_customer_fio ?>, тел. <?= $vks->init_customer_phone ?> </td>
                <?php if ($caVks): ?>
                    <td class="compare-td alert-warning"><?= isset($caVks->init_customer_fio) ? $caVks->init_customer_fio : ' - ' ?> </td>
                <?php endif ?>
            </tr>

            <tr>
                <td>Подключать другой ТБ/ЦА</td>
                <td><?= $vks->other_tb_required ? 'Да' : 'Нет' ?> </td>
                <?php if ($caVks): ?>
                    <td class="compare-td alert-warning"> -</td>
                <?php endif ?>
            </tr>
            <tr>
                <td>Код ЦА</td>
                <td><?= $vks->ca_code ? $vks->ca_code . " (?)" : 'Нет' ?> </td>
                <?php if ($caVks): ?>
                    <?php if ($caVks->status == VKS_STATUS_TRANSPORT_FOR_TB || $caVks->status == VKS_STATUS_APPROVED): ?>
                        <td class="compare-td alert-warning">
                    <span
                        class="connection-code-highlighter"
                        style="color: white;"> <?= isset($caVks->v_room_num) ? $caVks->v_room_num : $caVks->connection_code->value ?></span>
                        </td>
                    <?php else: ?>
                        <td class="compare-td alert-danger">
                            <span>Нет (Статус в ЦА: <?= $caVks->humanized->status_label ?>)</span>
                        </td>
                    <?php endif ?>
                <?php endif ?>
            </tr>
            <tr>
                <td>Участники <span
                        class="badge"> <?= count($vks->participants) + $vks->in_place_participants_count ?></span>
                </td>
                <td>
                    <div class="inside_parp no-left-padding">
                        <ol class="">
                            <li class="list-group-item-text"><span
                                    class="glyphicon glyphicon-phone"></span> C рабочих мест (IP телефон, Lynс, CMA
                                Desktop
                                и т.д.): <span
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
                                        <?php if (!$parp->container && !$parp->free): ?>
                                            <span
                                                class='glyphicon glyphicon-warning-sign text-danger points_check_where_admin_page'
                                                id='<?= $parp->id ?>' data-vks_id="<?= $vks->id ?>"
                                                data-start="<?= date_create($vks->start_date_time)->format("H:i") ?>"
                                                data-end="<?= date_create($vks->end_date_time)->format("H:i") ?>"
                                                data-date="<?= date_create($vks->date)->format("Y-m-d") ?>"
                                                title='Точка ВКС занята в другой конференции'></span>
                                        <?php endif ?>
                                    </li>
                                <?php endforeach; ?>

                            <?php endif ?>


                        </ol>
                    </div>
                </td>
                <?php if ($caVks): ?>
                    <td class="compare-td alert-warning">
                        <div class="inside_parp">
                            <ol class="">
                                <?php if (isset($caVks->participants)): ?>
                                    <?php if (isset($caVks->ca_participants)): ?>

                                        <li class="list-group-item-text"><span
                                                class="glyphicon glyphicon-phone"></span> Участники в
                                            ЦА: <?= $caVks->ca_participants ?></li>

                                    <?php endif ?>
                                    <?php foreach ($caVks->participants as $parp) : ?>
                                        <li class="list-group-item-text"><span
                                                class="glyphicon glyphicon-phone"></span> <?= $parp->full_path ?></li>
                                    <?php endforeach; ?>

                                <?php else: ?>
                                    <?php foreach ($caVks->inside_parp as $parp) : ?>
                                        <li class="list-group-item-text"><?= $parp->full_path ?></li>
                                    <?php endforeach; ?>
                                    <?php foreach ($caVks->phone_parp as $parp) : ?>
                                        <li class="list-group-item-text"><?= $parp->phone_num ?></li>
                                    <?php endforeach; ?>
                                    <?php $c = 1;
                                    foreach ($caVks->outside_parp as $parp) : ?>
                                        <a href="<?= $parp->attendance_value ?>" class="list-group-item-text"><span
                                                class="glyphicon glyphicon-file"></span>Участник #<?= $c ?></a>
                                        <?php $c++;
                                    endforeach; ?>
                                <?php endif ?>
                            </ol>
                        </div>
                    </td>
                <?php endif ?>

            </tr>
            <td>Комментарий для администратора</td>
            <td><?= $vks->comment_for_admin ?></td>
            <?php if ($caVks): ?>
                <?php
                $linkCode = ST::routeToInviteCompressor($caVks->referral);
                ?>
                <td class="compare-td alert-warning">Ссылка-приглашениe на ВКС<br>
                    <input style="width: 100%; padding: 5px; border: 1px solid #ccc;" value="<?= $linkCode ?>"/></td>

            <?php endif ?>
            </tr>
            <tr>
                <td>Создал пользователь</td>
                <td><?= $vks->owner ? $vks->owner->login : '<span class="text-danger"> Владелец не найден</span>' ?></td>
                <?php if ($caVks): ?>
                    <td class="compare-td alert-warning"><?= $caVks->owner->login ?></td>
                <?php endif ?>
            </tr>
            <tr>
                <td>Создано</td>
                <td><?= date_create($vks->created_at)->format("d.m.Y H:i") ?></td>
                <?php if ($caVks): ?>
                    <td class="compare-td alert-warning"> <?= date_create($caVks->created_at)->format("d.m.Y H:i") ?></td>
                <?php endif ?>
            </tr>
            <tr>
                <td>Запись ВКС</td>
                <td><?= $vks->record_required ? "<span class='text-danger'>Да</span>" : 'Нет' ?></td>
                <?php if ($caVks): ?>
                    <td>-</td>
                <?php endif ?>
            </tr>
            <tr>
                <td>Приватная ВКС</td>
                <td><?= $vks->is_private ? "<span class='text-success'>Да</span>" : 'Нет' ?></td>
                <?php if ($caVks): ?>
                    <td>-</td>
                <?php endif ?>
            </tr>

        </table>
        <hr>
        <h4>Заявки на тех. поддержку</h4>
        <?php include_once(CORE_REPOSITORY_REAL_PATH . "views/techsupport/tpl/_call_table.php") ?>

    </div>
    <!--    submission form -->
    <div class="col-lg-6 left-border" style="padding-left: 25px;">
        <?php if (!$vks->isPassebByCapacity): ?>
            <div class="alert alert-danger text-center"><h4 class="">Внимание!</h4> ВКС не проходит по
                производительности сервера, см. график нагрузки
            </div>
        <?php endif ?>
        <?php if ($vks->stack): ?>

            <p class="alert alert-danger">Эта ВКС в стеке (создана за один раз) со следующими ВКС</p>
            <div class="well">
                <table class="table table-bordered">
                    <th>id</th>
                    <th>Статус</th>
                    <th>Дата/время</th>
                    <th>Коды</th>
                    <?php foreach ($vks->stack->vkses as $stackVks): ?>
                        <?php if ($stackVks->id != $vks->id): ?>
                            <tr>
                                <td><?= ST::linkToVksPage($stackVks->id, true) ?></td>
                                <td><?= $stackVks->humanized->status_label ?></td>
                                <td><?= $stackVks->humanized->date ?>, <?= $stackVks->humanized->startTime ?>
                                    - <?= $stackVks->humanized->endTime ?></td>
                                <td>
                                    <?php if (count($stackVks->connection_codes)): ?>
                                        <ul>
                                            <?php foreach ($stackVks->connection_codes as $code) : ?>
                                                <li class=""><h5><?= $code['value'] ?><sup>(<?= $code['tip'] ?>)</sup>
                                                    </h5></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        Нет кодов, или они не выданы
                                    <?php endif ?>

                                </td>
                            </tr>
                        <?php endif ?>
                    <?php endforeach ?>
                </table>
            </div>
        <?php endif ?>
        <?php if ($last_version && count($last_version['connection_codes'])): ?>
            <div class="alert alert-danger"><p>Для ВКС уже выдавались код(ы) </p>

                <p>
                <ul>
                    <?php foreach ($last_version['connection_codes'] as $code) : ?>
                        <li class=""><h4><?= $code['value'] ?><sup>(<?= $code['tip'] ?>)</sup></h4></li>
                    <?php endforeach; ?>
                </ul>
                </p>
                <p class="alert alert-warning">Возможно ВКС отредактировали или перевели в статус "на согласование".<br>

                    Сейчас код не привязан к этой ВКС.</p>
            </div>

        <?php endif ?>
        <form class="form-horizontal" method="post" action="<?= ST::route("Vks/process/" . $vks->id) ?>">
            <?= Token::castTokenField() ?>
            <div class="form-group ">
                <h4 class="text-muted">Форма согласования</h4>
            </div>

            <div class="form-group">
                <h4>Код подключения
                    <button class="btn btn-default btn-sm" id="askCodes"
                            onclick="askFreeCodes('#askCodes','<?= $vks->start_date_time ?>', '<?= $vks->end_date_time ?>')"
                            type="button">
                        <span class="glyphicon glyphicon-question-sign"></span> <span class="text">Показать таблицу занятости кодов</span>
                    </button>
                </h4>
                <?php if ($vks->link_ca_vks_id && (is_null($caVks) || !($caVks->status == VKS_STATUS_APPROVED || $caVks->status == VKS_STATUS_TRANSPORT_FOR_TB))): ?>
                    <div class="alert alert-danger">!ВНИМАНИЕ: Статус ВКС в ЦА не позволяет никому
                        подключаться к ней
                    </div>
                <?php else: ?>
                <button type="button" class="btn btn-sm btn-success" name="add">+ код из шаблона</button>
                <button type="button" class="btn btn-sm btn-info" name="manual">+ код вручную</button>
                <br><br>


                <table class="code-table table table-striped" data-rows="1">
                    <th style="width: 25px;"></th>
                    <th class="col-lg-2">Шаблон</th>
                    <th class="col-lg-2">Префикс</th>
                    <th class="col-lg-1">Постфикс</th>
                    <th style="width: 125px;">Подсказка для пользователя</th>
                    <th style="width: 25px;"></th>
                    <tr>
                        <td colspan="6" class="emptyly"><i>Нет ни одного кода, выберите что-нибудь</i></td>
                    </tr>
                </table>

                <!--                    <div class="col-lg-4">-->
                <!--                        <button type="button" class="manual-code btn btn-info"-->
                <!--                            >Ввести вручную-->
                <!--                        </button>-->
                <!---->
                <!--                    </div>-->

                <!--                    build it-->

                </ul>
            </div>
            <div class="form-group alert alert-warning">
                <div class="checkbox">
                    <label>
                        <input type='checkbox' name='no-codes' data-checked="0"> <b>Согласовать без кода</b>
                    </label>
                </div>
            </div>
            <div class="form-group alert alert-danger">
                <div class="checkbox">
                    <label>
                        <input type='checkbox' name='no-check'> <b>Игнорировать проверку кодов</b>
                    </label>
                </div>
            </div>
            <?php endif ?>
            <div class="form-group">
                <label>Комментарий для пользователя</label>
                <textarea name="comment_for_user" maxlength="160" rows="4" class="form-control"></textarea>
            </div>

            <?= ST::setUpErrorContainer() ?>

            <div class="form-group text-center">
                <?php if (!($vks->link_ca_vks_id && (is_null($caVks) || !($caVks->status == VKS_STATUS_APPROVED || $caVks->status == VKS_STATUS_TRANSPORT_FOR_TB)))): ?>
                    <button class="btn btn-success btn-lg submit" name="status" type="button"
                            value="<?= VKS_STATUS_APPROVED ?>">
                        Утвердить
                    </button>

                <?php endif ?>
                <button class="btn btn-warning  btn-lg" name="status" type="submit" value="<?= VKS_STATUS_DECLINE ?>">
                    Отклонить
                </button>
            </div>

        </form>

        <?php if (count($versions)): ?>
            <h4>У ВКС есть версии:</h4>
            <hr>
            <ul>
                <?php foreach ($versions as $version) : ?>
                    <li><a href="<?= ST::route("VksVersion/compare/" . $vks->id . "/0/" . $version->version) ?>"><span
                                class="glyphicon glyphicon-list-alt"></span> Версия <?= $version->version ?></a><br>
                        (<?= date_create($version->created_at)->format("d.m.Y H:i:s") ?>) by
                        [<?= $version->changer->login ?>]
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="alert alert-warning">У этой ВКС нет версий</div>
        <?php endif ?>


    </div>
    <!--    submission form -->
    <div class="clearfix"></div>
    <hr>
    <div class="col-lg-12" style="margin-bottom: 200px;">
        <h3>График нагрузки на <?= $vks->humanized->date ?></h3>
        <hr>
        <div class="demo-container">

            <div>
                <div id="placeholder" class="demo-placeholder" style="width:100%;height:450px"></div>
            </div>
            <div class="clearfix"></div>
            <div>
                <button class="btn btn-default btn-sm pull-right" id="reset" type="button">Сброс</button>
            </div>


        </div>
    </div>
</div>

<?= ST::setUserJs('graph/renderMainGraph.js') ?>


