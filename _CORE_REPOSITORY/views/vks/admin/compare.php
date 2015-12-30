<?php
ST::deployTemplate('heads/ui_timepicker.inc');
RenderEngine::MenuChanger();
ST::setVarPhptoJS($origVks['id'], 'vksId');
//dump($origVks);
?>
<script>
    $(document).ready(function () {
        $(document).on("change", '.version', function () {
            location.href = "?route=VksVersion/compare/" + vksId + "/" + $("#v1").val() + "/" + $("#v2").val();
        })
    })

</script>
<div class="container">
    <div class="col-lg-6 text-left text-muted"><h4>ВКС #<?= $origVks['id'] ?></h4>
    </div>
    <div class="col-lg-6 text-right text-muted"><h4>Режим сравнения версий</h4>
    </div>
    <div class="col-lg-12">
        <hr>
    </div>
    <div class="col-lg-12 text-left text-muted ">
        <table class="table table-hover table-striped">
            <th></th>
            <th>
                <h4>Версия
                    <select class="version" id="v1">
                        <option value="0" <?= is_null($origVks['version']['version']) ? 'selected' : '' ?>>Оригинал
                        </option>
                        <?php foreach ($versions as $version): ?>
                            <option
                                value="<?= $version['version'] ?>" <?= $origVks['version']['version'] == $version['version'] ? 'selected' : '' ?>><?= $version->version ?>
                                от <?= date_create($version['created_at'])->format("d.m.Y H:i:s") ?>
                                [<?= $version['changer']['login'] ?>]
                            </option>
                        <?php endforeach; ?>
                    </select>
                </h4>
            </th>
            <th>
                <h4>Версия
                    <select class="version" id="v2">
                        <option value="0" <?= is_null($comparedVks['version']['version']) ? 'selected' : '' ?>>Оригинал
                        </option>
                        <?php foreach ($versions as $version): ?>
                            <option
                                value="<?= $version['version'] ?>" <?= $comparedVks['version']['version'] == $version['version'] ? 'selected' : '' ?>><?= $version['version'] ?>
                                от <?= date_create($version['created_at'])->format("d.m.Y H:i:s") ?>
                                [<?= $version['changer']['login'] ?>]
                            </option>
                        <?php endforeach; ?>
                    </select>
                </h4>
            </th>


            <tr>
                <td class="alert alert-default">Статус</td>
                <td class="alert alert-default"><?= $origVks['humanized']['status_label'] ?>
                </td>
                <td class="alert alert-<?= $compare_params['status'] ? 'default' : 'danger' ?>"><?= $comparedVks['humanized']['status_label'] ?></td>
            </tr>

            <tr>
                <td class="alert alert-default">Название</td>
                <td class="alert alert-default"><?= $origVks['title'] ?></td>
                <td class="alert alert-<?= $compare_params['title'] ? 'default' : 'danger' ?>"><?= $comparedVks['title'] ?></td>
            </tr>
            <tr>
                <td class="alert alert-default">Код подключения</td>
                <td class="alert alert-default">

                    <?php if (count($origVks['connection_codes'])): ?>
                        <?php foreach ($origVks['connection_codes'] as $code) : ?>
                            <p>
                                <span class="connection-code-highlighter">
                                    <?= $code['value'] ?> <?= strlen($code['tip']) ? "({$code['tip']})" : "" ?>
                                </span>
                            </p>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span class='connection-code-highlighter'>Код подключения не выдан</span>
                    <?php endif ?>


                </td>
                <td class="alert alert-<?= $compare_params['connection_codes'] ? 'default' : 'danger' ?>">

                    <?php if (count($comparedVks['connection_codes'])): ?>
                        <?php foreach ($comparedVks['connection_codes'] as $code) : ?>
                            <p>
                                <span class="connection-code-highlighter">
                                    <?= $code['value'] ?> <?= strlen($code['tip']) ? "({$code['tip']})" : "" ?>
                                </span>
                            </p>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span class='connection-code-highlighter'>Код подключения не выдан</span>
                    <?php endif ?>

                </td>
            </tr>
            <tr>

                <td class="alert alert-default">Дата</td>
                <td class="alert alert-default"><?= $origVks['humanized']['date'] ?></td>
                <td class="alert alert-<?= $compare_params['date'] ? 'default' : 'danger' ?>"><?= $comparedVks['humanized']['date'] ?></td>
            </tr>
            <tr>
                <td class="alert alert-default">Время</td>
                <td class="alert alert-default"><?= $origVks['humanized']['startTime'] ?>
                    - <?= $origVks['humanized']['endTime'] ?> </td>
                <td class="alert alert-<?= $compare_params['start_date_time'] && $compare_params['end_date_time'] ? 'default' : 'danger' ?>"><?= $comparedVks['humanized']['startTime'] ?>
                    - <?= $comparedVks['humanized']['endTime'] ?> </td>
            </tr>
            <tr>
                <td class="alert alert-default">Подразделение</td>
                <td class="alert alert-default"><?= $origVks['department_rel']['prefix'] ?>
                    . <?= $origVks['department_rel']['name'] ?></td>
                <td class="alert alert-default"><?= $comparedVks['department_rel']['prefix'] ?>
                    . <?= $comparedVks['department_rel']['name'] ?></td>
            </tr>
            <tr>
                <td class="alert alert-default">Ответственный</td>
                <td class="alert alert-default"><?= $origVks['init_customer_fio'] ?>,
                    тел. <?= $origVks['init_customer_phone'] ?>, почта: <?= $origVks['init_customer_mail'] ?>  </td>
                <td class="alert alert-<?= $compare_params['init_customer_fio'] ? 'default' : 'danger' ?>"><?= $comparedVks['init_customer_fio'] ?>,
                    тел. <?= $comparedVks['init_customer_phone'] ?>, почта: <?= $comparedVks['init_customer_mail'] ?></td>
            </tr>
            <tr>
                <td class="alert alert-default">Согласовал админ</td>
                <td class="alert alert-default">
                    <?php if (isset($origVks['approver'])): ?>
                        <?= $origVks['approver']['login'] ?>, тел.<?= $origVks['approver']['phone'] ?>
                    <?php else: ?>
                        Нет
                    <?php endif ?>
                </td>
                <td class="alert alert-<?= $compare_params['approver'] ? 'default' : 'danger' ?>">
                    <?php if (isset($comparedVks->approver)): ?>
                        <?= $comparedVks->approver->login ?>, тел.<?= $comparedVks->approver->phone ?>
                    <?php else: ?>
                        Нет
                    <?php endif ?>
                </td>
            </tr>
            <tr>
                <td class="alert alert-default">Комментарий для администратора</td>
                <td class="alert alert-default"><?= $origVks['comment_for_admin'] ?> </td>
                <td class="alert alert-<?= $compare_params['comment_for_admin'] ? 'default' : 'danger' ?>"><?= $comparedVks['comment_for_admin'] ?> </td>
            </tr>
            <tr>
                <td class="alert alert-default">Комментарий для пользователя</td>
                <td class="alert alert-default"><?= $origVks['comment_for_user'] ?> </td>
                <td class="alert alert-<?= $compare_params['comment_for_user'] ? 'default' : 'danger' ?>"><?= $comparedVks['comment_for_user'] ?> </td>
            </tr>
            <tr>
                <td class="alert alert-default">Список участников</td>
                <td class="alert alert-default">
                    <ul class="list-unstyled">
                        <li>Ip телефоны: <?= $origVks['in_place_participants_count'] ?></li>
                        <?php foreach ($origVks['participants'] as $parp) : ?>
                            <li class="list-group-item"><span
                                    class="glyphicon glyphicon-camera"></span> <?= $parp['full_path'] ?></li>
                        <?php endforeach; ?>

                    </ul>
                </td>
                <td class="alert alert-<?= $compare_params['in_place_participants_count'] && $compare_params['participants'] ? 'default' : 'danger' ?>">
                    <ul class="list-unstyled">
                        <li>Ip телефоны: <?= $origVks['in_place_participants_count'] ?></li>
                        <?php foreach ($origVks['participants'] as $parp) : ?>
                            <li class="list-group-item"><span
                                    class="glyphicon glyphicon-camera"></span> <?= $parp['full_path'] ?></li>
                        <?php endforeach; ?>

                    </ul>
                </td>
            </tr>
            <tr>
                <td class="alert alert-default">Владелец</td>
                <td class="alert alert-default"><?= $origVks['owner']['login'] ?> </td>
                <td class="alert alert-<?= $compare_params['owner'] ? 'default' : 'danger' ?>"><?= $comparedVks['owner']['login'] ?> </td>
            </tr>
            <tr>
                <td class="alert alert-default">Презентация</td>
                <td class="alert alert-default"><?= $origVks['humanized']['presentation'] ?> </td>
                <td class="alert alert-<?= $compare_params['presentation'] ? 'default' : 'danger' ?>"><?= $comparedVks['humanized']['presentation'] ?> </td>
            </tr>
            <tr>
                <td class="alert alert-default">Флаг</td>
                <td class="alert alert-default"><?= $origVks['flag'] ? 'Да' : 'Нет' ?> </td>
                <td class="alert alert-<?= $compare_params['flag'] ? 'default' : 'danger' ?>"><?= $comparedVks['flag'] ? 'Да' : 'Нет' ?> </td>
            </tr>
            <tr>
                <td class="alert alert-default">Упрощенная</td>
                <td class="alert alert-default"><?= $origVks['is_simple'] ? 'Да' : 'Нет' ?> </td>
                <td class="alert alert-<?= $compare_params['is_simple'] ? 'default' : 'danger' ?>"><?= $comparedVks['is_simple'] ? 'Да' : 'Нет' ?> </td>
            </tr>
            <tr>
                <td class="alert alert-default">Подключить другой ТБ</td>
                <td class="alert alert-default"><?= $origVks['other_tb_required'] ? 'Да' : 'Нет' ?> </td>
                <td class="alert alert-<?= $compare_params['other_tb_required'] ? 'default' : 'danger' ?>"><?= $comparedVks['other_tb_required'] ? 'Да' : 'Нет' ?> </td>
            </tr>
            <tr>
                <td class="alert alert-default">Приватная</td>
                <td class="alert alert-default"><?= $origVks['is_private'] ? 'Да' : 'Нет' ?> </td>
                <td class="alert alert-<?= $compare_params['is_private'] ? 'default' : 'danger' ?>"><?= $comparedVks['is_private'] ? 'Да' : 'Нет' ?> </td>
            </tr>
            <tr>
                <td class="alert alert-default">Записать ВКС</td>
                <td class="alert alert-default"><?= $origVks['record_required'] ? 'Да' : 'Нет' ?> </td>
                <td class="alert alert-<?= $compare_params['record_required'] ? 'default' : 'danger' ?>"><?= $comparedVks['record_required'] ? 'Да' : 'Нет' ?> </td>
            </tr>

        </table>

    </div>
</div>
<br><br>
<?php ST::deployTemplate('footer/mainFooter.inc'); ?>

