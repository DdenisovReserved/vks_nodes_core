<?php
ST::deployTemplate('heads/ui_timepicker.inc');
RenderEngine::MenuChanger();
?>
<div class="container">
    <div class="col-lg-6 col-lg-offset-3">

        <form class="form-horizontal" method="post" action="<?= ST::route("AuthNew/savePwd/".$user->id) ?>">
            <?= Token::castTokenField() ?>

            <div class="form-group">
                <h4>Изменить пароль</h4>
                <hr>
            </div>
            <div class="form-group">
                <label for="">Старый пароль</label>
                <input class="form-control" type="password" name="old_pwd"/>
            </div>

            <div class="form-group">
                <label for="">Новый пароль</label>
                <input class="form-control" type="password" name="new_pwd"/>
            </div>

            <div class="form-group">
                <label for="">Новый пароль (еще раз)</label>
                <input class="form-control" type="password" name="new_pwd_confirm"/>
            </div>

            <div class="form-group">
                <input class="btn btn-success" type="submit" value="Сохранить">
            </div>
        </form>
    </div>
</div>