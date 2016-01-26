<?php ST::deployTemplate('heads/ui_timepicker.inc');
RenderEngine::MenuChanger();?>

<div class="container">
    <div class="col-md-12 text-center">
        <br><br><br><br>
        <h1 class="text-muted">Ошибка</h1>
        <span class="text-muted" style="font-size: 160px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">500</span>
        <h1 class="text-muted">Ошибка в приложении</h1><hr>
        <?php if(strlen($message)): ?>
            <h2 class="text-danger">Текст ошибки: <?= $message ?></h2><hr>
        <?php endif ?>
        <h4>Обратитесь к администратору системы</h4>
        <h4 class="text-center"><a href="<?= ST::route('Index/index') ?>">Вернуться на главную</a></h4>
    </div>
</div>
