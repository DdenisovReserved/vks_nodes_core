<?php
ST::deployTemplate('heads/ui_timepicker.inc');
RenderEngine::MenuChanger();
//dump($_SESSION);
//Token::makeToken();
//dump($_SESSION);
$backPack = ST::lookAtBackPack();
$backPack = $backPack->request;
?>
<div class='container'>
    <div class=' col-md-offset-2 col-md-8'>
        <div class='col-md-offset-3 col-md-8'>

            <form class='form-horizontal' method='post' action="<?= ST::route('User/store') ?>">
                <div class='form-group'>
                    <h3>Регистрация нового пользователя</h3><hr>
                </div>
                <?= Token::castTokenField(); ?>
                <div class='form-group'>
                    <label>Логин:</label>
                    <input class='form-control' name='login' value="<?= $backPack->has('login') ? $backPack->get('login') : Null ?>" "/>
                    <span class="help-block">*Должен совпадать с адресом корпоративной почты (В  сегменте Alpha) (прим.: tomarov@ab.srb.local), на этот адрес мы отправим письмо для подтверждения регистрации</span>
                </div>
                <div class='form-group'>
                    <label>Пароль:</label><input class='form-control' type='password' name='password1'>
                </div>
                <div class='form-group'>
                    <label>Повторите пароль:</label><input class='form-control' type='password' name='password2'>
                </div>
                <div class='form-group'>
                    <label>ФИО (без сокращений):</label>
                    <input class='form-control' name='fio' value="<?= $backPack->has('fio') ? $backPack->get('fio') : Null ?>"/>
                    <span class="help-block">*Это значение будет подставляться в поле ФИО заказчика в ваших заявках</span>
                </div>
                <div class='form-group'>
                    <label>Телефон:</label>
                    <input class='form-control' name='phone'
                           value="<?= $backPack->has('phone') ? $backPack->get('phone') : Null ?>"/>
                    <span class="help-block">*Это значение будет подставляться в поле Телефон заказчика в ваших заявках</span>
                </div>
                <div class='form-group'>
                    <button class='btn btn-success'>Зарегистрироватьcя</button>
                </div>
            </form>
        </div>
    </div>
</div>

