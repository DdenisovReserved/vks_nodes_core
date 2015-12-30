<?php
include_once(CORE_REPOSITORY_REAL_PATH . "views/main.php");
global $_TB_IDENTITY;
ST::setUserCss('render-vks.css');
?>

<div class="col-lg-6 col-lg-offset-3 right-border padding25">

    <form class="form-horizontal" method="post" action="<?= ST::route('User/storeMyData') ?>">
        <div class="form-group">
            <div class="h3">Мои данные</div>
            <hr>
        </div>
        <div class="form-group">
            <label for="">Email</label>
            <input class="form-control" name="email" value="<?= $user->email ?>"/>
            <span class="help-block">*На этот адрес будут отправляться отчеты о созданных вами ВКС</span>
        </div>
        <div class="form-group">
            <label for="">ФИО</label>
            <input class="form-control" name="fio" value="<?= $user->fio ?>"/>
        </div>
        <div class="form-group">
            <label for="">Телефон</label>
            <input class="form-control" name="phone" value="<?= $user->phone ?>"/>
        </div>
        <div class="form-group">
            <label>Место работы</label>
            <select class='form-control' name='origin'>
                <?php if(!isset($user->origin)): ?>
                    <option>--Выберите значение--</option>
                <?php endif ?>
                <?php foreach ($_TB_IDENTITY as $tbId => $tbName) : ?>
                    <?php if($tbId == CA_CA): ?>
                        <?php if($user->is_from_domain && explode("\\", $user->login)[0] == 'ALPHA'): ?>
                            <option <?= $user->origin == $tbId ? 'selected' : '' ?>
                                value='<?= $tbId ?>'><?= $tbName['humanName'] ?></option>
                        <?php endif ?>
                    <?php else: ?>
                        <option <?= $user->origin == $tbId ? 'selected' : '' ?>
                            value='<?= $tbId ?>'><?= $tbName['humanName'] ?></option>
                    <?php endif ?>
                <?php endforeach; ?>

            </select>
        </div>


        <div class="form-group">
            <button class="btn btn-success btn-lg">Cохранить</button>
        </div>

    </form>
</div>
<div class="col-lg-3">
    <div class="panel panel-info">
        <div class="panel-heading">Информация</div>
        <div class="panel-body">Поля ФИО и Телефон будут автоматически подставляться в ваши заявки</div>
    </div>
    <div class="panel panel-warning">
        <div class="panel-heading">Обратите внимание</div>
        <div class="panel-body">В поле Email вы должны вписать ваш действующий адрес корпоративной почты, на него будут
            приходить все отчеты о ваших ВКС, сообщения администраторов и другая информация
        </div>
    </div>
</div>


