<?php
$here = FrontController::whereIm();
//pull unsubmitted vks
$vksCtrl = new Vks_controller();
$NotApprovedVks = $vksCtrl->countNotApprovedVks();
global $_TB_IDENTITY;
?>
<body>
<div id="custom-bootstrap-menu" class="navbar navbar-default navbar-static-top">
    <div class="container-fluid">
        <!--    <div class="container">-->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?= ST::route("Index/index") ?>">
                АС ВКС</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li role='presentation' class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                       aria-expanded="false"><b><?= TB_NAME ?></b> <span
                            class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <?php foreach ($_TB_IDENTITY as $node_code => $nodeInfo) : ?>
                            <li class=''>
                                <?php if ($node_code != MY_NODE && $node_code != 0): ?>
                                    <a href="<?= NODES_CORE_PATH . $nodeInfo['serviceName'] ?>"><?= $nodeInfo['humanName'] ?></a>
                                <?php elseif ($node_code == 0): ?>
                                    <a href="<?= HTTP_PATH ?>"><?= $nodeInfo['humanName'] ?></a>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>

                    </ul>
                </li>

                <li class='<?= ST::ifActiveMarkIt(['index/index']) ?>'>
                    <a href="<?= ST::route("Index/index") ?>">
                        Главная</a>
                </li>
                <?php if (!empty($NotApprovedVks)): ?>
                    <li class='<?= ST::ifActiveMarkIt(['vks/notapproved', 'vks/shownavks'], []) ?>'>
                        <a href="<?= ST::route('Vks/notApproved') ?>" class=""><span class="text-danger"><span
                                    class="glyphicon glyphicon-fire"></span> <b>Заявки</b></span>
                            <span class='label label-danger'><?= $NotApprovedVks ?></span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (!empty($getMyLinks)) : ?>
                    <li class=''>
                        <a href='?route=VksRelations/showmy'><span class="glyphicon glyphicon-fire"></span> Утверждение
                            <div class='label label-warning '><?php echo $getMyLinks; ?></div>
                        </a>
                    </li>
                <?php endif; ?>
                <li role='presentation'
                    class="dropdown <?= ST::ifActiveMarkIt(['vks/select','vks/create','vks/createsimple','vks/admincreate']) ?>">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">
                        Добавить<span
                            class="caret"></span>

                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li class=''>
                            <a href="<?= ST::route('Vks/select') ?>">Заявку</a>
                        </li>
                        <li class=''>
                            <a href='<?= ST::route('Vks/adminCreate') ?>'>
                                ВКС</a>
                        </li>
                    </ul>
                </li>
                <li role='presentation'
                    class="dropdown <?= ST::ifActiveMarkIt(['index/settings', 'attendancenew/show','attendancenew/create', 'attendancenew/edit', 'user/index'], ['load', 'settings', 'dashboard', 'log', 'departments', 'reporter']) ?>">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">
                        Система<span
                            class="caret"></span>

                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li class=''>
                            <a href="<?= ST::route('Dashboard/index') ?>">Панель
                                наблюдения</a>
                        </li>
                        <li class=''>
                            <a href="<?= ST::route("Load/showJsLoadGraph/" . date_create()->format("d.m.Y") . "") ?>">Нагрузка</a>

                        </li>
                        <li class=''>
                            <a href='<?= ST::route('Index/settings') ?>'>Настройки</a>
                        </li>
                        <li class=''>
                            <a href='<?= ST::route('Reporter/Index') ?>'>Отчеты</a>
                        </li>

                    </ul>
                </li>
                <li role='presentation' class="dropdown <?= ST::ifActiveMarkIt(['lk/show', 'user/editmydata', 'user/editcolors', 'authnew/changepwd']) ?>">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                       aria-expanded="false">ЛK<span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">

                        <li class=''>
                            <a href="<?= ST::route('Lk/show') ?>">Мои ВКС</a>
                        </li>
                        <li class=''>
                            <a href="?route=Lk/showMyTechSupportRequests">Мои запросы в ТП</a>
                        </li>
                        <li class=''>
                            <a href="<?= ST::route('User/editMyData') ?>">Мои данные</a>
                        </li>
                        <li class=''>
                            <a href="<?= ST::route('User/editColors') ?>">Цветовая схема</a>
                        </li>
                        <?php if(!App::$instance->user->is_from_domain): ?>
                            <li class=''>
                                <a href="<?= ST::route('AuthNew/changePwd/' . App::$instance->user->id) ?>">Смена пароля</a>
                            </li>
                        <?php endif ?>
                        <!--                            <li class='-->
                        <?php //echo $currentPage=="my_lists" ? "active": false; ?><!--'>-->
                        <!--                                <a href="?r=views/users/my_data"><span class="glyphicon glyphicon-th-list"></span> Мои списки</a>-->
                        <!--                            </li>-->
                    </ul>
                </li>
                <li class='<?= ST::ifActiveMarkIt(['attendancenew/showschedule'], []) ?>'>
                    <a class="" href="<?= ST::route("attendancenew/showschedule/0/".date_create()->format("Y-m-d")."/1") ?>">Расписания</a>
                </li>

                <li class=''>
                    <a class="" id="search-vks" href="<?= ST::route("Search/index") ?>">Поиск</a>
                </li>


            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php if(strlen(Settings_controller::getOther('help-phone'))): ?>
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                            aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <span class="navbar-brand" >
                        <span class="glyphicon glyphicon-phone-alt label-as-badge"></span> <?= Settings_controller::getOther('help-phone') ?></span>
                </div>
                <?php endif ?>
                <li><a href='<?= ST::route('AuthNew/logout') ?>'
                       class=""><span
                            class="glyphicon glyphicon-log-out" title="Выйти из системы (<?= App::$instance->user->login ?>)"></span></a>
                </li>

            </ul>
        </div>
        <!--/.nav-collapse -->
    </div>
</div>

<?php App::$instance->MQ->showMessage(); ?>

