<?php
ST::deployTemplate('heads/ui_timepicker.inc');
ST::setUserJs('settings/users.js');
RenderEngine::MenuChanger();
//dump($data['users']);
?>
<div class="container">
    <div class="col-md-12">
        <h3>Пользователи системы</h3>
        <hr>
        <div class="col-md-12 form-inline search-block alert alert-info">
            <b><span class="glyphicon glyphicon-search"></span> Поиск пользователей (по логину):</b>
            <input class="form-control" id="user-search-field"/>
        </div>

        <table id="founded-table" class="table table-striped table-hover"></table>

        <table class="table table-striped table-hover" id="user-table">
            <th>#</th>
            <th><a href="<?= RenderEngine::makeOrderLink('id') ?>">id</a></th>
            <th><a href="<?= RenderEngine::makeOrderLink('login') ?>">Логин</a></th>
            <th><a href="<?= RenderEngine::makeOrderLink('role') ?>">Роль</a></th>
            <th><a href="<?= RenderEngine::makeOrderLink('status') ?>">Статус</a></th>
            <th><a href="<?= RenderEngine::makeOrderLink('fio') ?>">Фио*</a></th>
            <th><a href="<?= RenderEngine::makeOrderLink('phone') ?>">Тел*</a></th>
            <th><a href="<?= RenderEngine::makeOrderLink('is_from_domain') ?>">Доменный*</a></th>
<!--            <th><a href="--><?//= RenderEngine::makeOrderLink('created_at') ?><!--">Создан</a></th>-->
            <th class="col-lg-2 text-center"><span class="glyphicon glyphicon-cog"></span> </th>
            <?php $c = 1;
            foreach ($data['users'] as $user) : ?>
                <tr class="<?= $user->status ? '' : 'alert alert-warning' ?>
                    <?= $user->status == USER_STATUS_BANNED ? "alert alert-danger" : '' ?>">
                    <td><?= $c ?></td>
                    <td><?= $user->id ?></td>
                    <td><?= $user->login ?></td>
                    <td><?= $user->humanized->role_label ?></td>
                    <td class="text-center"><?= $user->humanized->status_label ?></td>
                    <td><?= $user->fio ?></td>
                    <td><?= $user->phone ?></td>
                    <td class="text-center"><?= $user->is_from_domain ? '<span class="glyphicon glyphicon-ok text-success"></span>' : '<span class="glyphicon glyphicon-remove text-danger"></span>'?></td>
<!--                    <td>--><?php //echo $user->humanized->created_at ?><!--</td>-->

                    <td class="col-md-2 text-center">


                        <?php if (!$user->status): ?>
                            <a class="btn btn-default btn-sm" title="Подтвердить"
                               href="<?= ST::route('User/approve/' . $user->id) ?>"><span
                                    class="glyphicon glyphicon-check text-success"></span> </a>
                        <?php endif ?>

                        <a class="btn btn-default btn-sm" title="Редактировать"
                           href="<?= ST::route('User/edit/' . $user->id) ?>"><span
                                class="glyphicon glyphicon-edit text-info"></span> </a>

                        <?php if ($user->status != USER_STATUS_BANNED): ?>
                            <a class="btn btn-danger btn-sm" title="Забанить"
                               href="<?= ST::route('User/ban/' . $user->id) ?>"><span
                                    class="glyphicon glyphicon-ban-circle "></span> </a>
                        <?php else : ?>
                            <a class="btn btn-success btn-sm" title="Разбанить"
                               href="<?= ST::route('User/unban/' . $user->id) ?>"><span
                                    class="glyphicon glyphicon-check "></span> </a>
                        <?php endif ?>


                    </td>
                </tr>
                <?php $c++;  endforeach ?>

        </table>
    </div>
</div>