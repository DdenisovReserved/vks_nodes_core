<?php
ST::deployTemplate('heads/ui_timepicker.inc');

ST::setVarPhptoJS(AttendanceNew_controller::makeStackName(STACK_MULTIPLY), 'stackMultiName');
ST::setVarPhptoJS(1000, 'stackMultiCapacity');

RenderEngine::MenuChanger();

?>

<div class="container" xmlns="http://www.w3.org/1999/html">
    <div class="col-md-3">
        <div id="blocked-time-list" class="hidden">

        </div>
        <div id="selected-time-list" class="hidden">

        </div>
    </div>
    <div class="col-md-7 left-border">
        <h4 class="text-muted heading-main">Упрощенная заявка на проведение ВКС</h4>
        <hr>
        <form id="form1" class="form-horizontal" name="form1" method="post" action="?route=Vks/storeSimple">
            <?= Token::castTokenField(); ?>

            <div class="form-group">
                <label class="control-label col-lg-4">
                    Дата:
                </label>

                <div class="col-lg-3">
                    <input name="date" id="date-with-support" data-vks_blocked_type="1" class="form-control"
                           value="<?= $request->get('date') ?>"/>
                </div>
            </div>

            <div class="time-params">
                <div class="form-group">
                    <label class="control-label col-lg-4">
                        Время начала:
                    </label>

                    <div class="col-lg-3">
                        <input name='start_time' id='start_time' class='form-control' <?= $request->get('start_time') ? '' : 'disabled' ?>
                               value="<?= $request->get('start_time') ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-4">
                        Время окончания:
                    </label>

                    <div class="col-lg-3">
                        <input name='end_time' id='end_time' class='form-control' <?= $request->get('end_time') ? '' : 'disabled' ?>
                               value="<?= $request->get('end_time') ?>"/>
                    </div>
                </div>
            </div>

            <!--    <block end -->
            <!--    <block start -->

            <div class="form-group">
                <label class="control-label col-lg-4">Название видеоконференции:</label>

                <div class="col-lg-8">
                        <textarea name="title" maxlength="255" rows="4"
                                  class="form-control"><?= $request->get('title') ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4">Кол-во участников:</label>

                <div class="col-lg-2">
                    <select name="in_place_participants_count"
                            class="form-control">
                        <?php foreach (range(2, 10) as $val) : ?>
                            <option value="<?= $val ?>"><?= $val ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-4 col-lg-8">
                    <div class="checkbox">

                        <label>
                            <input name="is_private" type="checkbox" <?= $request->get('is_private') ? $request->get('is_private') ? 'checked' : '' : '' ?>/>&nbsp<b>Приватная ВКС</b>
                        </label>
                        <span style="font-size: 16px;" data-file="help_standart" data-element="is_private"
                              class="glyphicon glyphicon-question-sign text-muted pointer get_help_button"
                              title="Подсказка"></span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-lg-8">
                    <div class="checkbox">
                        <label>
                            <input name="record_required"
                                   type="checkbox" <?= $request->get('record_required') ? $request->get('record_required') ? 'checked' : '' : '' ?>/>&nbsp<b>Записать
                                ВКС</b>
                        </label>
                        <span style="font-size: 16px;" data-file="help_standart" data-element="record_required"
                              class="glyphicon glyphicon-question-sign text-muted pointer get_help_button"
                              title="Подсказка"></span>
                    </div>
                </div>
            </div>
            <!--            <hr>-->
            <!--            <div class="form-group">-->
            <!---->
            <!--                <label class="control-label col-lg-4">-->
            <!--                    Введите код проверки-->
            <!--                </label>-->
            <!---->
            <!--                <div class="col-md-5">-->
            <!---->
            <!--                    <img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image"/>&nbsp&nbsp<a-->
            <!--                        href="#" class="refresh-captcha"-->
            <!--                        onclick="document.getElementById('captcha').src = 'securimage/securimage_show.php?' + Math.random(); return false"><span-->
            <!--                            class="glyphicon glyphicon-refresh" title="обновить картинку"></span> </a>-->
            <!---->
            <!--                    <input type="text" class="form-control" name="captcha_code" size="10" maxlength="6"/>-->
            <!--                </div>-->
            <!---->
            <!--            </div>-->
            <?php ST::setUpErrorContainer(); ?>
            <div class="form-group">
                <div class="col-md-5 col-lg-offset-4">
                    <button id="submit" type="button" class="btn btn-success btn-lg">Отправить</button>
                </div>
            </div>

            <!--    <block start -->


        </form>
    </div>
</div>
</body>
<?php
    ST::setUserJs('uf-2.js');
ST::setUserJs('vks/block_checker.js');
$p = new ParticipationsV3Assert();
$p->init();
?>

