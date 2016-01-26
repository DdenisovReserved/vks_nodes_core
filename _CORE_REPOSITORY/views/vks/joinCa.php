<?php
ST::deployTemplate('heads/ui_timepicker.inc');

ST::setUserJs('uf-2.js');

$p = new ParticipationsV3Assert();
$p->init();

RenderEngine::MenuChanger();

?>

<div class="container">

    <div class="col-md-12 left-border">
        <h4 class="text-muted heading-main">Заявка по приглашению ЦА на ВКС <?= $caVks instanceof CAVks ? ST::linkToCaVksPage($caVks->id) : ST::linkToCaNsVksPage($caVks->id) ?></h4>
        <?php if (!$flag): ?>
            <div class="alert alert-danger"><h4>Обратите внимание:</h4> В этой ВКС, ваш ТБ не заявлен, вам нужно
                обратиться к владельцу ВКС:  <?= $caVks->owner->login ?>, <?= $caVks->owner->phone ?>. <br>Для
                исключения трудностей с проведением ВКС, попросите владельца добавить ваш ТБ в список участников
            </div>
        <?php endif ?>
        <hr>
        <form id="form1" class="form-horizontal" name="form1" method="post"
              action="?route=Vks/joinCaStore/<?= $referral ?>">
            <?= Token::castTokenField(); ?>
            <div class="col-lg-6">
                <div class="form-step-container">
                    <div class="form-group no-left-padding">
                        <div class='col-md-12 no-left-padding'>
                            <label class="no-left-padding text-muted">
                                <h4>Необходимые данные</h4>
                            </label>
                        </div>
                        <label>Подразделение</label>
                        <select name="department" class="form-control">
                            <option value="">--Выберите подразделение--</option>
                            <?php foreach ($departments as $department) : ?>
                                <option
                                    value="<?= $department->id ?>" <?= $vks->get('department') == $department->id ? 'selected' : false ?>><?= $department->prefix ?>
                                    . <?= $department->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-step-container ">
                    <div class="form-group no-left-padding">
                        <div class='col-md-12 no-left-padding'>
                            <label class="no-left-padding text-muted">
                                <h4>Дата\Время в нашем Планировщике</h4>
                            </label>
                        </div>
                        <div class='col-md-4 no-left-padding'>
                            <label>Дата</label>
                            <input id="date-with-support" name='' class='form-control'
                                   value="<?= date_create($caVks->start_date_time, new DateTimeZone(App::$instance->opt->ca_timezone))->setTimezone(new DateTimeZone(App::$instance->opt->timezone))->format("d.m.Y"); ?>" disabled/>
                        </div>
                        <div class='col-md-4'>
                            <label>Начало</label>
                            <input id='start_time' name='' class='form-control'
                                   value="<?= date_create($caVks->start_date_time,new DateTimeZone(App::$instance->opt->ca_timezone))->setTimezone(new DateTimeZone(App::$instance->opt->timezone))->format("H:i"); ?>" disabled/>
                        </div>
                        <div class='col-md-4'>
                            <label>Окончание</label>
                            <input id='end_time' name='' class='form-control'
                                   value="<?= date_create($caVks->end_date_time,new DateTimeZone(App::$instance->opt->ca_timezone))->setTimezone(new DateTimeZone(App::$instance->opt->timezone))->format("H:i"); ?>" disabled/>
                        </div>
                    </div>
                </div>
                <div class="form-step-container ">
                    <div class="form-group no-left-padding">
                        <div class='col-md-12 no-left-padding'>
                            <label class="no-left-padding text-muted">
                                <h4>Ответственный в ТБ</h4>
                            </label>
                        </div>
                        <div class='col-md-4 no-left-padding'>
                            <label>ФИО</label>
                            <input name='init_customer_fio' class='form-control'
                                   value="<?= $vks->get('init_customer_fio') ? $vks->get('init_customer_fio') : App::$instance->user->fio ?>"/>
                        </div>
                        <div class='col-md-4'>
                            <label>Телефон</label>
                            <input name='init_customer_phone' class='form-control'
                                   value="<?= $vks->get('init_customer_phone') ? $vks->get('init_customer_phone') : App::$instance->user->phone ?>"/>
                        </div>
                        <div class='col-md-4'>
                            <label>Эл. почта</label>
                            <input name='init_customer_mail' class='form-control'
                                   value="<?= $vks->get('init_customer_mail') ? $vks->get('init_customer_mail') : App::$instance->user->email ?>"/>
                        </div>
                    </div>

                </div>
                <div class="form-step-container">

                    <div class="form-group">
                        <div class="col-lg-12">
                            <label class="control-label"><h4>Список участников ВКС <span data-file="help_standart"
                                                                                         data-element="participants"
                                                                                         class="glyphicon glyphicon-question-sign text-muted pointer get_help_button"
                                                                                         title="Подсказка"></span></h4>
                            </label>

                            <div class="loader2" style="display: none;"><img src="<?=CORE_REPOSITORY_HTTP_PATH ?>images/loading.gif"/> Загрузка</div>
                            <br>
                            <button class="btn btn-info col-lg-8" type="button"
                                    id="participants_inside_open_popup"><span
                                    class="glyphicon glyphicon-list"></span> Выбрать
                                участников
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="vks-points-list-display">
                            <?php if ($vks->has('inner_participants') || $vks->has('in_place_participants_count')): ?>
                                <ol class="list-unstyled">
                                    <li class="in_place_plank">Количество участников с рабочих мест: <span
                                            class="label label-as-badge label-default"><?= $vks->has('in_place_participants_count') ? $vks->get('in_place_participants_count') : 0 ?></span>
                                    </li>
                                    <li>Количество участников из справочника точек: <span
                                            class="label label-as-badge label-default"><?= count($vks->get('inner_participants')) ?></span>
                                    </li>
                                </ol>
                            <?php else: ?>
                                <i>Список участников пуст</i>
                            <?php endif ?>
                        </div>
                    </div>
                    <hr>

                    <div class="form-group">
                        <label><h4>Комментарий для администратора <span data-file="help_standart"
                                                                        data-element="comment_for_admin"
                                                                        class="glyphicon glyphicon-question-sign text-muted pointer get_help_button"
                                                                        title="Подсказка"></span></h4></label>
                        <textarea name="comment_for_admin" id="comment_for_admin" maxlength="160" rows="4"
                                  class="form-control"/><?= $vks->get('comment_for_admin') ?></textarea>
                    </div>

                    <div class="form-group">
                        <div class="">
                            <div class="checkbox">

                                <label>
                                    <input name="is_private" type="checkbox"/>&nbsp<b>Приватная ВКС</b>
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
                                           type="checkbox" />&nbsp<b>Записать
                                        ВКС</b>
                                </label>
                        <span style="font-size: 18px;" data-file="help_standart" data-element="record_required"
                              class="glyphicon glyphicon-question-sign text-muted pointer get_help_button"
                              title="Подсказка"></span>
                            </div>
                        </div>
                    </div>
                    <?php include_once(CORE_REPOSITORY_REAL_PATH."views/vks/tpl/_tech_support_checkbox.php") ?>
                    <hr>
                    <?php ST::setUpErrorContainer(); ?>
