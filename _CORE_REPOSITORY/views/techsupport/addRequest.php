<?php include_once(CORE_REPOSITORY_REAL_PATH . "views/main.php"); ?>
<div class="col-lg-12">
    <h4>Запросить техническую поддержку на ВКС <?= ST::linkToVksPage($vks->id, true) ?></h4>
    <hr>
    <form class="form form-horizontal" action="<?= ST::route('TechSupport/storeRequest/' . $vks->id) ?>" method="post">

        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Точка</label>


            <div class="col-sm-10">
                <div class="form-group">
                    <div class="checkbox">
                        <label style="font-size: 14px;">
                            <?php if (!count($available_points)): ?>
                                <i>Точки на которых оказывается тех. поддержка, не определены (обратитесь к администратору)</i>
                            <?php else: ?>
                                <?php foreach ($available_points as $attendance): ?>
                                    <p>
                                    <input name="att_id" value="<?= $attendance['id'] ?>"
                                           type="radio" <?= $attendance['selectable'] ? '' : 'disabled' ?>/>&nbsp<?= AttendanceNew_controller::makeFullPath($attendance['id']) ?><?= $attendance['selectable'] ? '' : ' <span style="font-size: 8px;" class="label label-success">Заявка создана</span>' ?>
                                    </p>
                                <?php endforeach ?>
                            <?php endif ?>
                        </label>
                    </div>
                </div>
                <span class="help-block">*Выберите точку в которой вам требуется техническая поддержка</span>

            </div>
        </div>
        <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">Сообщение</label>

            <div class="col-sm-10">
                <textarea name="user_message" maxlength="255" rows="5" class="form-control"></textarea>
                <span class="help-block">*Краткое сообщение для тех. поддержки</span>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button class="btn btn-success" type="submit">Сохранить</button>
            </div>
        </div>


    </form>
</div>
