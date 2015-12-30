<?php
ST::deployTemplate('heads/ui_timepicker.inc');
RenderEngine::MenuChanger();
?>
<div class="container">
    <div class="col-lg-8 col-lg-offset-2">
        <h3>Редактировать подсказки</h3>
        <hr>
        <form class="form-horizontal" method="post" action="<?= ST::route('Settings/storeHelp') ?>">
            <?php  foreach($helps as $key => $help): ?>

                <input name="help[<?= $key ?>][humanized]"  class="form-control hidden"  id="content"  value="<?= $help['humanized'] ?>"/>

                <input name="help[<?= $key ?>][name]"  class="form-control hidden" id="content"  value="<?= $help['name'] ?>"/>

                <div class="form-group">
                    <label for="content" class="col-sm-3 control-label"><?= $help['humanized'] ?></label>
                    <div class="col-sm-9">
                        <textarea name="help[<?= $key ?>][content]"  class="form-control" rows="6" id="content"><?= $help['content'] ?></textarea>
                    </div>
                </div>
            <?php endforeach ?>


            <div class="form-group">
                <div class="col-sm-9 col-lg-offset-3">
                    <button type="submit" class="btn btn-success btn-lg">Сохранить</button>
                </div>
            </div>
        </form>
    </div>
</div>