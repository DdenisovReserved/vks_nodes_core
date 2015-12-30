<?php
ST::deployTemplate('heads/ui_timepicker.inc');
$init = App::get_instance();

RenderEngine::MenuChanger();
$backPack = ST::lookAtBackPack();
$backPack = $backPack->request;
?>
<script>
    $(document).ready(function () {
        requiredCapcha("#submit");
    })
</script>

<div class='container'>
    <div class=' col-md-offset-2 col-md-8 block-border-shadow'>
        <div class='col-md-offset-3 col-md-6'>

            <form class='form-horizontal' id="form1" method='post'
                  action="<?= ST::route("AuthNew/processRecallpwd") ?>">
                <div class='form-group'>
                    <h4>Восстановить пароль</h4>
                    <hr>
                </div>
                <div class='form-group'>
                    <label>Логин в систему:</label>
                    <input class='form-control' name='login'
                           value="<?= $backPack->has('login') ? $backPack->get('login') : Null ?>"/>
                </div>
                <div class="form-group">
                    <div class="col-md-12  no-left-padding">
                        <label class="">Введите код проверки</label>
                    </div>
                    <div class="col-md-12  no-left-padding">

                        <img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image"/>&nbsp&nbsp<a
                            href="#" class="refresh-captcha"
                            onclick="document.getElementById('captcha').src = 'securimage/securimage_show.php?' + Math.random(); return false"><span
                                class="glyphicon glyphicon-refresh" title="обновить картинку"></span> </a></div>
                    <div class="col-md-4  no-left-padding">
                        <input type="text" class="form-control" name="captcha_code" size="10" maxlength="6"/>

                    </div>

                </div>
                <?php ST::setUpErrorContainer(); ?>
                <div class='form-group'>
                    <button class='btn btn-success' id="submit" type="button">Отправить</button>
                </div>
            </form>
        </div>
    </div>
</div>



