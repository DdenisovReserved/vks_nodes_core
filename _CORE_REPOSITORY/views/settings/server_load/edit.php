<?php
ST::deployTemplate('heads/ui_timepicker.inc');
RenderEngine::MenuChanger();
$backPack = ST::lookAtBackPack();

?>
<div class="container">
    <div class="col-lg-6 col-lg-offset-3">
        <form class="form-horizontal" method='post' action="<?= ST::route("Settings/storeServersLoad") ?>">
            <?= Token::castTokenField() ?>
            <?php $c=1; foreach ($servers as $server) : ?>

                <div class="form-group">
                    <label for="">Название</label>
                    <input class="form-control" name="server[<?= $c ?>][alias]"
                           value="<?= $backPack->request->get('server')[$c]['alias'] ? $backPack->request->get('server')[$c]['alias'] : $server['alias'] ?>"/>
                </div>
                <div class="form-group">
                    <label for="">Производительность общая</label>
                    <input class="form-control" name="server[<?= $c ?>][capacity]" value="<?= $backPack->request->get('server')[$c]['capacity'] ? $backPack->request->get('server')[$c]['capacity'] : $server['capacity'] ?>"/>
                </div>
            <?php $c++; endforeach; ?>

            <input class="btn btn-success" type="submit" value="Сохранить">
        </form>
    </div>
</div>