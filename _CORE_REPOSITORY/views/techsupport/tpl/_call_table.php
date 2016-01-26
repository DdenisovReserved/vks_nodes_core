<?php if (!count($vks->tech_support_requests)): ?>
    <span class="text-muted"><i>Список пуст</i></span>
<?php else: ?>
    <table class="table table-bordered">
        <th>#</th>
        <th>Точка</th>
        <th>Заказал</th>
        <th>Сообщение</th>
        <th>Статус</th>
        <?php $c = 1;
        foreach ($vks->tech_support_requests as $requests): ?>
            <tr>
                <td><?= $c ?></td>
                <td><?= $requests->attendance->full_path ?></td>
                <td><?= $requests->owner ? $requests->owner->fio : 'не определен' ?>
                    , <?= $requests->owner ? $requests->owner->phone : 'не определен' ?></td>
                <td><?= $requests->user_message ?></td>
                <td><?= $requests->status_label ?></td>

            </tr>
            <?php $c++; endforeach ?>
    </table>
<?php endif ?>