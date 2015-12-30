<?php include_once(CORE_REPOSITORY_REAL_PATH . "views/main.php"); ?>
<div class="col-lg-3">
    <a href="<?= ST::route('BlockedTime/index') ?>" class="btn btn-default">Назад</a>

</div>
<div class="col-lg-6">
    <h3>Создать блокировку в работе системы</h3><hr>
    <form class="form-horizontal" method="post" action="<?= ST::route('BlockedTime/store') ?>">
        <?php include_once(CORE_REPOSITORY_REAL_PATH . 'views/settings/blockedtime/_form.php') ?>
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
                <button type="submit" class="btn btn-success btn-lg">Создать</button>
            </div>
        </div>
    </form>
</div>