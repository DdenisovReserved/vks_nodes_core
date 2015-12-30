<!-- Modal -->
<div class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Поиск ВКС</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="alert alert-info text-center"><h4><span
                                        class="glyphicon glyphicon-info-sign text-danger "></span> Поиск
                                    производится по id ВКС</h4></div>
                            <div class="form-inline col-lg-offset-2" action="#">
                                <div class="form-group col-lg-12">
                                    <input id="search-input" class="form-control"/>
                                    <button class="btn btn-success" type="button" id="search-button">Найти</button>
                                </div>
                            </div>

                            <div class="col-lg-12" id="search-results"></div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-primary" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
