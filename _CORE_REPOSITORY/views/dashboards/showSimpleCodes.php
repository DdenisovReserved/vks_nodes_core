<?php
header('refresh: 60');
ST::deployTemplate('heads/ui_timepicker.inc');
RenderEngine::MenuChanger();
ST::setVarPhptoJS($date, 'currentDate');
?>
</head>
<!--init calendar -->
<script>
    $(document).ready(function () {
        $(".date-pick").datepicker({
            onSelect: function (date) {
                location.href = "<?php echo App::$instance->opt->appHttpPath; ?>?route=Dashboard/showSimpleCodes/" + date;
            },
            defaultDate: currentDate
        });
    })
</script>
<!--!init calendar -->
<div class="container">
    <h3 class="text-muted">Пул кодов для простых ВКС на <?= $date ?></h3>
    <hr>
    <div class="col-lg-3">
        <div class="date-pick pull-right"></div>
    </div>
    <div class="col-lg-9">
        <table class="table table-bordered">
            <?php foreach ($collected as $code => $vkses) : ?>
                <tr>
                    <td class="col-lg-2 alert alert-info text-center"><h4><?= $code ?></h4></td>
                    <td>
                        <ol>
                            <?php if (count($vkses)): ?>

                                <?php foreach ($vkses as $vks) : ?>
                                    <li class="list-group-item"><a class='show-as-modal' data-type='local'
                                                                   data-id='<?= $vks->id ?>'
                                                                   href=" <?= ST::linkToVksPage($vks->id, 1, 1) ?>"> <span
                                                class="label label-success label-as-badge">#<?= $vks->id ?></span></a>
                                        (<?= date_create($vks->start_date_time)->format('H:i') ?> -
                                        <?= date_create($vks->end_date_time)->format('H:i') ?>) <?= $vks->title ?>

                                    </li>
                                <?php endforeach; ?>

                            <?php else: ?>
                                <li class="list-unstyled text-success"> free</li>
                            <?php endif ?>
                        </ol>


                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
<br><br>
<?php //ST::deployTemplate('footer/mainFooter.inc'); ?>
