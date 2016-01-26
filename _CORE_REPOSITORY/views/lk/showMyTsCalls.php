<?php include_once(CORE_REPOSITORY_REAL_PATH . "views/main.php"); ?>
<div class="container">
    <div class="col-lg-12">
        <h3>Мои запросы технической поддержки на ВКС</h3>
        <hr>
        <?php if (!count($calls)): ?>
            <i>Список пуст</i>
        <?php else: ?>
            <table class="table table-bordered">

                <th>#ВКС</th>
                <th>Название</th>
                <th>Дата\время</th>
                <th>Точка</th>
                <th>Статус</th>
                <th>Создано</th>
                <?php $c = 1;
                foreach ($calls as $call): ?>
                    <tr>
                        <td><?= ST::linkToVksPage($call->vks->id, true) ?></td>
                        <td><?= $call->vks->title ?></td>
                        <td>
                            <?= date_create($call->vks->date)->format("d.m.Y") ?>,
                            <?= date_create($call->vks->start_date_time)->format("H:i") ?>
                            - <?= date_create($call->vks->end_date_time)->format("H:i") ?>
                        </td>
                        <td><?= $call->attendance->full_path ?> </td>
                        <td><?= $call->status_label ?> </td>
                        <td><?= date_create($call->created_at)->format("d.m.Y H:i:s") ?> </td>
                    </tr>
                    <?php $c++; endforeach ?>
            </table>
            <?= $pages ?>
        <?php endif ?>
    </div>
</div>
