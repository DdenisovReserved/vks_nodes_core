<?php
include_once(CORE_REPOSITORY_REAL_PATH . "views/main.php");
ST::setUserCss('attendance/style.css');
ST::setUserCss('attendance/manage-style.css');

$p = new Select2Assert();
$p->init();
$points = ST::lookAtBackPack();
$points = $points->request;

?>
<div class="container">
    <div class="col-lg-1"><a class="btn btn-default" href="?route=AttendanceNew/show/<?= $data['rootId'] ?>"> Назад</a>
    </div>
    <div class="col-md-11  block-border-shadow-normal-padding left-border padding25">
        <h4>Создать точку/контейнер</h4>
        <hr>
        <div class="alert alert-danger">Свойтво "техническая поддержка" используется в экспериментальном режиме и пока нигде не используется</div>
        <div class="">
            <button class="btn btn-info add-point-row" type="button">+ Запись</button>
        </div>
        <form class="form-horizontal" method="post" action="?route=AttendanceNew/store">
            <table class="table table-bordered" id="point-table" data-number="<?= $points->get('point') ? count($points->get('point')) : 1 ?>">
                <th>Имя</th>
                <th>ip*</th>
                <th>Тип</th>
                <th>Родитель</th>
                <th>Активна</th>
                <th>Проверка**</th>
                <th>Тех. поддержка**</th>
                <th>&nbsp</th>
                <?php if ($points->get('point')): ?>
                <?php $c = 1; foreach($points->get('point') as $point): ?>
                    <tr class="point-row">
                        <td>
                            <input name="point[<?= $c ?>][name]" class="form-control" maxlength="160" value="<?= $point['name'] ?>"/>
                        </td>
                        <td>
                            <input name="point[<?= $c ?>][ip]" class="form-control" maxlength="160" value="<?= $point['ip'] ?>"/>
                        </td>
                        <td>
                            <select name="point[<?= $c ?>][container]" class="form-control">
                                <option value="1" <?= $point['container'] ? 'selected' : '' ?>>Контейнер</option>
                                <option value="0" <?= !$point['container'] ? 'selected' : '' ?>>Точка</option>
                            </select>
                        </td>
                        <td>
                            <select name="point[<?= $c ?>][parent_id]" class="form-control container-select">
                                <?php if ($data['rootId'] == 1): ?>
                                    <option value="<?= $data['rootId'] ?>">Корневой контейнер</option>
                                <?php else: ?>
                                    <option
                                        value="<?= $data['rootId'] ?>"><?= Attendance::findOrFail($data['rootId'])->name ?></option>
                                <?php endif; ?>
                                <?php foreach ($data['containers'] as $container): ?>
                                    <?php if ($container->id != $data['rootId']): ?>
                                        <?php if ($container->id == 1): ?>
                                            <option value="<?= $container->id ?>" <?= $point['parent_id'] == $container->id ? 'selected' : '' ?>>Контейнер корневой</option>
                                        <?php else: ?>
                                            <option value="<?= $container->id ?>" <?= $point['parent_id'] == $container->id ? 'selected' : '' ?>><?= $container->name ?></option>
                                        <?php endif; ?>

                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td class="text-center">
                            <input name=point[<?= $c ?>][active]" type="checkbox" <?= isset($point['active']) ? 'checked' : '' ?>/>
                        </td>
                        <td class="text-center">
                            <input name=point[<?= $c ?>][check]" type="checkbox" <?= isset($point['check']) ? 'checked' : '' ?>/>
                        </td>
                        <td class="text-center">
                            <input name=point[<?= $c ?>][tech_supportable]" type="checkbox" <?= isset($point['tech_supportable']) ? 'checked' : '' ?>/>
                        </td>
                        <td class="text-center">
                            <span class="btn btn-default btn-sm point-row-delete pointer" <?= $c==1 ? "disabled" : false ?>>Удалить</span>
                        </td>
                    </tr>
                    <?php $c++; endforeach; ?>
                <?php else: ?>
                    <tr class="point-row">
                        <td>
                            <input name="point[1][name]" class="form-control" maxlength="160"/>
                        </td>
                        <td>
                            <input name="point[1][ip]" class="form-control" maxlength="160" />
                        </td>
                        <td>
                            <select name="point[1][container]" class="form-control">
                                <option value="1">Контейнер</option>
                                <option value="0" selected>Точка</option>
                            </select>
                        </td>
                        <td>
                            <select name="point[1][parent_id]" class="form-control container-select">
                                <?php if ($data['rootId'] == 1): ?>
                                    <option value="<?= $data['rootId'] ?>">Корневой контейнер</option>
                                <?php else: ?>
                                    <option
                                        value="<?= $data['rootId'] ?>"><?= Attendance::findOrFail($data['rootId'])->name ?></option>
                                <?php endif; ?>
                                <?php foreach ($data['containers'] as $container): ?>
                                    <?php if ($container->id != $data['rootId']): ?>
                                        <?php if ($container->id == 1): ?>
                                            <option value="<?= $container->id ?>">Контейнер корневой</option>
                                        <?php else: ?>
                                            <option value="<?= $container->id ?>"><?= $container->name ?></option>
                                        <?php endif; ?>

                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td class="text-center">
                            <input name=point[1][active]" type="checkbox" checked/>
                        </td>
                        <td class="text-center">
                            <input name=point[1][check]" type="checkbox" checked/>
                        </td>
                        <td class="text-center">
                            <input name=point[1][tech_supportable]" type="checkbox"/>
                        </td>
                        <td class="text-center">
                            <span class="btn btn-default btn-sm point-row-delete pointer" disabled>Удалить</span>
                        </td>
                    </tr>
                <?php endif; ?>

            </table>
            <div class="form-group">
                <button class="btn btn-success">Сохранить</button>
            </div>
            <div class="form-group">
                *ip адрес у контейнеров игнорируется и нигде не показывается<br>
                **игнорируется у контейнеров
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        $(document).on('click', '.add-point-row', function () {
            if ($("#point-table").find("tr").length > 30) {
                alert('Такое кол-во записей вставить будет проблематично!, сохраните хотя бы эти.')
            } else {
                var current = $("#point-table").attr('data-number');

                $(".point-row:last").clone()
                    .find('input:text').val('').each(function () {
                        $(this).attr('name', $(this).attr('name').replace(/(\d+)/g, Number(current) + 1))
                    }).end()
                    .find('input:checkbox').each(function () {
                        $(this).attr('name', $(this).attr('name').replace(/(\d+)/g, Number(current) + 1))
                    }).end()
                    .find('select').each(function () {
                        $(this).attr('name', $(this).attr('name').replace(/(\d+)/g, Number(current) + 1))
                    }).end()
                    .find('.point-row-delete').attr('disabled', false)
                    .end()
                    .appendTo("#point-table");


                $("#point-table").attr('data-number', Number(current) + 1)
            }

        });
        $(document).on('click', '.point-row-delete', function () {
            $(this).parent().parent().remove();

        });
    })
</script>