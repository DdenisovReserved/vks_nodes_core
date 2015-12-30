<?php include_once(CORE_REPOSITORY_REAL_PATH . "/views/main.php"); ?>

<div class="container">
    <div class="col-lg-12">

        <form class="form-horizontal" method='post' action="<?= ST::route("Modules/store") ?>">
            <?= Token::castTokenField() ?>

                <h3>Подключаемые модули</h3>
                <hr>
            <table class="table table-bordered">
                <th>#</th>
                <th>Имя</th>
                <th>Описание</th>
                <th>Статус</th>
                <?php $c = 1;
                foreach ($modules as $module): ?>
                <tr class="alert alert-<?= intval($module['value']) ? 'success' : 'danger' ?>">
                    <td><?= $c ?></td>
                    <td><?= $module['description'] ?></td>
                    <td><?= $module['help'] ?></td>
                    <input class="hidden" name="modules[<?= $c ?>][name]" value="<?= $module['name'] ?>"/>
                    <input class="hidden" name="modules[<?= $c ?>][description]" value="<?= $module['description'] ?>"/>
                    <input class="hidden" name="modules[<?= $c ?>][help]" value="<?= $module['help'] ?>"/>
                    <td><select name="modules[<?= $c ?>][value]" class="">
                            <option value="1" <?= intval($module['value']) ? 'selected' : null ?>>Включено</option>
                            <option value="0" <?= intval($module['value']) ? null : 'selected' ?>>Выключено</option>
                        </select>
                    </td>
                </tr>
    <?php $c++;
    endforeach; ?>
    </table>

        <input class="btn btn-success" type="submit" value="Сохранить">
    </form>
</div>
</div>
