<?php
Auth::isAdminOrDie(App::$instance);
ST::deployTemplate('heads/ui_timepicker.inc');
ST::setUserCss('attendance/style.css');
ST::setUserCss('attendance/manage-style.css');
RenderEngine::MenuChanger();
//dump($data['backPack']->parent_id);
//dump($data);
?>
<script>
    $(document).ready(function () {
        showOnly(".show10-1", 5);
        showOnly(".show10-2", 5);
    })
</script>
<div class="container">
    <div class="col-lg-1 col-sm-offset-2"><a class="btn btn-default"
                                             href="?route=AttendanceNew/show/<?= $data['backPack']->parent_id ?>">
            Назад</a></div>
    <div class="col-md-6 block-border-shadow-normal-padding left-border padding25">
        <h4>Редактировать точку/контейнер</h4>
        <hr>
        <div class="alert alert-danger">Свойтво "техническая поддержка" используется в экспериментальном режиме и пока нигде не используется</div>
        <form class="form-horizontal" method="post" action="?route=AttendanceNew/update/<?= $data['backPack']->id ?>">
            <div class="form-group">
                <label>Имя:</label>
                <input name="name" class="form-control"
                       value="<?php echo (isset($data['backPack']->name) && !empty($data['backPack']->name)) ? $data['backPack']->name : '' ?>"
                       maxlength="160"/>
            </div>
            <?php if (!$data['backPack']->container): ?>
                <div class="form-group">
                    <label>Ip:</label>
                    <input name="ip" class="form-control"
                           value="<?php echo (isset($data['backPack']->ip) && !empty($data['backPack']->ip)) ? $data['backPack']->ip : '' ?>"
                           maxlength="160"/>
                </div>
            <?php endif; ?>
            <div class="form-group">
                <label>Тип:</label>
                <select name="container" class="form-control">

                    <?php if (isset($data['backPack']->container) && $data['backPack']->container) : ?>
                        <option value="1">Контейнер</option>
                        <option value="0">Точка</option>
                    <?php else: ?>
                        <option value="0">Точка</option>
                        <option value="1">Контейнер</option>

                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Родительский контейнер:</label>
                <select name="parent_id" class="form-control">
                    <?php if ($data['backPack']->parent_id == 1): ?>
                        <option value="<?= $data['backPack']->parent_id ?>">Корневой контейнер</option>
                    <?php else: ?>
                        <option
                            value="<?= $data['backPack']->parent_id ?>"><?= Attendance::findOrFail($data['backPack']->parent_id)->name ?></option>
                    <?php endif; ?>
                    <?php foreach ($data['containers'] as $container): ?>
                        <?php if ($backPack->parent_id != $container->id): ?>
                            <?php if ($container->id !== $backPack->id): ?>
                                <option value="<?= $container->id ?>"><?= $container->name ?></option>

                            <?php endif ?>

                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>


            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input name="active" type="checkbox" <?= $data['backPack']->active ? "checked" : '' ?>/>&nbspАктивна
                    </label>
                </div>
            </div>
            <?php if (!$data['backPack']->container): ?>
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input name="check" type="checkbox" <?= $data['backPack']->check ? "checked" : '' ?>/>&nbspПроверка
                            на участие в других ВКС
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input name="tech_supportable" type="checkbox" <?= $data['backPack']->tech_supportable ? "checked" : '' ?>/>&nbspТехническая поддержка
                        </label>
                    </div>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <button class="btn btn-success">Сохранить</button>
            </div>
        </form>
    </div>


</div>