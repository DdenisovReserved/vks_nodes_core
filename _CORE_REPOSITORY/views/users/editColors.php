<?php include_once(CORE_REPOSITORY_REAL_PATH . "/views/main.php");
$p = new ColorPickerAssert();
$p->init();
?>
<script>
    $(function () {
        $('.colorpicker').pickAColor({
            showSpectrum: false,
            fadeMenuToggle: false
        });

        $(".colorpicker").on("change", function () {
            $(this).attr('value', $(this).val());
            $(this).parent().parent().parent().parent().parent().find(".fc-event").css($(this).data('type'), "#" + $(this).attr('value'));
        });

    });
</script>


<div class="container">
    <div class="col-lg-12">
        <h3>Редактор цветовой схемы <span class="pull-right"><a class="btn btn-info confirmation"
                                                                href="<?= ST::route("User/restoreDefaultsColors") ?>">По-умолчанию</a> </span>
        </h3>
        <hr>
        <form class="form form-horizontal" action="<?= ST::route('User/storeColors') ?>" method="post">

            <?php $c = 1;
            foreach ($colors as $colorName => $color): ?>
                <?php if (in_array($colorName, $permittedColors)): ?>
                    <div class="form-group">
                        <label class="control-label col-lg-3"><?= $color['description'] ?></label>

                        <div class="col-lg-3">
                            <a class="fc-day-grid-event fc-event fc-start fc-end pointer modal-event-ws"
                               style="background-color:<?= $color['backgroundColor'] ?>;border-color:<?= $color['borderColor'] ?>; width: 210px; color: <?= $color['textColor'] ?>;"
                               event-id="1">
                                <div class="fc-content"><span class="fc-time"><div><span
                                                class="label label-success label-as-badge">#1</span> 09:00 - 11:00
                                            <div class="plank-title">test name font</div>
                                        </div></span> <span class="fc-title">&nbsp;</span></div>
                            </a>
                        </div>
                        <div class="col-lg-6 left-border">
                            <input name="color[<?= $c ?>][name]" class="form-control hidden"
                                   value="<?= $colorName ?>"/>
                            <input name="color[<?= $c ?>][description]" class="form-control hidden"
                                   value="<?= $color['description'] ?>"/>

                            <div class="form-group">
                                <label class="col-lg-2 control-label">Фон</label>

                                <div class="col-lg-5">
                                    <input name="color[<?= $c ?>][backgroundColor]" data-type="background-color"
                                           class="form-control colorpicker" value="<?= $color['backgroundColor'] ?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">Граница</label>

                                <div class="col-lg-5">
                                    <input name="color[<?= $c ?>][borderColor]" data-type="border-color"
                                           class="form-control colorpicker"
                                           value="<?= $color['borderColor'] ?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">Текст</label>

                                <div class="col-lg-5">
                                    <input name="color[<?= $c ?>][textColor]" data-type="color"
                                           class="form-control colorpicker"
                                           value="<?= $color['textColor'] ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                <?php endif; ?>
                <?php $c++; endforeach; ?>
            <div class="form-group text-center">
                <input class="btn btn-success btn-lg" type="submit" value="Сохранить">
            </div>
        </form>
    </div>
</div>




