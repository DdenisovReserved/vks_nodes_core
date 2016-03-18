<?php if (!Auth::isLogged(App::$instance)): ?>
    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
        <div class="container">
            <h1>Добро пожаловать в систему планирования видеоконференций</h1>
<!--            <p>Для полноценной работы системы пожалуйста войдите используя свой доменный логин и пароль-->
<!--            </p>-->
<!--            <p> Или <a href="--><?//= ST::routeToCa('AuthNew/register') ?><!--" class="text-info">зарегистрируйтесь</a>, это не долго</p>-->
            <p>
                <a class="btn btn-success btn-lg" href="<?= ST::routeToDomainGate() ?>" role="button">Войти &raquo;</a>
            </p>
            <p class="text-muted small" style="font-size: 14px;">
                *Если вы администратор или у вас есть логин и пароль выданный АС ВКС, нажмите
                <a href="<?= ST::routeToCa('AuthNew/login') ?>" role="button">вход по паролю</a>
            </p>
        </div>
    </div>
<?php endif ?>