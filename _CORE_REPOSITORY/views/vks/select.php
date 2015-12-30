<?php
ST::deployTemplate('heads/ui_timepicker.inc');
RenderEngine::MenuChanger();
ST::setUserJs('uf-2.js');
?>
<div class="container">

    <div class="col-md-12">
        <h3 class="text-muted heading-main">Заявка на проведение ВКС</h3>
        <hr>

            <h4 class="text-info">Выберите тип создаваемой ВКС</h4>

            <div class="col-md-6 alert alert-warning">

                <a href="<?= ST::route('Vks/create') ?>" class='btn  btn-warning btn-lg  col-lg-12'><span
                        class="glyphicon glyphicon-eye-open"></span> Стандартная заявка
                </a>
            </div>
            <div class="col-md-6 alert alert-success">
                <a href="<?= ST::route('Vks/createSimple') ?>" class='btn btn-success btn-lg col-lg-12'><span
                        class="glyphicon glyphicon-eye-close"></span> Упрощенная заявка
                </a>
            </div>
            <div class="col-md-12 text-center ">
                <h3><div class="get_help_button pointer text-muted"  data-file="help_standart" data-element="main"><span class="glyphicon glyphicon-question-sign text-muted " title="Подсказка"></span> Помощь</div></h3>
            </div>

<!--            <div class="col-md-6 alert alert-info text-center">-->
<!--                <form class="form-horizontal" method='post' action="--><?//= ST::route("Vks/joinCaCreate") ?><!--">-->
<!--                    --><?//= Token::castTokenField() ?>
<!--                    <div class="form-group">-->
<!--                        <h4 class="text-left" style="padding-left: 20px">По приглашению ЦА</h4>-->
<!--                     </div>-->
<!--                    <div class="form-group">-->
<!--                        <div class="col-lg-12">-->
<!--                            <input class="form-control input-lg " name="referrer" value="Введите код приглашения"/>-->
<!--                        </div>-->
<!---->
<!--                    </div>-->
<!--                    <div class="form-group">-->
<!--                    <input class="btn btn-info btn-lg" type="submit" value="Создать">-->
<!--                    </div>-->
<!--                </form>-->
<!---->
<!---->
<!--                <div class="clearfix"></div>-->
<!--            </div>-->

    </div>
</div>


</body>

