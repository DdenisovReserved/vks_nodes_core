<!--
<div class="form-group">
    <label for="inputEmail5" class="col-sm-3 control-label">Заблокировать заявки на ВКС</label>

    <div class="col-sm-9">
        <select name="vks_type_blocked" class="form-control" id="inputEmail5" placeholder="Заблокировать заявки">
            <option value="<?php //echo VKS::TYPE_REGULAR ?>" <?php //echo $request->get('vks_type_blocked') == VKS::TYPE_REGULAR ? 'selected' : '' ?>>Стандартные</option>
            <option value="<?php //echo VKS::TYPE_SIMPLE ?>" <?php //echo $request->get('vks_type_blocked') == VKS::TYPE_SIMPLE ? 'selected' : '' ?>>Упрощенные</option>
        </select>
    </div>
</div>
-->


<div class="form-group">
    <label for="inputEmail3" class="col-sm-3 control-label">Начало блокировки</label>
    <div class="col-sm-4">
        <input type="text" name="start_at_date" value="<?= $request->get('start_at_date') ?>" class="form-control" id="input_start_at_date" placeholder="Дата">
    </div>
    <div class="col-sm-4">
        <input type="text" name="start_at_time" value="<?= $request->get('start_at_time') ?>" class="form-control" id="input_start_at_time" placeholder="Время">
    </div>
</div>
<div class="form-group">
    <label for="inputEmail4" class="col-sm-3 control-label">Окончание блокировки</label>

    <div class="col-sm-4">
        <input type="text" name="end_at_date" value="<?= $request->get('end_at_date') ?>" class="form-control" id="input_end_at_date" placeholder="Дата">
    </div>
    <div class="col-sm-4">
        <input type="text" name="end_at_time" value="<?= $request->get('end_at_time') ?>" class="form-control" id="input_end_at_time" placeholder="Время">
    </div>
</div>

<div class="form-group">
    <label for="inputEmail6" class="col-sm-3 control-label">Информация для пользователей (причина блокировки)</label>

    <div class="col-sm-9">
        <textarea type="text" name="description" rows="5" class="form-control" id="inputEmail6" placeholder="Причина блокировки"><?= $request->get('description') ?></textarea>
    </div>
</div>

<script>
    $(function() {
        $("#input_start_at_date,#input_end_at_date").datepicker({
            'minDate': new Date()
        });

        $("#input_start_at_time, #input_end_at_time").timepicker({
            'minTime': '08:00',
            'maxTime': '20:00',
            'show2400': true,
            'timeFormat': "H:i",
            'step': "15",
            'forceRoundTime': true
        });
    })
</script>