<!--                    <div class="form-group">-->
<!--                        <div class="col-md-12  no-left-padding">-->
<!--                            <label class="">Введите код проверки</label>-->
<!--                        </div>-->
<!--                        <div class="col-md-12 no-left-padding">-->
<!---->
<!--                            <img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image"/>&nbsp&nbsp<a-->
<!--                                href="#" class="refresh-captcha"-->
<!--                                onclick="document.getElementById('captcha').src = 'securimage/securimage_show.php?' + Math.random(); return false"><span-->
<!--                                    class="glyphicon glyphicon-refresh" title="обновить картинку"></span> </a></div>-->
<!--                        <div class="col-md-7 no-left-padding"><br>-->
<!--                            <input type="text" class="form-control" name="captcha_code" size="10" maxlength="6"/>-->
<!---->
<!--                        </div>-->
<!---->
<!--                    </div>-->
                    <div class="form-group">
                        <button type="button" id="submit" class="btn btn-success btn-lg">Отправить</button>
                    </div>
                </div>
            </div>
            <!--            left column end-->
            <div class="col-lg-6 left-border alert alert-warning ">
                <div class="form-step-container">
                    <div class="form-group">
                        <h4 class="text-muted">Данные из приглашения</h4>
                    </div>
                    <div class="form-group">
                        <label>Название видеоконференции<span class="text-danger">*</span> </label>
                        <textarea name="title" maxlength="255" rows="5"
                               class="form-control" disabled><?= $caVks->title ?></textarea>
                    </div>
                </div>
                <div class="form-step-container">
                    <div class="form-group no-left-padding">
                        <div class="col-md-4 no-left-padding">
                            <label>
                                Дата:<span class="text-danger">*</span>
                            </label>
                            <input name="date"  class="form-control"
                                   value="<?= date_create($caVks->date)->format("d.m.Y"); ?> " disabled/>
                        </div>
                        <div class="time-params">
                            <div class='col-md-4'>
                                <label>
                                    Начало:<span class="text-danger">*</span>
                                </label>
                                <input name='start_time'  class='form-control' disabled
                                       value="<?= date_create($caVks->start_date_time)->format("H:i"); ?>" disabled/>
                            </div>
                            <div class='col-md-4'>
                                <label>
                                    Окончание:<span class="text-danger">*</span>
                                </label>
                                <input name='end_time' class='form-control' disabled
                                       value="<?= date_create($caVks->end_date_time)->format("H:i"); ?>" disabled/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-step-container">
<!--                    --><?php //dump($caVks) ?>
                    <div class="form-group">
                        <label>Председательствующий<span class="text-danger">*</span> </label>
                        <input value="<?= isset($caVks->init_head_fio) ?  $caVks->init_head_fio : '-'?>" class="form-control" disabled/>
                    </div>
                    <div class="form-group">
                        <label>Владелец ВКС<span class="text-danger">*</span> </label>
                        <input value="<?= $caVks->owner->login ?>" class="form-control" disabled/>
                        <input value="<?= $caVks->owner->fio ?>" class="form-control" disabled/>
                        <input value="<?= $caVks->owner->phone ?>" class="form-control" disabled/>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
</body>


