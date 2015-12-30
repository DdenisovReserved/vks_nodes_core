<?php
ST::deployTemplate('heads/ui_timepicker.inc');
RenderEngine::MenuChanger();
?>

<div class="container">
    <div class="col-lg-12">
        <h3>Управление подразделениями</h3>
        <hr>
        <a href="<?= ST::route('Departments/create') ?>" class="btn btn-success">Создать</a>
        <br><br>
        <table class="table table-striped table-hover">
            <th>id</th>
            <th>Префикс</th>
            <th>Название</th>
            <th></th>
            <?php foreach ($departments as $dep) : ?>
                <tr>
                    <td><?= $dep->id ?></td>
                    <td><?= $dep->prefix ?></td>
                    <td><?= $dep->name ?></td>
                    <td>

                        <a class="btn btn-default btn-sm" href="<?= ST::route('Departments/edit/' . $dep->id) ?>"><span
                                class="glyphicon glyphicon-edit" title="Редактировать"></span></a>

                        <a class="btn btn-default btn-sm confirmation"
                           href="<?= ST::route('Departments/delete/' . $dep->id) ?>"><span
                                class="glyphicon glyphicon-remove"
                                title="Удалить"></span></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

    </div>
</div>