<?php
ST::deployTemplate('heads/ui_timepicker.inc');
RenderEngine::MenuChanger();
$p = new SearchAssert();
$p->init();
?>
<div class="container">
    <div class="col-lg-offset-3 col-lg-6">
        <h3>Страница поиска ВКС</h3>
        <hr>
    </div>
    <div class="col-lg-offset-3 col-lg-6">
        <div class="alert alert-warning text-center"><h4><span
                    class="glyphicon glyphicon-info-sign text-danger "></span> В настоящий момент поиск производится
                только по id вкс</h4></div>
        <div class="form-inline" action="#">
            <div class="form-group col-lg-12">
                <input id="search-input" class="form-control"/>
                <button class="btn btn-info" type="button" id="search-button">Найти</button>
            </div>
        </div>

        <div class="col-lg-12" id="search-results"></div>

    </div>
</div>

<?php ST::deployTemplate('footer/mainFooter.inc'); ?>
