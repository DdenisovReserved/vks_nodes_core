<?php
ST::deployTemplate('heads/ui_timepicker.inc');
$init = App::get_instance();

RenderEngine::MenuChanger();
if (!isset($valid) || !$valid) ST::routeToErrorPage('500');

?>
<!--<script>-->
<!--    $(document).ready(function () {-->
<!--        requiredCapcha("#submit");-->
<!--    })-->
<!--</script>-->

<div class='container'>
    <div class=' col-md-offset-2 col-md-8 block-border-shadow'>
        <div class='col-md-offset-3 col-md-9'>

            <form class='form-horizontal' id="form1" method='post'
                  action="<?= ST::route("AuthNew/processResetPwd/".$user->id) ?>">
                <div class='form-group'>
                    <h4>Введите новый пароль для учетной записи <br><?= $user->login ?></h4>
                    <hr>
                </div>
                <div class='form-group'>
                    <label>Пароль:</label><input class='form-control' type='password' name='password1'>
                </div>
                <div class='form-group'>
                    <label>Повторите пароль:</label><input class='form-control' type='password' name='password2'>
                </div>
<!--                <div class="form-group">-->
<!--                    <div class="col-md-12  no-left-padding">-->
<!--                        <label class="">Введите код проверки</label>-->
<!--                    </div>-->
<!--                    <div class="col-md-12  no-left-padding">-->
<!---->
<!--                        <img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image"/>&nbsp&nbsp<a-->
<!--                            href="#" class="refresh-captcha"-->
<!--                            onclick="document.getElementById('captcha').src = 'securimage/securimage_show.php?' + Math.random(); return false"><span-->
<!--                                class="glyphicon glyphicon-refresh" title="обновить картинку"></span> </a></div>-->
<!--                    <div class="col-md-4  no-left-padding">-->
<!--                        <input type="text" class="form-control" name="captcha_code" size="10" maxlength="6"/>-->
<!---->
<!--                    </div>-->
<!---->
<!--                </div>-->
                <?php ST::setUpErrorContainer(); ?>
                <div class='form-group'>
                    <button class='btn btn-success' id="submit" type="button">Отправить</button>
                </div>
            </form>
        </div>
    </div>
</div>



