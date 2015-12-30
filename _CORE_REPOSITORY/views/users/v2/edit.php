<?php
ST::deployTemplate('heads/ui_timepicker.inc');
RenderEngine::MenuChanger();
$backPack = $data['backPack'];
?>
<div class="container">
    <div class="col-md-6 col-md-offset-3">
        <h3>Редактировать пользователя</h3>
        <hr>
        <form class="form-horizontal" method="post" action="<?= ST::route("User/update/" . $backPack->id) ?>">
            <?= Token::castTokenField();  ?>
            <div class="form-group">
                <label>Логин </label>
                <input name="login" class="form-control"
                       value="<?= isset($backPack->login) ? $backPack->login : false ?>"/>
            </div>
            <div class="form-group">
                <label>ФИО</label>
                <input name="fio" class="form-control" value="<?= isset($backPack->fio) ? $backPack->fio : false ?>"/>
            </div>
            <div class="form-group">
                <label>Телефон</label>
                <input name="phone" class="form-control"
                       value="<?= isset($backPack->phone) ? $backPack->phone : false ?>"/>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input name="email" class="form-control"
                       value="<?= isset($backPack->email) ? $backPack->email : false ?>"/>
            </div>
            <div class="form-group">
                <label>Статус</label>
                <select name="status" class="form-control">
                    <option value="<?= USER_STATUS_APPROVED ?>" <?= ($backPack->status == USER_STATUS_APPROVED) ? 'selected' : false ?>>Утвержден</option>
                    <option value="<?= USER_STATUS_NOTAPPROVED ?>"<?= ($backPack->status == USER_STATUS_NOTAPPROVED) ? 'selected' : false ?>>Не Утвержден</option>
                    <option value="<?= USER_STATUS_BANNED ?>" <?= ($backPack->status == USER_STATUS_BANNED) ? 'selected' : false ?>>Забанен</option>
                </select>
            </div>
            <div class="form-group">
                <label>Роль</label>
                <select name="role" class="form-control">
                    <option value="<?= ROLE_USER ?>"<?= ($backPack->role == ROLE_USER) ? 'selected' : false ?>>Пользователь</option>
                    <option value="<?= ROLE_ADMIN ?>"<?= ($backPack->role == ROLE_ADMIN) ? 'selected' : false ?>>Админ</option>
                </select>
            </div>
            <?php if(!$backPack->is_from_domain): ?>
            <div class="form-group alert alert-warning">
                <input type="checkbox" id="password-reset" name="password-reset">
                <label for="password-reset">Сбросить пароль на первоначальный (1-6)</label>
            </div>
            <?php endif ?>
            <div class="form-group">
                <button class="btn btn-success">Сохранить</button>
<!--                <a class="btn btn-danger" href="--><?php //// ST::route("User/delete/".$backPack->id)?><!--">Удалить</a>-->
            </div>
        </form>
    </div>
</div>