<?php include_once(CORE_APP_PATH . 'views/main.php') ?>
<div class="container">
    <div class="col-lg-12">
        <h3>Аннулировать ВКС <?= ST::linkToVksPage($vks->id, true) ?></h3>
        <hr>
        <form class="form-horizontal" method="post" action="<?= ST::route('Vks/dropByAdmin/' . $vks->id) ?>">
            <div class="form-group">
                <label class="control-label col-lg-3">Комментрарий для пользователя</label>

                <div class="col-lg-6">
                    <textarea name="comment_for_user" class="form-control" rows="5"></textarea>
                </div>
            </div>
            <div class="form-group ">
                <div class="col-lg-6 col-lg-offset-3">
                    <div class="checkbox">
                        <label class="text-success">
                            <input name="notificate" type="checkbox" checked/>&nbspОтправить уведомление владельцу ВКС
                        </label>
                        <span class="help-block"></span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-6 col-lg-offset-3">
                    <button type="submit" class="btn btn-danger" value="">Аннулировать</button>
                </div>
            </div>
        </form>

    </div>
</div>

