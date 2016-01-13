<?php
ST::deployTemplate('heads/ui_timepicker.inc');
ST::setUserJs('uf-2.js');

$p = new ParticipationsV3Assert();
$p->init();
$p = new Select2Assert();
$p->init();

RenderEngine::MenuChanger();

$vks = ST::lookAtBackPack();
$vks = $vks->request;
ST::setUserJs('users/search.js');
ST::setUserJs('vks/approve/in_direct_insertion.js');
ST::setUserJs('codes/askFreeCodes.js');
?>
<script>
    function localAskFreeCodes() {
        var date = $("#date-with-support").val();
        var start_time = $("#start_time").val();
        var end_time = $("#end_time").val();

        if (!date || !start_time || !end_time) {
            alert("Заполните поля: дата, время начала/окончания");
            return false;
        }

        askFreeCodes('#askCodes', date + " " + start_time, date + " " + end_time);
    }
</script>
<div class="container">
    <div class="col-md-2">

        <div id="selected-time-list" class="hidden">

        </div>
        <div id="blocked-time-list" class="hidden">

        </div>
    </div>
    <div class="col-md-10 left-border">
        <h4 class="text-muted heading-main">Добавить ВКС в расписание</h4>
        <hr>
        <form id="form1" class="form-horizontal" name="form1" method="post" action="?route=Vks/adminStore">
            <?= Token::castTokenField(); ?>

            <div class="form-step-container" data-step="2">

                <div class="form-group">
                    <div class="col-md-4  no-left-padding">
                        <label>
                            <h4>Дата</h4>
                        </label>
                        <input name="date" id="date-with-support" class="form-control"
                               value="<?= $vks->get('date') ?>"/>
                    </div>

                    <div class="time-params">
                        <div class='col-md-4'>
                            <label>
                                <h4>Время начала</h4>
                            </label>
                            <input name='start_time' id='start_time' class='form-control' disabled
                                   value="<?= $vks->get('start_time') ?>"/>
                        </div>
                        <div class='col-md-4'>
                            <label>
                                <h4>Время окончания</h4>
                            </label>
                            <input name='end_time' id='end_time' class='form-control' disabled
                                   value="<?= $vks->get('end_time') ?>"/>
                        </div>
                    </div>
                </div>

            </div>

            <div class="form-step-container">
                <div class="form-group">
                    <label><h4>Подразделение </h4></label>
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
                    <div class="checkbox">
                        <label>
                            <input type='checkbox' name='needTB'
                                   data-checked='<?= $vks->get('needTB') ? '1' : '0' ?>' <?= $vks->get('needTB') ? 'checked' : '' ?>>Подключить
                            другой ТБ/ЦА
                        </label>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group hidden alert alert-info" id="tbs">
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
                <div class=" no-left-padding col-lg-5 col-lg-offset-1">
                    <div class="form-group hidden alert alert-warning" id="ca_participants">
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

                </div>
            </div>
            <div class="clearfix"></div>
            <!--    <block end -->
            <!--    <block start -->
            <div class="form-step-container">
                <div class="form-group">
                    <label><h4>Название видеоконференции</h4></label>
                    <input name="title" maxlength="255"
                           class="form-control" value="<?= $vks->get('title') ?>"/>
                </div>
                <hr>
                <div class="form-group">
                    <div class='col-md-4  no-left-padding'>
                        <label><h4>ФИО ответственного</h4></label>
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
                               value="<?= $vks->get('init_customer_mail') ? $vks->get('init_customer_mail') : App::$instance->user->login ?>"/>
                    </div>

                </div>
            </div>
            <!--    <block end -->
            <!--    <block start -->
            <div class="form-step-container" data-step="3">
                <div class="form-group">
                    <div class="col-lg-6">
                        <label class="control-label"><h4>Список участников ВКС</h4></label>

                        <div class="loader2" style="display: none;"><img
                                src="<?= CORE_REPOSITORY_HTTP_PATH ?>images/loading.gif"/> Загрузка
                        </div>
                        <br>
                        <button class="btn btn-info col-lg-8" type="button" id="participants_inside_open_popup"><span
                                class="glyphicon glyphicon-list"></span> Выбрать
                            участников
                        </button>


                    </div>
                    <div class="col-lg-6 hidden">

                        <label class="control-label">Кол-во участников с рабочих мест (IP телефон, Lynс, CMA Desktop и
                            т.д.)</label>
                        <input name="in_place_participants_count" class="form-control" value="0"/>

                    </div>
                </div>
                <div class="form-group">
                    <div class="vks-points-list-display"><i>Список участников пуст</i></div>
                </div>
                <hr>

                <div class="form-group">
                    <label><h4>Комментарий для админа</h4></label>
                        <textarea name="comment_for_admin" id="comment_for_admin" maxlength="160"
                                  class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label><h4>Комментарий для пользователя</h4></label>
                    <textarea name="comment_for_user" maxlength="160" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label class=" control-label"><h4>Владелец</h4></label>

                    <select class="js-user-apiFind form-control" name="owner_id">
                        <option value="<?= App::$instance->user->id ?>"><?= App::$instance->user->login ?></option>
                    </select>
                </div>
                <div class="form-group">
                    <h4>Код подключения
                        <button class="btn btn-default btn-sm" id="askCodes" onclick="localAskFreeCodes()"
                                type="button">
                            <span class="glyphicon glyphicon-question-sign"></span> <span class="text">Показать таблицу занятости кодов</span>
                        </button>
                    </h4>

                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-success" name="add">+ код из шаблона</button>
                        <button type="button" class="btn btn-sm btn-info" name="manual">+ код вручную</button>


                    </div>

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
                            <input type='checkbox' name='no-check'> <b>Игнорировать проверку</b>
                        </label>
                    </div>
                </div>
                <hr>
                <div class="form-group alert alert-info">
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
                <div class="form-group alert alert-info">
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
                <hr>
                <?php ST::setUpErrorContainer(); ?>
                <div class="form-group text-left">
                    <button type="button" id="submit_admin" class="btn btn-success btn-lg">Отправить</button>
                </div>

            </div>
            <!--    <block start -->


        </form>
    </div>
</div>
</body>


