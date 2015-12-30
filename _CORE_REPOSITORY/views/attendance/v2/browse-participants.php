<?php
ST::setUserJs('attendance/v2/core-participants.js');
?>

<script>
    $(document).ready(function () {
        $(".nav-container").each(function () {
            $(this).hide();
        });
        $("#main-container").show();

        $("[name='in_place_participants_count_modal']").val($("[name='in_place_participants_count']").val());

    });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>

<div class="container-response">
    <div class="errors-cnt-simple"></div>
    <div class="col-lg-12 navigation">
        <span class="col-md-9">
        <button class="btn btn-default btn-success active" id="nav-points" type="button"><span
                class="glyphicon glyphicon-camera"></span> Справочник
            точек
        </button>
        </span>
        <span class="col-md-3 text-right nopadding">
                    <div class="form-inline">
                        <input id="search-input" class="form-control pull-left" style="width: 70%"/>
                        <button class="btn btn-default " type="button" id="search-button" style="width: 29%"><span
                                class="glyphicon glyphicon-search"></span> Найти
                        </button>
                    </div>
        </span>
    </div>
    <div class="col-lg-12 navigation">
        <div id="main-container" class="col-md-9 nav-container" style="min-height: 750px; height: auto;">


            <!--        <div id="search-block">-->
            <!--            <span class="col-lg-8 col-lg-offset-2 nopadding"><input id="search-input" class="form-control"/></span>-->
            <!--            <span class="col-lg-2 nopadding"><button class="btn btn-info" id="search-button">Найти</button></span>-->
            <!--        </div>-->
            <div class="clearfix"></div>
            <br>

            <div class="col-md-12 nopadding">
                <ol id="path" class="breadcrumb"></ol>

            </div>

            <div class="col-md-6">
                <h4><span class="glyphicon glyphicon-th"></span> Контейнеры</h4>
            </div>
            <div class="col-md-6">
                <h4><span class="glyphicon glyphicon-camera"></span> Точки подключения</h4>
            </div>
            <div class="col-md-12">
                <hr class="nopadding">
                <div class="col-md-6 text-right">
                    <h5 id="cOrder" class="text-muted"></h5>
                </div>
                <div class="col-md-6 text-right">
                    <h5 id="pOrder" class="text-muted"></h5>
                </div>
            </div>
            <div class="clearfix"></div>
            <div id="containers-container" class="col-md-6">
                &nbsp
            </div>
            <div id="points-container" class="col-md-6 left-border">
                &nbsp
            </div>

        </div>
            <div class="col-md-3 well" style="margin-top:20px;">
                <div class="">
                    <h4>Выбранные точки <span id="points-notificator"></span><span class="btn btn-link small"
                                                                                   id="clear_stack"><i>Удалить
                                все</i></span></h4>
                </div>
                <div class="" id="stack-content">
                    &nbsp
                </div>
            </div>
            <div class="col-md-3 alert alert-danger">
                <div class="">
                    <h5><span
                            class="col-lg-8">Кол-во участников с рабочих мест (IP телефон, Lynс, CMA Desktop и т.д.)</span><span
                            class="col-lg-4"><input class="form-control" value="0"
                                                    name="in_place_participants_count_modal"></span>
                    </h5>

                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <hr>
                    <button class="btn btn-success" type="button" id="close-attendance-form"><span
                            class="glyphicon glyphicon-ok-sign"></span> Сохранить и закрыть
                    </button>
                </div>
            </div>

    </div>