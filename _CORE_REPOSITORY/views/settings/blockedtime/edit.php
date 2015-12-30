<?php include_once(CORE_REPOSITORY_REAL_PATH . "views/main.php"); ?>
<div class="col-lg-3">
    <a href="<?= ST::route('BlockedTime/index') ?>" class="btn btn-default">Назад</a>

</div>
<div class="col-lg-6">

        <h4>Изменить блокировку в работе системы
        <span class="pull-right"><a class="btn btn-info" href="<?= ST::route('BlockedTime/copy/' . $block->id) ?>" title="Копировать"><span class="glyphicon glyphicon-duplicate"></span></a></span> </h4>

    <div class="clearfix"></div>
    <hr>
    <form class="form-horizontal" method="post" action="<?= ST::route('BlockedTime/update/' . $block->id) ?>">
        <?php include_once(CORE_REPOSITORY_REAL_PATH . 'views/settings/blockedtime/_form.php') ?>
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
                <button type="submit" class="btn btn-success btn-lg">Изменить</button>
                <a href="<?= ST::route('BlockedTime/delete/' . $block->id) ?>"
                   class="btn confirmation btn-danger btn-sm">Удалить блокировку</a>
            </div>
        </div>
    </form>
</div>
