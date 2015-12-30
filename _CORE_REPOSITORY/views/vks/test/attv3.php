<?php
//ST::deployTemplate('heads/ui_timepicker.inc');
ST::setUserJs('attendance/v3/RepositoryPoints.class.js');
ST::setUserJs('attendance/v3/RenderEngine.class.js');
ST::setUserJs('attendance/v3/actions-script.js');
ST::setUserJs('jquery/jquery.cookie.js');
?>
<style>
    .breadcrumbs {
        /*background-color: #f5f5f5;*/
        margin-bottom: 3px;
    }

    #points_container {
        /*background-color: #f5f5f5;*/
        min-height: 500px;
        max-height: 500px;
        overflow: auto;
        margin-top: 10px;

    }

    #points_container > li, #selected_points_container > li {
        font-size: 13px !important;
        /*padding: 1px;*/
        padding-left: 15px;
    }

    #selected_points_container > li {
        font-size: 12px !important;
    }

    #points_container > li > div > label:hover, #selected_points_container > li > div > label:hover {
        color: #ff5e00;
    }

    #selected_points_container {
        background-color: #fbfbfb;
        padding-left: 15px;
        /*padding: 1px;*/
        min-height: 500px;
        max-height: 500px;
        overflow: auto;
        margin-top: 10px;

    }

    .small-text button {
        font-size: 10px !important;
    }

    .inplace {
        background-color: wheat;
        padding: 1px;
        padding-right: 15px !important;
    }

</style>
<div class="container">
    <div class="col-lg-12 breadcrumbs"></div>
    <div class="clearfix"></div>

    <div class="panel-block">
        <div class="col-lg-6">
            <div class="" id="points_control">
                <span class="col-lg-6 points-buttons-block">
                <button type="button" disabled class="btn btn-success btn-sm add_points_button"
                        title="Добавить в список">
                    <span class="glyphicon glyphicon-plus-sign"></span>
                </button>
                <button type="button" title="Сортировка" class="btn btn-default btn-sm sort"
                        data-sort_target="points_container"
                        data-sort_desc="0"><span class="glyphicon glyphicon-sort"></span>
                </button>
                <button type="button" class="btn btn-default btn-sm all" title="Выбрать все" data-selected="0"
                        data-all_target="points_container"><span class="glyphicon glyphicon-filter"></span>
                </button>
                <button type="button" class="btn btn-info btn-sm point-back-button" title="Шаг назад"><span
                        class="glyphicon glyphicon-arrow-left"></span>
                </button>
               </span>
                <span class="col-lg-6 pull-right text-right no-right-padding">
                    <span class="col-md-8 no-right-padding">
                        <input class="form-control" id="point-search-field"/>
                    </span>
                <button class="btn btn-default" id="point-search-button">Найти</button>
                </span>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="" id="points_selected_control">
                <span class="label label-default label-as-badge" id="selected_counter">0</span>
                <button type="button" disabled class="btn btn-danger btn-sm remove_points_button" title="Убрать"><span
                        class="glyphicon glyphicon-minus-sign"></span></button>
                <button type="button" title="Сортировка" class="btn btn-default btn-sm sort"
                        data-sort_target="selected_points_container"
                        data-sort_desc="0"><span class="glyphicon glyphicon-sort"></span>
                </button>
                <button type="button" class="btn btn-default btn-sm all" data-selected="0"
                        data-all_target="selected_points_container" title="Выбрать все"><span
                        class="glyphicon glyphicon-filter"></span>
                </button>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="col-lg-6">
        <ul class="list-unstyled connectedSortable" id="points_container"></ul>
    </div>

    <div class="col-lg-6  left-border">
        <ul class="list-unstyled connectedSortable" id="selected_points_container">

        </ul>
    </div>
    <div class="clearfix"></div>
    <div class="col-lg-6">
        <div class="alert alert-danger" style="min-height: 5.2em;">
                <span class="col-lg-8">
                <label for="exampleInputEmail2">Количество участников с рабочих мест<br> (IP телефоны, Lynс, CMA Desktop
                    и
                    т.д.)</label>
                </span>
                <span class="col-lg-2"><input type="" class="form-control" id="points_add_inplace_field"
                                              placeholder="" value="0"/></span>
                <span class="col-lg-2">
                <button type="button" class="btn btn-info" id="points_add_inplace"><span
                        class="glyphicon glyphicon-plus-sign"</button>
                    </span>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="col-lg-6 text-center">
        <div class="alert alert-default" style="min-height: 5.2em;">
            <button class="btn btn-success btn-lg" id="point_save_and_exit">Сохранить и продолжить заполнение заявки</button>
        </div>
    </div>
</div>
