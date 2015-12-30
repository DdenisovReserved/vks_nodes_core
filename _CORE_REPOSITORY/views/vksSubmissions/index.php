<?php
ST::deployTemplate('heads/ui_timepicker.inc');
ST::setUserCss("renderVksNoApproved.css");
ST::setUserCss("attendance/style.css");
RenderEngine::MenuChanger();

?>
<div class="container">
    <div class="col-lg-12">
        <h4>Неутвержденные заявки</h4>
        <hr>
        <?php if (!count($vksList)): ?>
            <div class="alert alert-danger">Неутвержденных заявок нет</div>
        <?php die; endif ?>
        <table class="table table-spriped table-hover">
            <th>#</th><th class="text-center"><a href="<?= RenderEngine::makeOrderLink('id') ?>">id</th>
            <th><a href="<?= RenderEngine::makeOrderLink('date') ?>">Дата</a></th>
            <th><a href="<?= RenderEngine::makeOrderLink('start_date_time') ?>">Время</a></th>
            <th><a href="<?= RenderEngine::makeOrderLink('title') ?>">Наименование</a></th>
            <th>Ответственный</th>
            <th><a href="<?= RenderEngine::makeOrderLink('created_at') ?>">Время создания</a></th>
            <th class="text-center">Повторно</th>
            <th class="text-center">Стек</th>
            <th></th>
            <?php $c = 1; foreach ($data['vksList'] as $vks) :  $vks =(object) $vks ?>
                <tr>
                    <td><?= $c ?></td><td class="text-center"><?= $vks->id ?></td>
                    <td><?= $vks->humanized->date ?></td>
                    <td><?= $vks->humanized->startTime ?> - <?= $vks->humanized->endTime ?></td>
                    <td><?= $vks->title ?></td>
                    <td><?= $vks->init_customer_fio ?>, тел.<?= $vks->init_customer_phone ?></td>
                    <td><?= date_create($vks->created_at)->format("d.m.Y H:i") ?></td>
                    <td class="text-center"><span class="glyphicon <?= $vks->again ? 'glyphicon-ok text-success' : 'glyphicon-remove text-danger' ?>"></span></td>
                    <td class="text-center"><?= $vks->stack ? "<span class='label label-as-badge label-info'>".$vks->stack->id."</span>" : '-' ?></td>
                    <td><a href="<?= ST::route('Vks/showNaVks/'.$vks->id) ?>" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-arrow-right text-info"></span></a></td>
                </tr>
            <?php $c++ ;endforeach; ?>
        </table>
        <?= $data['pages'] ?>
    </div>
</div>

