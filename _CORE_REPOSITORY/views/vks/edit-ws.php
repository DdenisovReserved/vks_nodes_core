<?php
ST::deployTemplate('heads/ui_timepicker.inc');

ST::setVarPhptoJS("vks_participants_edit", "formCookieParticipantsName", true, true, true);

RenderEngine::MenuChanger();
?>
<div class="container">
    <div class="col-lg-12 text-right">
        <h4 class="text-muted">Редактировать заявку на ВКС</h4><hr>
    </div>
    <div class="col-lg-3">
        <div id="blocked-time-list" class="hidden"></div>
        <div class="alert alert-danger">Любые изменения в заявке согласовываются с администорами (ВКС получает статус "Согласование")
        </div>
        <div class="alert alert-info">Текущий статус заявки: <?= $vks->humanized->status_label ?>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                Коды подключения к ВКС
            </div>
            <div class="panel-body">
                <?php if (isset($vks->connection_codes) && $vks->status == VKS_STATUS_APPROVED): ?>
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
                <?php endif ?>
            </div>
        </div>
    </div>
    <div class="col-md-9 left-border padding25">



        <form id="form1" data-id="<?= $vks->id ?>" action="?route=Vks/update/<?= $vks->id ?>"
              class="form-horizontal" name="form1"
              method="post">
            <?= Token::castTokenField(); ?>


            <div class="vks-time-line form-step-container">

                <div class="form-group">

                    <div class="time-params-edited time_container">
                        <div class="col-md-4 no-left-padding">
                            <label><h4>Дата</h4></label>
                            <input data-vks_blocked_type="0" name="date" id="date-with-support" <?= ($check && $strict) ? 'disabled' : '' ?> class="form-control" value="<?= $vks->humanized->date ?>"/>
                        </div>
                        <div class='col-md-4'>
                            <label><h4>Время начала</h4></label>
                            <input name='start_time' id="start_time" <?= ($check && $strict) ? 'disabled' : '' ?> class='form-control'
                                   value="<?= $vks->humanized->startTime ?>"/>
                        </div>
                        <div class='col-md-4'>
                            <label>
                                <h4>Время окончания</h4>
                            </label>
                            <input name='end_time' id="end_time" <?= ($check && $strict) ? 'disabled' : '' ?> class='form-control'
                                   value="<?= $vks->humanized->endTime ?>"/>
                        </div>
                    </div>

                </div>
                <div class="text-center"><?= ($check && $strict) ? '<button type="button" class="btn btn-danger btn-sm time_reblock">Изменить время</button>' : '' ?>        </div>
                <div class="date-error-container"></div>
                <div class="freeAdmins"></div>
            </div>
            <div class="form-step-container">
                <div class="form-group">
                </div>
                <div class="form-group">
                    <label><h4>Название видеоконференции</h4></label>
                    <input name="title" id="title"
                           class="form-control" maxlength="255" value='<?= $vks->title ?>'/>
                </div>
                <hr>
                <div class="form-group">
                    <label><h4>Подразделение</h4></label>
                    <select name="department" class="form-control">

                        <?php foreach ($departments as $department) : ?>
                            <option
                                value="<?= $department->id ?>" <?= $department->id == $vks->department ? 'selected' : '' ?>><?= $department->prefix ?>
                                . <?= $department->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">

                    <div class='col-md-4 no-left-padding'>
                        <label><h4>ФИО ответственного</h4></label>
                        <input name='init_customer_fio' class='form-control'
                               value="<?= $vks->init_customer_fio ?>"/>
                    </div>
                    <div class='col-md-4'>
                        <label><h4>Телефон</h4></label>
                        <input name='init_customer_phone' class='form-control'
                               value="<?= $vks->init_customer_phone ?>"/>
                    </div>
                    <div class='col-md-4'>
                        <label><h4>Эл. Почта</h4></label>
                        <input name='init_customer_mail' class='form-control'
                               value="<?= $vks->init_customer_mail ?>"/>
                    </div>

                </div>

            </div>

            <!--    <block end -->
            <!--    <block start -->
            <div class="form-step-container" data-step="3">
                <div class="form-group">
                    <div class="col-lg-6">
                        <label class="control-label"><h4>Список участников ВКС</h4></label>

                        <div class="loader2" style="display: none;"><img src="images/loading.gif"/> Загрузка</div>
                        <br>
                        <button class="btn btn-info col-lg-12" type="button" id="participants_inside_open_popup"><span
                                class="glyphicon glyphicon-list"></span> Выбрать
                            участников
                        </button>


                    </div>
                    <div class="col-lg-6 hidden">

                        <label class="control-label">Кол-во участников с рабочих мест (IP телефон, Lynс, CMA Desktop и
                            т.д.)</label>
                        <input name="in_place_participants_count" class="form-control"
                               value="<?= $vks->in_place_participants_count ?>"/>

                    </div>
                </div>
                <div class="form-group">
                    <div class="vks-points-list-display">
                        <ol class="list-unstyled">
                            <li class="in_place_plank">Количество участников с рабочих мест: <span
                                    class="label label-as-badge label-default"><?= $vks->in_place_participants_count ? $vks->in_place_participants_count : 0 ?></span>
                            </li>
                            <li>Количество участников из справочника точек: <span
                                    class="label label-as-badge label-default"><?= count($vks->participants) ?></span>
                            </li>
                        </ol>
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <label><h4>Комментарий для админа</h4></label>
                    <textarea name="comment_for_admin" rows="4" id="comment_for_admin" maxlength="255"
                              class="form-control" /><?= $vks->comment_for_admin ?></textarea>
                </div>

                <div class="form-group">
                    <div class="">
                        <div class="checkbox">

                            <label>
                                <input name="is_private" type="checkbox" <?= $vks->is_private ? 'checked' : '' ?>/>&nbsp<b>Приватная ВКС</b>
                            </label>
                        <span style="font-size: 18px;" data-file="help_standart" data-element="is_private"
                              class="glyphicon glyphicon-question-sign text-muted pointer get_help_button"
                              title="Подсказка"></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="">
                        <div class="checkbox">
                            <label>
                                <input name="record_required"
                                       type="checkbox" <?= $vks->record_required ? 'checked' : '' ?>/>&nbsp<b>Записать
                                    ВКС</b>
                            </label>
                        <span style="font-size: 18px;" data-file="help_standart" data-element="record_required"
                              class="glyphicon glyphicon-question-sign text-muted pointer get_help_button"
                              title="Подсказка"></span>
                        </div>
                    </div>
                </div>

                <hr>
                <?php ST::setUpErrorContainer(); ?>
<!--                <div class="form-group">-->
<!--                    <div class="col-md-12  no-left-padding">-->
<!--                        <label class="">Введите код проверки</label>-->
<!--                    </div>-->
<!--                    <div class="col-md-12  no-left-padding">-->
<!---->
<!--                        <img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image"/>&nbsp&nbsp<a-->
<!--                            href="#" class="refresh-captcha"-->
<!--                            onclick="document.getElementById('captcha').src = 'securimage/securimage_show.php?' + Math.random(); return false"><span-->
<!--                                class="glyphicon glyphicon-refresh" title="обновить картинку"></span> </a></div>-->
<!--                    <div class="col-md-4  no-left-padding">-->
<!--                        <input type="text" class="form-control" name="captcha_code" size="10" maxlength="6"/>-->
<!---->
<!--                    </div>-->
<!--                </div>-->
                <div class="form-group">
                    <button type="button" id="submit"  class="btn btn-success btn-lg">Сохранить</button>

                </div>
                <br><br><br><br>
            </div>
        </form>
    </div>
</div>
<?php
$p = new ParticipationsV3Assert();
$p->init();
ST::setUserJs('uf-2.js');
//ST::setUserJs('vks/block_checker.js');
ST::setUserJs('ajaxfileupload.js');
?>