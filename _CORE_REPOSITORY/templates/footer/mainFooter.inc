<?php
$stat['all'] = Vks::approved()->count();
$stat['linked']= Vks::approved()->whereNotNull('link_ca_vks_type')->where('other_tb_required',0)->count();
$stat['withTb'] = Vks::approved()->whereNotNull('link_ca_vks_type')->where('other_tb_required',1)->count();
$stat['standart'] = Vks::approved()->where('is_simple',0)->count();
$stat['simple'] = Vks::approved()->where('is_simple',1)->count();

?>
<div id="footer" class="footer">
    <div class="container">
        <div class="col-md-2">
            <br>
            <a class="navbar-brand" href="<?= ST::route("Index/index") ?>">
                <img src="<?= CORE_REPOSITORY_HTTP_PATH ?>images/vkslogo120.png" class="logo">
            </a>
            <div class="clearfix"></div>

        </div>
        <div class="col-md-3 left-border right-border">
            <ul>
                <li>&nbsp</li>

                <li>
                    <p class="">Статистика по ВКС</p>
                    <ul class="list-unstyled">
                        <li><p class="">Всего: <?= $stat['all'] ?></p></li>
                        <li><p class="">Стандартных: <?= $stat['standart'] ?></p></li>
                        <li><p class="">Простых: <?= $stat['simple'] ?></p></li>
                        <hr>
                        <li><p class="">По приглашениям: <?= $stat['linked'] ?></p></li>
                        <li><p class="">С другими ТБ: <?= $stat['withTb'] ?></p></li>

                    </ul>
                </li>
                <li>&nbsp</li>

            </ul>
        </div>
        <div class="col-md-6 ">
            <ul>
                <li>&nbsp</li>
<!--                <li><p class=""><span class="glyphicon glyphicon-dashboard"></span> <a class="text-white" href='--><?//= ST::routeToCa('Statistic/index') ?><!--'>Статистика посещений</a></p></li>-->

                <li><p class=""><span class="glyphicon glyphicon-envelope"></span> <a href="mailto:Tomarov1-iv@mail.ca.sbrf.ru" style="color: #ffffff;">Написать разработчику</a></p></li>

                <li><p class=""><span class="glyphicon glyphicon-link"></span> <a class="text-capitalize text-white" href='?route=Index/changelog'>
                         Версия: <?= NODE_VERSION ?></a></p></li>

                <li><p class=""><span class="glyphicon glyphicon-pawn"></span> Cache: <?= App::$instance->cache->instance ? 'On' : "Off" ?></p></li>
                <li><p class="">
                        <span class="glyphicon glyphicon-link"></span> <a href='<?= ST::routeToCa('AuthNew/login') ?>' style="color:white">Вход по паролю
                        </a>
                    </p>
                </li>



            </ul>
        </div>

    </div>

</div>