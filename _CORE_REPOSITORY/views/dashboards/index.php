<?php
header('refresh: 60');
ST::deployTemplate('heads/ui_timepicker.inc');
RenderEngine::MenuChanger();
//dump($sortedEvents['now']);
?>
<script type="text/javascript">
    function startTime() {
        var today = new Date();
        var h = today.getHours();
        var m = today.getMinutes();
        var s = today.getSeconds();
        // add a zero in front of numbers<10
        m = checkTime(m);
        s = checkTime(s);
        document.getElementById('clock').innerHTML = h + ":" + m + ":" + s;
        t = setTimeout('startTime()', 500);
    }
    function checkTime(i) {
        if (i < 10) {
            i = "0" + i;
        }
        return i;
    }
</script>
</head>
<div class="container">
    <div class="col-lg-12 no-left-padding">
        <a class="btn btn-link" href="<?= ST::route('Dashboard/showSimpleCodes/' . date_create()->format('Y-m-d')) ?>">Пул
            простых кодов</a>
        <a class="btn btn-link" href="<?= ST::route('Dashboard/showCACodes/' . date_create()->format('Y-m-d')) ?>">Пул в
            ЦА</a>
    </div>
    <div class="col-lg-12">
        <h3>Панель наблюдения (автообновление 1 раз в минуту) <span class="pull-right"><span
                    class="glyphicon glyphicon-time"></span> <span
                    id="clock"></span></span></h3>
        <script type="text/javascript">startTime()</script>
    </div>
    <div class="clear"></div>
    <div class="col-lg-12">
        <div class="col-md-4 alert alert-success">
            <h4>Завершены</h4>
            <hr>
            <?= $sortedEvents['past']['html'] ?>
        </div>
        <div class="col-lg-4 alert alert-info">
            <h4>Проходят сейчас</h4>
            <hr>
            <?= $sortedEvents['now']['html'] ?>
        </div>
        <div class="col-lg-4 alert alert-warning">
            <h4>Пока не начались</h4>
            <hr>
            <?= $sortedEvents['future']['html'] ?>
        </div>
    </div>
</div>
<br><br>
<?php //ST::deployTemplate('footer/mainFooter.inc'); ?>
