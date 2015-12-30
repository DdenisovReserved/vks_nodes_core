<?php
ST::deployTemplate('heads/ui_timepicker.inc');
RenderEngine::MenuChanger();
?>
<div class="container">
    <div class="col-lg-8 col-lg-offset-2">
        <h3>Редактировать публичное сообщение</h3>
        <hr>
        <form class="form-horizontal" method="post" action="<?= ST::route('Settings/storePublicMessage') ?>">
            <div class="form-group">
                <label for="content" class="col-sm-0 control-label"></label>
                <div class="col-sm-12">
                    <textarea name="content"  class="form-control" rows="6" id="content" placeholder="Публичное сообщение"><?= $request->get('content') ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="active" <?= $request->get('active') ? 'checked' : '' ?>> Активно (показывать на главной)
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-success btn-lg">Сохранить</button>
                </div>
            </div>
        </form>
    </div>
</div>
