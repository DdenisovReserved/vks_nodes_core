<?php
$getToday = date("d.m.Y");
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
        </a>
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
            <li class='<?= ST::ifActiveMarkIt(['index/index', 'vks/index']) ?>'>
                <a href="<?= ST::route("Index/index") ?>">Главная</a>
            </li>
            <li class='<?= ST::ifActiveMarkIt(['vks/create', 'vks/select'], []) ?>'>
                <a href="<?= ST::route("Vks/select") ?>">Создать
                    заявку</a>
            </li>
            <!--                <li class='--><?php //echo $currentPage=="issue-index" ? "active": false; ?><!--'>-->
            <!--                    <a href='?r=views/tracker/issue-index'><span class="glyphicon glyphicon-education"></span> Баг трекер</a></li>-->
            <?php if (!empty($getMyLinks)) : ?>
                <li class='<?= ST::ifActiveMarkIt([], ['vksrelations']) ?>'>
                    <a href='?route=VksRelations/showmy''>Утверждение
                    <div class='label label-warning '><?= $getMyLinks ?></div>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (Auth::isLogged(App::$instance)) : ?>
                <li role='presentation' class="dropdown <?= ST::ifActiveMarkIt([], ['lk', 'authnew']) ?>">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">
                        Личный кабинет<span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li class=''>
                            <a href="?route=Lk/show">Мои ВКС</a>
                        </li>
                        <li class=''>
                            <a href="<?= ST::route('User/editMyData') ?>">Мои данные</a>
                        </li>
                        <li class=''>
                            <a href="<?= ST::route('User/editColors') ?>">
                                Цветовая схема</a>
                        </li>
                        <?php if(!App::$instance->user->is_from_domain): ?>
                            <li class=''>
                                <a href="<?= ST::route('AuthNew/changePwd/' . App::$instance->user->id) ?>">Смена пароля</a>
                            </li>
                        <?php endif ?>

                    </ul>
                </li>
            <?php endif; ?>
            <li class='<?= ST::ifActiveMarkIt(['AttendanceNew/showSchedule'], []) ?>'>
                <a class="" href="<?= ST::route("AttendanceNew/showSchedule/0/".date_create()->format("Y-m-d")."/1") ?>">Расписания</a>
            </li>
            <li class='<?= ST::ifActiveMarkIt([], ['search']) ?>'>
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
            <?php if (Auth::isLogged(App::$instance)) : ?>
                <li><a href='<?= ST::route('AuthNew/logout') ?>'
                       class="<?php if (Auth::isVip(App::$instance)) echo "vip"; ?>"><span
                            class="glyphicon glyphicon-log-out" title="Выйти из системы (<?= App::$instance->user->login ?>)"></span></a>
                </li>
            <?php else : ?>
            <li class='<?= ST::ifActiveMarkIt(['authnew/login', 'authnew/showrecallpwd']) ?>'>
                <a href='<?= ST::routeToCARaw('domain_gate/warp.php') ?>'>Вход
                </a>
            </li>
            <li class=''><a
                    href='<?= HTTP_PATH . "?route=AuthNew/register" ?>'>Регистрация</a>
            </li>
        </ul>
        <?php endif; ?>


        <!--            </ul>-->
    </div>
    <!--/.nav-collapse -->
    </div>
</div>
<!--<div class="alert alert-danger text-center">-->
<?php ////echo $init->opt->pm; ?>
<!--    </div>-->
<?php App::$instance->MQ->showMessage(); ?>