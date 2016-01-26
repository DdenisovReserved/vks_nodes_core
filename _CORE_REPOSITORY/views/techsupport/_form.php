<div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Список почтовых адресов (через запятую)</label>

    <div class="col-sm-10">
        <textarea name="email_list" rows="5" class="form-control"
                  placeholder="email_list"><?= count($attendance->tech_support_container->email_list) ? implode(",",$attendance->tech_support_container->email_list) : null ?></textarea>
    </div>
</div>
<div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Список поключенных к рассылке пользователей</label>

    <div class="col-sm-10">
        <select class="js-user-apiFind form-control" multiple name="recipients[]">
            <?php if(isset($attendance->tech_support_container) && count($attendance->tech_support_container->recipients)): ?>
                <?php foreach($attendance->tech_support_container->recipients as $recipient): ?>
                    <option value="<?= $recipient->user->id ?>" selected><?= $recipient->user->login ?></option>
                <?php endforeach ?>
            <?php endif ?>

        </select>
    </div>
</div>
<div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Список инженеров</label>

    <div class="col-sm-10">
        <select class="js-user-apiFind form-control" multiple name="engineers[]">
            <?php if(count($attendance->tech_support_engineers)): ?>
                <?php foreach($attendance->tech_support_engineers as $engineer): ?>
                    <option value="<?= $engineer->user->id ?>" selected><?= $engineer->user->login ?></option>
                <?php endforeach ?>
            <?php endif ?>
        </select>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <div class="checkbox">
            <label>
                <input type="checkbox" <?= isset($attendance->tech_support_container->enabled) && $attendance->tech_support_container->enabled ? 'checked' : '' ?> name="enabled"> Включено
            </label>
        </div>
    </div>
</div>

