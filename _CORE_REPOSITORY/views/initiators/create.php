<?php
ST::deployTemplate('heads/ui_timepicker.inc');
RenderEngine::MenuChanger();
?>

<div class="container">
    <div class="col-lg-6 col-lg-offset-3 left-border padding25">
        <h3>Создать инициатора</h3>
        <hr>
        <form class="form-horizontal" method='post' action="<?= ST::route("Initiators/store") ?>">
            <?= Token::castTokenField() ?>
            <div class="form-group">
                <label for="">Название</label>
                <input class="form-control" name="name"/>
            </div>
            <div class="form-group">
                <input class="btn btn-success" type="submit" value="Сохранить">
            </div>
        </form>