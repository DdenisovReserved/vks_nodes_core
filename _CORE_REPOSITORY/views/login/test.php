<?php
ST::deployTemplate('heads/ui_timepicker.inc');
RenderEngine::MenuChanger();


if ($_SERVER['REQUEST_METHOD']=='POST') {
    ST::makeDebug($_POST);
    $ctrl = new auth_controller();
    ST::makeDebug($ctrl->debugRegister());

}

echo "<div class='container'>
      <div class=' col-md-offset-2 col-md-8 block-border-shadow'>";
//если сквозная аутентификация проверяем по таблице, без ввода
    //show fields
    echo "<div class='col-md-offset-3 col-md-6'>";
    echo "<h4>Генератор</h4>";
    echo "<form action='' class='form-horizontal' method='post'>";
    echo "<div class='form-group'>";
    echo "<label>Логин</label><input class='form-control' name='login'>";
    echo "</div>";
    echo "<div class='form-group'>";
    echo "<label>Пароль</label><input class='form-control' type='password' name='password1'>";
    echo "</div>";
    echo "<div class='form-group'>";
    echo "<label>Повторите пароль</label><input class='form-control' type='password' name='password2'>";
    echo "</div>";
    echo "<div class='form-group'>";
    echo "<button class='btn btn-default'>Войти</button>";
    echo "</div>";
    echo "</form>";
    echo "</div>";

