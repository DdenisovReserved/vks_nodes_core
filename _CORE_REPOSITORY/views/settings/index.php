<?php
ST::deployTemplate('heads/ui_timepicker.inc');
RenderEngine::MenuChanger();
ST::setUserCss('settings/core.css');
?>
<div class="container">
    <div class="col-md-8 col-md-offset-2 block-border-shadow-normal-padding ">
        <h4>Настройки</h4>
        <hr>
        <ul>
<!--            <li><a href="?route=Modules/edit/" class=""><h3 style="margin-top: -15px;"><span class="glyphicon glyphicon-compressed"></span></h3>Модули</a></li>-->
            <li><a href="?route=AttendanceNew/show/" class=""><h3 style="margin-top: -15px;"><span class="glyphicon glyphicon-book"></span></h3>Справочник точек</a></li>
            <li><a href="<?= ST::route("User/index") ?>" class=""><h3 style="margin-top: -15px;"><span
                            class="glyphicon glyphicon-user"></span></h3>Пользователи системы</a></li>
            <li><a href="<?= ST::route("log/index") ?>" class=""><h3 style="margin-top: -15px;"><span
                            class="glyphicon glyphicon-bed"></span></h3>Логи</a></li>
            <li><a href="<?= ST::route("Settings/editServersLoad") ?>" class=""><h3 style="margin-top: -15px;"><span
                            class="glyphicon glyphicon-modal-window"></span></h3>Производ. серверов ВКС ТБ</a></li>
            <li><a href="<?= ST::route("Settings/editSimpleVksCodeSet") ?>"><h3 style="margin-top: -15px;"><span
                            class="glyphicon glyphicon-object-align-horizontal"></span></h3>Диапазон кодов для простых вкс</a></li>
            <li><a href="<?= ST::route("Departments/index") ?>"><h3 style="margin-top: -15px;"><span
                            class="glyphicon glyphicon-list-alt"></span></h3>Подразделения</a></li>
            <!--            <li><a href="--><? //=ST::route("Initiators/index") ?><!--">Инициаторы</a> </li>-->
            <li><a href="<?= ST::route("Settings/editCodeDelivery") ?>"><h3
                        style="margin-top: -15px;"><span
                            class="glyphicon glyphicon-globe"></span></h3>Шаблоны выдачи кодов подключения</a></li>
            <li><a href="<?= ST::route("BlockedTime/index") ?>"><h3 style="margin-top: -15px;"><span
                            class="glyphicon glyphicon-time"></span></h3>Блокировки</a></li>
            <li><a href="<?= ST::route("Settings/managePublicMessage") ?>"><h3 style="margin-top: -15px;"><span
                            class="glyphicon glyphicon-comment"></span></h3>Публичное сообщение</a></li>
            <li><a href="<?= ST::route("Settings/manageHelp") ?>"><h3 style="margin-top: -15px;"><span
                            class="glyphicon glyphicon-question-sign"></span></h3>Подсказки на формах</a></li>
            <li><a href="<?= ST::route("Settings/editOther") ?>"><h3 style="margin-top: -15px;"><span
                            class="glyphicon glyphicon-cog"></span></h3>Разное</a></li>

        </ul>
    </div>
