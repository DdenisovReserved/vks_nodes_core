<?php
ST::deployTemplate('heads/ui_timepicker.inc');

ST::setVarPhptoJS("vks_participants_edit", "formCookieParticipantsName", true, true, true);
$p = new Select2Assert();
$p->init();
ST::setUserJs('users/search.js');
RenderEngine::MenuChanger();

//is edit page
ST::setVarPhptoJS(true, "editLogic");

?>
<script>
    $(document).ready(function () {
        $(document).on("click", ".remove_line", function () {
            if (confirm('Удалить код?'))
                $(this).parent().parent().parent().parent().remove();
        })
    })
</script>
<div class="container" xmlns="http://www.w3.org/1999/html">
    <div class="col-lg-6 text-left text-muted"><h4>ВКС #<?= $vks->id ?> Режим редактирования (Вид Администратора)</h4>
    </div>
    <div class="col-lg-6 text-right text-muted">
        <div class='action-buttons'>
            <div class="text-right">
                <div class="btn-group" role="group" aria-label="...">

                    <?php if ($vks->humanized->isCloneable): ?>
                        <a class='btn btn-default btn-sm' href='?route=Vks/makeClone/<?= $vks->id ?>'
                           title='Клонировать'><span class='glyphicon glyphicon-duplicate'></span></a>
                    <?php else: ?>
                        <span class='btn btn-default btn-sm' href='' disabled title='Клонировать'><span
                                class='glyphicon glyphicon-duplicate'></span></span>
                    <?php endif; ?>

                    <?php if ($vks->humanized->isEditable): ?>
                        <a class='btn btn-info btn-sm' href='?route=Vks/edit/<?= $vks->id ?>'
                           title='Редактировать'><span class='glyphicon glyphicon-edit'></span></a>
                    <?php else: ?>
                        <span class='btn btn-default btn-sm' href='' disabled title='Редактировать'><span
                                class='glyphicon glyphicon-edit'></span></span>
                    <?php endif; ?>

                    <?php if ($vks->humanized->isDeletable): ?>
                        <?php if (Auth::isLogged(App::$instance) && Auth::isAdmin(App::$instance)): ?>
                            <a class='btn btn-danger btn-sm' href='?route=Vks/annulate/<?= $vks->id ?>'
                               title='Аннулировать'><span class='glyphicon glyphicon-remove-sign'></span></a>
                        <?php elseif (Auth::isLogged(App::$instance)): ?>
                            <a class='btn btn-danger btn-sm confirmation' href='?route=Vks/cancel/<?= $vks->id ?>'
                               title='Аннулировать'><span class='glyphicon glyphicon-remove-sign'></span></a>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class='btn btn-default btn-sm' href='' disabled title='Аннулировать'><span
                                class='glyphicon glyphicon-remove-sign'></span></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <hr>
    </div>

    <div class="col-md-3">
    <div id="blocked-time-list" class="hidden"></div>


        <h4>Версии</h4>
        <hr>
        <?php if (count($versions)): ?>
            <ul>
                <?php foreach ($versions as $version) : ?>
                    <li><a href="<?= ST::route("VksVersion/compare/" . $vks->id . "/0/" . $version->version) ?>"><span
                                class="glyphicon glyphicon-list-alt"></span> Версия <?= $version->version ?></a><br>
                        (<?= date_create($version->created_at)->format("d.m.Y H:i:s") ?>) by
                        [<?= $version->changer->login ?>]
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="alert alert-warning">В эту вкс правки пока не вносились</div>
        <?php endif ?>

    </div>
    <div class="col-md-9 left-border">

        <form id="form1" data-id="<?= $vks->id ?>" action="?route=Vks/adminUpdate/<?= $vks->id ?>"
              class="form-horizontal" name="form1"
              method="post">
            <?= Token::castTokenField(); ?>

            <div class="vks-time-line form-step-container alert alert-warning padding25">

                <div class="form-group">
                    <label class="col-sm-3 control-label">Статус</label>

                    <div class="col-sm-8">
                        <select name="status" class="form-control">
                            <option
                                value="<?= VKS_STATUS_APPROVED ?>" <?= $vks->status == VKS_STATUS_APPROVED ? 'selected' : '' ?>>
                                Согласовано
                            </option>
                            <option
                                value="<?= VKS_STATUS_PENDING ?>" <?= $vks->status == VKS_STATUS_PENDING ? 'selected' : '' ?>>
                                На согласовании
                            </option>
                            <option
                                value="<?= VKS_STATUS_DROP_BY_USER ?>" <?= $vks->status == VKS_STATUS_DROP_BY_USER ? 'selected' : '' ?>>
                                Удалена пользователем
                            </option>
                            <option
                                value="<?= VKS_STATUS_DECLINE ?>" <?= $vks->status == VKS_STATUS_DECLINE ? 'selected' : '' ?>>
                                Отказ
                            </option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Коды подключения</label>

                    <div class="col-sm-8">
                        <?php if (count($vks->connection_codes)): ?>
                            <?php $c = 1;
                            foreach ($vks->connection_codes as $code) : ?>
                                <div class="code-holder">
                                    <div class="col-lg-1">
                                        <div class="checkbox">
                                            <label>
                                                <span
                                                    class="remove_line glyphicon glyphicon-remove-circle text-danger pointer"></span>
                                            </label>
                                        </div>

                                    </div>
                                    <div class="col-lg-4">
                                        <input name="code[<?= $c ?>][value]" class='form-control'
                                               value='<?= $code->value_raw ?>'/>
                                    </div>
                                    <div class="col-lg-6">
                                        <input name="code[<?= $c ?>][tip]" class='form-control'
                                               value='<?= $code->tip ?>'/>
                                        <span>*подсказка для пользователя</span>
                                    </div>

                                </div>
                                <div class="clearfix"></div>
                                <?php $c++; endforeach; ?>
                        <?php else: ?>
                            <p><i>Список пуст</i></p>
                        <?php endif ?>


                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Владелец</label>

                    <div class="col-sm-8">


                        <select class="js-user-apiFind form-control" name="owner_id">
                            <?php if ($vks->owner): ?>
                                <option value="<?= $vks->owner->id ?>"
                                        selected="selected"><?= $vks->owner->login ?></option>
                            <?php else: ?>
                                <option value="">--Нет владельца--</option>
                            <?php endif ?>

                        </select>
                    </div>
                </div>

            </div>
            <!--        here show time line      -->
            <div class="here-render-timeLine text-center"></div>
            <!--        !here show time line      -->
            <div class="vks-time-line form-step-container">
                <div class="form-group">
                    <div class="time-params-edited">
                        <div class="col-md-4">
                            <label><h4>Дата: </h4></label>
                            <input data-vks_blocked_type="0" name="date" id="date-with-support" class="form-control"
                                   value="<?= $vks->humanized->date ?>"/>
                        </div>
                        <div class='col-md-4'>
                            <label><h4>Время начала:</h4></label>
                            <input name='start_time' id="start_time" class='form-control'
                                   value="<?= $vks->humanized->startTime ?>"/>
                        </div>
                        <div class='col-md-4'>
                            <label>
                                <h4>Время окончания:</h4>
                            </label>
                            <input name='end_time' id="end_time" class='form-control'
                                   value="<?= $vks->humanized->endTime ?>"/>
                        </div>
                    </div>
                </div>
                <div class="date-error-container"></div>
                <div class="freeAdmins"></div>
            </div>
            <div class="form-step-container">
                <div class="form-group">
                    <h4 class="text-muted">
                        Название и место проведения ВКС, данные по заказчику ВКС
                    </h4>
                </div>
                <div class="form-group">
                    <label>Название видеоконференции</label>
                    <input name="title" id="title"
                           class="form-control" maxlength="255" value='<?= $vks->title ?>'/>
                </div>
                <hr>
                <div class="form-group">
                    <label>Подразделение</label>
                    <select name="department" class="form-control">

                        <?php foreach ($departments as $department) : ?>
                            <option
                                value="<?= $department->id ?>" <?= $department->id == $vks->department ? 'selected' : '' ?>><?= $department->prefix ?>
                                . <?= $department->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Подключить другой ТБ/ЦА</label>
                    <select name="other_tb_required" class="form-control" disabled>

                        <option value="1" <?= $vks->other_tb_required ? 'selected' : '' ?>>Да</option>
                        <option value="0" <?= !$vks->other_tb_required ? 'selected' : '' ?>>Нет</option>

                    </select>
                </div>


                <div class="form-group">
                    <div class='col-md-12'>
                        <label>
                            <h4>Ответственный</h4>
                        </label>
                    </div>
                    <div class='col-md-4'>
                        <label>ФИО</label>
                        <input name='init_customer_fio' class='form-control'
                               value="<?= $vks->init_customer_fio ?>"/>
                    </div>
                    <div class='col-md-4'>
                        <label>Телефон</label>
                        <input name='init_customer_phone' class='form-control'
                               value="<?= $vks->init_customer_phone ?>"/>
                    </div>
                    <div class='col-md-4'>
                        <label>Эл. Почта</label>
                        <input name='init_customer_mail' class='form-control'
                               value="<?= $vks->init_customer_mail ?>"/>
                    </div>

                </div>

            </div>

            <!--    <block end -->
            <!--    <block start -->
            <div class="form-step-container" data-step="3">
                <div class="form-group">
                    <h4 class="text-muted">Участники ВКС, доп. информация</h4>
                </div>
                <div class="form-group">
                    <div class="col-lg-6">
                        <label class="control-label">Список участников ВКС<br><br></label>

                        <div class="loader2" style="display: none;"><img src="<?=CORE_REPOSITORY_HTTP_PATH ?>images/loading.gif"/> Загрузка</div>
                        <br>
                        <button class="btn btn-info col-lg-12" type="button" id="participants_inside_open_popup"><span
                                class="glyphicon glyphicon-list"></span> Выбрать
                            участников
                        </button>


                    </div>
                    <div class="col-lg-6 hidden">

                        <label class="control-label">Кол-во участников с рабочих мест (IP телефон, Lynс, CMA Desktop и
                            т.д.)</label>
                        <input name="in_place_participants_count" class="form-control"
                               value="<?= $vks->in_place_participants_count ?>"/>

                    </div>
                </div>
                <div class="form-group">
                    <div class="vks-points-list-display">
                        <ol class="list-unstyled">
                            <li class="in_place_plank">Количество участников с рабочих мест: <span
                                    class="label label-as-badge label-default"><?= $vks->in_place_participants_count ? $vks->in_place_participants_count : 0 ?></span>
                            </li>
                            <li>Количество участников из справочника точек: <span
                                    class="label label-as-badge label-default"><?= count($vks->participants) ?></span>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="form-step-container">
                <br>

                <div class="form-group">
                    <label>Комментарий для админа</label>
                    <textarea name="comment_for_admin" id="comment_for_admin" maxlength="255"
                              class="form-control" disabled/><?= $vks->comment_for_admin ?></textarea>
                </div>
                <div class="form-group">
                    <label>Комментарий для пользователя</label>
                    <textarea name="comment_for_user" id="comment_for_admin" maxlength="255"
                              class="form-control"/><?= $vks->comment_for_user ?></textarea>
                </div>
                <div class="form-group">
                    <div class="col-lg-12">
                        <div class="form-group alert alert-danger">
                            <div class="checkbox">
                                <label>
                                    <input name="is_private" type="checkbox" <?= $vks->is_private ? 'checked' : '' ?>/>&nbspПриватная ВКС
                                </label>
                                        <span
                                            class="help-block">*Видимость кода подключения к ВКС. Если отмечено, код подключения к ВКС видят все</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group alert alert-danger">
                            <div class="checkbox">
                                <label>
                                    <input name="record_required"
                                           type="checkbox" <?= $vks->record_required ? 'checked' : '' ?>/>&nbspЗаписать ВКС </label>
                                 <span class="help-block">*Требуется видеозапись ВКС</span>
                            </div>
                                        <span
                                            class="help-block"></span>
                        </div>
                    </div>

                </div>
            </div>
            <div class="form-step-container">
                <?php ST::setUpErrorContainer(); ?>
                <div class="form-group">
                    <button type="button" id="submit" value="0" name="notify" class="btn btn-success ">Сохранить без
                        уведомления пользователя
                    </button>
                    <button type="button" id="submit" value="1" name="notify" class="btn btn-info ">Сохранить и
                        уведомить пользователя
                    </button>
                </div>
            </div>
            <br><br><br><br>
    </div>
    </form>
</div>
</div>
<?php
$p = new ParticipationsV3Assert();
$p->init();
ST::setUserJs('uf-2.js');
//ST::setUserJs('vks/block_checker.js');
?>