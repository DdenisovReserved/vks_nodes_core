<?php ST::deployTemplate('heads/ui_timepicker.inc');
RenderEngine::MenuChanger();?>

<div class="container">
    <div class="col-md-12 text-center">
        <br><br><br><br>
        <h1 class="text-muted">Ошибка</h1>
        <span class="text-muted" style="font-size: 160px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">401</span>

        <h1 class="text-muted">Недостаточно прав для доступа</h1>
        <hr>
        <h4 class="text-center"><?= !Auth::isLogged(App::$instance) ? "<a href='".ST::routeToCa('AuthNew/login')."'>Войти в систему</a> " :'' ?></h4>
    </div>
</div>