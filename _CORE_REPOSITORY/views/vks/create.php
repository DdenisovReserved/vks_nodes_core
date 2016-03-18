<?php
ST::deployTemplate('heads/ui_timepicker.inc');
RenderEngine::MenuChanger();
?>

<div class="container">
    <div class="col-md-2">
        <div id="blocked-time-list" class="hidden">

        </div>
        <div id="selected-time-list" class="hidden">

        </div>
    </div>
    <div class="col-md-10 left-border">
        <h4 class="text-muted heading-main">Стандартная заявка на проведение ВКС</h4>
        <hr>
        <form id="form1" class="form-horizontal" name="form1" method="post" action="?route=Vks/store">
            <?= Token::castTokenField(); ?>
            <?php if ($vks->has('dates')): ?>
                <?php $c = 1;
                foreach ($vks->get('dates') as $date): ?>
                    <div class="form-step-container time_container">
                        <div class="form-group">
                            <div class="<?= $c == 1 ? 'col-md-4' : 'col-md-3' ?>  no-left-padding">
                                <label>
                                    <h4>Дата </h4>
                                </label>
                                <input data-vks_blocked_type="0" name="dates[<?= $c ?>][date]"
                                       id="<?= $c == 1 ? 'date-with-support' : '' ?>"
                                       class="form-control <?= $c != 1 ? 'clonedDP' : '' ?>"
                                       value="<?= $date['date'] ?>"/>

                            </div>
                            <div class="time-params">
                                <div class='<?= $c == 1 ? 'col-md-4' : 'col-md-3' ?>'>
                                    <label>
                                        <h4>Время начала</h4>
                                    </label>
                                    <input name='dates[<?= $c ?>][start_time]' id='start_time'
                                           class='form-control start_time'
                                        <?= $date['start_time'] ? '' : 'disabled' ?>
                                           value="<?= $date['start_time'] ?>"/>
                                </div>
                                <div class='<?= $c == 1 ? 'col-md-4' : 'col-md-3' ?>'>
                                    <label>
                                        <h4>Время окончания</h4>
                                    </label>
                                    <input name='dates[<?= $c ?>][end_time]' id='end_time' class='form-control end_time'
                                        <?= $date['end_time'] ? '' : 'disabled' ?>
                                           value="<?= $date['end_time'] ?>"/>
                                </div>
                                <?php if ($c != 1): ?>
                                    <div class='col-md-2'>
                                        <label>
                                            <h4>&nbsp </h4>
                                        </label>
                                        <button class="form-control btn btn-danger btn-sm dates_remove" type="button">
                                            Удалить
                                        </button>
                                    </div>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                    <?php $c++; endforeach; ?>
            <?php else: ?>
                <div class="form-step-container time_container">
                    <div class="form-group">
                        <div class="col-md-4  no-left-padding">
                            <label>
                                <h4>Дата <span data-file="help_standart" data-element="date"
                                               class="glyphicon glyphicon-question-sign text-muted pointer get_help_button"
                                               title="Подсказка"></span></h4>
                            </label>
                            <input data-vks_blocked_type="0" name="dates[1][date]" id="date-with-support"
                                   class="form-control"
                                   value="<?= $vks->get('date') ?>"/>
                        </div>
                        <div class="time-params">
                            <div class='col-md-4'>
                                <label>
                                    <h4>Время начала</h4>
                                </label>
                                <input name='dates[1][start_time]' id='start_time' class='form-control start_time'
                                    <?= $vks->get('start_time') ? '' : 'disabled' ?>
                                       value="<?= $vks->get('start_time') ?>"/>
                            </div>
                            <div class='col-md-4'>
                                <label>
                                    <h4>Время окончания</h4>
                                </label>
                                <input name='dates[1][end_time]' id='end_time' class='form-control end_time'
                                    <?= $vks->get('start_time') || $vks->get('end_time') ? '' : 'disabled' ?>
                                       value="<?= $vks->get('end_time') ?>"/>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="text-center">
                <button type="button" style="min-height: 3em;" class="btn btn-info btn-sm add_time form-step-container"
                        data-number="<?= $vks->has('dates') ? count($vks->get('dates')) : 1 ?>"><span
                        class="glyphicon glyphicon-plus-sign"></span> Добавить время
                </button>
            </div>
            <div class="clearfix"></div>

            <div class="form-step-container">
                <div class="form-group">
                    <label><h4>Подразделение <span data-file="help_standart" data-element="department"
                                                   class="glyphicon glyphicon-question-sign text-muted pointer get_help_button"
                                                   title="Подсказка"></span></h4></label>
                    <select name="department" class="form-control">
                        <option value="">--Выберите подразделение--</option>
                        <?php foreach ($departments as $department) : ?>
                            <option
                                value="<?= $department->id ?>" <?= $vks->get('department') == $department->id ? 'selected' : false ?>><?= $department->prefix ?>
                                . <?= $department->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group alert alert-info">
                    <div class="checkbox" >
                        <label>
                            <input type='checkbox' <?=!count($tbs) ? 'disabled' : ''   ?> name='needTB'
                                   data-checked='<?= $vks->get('needTB') ? '1' : '0' ?>' <?= $vks->get('needTB') ? 'checked' : '' ?>>Подключить
                            другой ТБ/ЦА
                        </label>
                    </div>
                </div>
                <div class="clearfix"></div>

                <div class="col-lg-6">
                    <br>

                    <div class="form-group hidden" id="tbs">
                        <label>Выберите ТБ с которыми хотите связаться</label>
                        <ul class="list-unstyled">
                            <?php foreach ($tbs as $tb) : if ($tb->id != App::$instance->tbId) : ?>
                                <li>
                                    <div class="checkbox">
                                        <label>
                                            <input type='checkbox'
                                                   name='participants[]'
                                                <?php if ($vks->get('participants')) {
                                                    foreach ($vks->get('participants') as $parp) {
                                                        if ($parp == $tb->id) echo " checked ";
                                                    }
                                                }
                                                ?>
                                                   value="<?= $tb->id ?>"> <?= $tb->name ?>
                                        </label>
                                    </div>

                                </li>
                            <?php endif; endforeach; ?>
                        </ul>
                    </div>
                </div>
                <div class="no-left-padding col-lg-5 hidden col-lg-offset-1" id="ca_participants">
                    <div class="form-group alert alert-warning" >
                        <select name="ca_participants">
                            <option value="0">0</option>
                            <?php $range = range(1, 10); ?>
                            <?php foreach ($range as $variant) : ?>
                                <option
                                    value="<?= $variant ?>" <?php if ($vks->get('ca_participants') && intval($vks->get('ca_participants')) == intval($variant)) {
                                    echo 'selected';
                                } ?>><?= $variant ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label>Ожидаемое кол-во участников в ЦА</label>
                    </div>
                    <div class="form-group  alert alert-info">
                       <label>Код в ЦА:</label>
                       <input class="form-control" name="i_know_ca_code" value="<?= $vks->get('i_know_ca_code') ? $vks->get('i_know_ca_code') : '' ?>" maxlength="40"/>
                       <span class="help-block">*Если у вас есть код подключения к конференции в ЦА, введите его в это поле</span>
                    </div>


                </div>
            </div>
            <div class="clearfix"></div>
            <!--    <block end -->
            <!--    <block start -->
            <div class="form-step-container">
                <div class="form-group">
                    <label><h4>Название видеоконференции <span data-file="help_standart" data-element="title"
                                                               class="glyphicon glyphicon-question-sign text-muted pointer get_help_button"
                                                               title="Подсказка"></span></h4></label>
                    <input name="title" maxlength="255"
                           class="form-control" value="<?= $vks->get('title') ?>"/>
                </div>
                <hr>
                <div class="form-group">
                    <div class='col-md-4  no-left-padding'>
                        <label><h4>ФИО ответственного <span data-file="help_standart" data-element="customer"
                                                            class="glyphicon glyphicon-question-sign text-muted pointer get_help_button"
                                                            title="Подсказка"></span></h4></label>
                        <input name='init_customer_fio' class='form-control'
                               value="<?= $vks->get('init_customer_fio') ? $vks->get('init_customer_fio') : App::$instance->user->fio ?>"/>
                    </div>
                    <div class='col-md-4'>
                        <label><h4>Телефон</h4></label>
                        <input name='init_customer_phone' class='form-control'
                               value="<?= $vks->get('init_customer_phone') ? $vks->get('init_customer_phone') : App::$instance->user->phone ?>"/>
                    </div>
                    <div class='col-md-4'>
                        <label><h4>Email</h4></label>
                        <input name='init_customer_mail' class='form-control'
                               value="<?= $vks->get('init_customer_mail') ? $vks->get('init_customer_mail') : App::$instance->user->email ?>"/>
                    </div>

                </div>
            </div>
            <!--    <block end -->
            <!--    <block start -->
            <div class="form-step-container" data-step="3">
                <div class="form-group">
                    <div class="col-lg-6">
                        <label class="control-label"><h4>Список участников ВКС <span data-file="help_standart"
                                                                                     data-element="participants"
                                                                                     class="glyphicon glyphicon-question-sign text-muted pointer get_help_button"
                                                                                     title="Подсказка"></span></h4>
                        </label>

                        <div class="loader2" style="display: none;"><img
                                src="<?= CORE_REPOSITORY_HTTP_PATH ?>images/loading.gif"/> Загрузка
                        </div>
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
                        <textarea name="comment_for_admin" id="comment_for_admin" maxlength="255" rows="4"
                                  class="form-control"/><?= $vks->get('comment_for_admin') ?></textarea>
                </div>
                <div class="form-group">
                    <div class="">
                        <div class="checkbox">

                            <label>
                                <input name="is_private"
                                       type="checkbox" <?= $vks->has('is_private') ? $vks->get('is_private') ? 'checked' : '' : '' ?>/>&nbsp<b>Приватная
                                    ВКС</b>
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
                                       type="checkbox" <?= $vks->has('record_required') ? $vks->get('record_required') ? 'checked' : '' : '' ?>/>&nbsp<b>Записать
                                    ВКС</b>
                            </label>
                        <span style="font-size: 18px;" data-file="help_standart" data-element="record_required"
                              class="glyphicon glyphicon-question-sign text-muted pointer get_help_button"
                              title="Подсказка"></span>
                        </div>
                    </div>
                </div>
                <?php // include_once(CORE_REPOSITORY_REAL_PATH."views/vks/tpl/_tech_support_checkbox.php") ?>
                <hr>
                <?php ST::setUpErrorContainer(); ?>
                <div class="form-group text-left">
                    <button type="button" id="submit" class="btn btn-success btn-lg">Отправить</button>
                </div>
            </div>
            <!--    <block start -->

        </form>
    </div>
</div>
</body>
<?php
$p = new ParticipationsV3Assert();
$p->init();
ST::setUserJs('uf-2.js');
//ST::setUserJs('vks/block_checker.js');
?>
<script>

    $(document).ready(function () {
        $(".clonedDP").each(function () {
            $(this).datepicker({'minDate': new Date()})
        });

//                $('#participants_inside_open_popup').click()

    })


</script>

