<?php
ST::deployTemplate('heads/ui_timepicker.inc');
$init = App::get_instance();

RenderEngine::MenuChanger();

//login out from system
if (isset($_GET['drop'])) {
    setcookie("Auth", "", time() - 3600);
    ST::redirectToRoute("Index/index");
}
?>
<div class='container'>
    <div class=' col-md-offset-2 col-md-8 block-border-shadow'>
        <div class='col-md-offset-3 col-md-9'>

            <form class='form-horizontal' method='post' action="<?= ST::route("AuthNew/loginProcess") ?>">
                <div class='form-group'>
                    <h3>Войти в систему</h3>
                    <hr>
                    <div class="alert alert-danger">
                        Нет учетной записи? <a class='btn btn-link text-primary'
                                               href="<?= ST::route('AuthNew/register') ?>">Зарегистрироваться можно тут
                            <span class="glyphicon glyphicon-link"></span></a>
                        <hr>
                    </div>
                    <div class='form-group'>
                        <label class="control-label col-sm-2">Логин:</label>

                        <div class="col-lg-8">
                            <input class='form-control' name='login'/>
                    <span
                        class="help-block">*Совпадает с адресом корпоративной почты (прим.: tomarov@ab.srb.local)</span>
                        </div>
                    </div>
                    <div class='form-group'>

                        <label class="control-label col-sm-2">Пароль:</label>

                        <div class="col-lg-8">
                            <input class='form-control' type='password'
                                   name='password'>
                        </div>
                    </div>

                    <div class='form-group'>
                        <div class="col-lg-8 col-lg-offset-2">
                            <div class="checkbox">
                                <label>
                                    <input type='checkbox' name='remMeVks'>Запомнить меня
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class='form-group'>
                        <div class="col-lg-8 col-lg-offset-2">
                            <button class='btn btn-success btn-lg' type="submit">Войти</button>
                            <a class='btn btn-link' href="<?= ST::route('AuthNew/showRecallPwd') ?>">Не помню пароль</a>
                        </div>
                    </div>
            </form>
        </div>
    </div>
</div>

