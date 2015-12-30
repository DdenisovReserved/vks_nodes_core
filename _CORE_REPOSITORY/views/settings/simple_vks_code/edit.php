<?php
ST::deployTemplate('heads/ui_timepicker.inc');
RenderEngine::MenuChanger();
?>
<div class="container">
    <div class="col-lg-6 col-lg-offset-3">

        <form class="form-horizontal" method='post' action="<?= ST::route("Settings/storeSimpleVksCodeSet") ?>">
            <?= Token::castTokenField() ?>
            <div class="form-group">
                <h3>Диапазон кодов для простых вкс</h3><hr>
            </div>
                <div class="form-group">
                    <label for="">Стартовое значение кода</label>
                    <input class="form-control" name="start"
                           value="<?= $code->code['start'] ?>"/>
                </div>
                <div class="form-group">
                    <label for="">Конечное значение кода</label>
                    <input class="form-control" name="end" value="<?= $code->code['end'] ?>"/>
                </div>
            <div class="form-group">
                <h4>В пуле: <span class="label label-default label-as-badge"><?= count(range($code->code['start'],$code->code['end'])) ?></span> </h4>
            </div>

            <input class="btn btn-success" type="submit" value="Сохранить">
        </form>
    </div>
</div>