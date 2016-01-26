<?php if (!Auth::isLogged(App::$instance)): ?>
    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
        <div class="container">
            <h1>Добро пожаловать в систему планирования видеоконференций</h1>
            <p>Для полноценной работы системы пожалуйста <a href="<?= ST::routeToDomainGate() ?>" class="text-info">войдите</a> используя свой доменный логин и пароль
            </p>
            <p> Или <a href="<?= ST::routeToCa('AuthNew/register') ?>" class="text-info">зарегистрируйтесь</a>, это не долго</p>
            <p>
                <a class="btn btn-success btn-lg" href="<?= ST::routeToDomainGate() ?>" role="button">Войти через домен &raquo;</a>
                <a class="btn btn-link" href="<?= ST::routeToCa('AuthNew/register') ?>" role="button">Зарегистрироваться</a>
                <a class="btn btn-link" href="<?= ST::routeToCa('AuthNew/login') ?>" role="button">Войти c паролем</a>
            </p>
        </div>
    </div>
<?php endif ?>