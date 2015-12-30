<?php
ST::deployTemplate('heads/ui_timepicker.inc');
RenderEngine::MenuChanger();
$model = new users_model();
$getUser = $model->getUserByToken($_GET['t']);
empty($getUser) ? die("Bad request") : true;

$ctrl = new auth_controller();
!is_int($ctrl->makeThisUserApproved($getUser['id'])) ? die("Bad request & already Approved") : true;
?>
<div class='container'>
    <div class=' col-md-offset-2 col-md-8 block-border-shadow'>
        <div class='col-md-offset-2 col-md-8'>

            <form class='form-horizontal' method='post'>
                <div class='form-group'>
                    <h4>Подтверждение регистрации для пользователя: <?php echo $getUser["login"]; ?></h4>
                </div>
                <div class='form-group'>
                    <p>Добро пожаловать в систему, <?php echo $getUser["fio"]; ?></p>
                </div>
                <div class='form-group'>
                    <p>Ваша учетная запись<b> <?php echo $getUser["login"]; ?></b> подтверждена</p>
                </div>
                <div class='form-group'>
                    <p><a href="?r=views/login/login" class="btn btn-success">На страницу авторизации</a> </p>
                </div>

            </form>
        </div>
    </div>
</div>

