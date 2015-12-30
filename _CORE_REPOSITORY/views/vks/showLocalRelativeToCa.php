<?php
ST::deployTemplate('heads/ui_timepicker.inc');
RenderEngine::MenuChanger();

if (!Auth::isLogged(App::$instance)) {
    App::$instance->MQ->setMessage('Создавать заявки могут только зарегистрированные пользователи, пожалуйста, войдите в систему или зарегистрируйтесь');
    ST::redirectToRoute('AuthNew/login');
}

?>
<div class="container">

    <div class="col-md-12">
        <h3 class="text-muted heading-main">ВКС в нашем Планировщике созданные на основе ВКС ЦА <?= ST::linkToCaVksPage($caVksId , true) ?></h3>
        <hr>
        <?php if (count($vkses)): ?>
            <table class="table table-striped table-hover">
            <th class="col-lg-1">id</th><th class="col-lg-2">Тема</th><th class="col-lg-2">Заказчик</th><th class="col-lg-5">Участники</th><th class="col-lg-2">Создана</th>
                <th>Статус</th>
            <?php foreach ($vkses as $vks):?>
                <tr>
                    <td><?= ST::linkToVksPage($vks->id, true); ?></td>
                    <td><?= $vks->title ?></td>
                    <td><?= $vks->owner->login ?><?= $vks->owner->phone ? ", (". $vks->owner->phone .")": "" ?></td>
                    <td> <div class="inside_parp">
                            <ul class="list-unstyled">
                                <li class="list-group-item-text"><span
                                        class="glyphicon glyphicon-phone"></span> C рабочих мест: <span
                                        class="label label-as-badge label-default"><?= $vks->in_place_participants_count ?></span>
                                </li>
                                <?php if ($vks->participants): ?>

                                    <?php foreach ($vks->participants as $parp) : ?>
                                        <li class="list-group-item-text">
                                            <?php if ($parp->container): ?>
                                                <span class="text-success glyphicon glyphicon-folder-open"
                                                      title="Кто-то из контейнера"></span>&nbsp
                                            <?php else: ?>
                                                <span class="text-info glyphicon glyphicon-camera"
                                                      title="Точка"></span>&nbsp
                                            <?php endif; ?><?= Auth::isAdmin(App::$instance) && strlen($parp->ip) ? "<span class='text-primary'>[{$parp->ip}]</span> " : "" ?><?=  $parp->full_path ?>
                                        </li>
                                    <?php endforeach; ?>

                                <?php endif ?>


                            </ul>
                        </div></td>
                    <td><?= date_create($vks->created_at)->format("d.m.Y H:i") ?></td>
                    <td><?= $vks->humanized->status_label ?></td>
                </tr>

            <?php endforeach; ?>
            </table>
        <?php else: ?>
            <div class="alert alert-info">Список пуст</div>
        <?php endif ?>
    </div>
</div>


</body>

