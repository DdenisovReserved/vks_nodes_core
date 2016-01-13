<html>
<?php
ST::setUserJs('attendance/v3/init.js');
//?>
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

    .disabled {
        color: #ccc;
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
<div class="modal fade">
    <div class="modal-dialog" style="width: 90%;  padding: 0; margin-top: 10px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close btn btn-link" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Выбор участников ВКС
                    <span class="pull-right" style="padding-right: 5px; margin-top: -6px;">
                        <span class="btn btn-link text-success" id="point_save_and_exit">
                            Сохранить и продолжить
                        </span>
                    </span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="col-lg-12 breadcrumbs"></div>
                    <div class="clearfix"></div>

                    <div class="panel-block">
                        <div class="col-lg-6">
                            <div class="" id="points_control">
                <span class="col-lg-6 points-buttons-block">
                    <button type="button" class="btn btn-info btn-sm point-back-button" title="Шаг назад"><span
                            class="glyphicon glyphicon-arrow-left"></span>
                    </button>
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
                                <button type="button" disabled class="btn btn-danger btn-sm remove_points_button"
                                        title="Убрать"><span
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
                            <div class='text-center empty_text' style='margin-top: 5em;'><i>Переместите в это поле
                                    участников ВКС из предлагаемого списка</i></div>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-lg-6">
                        <div class="alert alert-warning" style="min-height: 100px;">


                            <div class="text-danger">
                        <span class="col-lg-6" style="border-left: 5px solid darkred;">
                            <label for="exampleInputEmail2" style="font-size: 11px;">Количество участников с рабочих
                                мест<br> (IP телефоны, Lynс,
                                CMA Desktop
                                и
                                т.д.)
                            </label>
                        </span>
                         <span class="col-lg-3">
                             <span style="width: 30px; padding-right: 5px;">
                                     <span class="glyphicon glyphicon-minus pointer" id="decrement-points_add_inplace_field"></span>
                             </span>

                                <input class="text-primary" style="width: 40px; text-align: center;  padding: 7px;"
                                       id="points_add_inplace_field"
                                       placeholder="" value="0"/>
                             <span style="width: 30px; padding-left: 5px;">
                                     <span id="increment-points_add_inplace_field" class=" text-success pointer glyphicon glyphicon-plus"></span>
                             </span>
                        </span>
                        <span class="col-lg-2">
                        <button type="button" class="btn btn-success btn-sm" id="points_add_inplace">
                            Добавить
                        </button>
                        </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 left-border">
                        &nbsp
                    </div>
                    <div class="clearfix"></div>
                </div>


            </div>
            <div class="modal-footer text-center" style="text-align: center !important;">
                <button class="btn btn-success btn-lg" id="point_save_and_exit">Сохранить и продолжить</button>
            </div>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

</html>