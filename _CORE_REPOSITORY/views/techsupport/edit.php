<?php include_once(CORE_REPOSITORY_REAL_PATH . "views/main.php");
$p = new Select2Assert();
$p->init();
ST::setUserJs('users/search.js');
?>
<style>
    .li-hover:hover {
       color: #ff7800 !important;
    }
</style>
<div class="container">
    <div class="col-lg-1">
        <a class="btn btn-default" href="<?= ST::route("AttendanceNew/show/".$attendance->parent_id) ?>">Назад</a>
        </div>
    <div class="col-lg-8 left-border padding25">
        <h4>Параметры технической поддержки точки<br> <span
                class="text-success"><?= AttendanceNew_controller::makeFullPath($attendance->id) ?></span></h4>

        <?php if ($attendance->container): ?>
            <div class="alert alert-danger">
                Вы выбрали контейнер, все нижестоящие точки будут наследовать эту тех. поддержку (если она включена)
            </div>
        <?php endif ?>

        <hr>
        <form class="form form-horizontal" method="post"
              action="<?= ST::route('TechSupport/store/' . $attendance->id) ?>">


            <?php include_once(CORE_REPOSITORY_REAL_PATH . "views/techsupport/_form.php") ?>
            <div class="form-group">
                <div class="col-sm-10 col-sm-offset-2">
                    <button class="btn btn-success" type="submit">Сохранить</button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-lg-3 left-border padding25">
        <h4>Последние сохраненные Тех. поддержки</h4>
        <hr>
        <?php if (!count($last_editing)): ?>
            <i>Список пуст</i>
        <?php else: ?>
            <ul>
                <?php foreach ($last_editing as $edited_container): ?>
                    <li class="li-hover"><?= AttendanceNew_controller::makeFullPath($edited_container->attendance->id) ?>
                        <a href="<?= ST::route("TechSupport/cloneTechSupport/" . $edited_container->attendance->id . "/" . $attendance->id) ?>"
                           title="Скопировать тех. поддержку из этой точки"><span
                                class="glyphicon glyphicon-duplicate text-info"></span> </a></li>
                <?php endforeach ?>
            </ul>
        <?php endif ?>
    </div>
</div>
