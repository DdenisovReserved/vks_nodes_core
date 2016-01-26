<div class="form-group">

    <?php if (count($available_points)): ?>
        <div class="checkbox">
            <label>
                <input type="checkbox"
                       name="tech_support_required" <?= $vks->has('tech_support_required') ? $vks->get('tech_support_required') ? 'checked' : '' : '' ?> />&nbsp<b>Требуется
                    техническая поддержка</b>
            </label>
            <span class="help-block"><i>*Техподдержку можно запросить и после создания ВКС</i></span>
        </div>

        <div id="tech_support_points_list"
             class="<?= $vks->has('tech_support_required') ? $vks->get('tech_support_required') ? '' : 'hidden' : 'hidden' ?>">
            <hr>
            <div class="col-lg-12">
                <div style="font-size: 14px;">
                    <?php if (!count($available_points)): ?>
                        <i>Точки на которых оказывается тех. поддержка, не определены (обратитесь к
                            администратору)</i>
                    <?php else: ?>
                        <label>Выберите точку:</label>
                        <?php foreach ($available_points as $attendance): ?>
                    <div class="checkbox">
                                <label>
                                    <input <?=  $vks->get('tech_support_att_id') == $attendance['id'] ? 'checked' : '' ?> name="tech_support_att_id" value="<?= $attendance['id'] ?>"
                                           type="radio" <?= $attendance['selectable'] ? '' : 'disabled' ?>/>&nbsp<?= AttendanceNew_controller::makeFullPath($attendance['id']) ?><?= $attendance['selectable'] ? '' : ' <span style="font-size: 8px;" class="label label-success">Заявка создана</span>' ?>
                                </label>
                            </div>
                        <?php endforeach ?>
                    <?php endif ?>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <label class="no-left-padding">Краткое сообщение для тех. поддержки</label>
                    <textarea name="user_message" maxlength="255" rows="5" class="form-control"><?= $vks->get('user_message')?>
</textarea>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="checkbox">
            <label>
                <input type="checkbox"
                       name="tech_support_required" <?= $vks->has('tech_support_required') ? $vks->get('tech_support_required') ? 'checked' : '' : '' ?>
                       disabled/>&nbsp<b>Требуется техническая поддержка</b>
            </label>
            <span class="help-block"><i>*Администратор не определил точек с тех. подержкой, эта опция не
                    доступна</i></span>
        </div>
    <?php endif ?>

</div>
<script>
    $(function () {

        $(document).on('click', "input[name='tech_support_required']", function () {
            var $this = $(this);
            var $list = $("#tech_support_points_list");

            if ($this.is(":checked")) {
                $list.removeClass("hidden");
            } else {
                $list.addClass("hidden");
            }
        });


    })
</script>