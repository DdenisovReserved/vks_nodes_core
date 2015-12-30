<?php
ST::deployTemplate('heads/ui_timepicker.inc');
RenderEngine::MenuChanger();
?>

<div class="container">
    <div class="col-lg-6 col-lg-offset-3 left-border">
        <h3>Управление подразделениями</h3>
        <hr>
        <a href="<?= ST::route('Initiators/create') ?>" class="btn btn-success">Создать</a>
        <br><br>
        <table class="table table-striped table-hover">
            <th>id</th>
            <th>Название</th>
            <th></th>
            <?php foreach ($initiators as $dep) : ?>
                <tr>
                    <td><?= $dep->id ?></td>
                    <td><?= $dep->name ?></td>
                    <td>

                        <a class="btn btn-default btn-sm" href="<?= ST::route('Initiators/edit/' . $dep->id) ?>"><span
                                class="glyphicon glyphicon-edit" title="Редактировать"></span></a>

                        <a class="btn btn-default btn-sm confirmation"
                           href="<?= ST::route('Initiators/delete/' . $dep->id) ?>"><span
                                class="glyphicon glyphicon-remove"
                                title="Удалить"></span></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

    </div>
</div>