<?php
ST::deployTemplate('heads/ui_timepicker.inc');
RenderEngine::MenuChanger();
//dump($logList[0]);
?>
<div class="container">
    <div class="col-lg-12">
        <h3 class="text-primary">Лог событий
            <?php if ($eventType): ?>
                [<?= $eventType ?>]
            <?php else: ?>
                [все события]
            <?php endif ?>
            </h3><hr>

        <div class="filters-container">
            <span class="btn btn-success btn-sm">Фильтры:</span>
            <a class="btn btn-info btn-sm" href="<?= ST::route("log/index/") ?>">Все</a>
            <a class="btn btn-default btn-sm" href="<?= ST::route("log/index/".LOG_USER_LOGIN) ?>">Вход пользователя</a>
            <a class="btn btn-default btn-sm" href="<?= ST::route("log/index/".LOG_USER_REGISTER) ?>">Регистрация пользователя</a>
            <a class="btn btn-default btn-sm" href="<?= ST::route("log/index/".LOG_ADMIN_LOGIN) ?>">Вход Админа</a>
            <a class="btn btn-default btn-sm" href="<?= ST::route("log/index/".LOG_VKSWS_CREATED) ?>">Создание ВКС c Поддержкой</a>
            <a class="btn btn-default btn-sm" href="<?= ST::route("log/index/".LOG_VKSWS_UPDATED) ?>">Изменение ВКС c Поддержкой</a>
            <a class="btn btn-default btn-sm" href="<?= ST::route("log/index/".LOG_VKSWS_DELETED) ?>">Удаление ВКС c Поддержкой</a>
            <a class="btn btn-default btn-sm" href="<?= ST::route("log/index/".LOG_VKSNS_CREATED) ?>">Создание ВКС в ВК</a>
            <a class="btn btn-default btn-sm" href="<?= ST::route("log/index/".LOG_VKSNS_UPDATED) ?>">Изменение ВКС в ВК</a>
            <a class="btn btn-default btn-sm" href="<?= ST::route("log/index/".LOG_VKSNS_DELETED) ?>">Удаление ВКС в ВК</a>
            <a class="btn btn-default btn-sm" href="<?= ST::route("log/index/".LOG_MAIL_SENDED) ?>">Отправка почты</a>
            <a class="btn btn-default btn-sm" href="<?= ST::route("log/index/".LOG_MAIL_SENDED) ?>">Изменение конфигурации </a>
            <a class="btn btn-default btn-sm" href="<?= ST::route("log/index/".LOG_SECURITY) ?>">Безопасность</a>
            <a class="btn btn-default btn-sm" href="<?= ST::route("log/index/".LOG_ADMIN_SCHEDULE) ?>">РРаПВКС</a>
            <a class="btn btn-default btn-sm" href="<?= ST::route("log/index/".LOG_OTHER_EVENTS) ?>">Другие</a>
        </div><hr>


        <?php if (count($logList)==0): ?>
            <div class="alert alert-info">Таких событий не найдено</div>
        <?php die; endif ?>
        <table class="table table-striped table-hover">
            <th><a href="<?= RenderEngine::makeOrderLink('id') ?>">#</a></th>
            <th><a href="<?= RenderEngine::makeOrderLink('event_type') ?>">Тип события</a></th>
            <th><a href="<?= RenderEngine::makeOrderLink('content') ?>">Содержание</a></th>
            <th><a href="<?= RenderEngine::makeOrderLink('from_ip') ?>">ip</a></th>
            <th><a href="<?= RenderEngine::makeOrderLink('by_user') ?>">Пользователь</a></th>
            <th><a href="<?= RenderEngine::makeOrderLink('created_at') ?>">Время</a></th>
            <?php foreach ($logList as $log) : ?>
                <tr>
                    <td><?= $log->id ?></td>
                    <td><?= $log->humanized->event_type ?></td>
                    <td><?= $log->content ?></td>
                    <td><?= $log->from_ip ?></td>
                    <td><?= @$log->user->login ?></td>
                    <td><?= date_create($log->created_at)->format("d.m.Y H:i:s") ?></td>
                </tr>

            <?php endforeach; ?>
        </table>
        <?= $pages ?>
    </div>
</div>