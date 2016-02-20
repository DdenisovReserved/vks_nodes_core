<?php
header('refresh:60');
include_once(CORE_REPOSITORY_REAL_PATH . "views/main.php");
$asrt = new FlotGraphAssert();
$asrt->init();
?>
<script type='text/javascript'>
    <?php
    echo "var plotTicks = ". json_encode($graph->plotTicks) . ";\n";
    echo "var randomData = ". json_encode($graph->data) . ";\n";
    echo "var thresholdCou = ". json_encode($graph->threshold) . ";\n";
    ?>
</script>
<div class="container">
    <div class="col-lg-12" style="margin-bottom: 200px;">
        <h3>График нагрузки на <?= date_create(FrontController::getParams())->format("d.m.Y") ?><span class="pull-right"><input id="dp_another_date" class="hidden" disabled/><button type="button" class="btn btn-default btn-sm" id="btn_another_date">Другая дата</button> </span></h3>
        <hr>
        <div class="demo-container">

            <div>
                <div id="placeholder" class="demo-placeholder" style="width:100%;height:450px"></div>
            </div>
            <div class="clearfix"></div>
            <div><button class="btn btn-default btn-sm pull-right" id="reset" type="button">Сброс</button></div>


        </div>
    </div>

</div>
<?= ST::setUserJs('graph/renderMainGraph.js') ?>