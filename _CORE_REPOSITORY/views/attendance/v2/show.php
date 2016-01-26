<?php
ST::deployTemplate('heads/ui_timepicker.inc');
ST::setUserCss('attendance/style.css');
ST::setUserCss('attendance/manage-style.css');
RenderEngine::MenuChanger();
$rowCounter = 1;

?>
<div class="container">
    <div class="col-md-12 block-border-shadow-normal-padding ">
        <!--            if no points -->
        <a class="btn btn-success" href="?route=AttendanceNew/create/<?=
        (empty(FrontController::getParams())) ? 1 : FrontController::getParams();
        ?>"><span class="glyphicon glyphicon-plus-sign"></span> Создать</a><br>
        <!--        deploy breadcrumps -->
        <div class="breadcrumb-container">
            <a href="?route=AttendanceNew/show/1" class="btn btn-default btn-sm"><span
                    class="glyphicon glyphicon-home"></span></a>
            <?php if ($data['breadCrumps']): ?>
                <?php foreach ($data['breadCrumps'] as $point) : ?>
                    <a href="?route=AttendanceNew/show/<?= $point->id ?>"
                       class="btn btn-default btn-sm <?php if (FrontController::getParams() == $point->id) echo "btn-info"; ?>"><?= $point->name ?></a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <!--        !deploy breadcrumps -->
        <?php if (count($data['points']) == 0): ?>
            <div class="alert alert-danger">В данном контейнере ничего нет</div>
            <?php die; ?>
        <?php endif; ?>

        <table class='table table-bordered table-striped table-hover'>

            <th class="col-lg-1 text-center">#</th>
            <th class="text-center"><a href="<?= RenderEngine::makeOrderLink('container') ?>">Тип</a></th>
            <th class="col-lg-3"><a href="<?= RenderEngine::makeOrderLink('name') ?>">Название</a></th>
            <th class="col-lg-2"><a href="<?= RenderEngine::makeOrderLink('ip') ?>">ip*</a></th>
            <th class="col-lg-1 text-center"><span class="glyphicon glyphicon-log-in"
                                                   title="Объектов (точек и контейнеров) внутри"></span></th>
            <th class="col-lg-1 text-center"><span class="glyphicon glyphicon-flash" title="Активность точки"></span>
            </th>
            <th class="col-lg-1 text-center"><span class="glyphicon glyphicon-random"
                                                   title="Проверять на занятость при создании ВКС"></span></th>
            <th class="col-lg-1 text-center"><span class="glyphicon glyphicon-screenshot"
                                                   title="Техническая поддержка"></span></th>


            <th class="col-lg-2 text-center"><span class="glyphicon glyphicon-cog" title="Действия"></span></th>

            <!--            loop throught points data -->
            <?php foreach ($data['points'] as $point) : ?>
                <tr <?php if (!$point->active): ?>
                    <?= "class='alert alert-danger'" ?>
                <?php endif; ?>>
                    <td class="text-center"><?= $rowCounter ?></td>

                    <td class="text-center">
                        <?php if ($point->container): ?>
                            <span class='glyphicon glyphicon-th-large text-success' title='Контейнер'></span>
                        <?php else: ?>
                            <span class='glyphicon glyphicon-camera text-info' title='Точка'></span>
                        <?php endif; ?>
                    </td>
                    <?php if ($point->container): ?>
                        <td><a style="font-size: 16px;"
                               href="?route=AttendanceNew/show/<?= $point->id ?>"> <?= $point->name ?></a></td>
                        <td>-</td>
                    <?php else: ?>
                        <td><span style="font-size: 16px;"><?= $point->name ?></span></td>
                        <td><span style="font-size: 16px;"><?= $point->ip ?></span></td>
                    <?php endif; ?>
                    <td class="text-center">
                        <?php if ($point->container): ?>
                            <span class="badge"><?= count($point->childs) ?></span>
                        <?php else: ?>
                            <?= "-" ?>
                        <?php endif; ?>
                    </td>

                    <td class="text-center">
                        <?php if ($point->active): ?>
                            <span class="glyphicon glyphicon-ok text-success"
                                  title="Активна и видна пользователям"></span>
                        <?php else: ?>
                            <span class="glyphicon glyphicon-remove text-danger"
                                  title="Не активна и не видна"></span>
                        <?php endif; ?>
                    </td>

                    <td class="text-center">
                        <?php if ($point->check): ?>
                            <span class="glyphicon glyphicon-ok text-success"
                                  title="проверяем"></span>
                        <?php else: ?>
                            <span class="glyphicon glyphicon-remove text-danger"
                                  title="не проверяем"></span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <?php if (!$point->container): ?>
                            <?php if ($point->tech_supportable): ?>
                                <span class="glyphicon glyphicon-ok text-success"
                                      title="ТП включена"></span>
                            <?php else: ?>
                                <span class="glyphicon glyphicon-remove text-danger"
                                      title="ТП Выключена"></span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="glyphicon glyphicon-minus text-muted"
                                  title="ТП для контейнеров не предоставляется"></span>
                        <?php endif; ?>
                    </td>

                    <td class="text-center">

                        <a class="btn btn-default btn-sm" href="?route=AttendanceNew/edit/<?= $point->id ?>"><span
                                class="glyphicon glyphicon-edit" title="Редактировать"></span></a>
                        <a class="btn btn-default btn-sm confirmation"
                           href="?route=AttendanceNew/delete/<?= $point->id ?>"><span class="glyphicon glyphicon-remove"
                                                                                      title="Удалить"></span></a>
                    </td>

                </tr>
                <?php $rowCounter++; endforeach; ?>
        </table>
        <?= $pages ?>

    </div>
</div>
