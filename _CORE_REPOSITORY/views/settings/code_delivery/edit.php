<?php
ST::deployTemplate('heads/ui_timepicker.inc');
RenderEngine::MenuChanger();
$backPack = ST::lookAtBackPack();
ST::setUserJs('settings/code_delivery/core.js');
?>
<div class="container">
    <div class="col-lg-2">
        <button type="button" class="btn btn-info" id="add">
            Добавить шаблон
        </button>
    </div>
    <div class="col-lg-9">
        <div class="alert alert-danger">
            <h4>Обратите внимание</h4>
            <ul>
                <li>
                    Диапазоны в которых больше 100 значений будут подвешивать интерфейс, желательно не превышать этого значения
                </li>
                <li>
                    Недостающие цифры в хвосте заполняются нулями
                </li>
            </ul>
        </div>
        <form class="form-horizontal" method='post' action="<?= ST::route("Settings/storeCodeDelivery") ?>">
            <?= Token::castTokenField() ?>
            <table class="table table-striped table-hover">
                <tr class="table-head text-primary">
                    <th class="">Название</th>
                    <th>Основа</th>
                    <th>Генерируем хвост к основе из * (цифр)</th>
                    <th>Начинать с</th>
                    <th>До</th>
                    <th>Всего</th>
                    <th></th>
                </tr>
                <?php $c = 1;
                foreach ($codes as $code) :?>
                    <tr class="<?= $c == 1 ? 'copy-me' : "" ?>" data-codes="<?= count($codes) ?>">

                        <td class="text-right col-lg-4">

                            <input class="form-control" name="option[<?= $c ?>][name]" value="<?= $code['name'] ?>">
                        </td>

                        <td class="col-lg-3"><input class="form-control" name="option[<?= $c ?>][core]"
                                                    value="<?= $code['core'] ?>" maxlength="120"/></td>

                        <td class="col-lg-1"><input class="form-control" name="option[<?= $c ?>][mean]"
                                                    value="<?= $code['mean'] ?>" maxlength="1"/></td>

                        <td class="col-lg-2"><input class="form-control" name="option[<?= $c ?>][start]"
                                                    value="<?= $code['start'] ?>"/></td>


                        <td class="col-lg-2">
                            <input class="form-control" name="option[<?= $c ?>][end]"
                                   value="<?= $code['end'] ?>"/></td>
                        <td class="text-center all">
                            <?= count(range(floatval($code['end']), floatval($code['start']))) <= 0 ? '<span class="label label-danger">пул сконфигурирован некорректно</span>' : count(range(floatval($code['end']), floatval($code['start']))) ?>
                        </td>
                        <td class="col-lg-2">
                            <button class="btn btn-danger btn-sm remove-inp" type="button"><span
                                    class="glyphicon glyphicon-remove-circle"></span></button>
                        </td>

                    </tr>
                    <?php $c++; endforeach; ?>
            </table>
            <div class="form-group text-center">
                <input class="btn btn-success btn-lg" type="submit" value="Сохранить">
            </div>
        </form>
    </div>
</div>

