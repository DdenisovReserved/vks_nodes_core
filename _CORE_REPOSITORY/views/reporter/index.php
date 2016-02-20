<?php include_once(CORE_REPOSITORY_REAL_PATH . "views/main.php"); ?>
<div class="container">
    <div class="col-lg-6 col-lg-offset-3 left-border">
        <h3>Генератор отчетов по созданным ВКС</h3>
        <hr>
        <h4>Задайте параметры выборки</h4><hr>
        <form class="form-horizontal" method="post" action="<?= ST::route('Reporter/collect') ?>">
            <div class="form-group">
                <label for="date_start" class="col-sm-3 control-label">Начало</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="dates[start]" id="date_start" placeholder="date_start">
                </div>
            </div>
            <div class="form-group">
                <label for="date_end" class="col-sm-3 control-label">Окончание</label>
                <div class="col-sm-3">
                    <input type="text" name="dates[end]" class="form-control" id="date_end" placeholder="date_end">
                </div>
            </div>
<!--            <div class="form-group">-->
<!--                    <label for="inputPassword3" class="col-sm-3 control-label">Тип ВКС</label>-->
<!--                <div class=" col-sm-6">-->
<!--                    <div class="checkbox">-->
<!--                        <label>-->
<!--                            <input type="checkbox" name="filter[type][standart]" checked> Стандартные-->
<!--                        </label>-->
<!--                    </div>-->
<!--                    <div class="checkbox">-->
<!--                        <label>-->
<!--                            <input type="checkbox" name="filter[type][simple]" checked> Простые-->
<!--                        </label>-->
<!--                    </div>-->
<!--                    <div class="checkbox">-->
<!--                        <label>-->
<!--                            <input type="checkbox" name="filter[type][tbtotb]" checked> ТБ-ТБ-->
<!--                        </label>-->
<!--                    </div>-->
<!--                    <div class="checkbox">-->
<!--                        <label>-->
<!--                            <input type="checkbox" name="filter[type][invitedbyca]" checked> По приглашению из ЦА-->
<!--                        </label>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-3 control-label">Cтатус</label>
                <div class=" col-sm-6">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="filter[status][<?= VKS_STATUS_APPROVED ?>]" checked>Утверждена
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="filter[status][<?= VKS_STATUS_PENDING ?>]" checked>На согласовании
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="filter[status][<?= VKS_STATUS_DECLINE ?>]" checked>Отклонена при согласовании
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="filter[status][<?= VKS_STATUS_DELETED ?>]" checked>Удалена администратором
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="filter[status][<?= VKS_STATUS_DROP_BY_USER ?>]" checked>Удалена пользователем
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    <button type="submit" class="btn btn-success">Получить отчет</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(function () {
        $("#date_start, #date_end").datepicker({
            numberOfMonths: [1, 2]
        });
    })
</script>