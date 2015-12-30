<?php
ST::deployTemplate('heads/ui_timepicker.inc');
RenderEngine::MenuChanger();
$backPack = ST::lookAtBackPack();

?>
<div class="container">
    <div class="col-lg-6 col-lg-offset-3">
        <h3>Редактировать прочие настройки</h3>
        <hr>

        <form class="form-horizontal" method='post' action="<?= ST::route("Settings/storeOther") ?>">
            <?= Token::castTokenField() ?>
            <?php $c = 1;
            foreach ($options as $option) : ?>
                <input class="hidden" name="option[<?= $c ?>][description]" value="<?= $option['description'] ?>">
                <input class="hidden" name="option[<?= $c ?>][name]" value="<?= $option['name'] ?>">
                <?php if(in_array($option['name'],['attendance_strict','attendance_check_enable', 'notify_admins'])): ?>


                    <div class="form-group">
                        <label for="" class="control-label col-lg-6"><?= $option['description'] ?></label>

                        <div class="col-lg-6">
                            <select name="option[<?= $c ?>][value]" class="form-control">
                                <option value="1" <?= $option['value']==1 ? 'selected' : "" ?>>Вкл</option>
                                <option value="0" <?= $option['value']==0 ? 'selected' : "" ?>>Выкл</option>
                            </select>

                        </div>

                    </div>
                <?php else: ?>
                    <div class="form-group">
                        <label for="" class="control-label col-lg-6"><?= $option['description'] ?></label>
                        <div class="col-lg-6">
                            <input class="form-control" name="option[<?= $c ?>][value]"
                                   value="<?= $option['value'] ?>"/>
                        </div>

                    </div>
                <?php endif ?>

                <?php $c++; endforeach; ?>
            <div class="form-group text-center">
                <input class="btn btn-success" type="submit" value="Сохранить">
            </div>
        </form>
    </div>
</div>