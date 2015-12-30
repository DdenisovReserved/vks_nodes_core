<?php
ST::deployTemplate('heads/ui_timepicker.inc');
RenderEngine::MenuChanger();

?>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
<div class="container">

    <div class="row">
        <div class="col-lg-8 col-lg-offset-2">
            <h3 class="text-primary" data-toggle="tooltip" data-placement="left"
                title="Результаты создания ВКС">Результаты создания ВКС #<?= $vks->id ?></h3>
            <hr>
            <div>

                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#main" aria-controls="main" role="tab"
                                                              data-toggle="tab">Основные</a></li>
                    <li role="presentation"><a href="#participants" aria-controls="participants" role="tab"
                                               data-toggle="tab">Участники</a></li>
                    <li role="presentation"><a href="#techsupp" aria-controls="techsupp" role="tab" data-toggle="tab">Тех.
                            поддержка</a></li>
                    <li role="presentation"><a href="#invites" aria-controls="invites" role="tab" data-toggle="tab">Приглашения</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="main">

                        <br>
                        <table class="table table-striped table-hover">
                            <th>Параметр</th>
                            <th>Значение</th>
                            <tr>
                                <td>id</td>
                                <td><?= $vks->id ?></td>
                            </tr>
                            <tr>
                                <td>Тема</td>
                                <td><?= $vks->title ?></td>
                            </tr>
                            <tr>
                                <td>Дата</td>
                                <td><?= $vks->humanized->date ?></td>
                            </tr>
                            <tr>
                                <td>Время</td>
                                <td><?= $vks->humanized->startTime ?> - <?= $vks->humanized->endTime ?></td>
                            </tr>
                            <tr>
                                <td>Место проведения (нахождение спикера)</td>
                                <td><?= $vks->humanized->location ?></td>
                            </tr>
                            <tr>
                                <td>Статус</td>
                                <td><?= $vks->humanized->status ?></td>
                            </tr>
                            <tr>
                                <td>Код подключения</td>
                                <td>

                                    <?php if (count($vks->connection_codes)): ?>
                                        <?php foreach ($vks->connectionCode as $code) : ?>
                                            <p>
                                <span class="connection-code-highlighter">
                                    <?= $code->value ?> <?= strlen($code->tip) ? "({$code->tip})" : "" ?>
                                </span>
                                            </p>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class='connection-code-highlighter'>Код подключения не выдан</span>
                                    <?php endif ?>

                                </td>
                            </tr>
                        </table>

                    </div>

                    <div role="tabpanel" class="tab-pane" id="participants">
                        <br>
                        <table class="table table-striped table-hover">
                            <?php $c = 1;
                            foreach ($vks->participants as $parp) : ?>
                                <tr>
                                    <td><?= $c ?></td>
                                    <td><span
                                            class="glyphicon glyphicon-camera"></span> <?= $parp->full_path ?></td>
                                </tr>
                                <?php $c++; endforeach; ?>


                            <?php foreach ($vks->phoneParp as $parp) : ?>
                                <tr>
                                    <td><?= $c ?></td>
                                    <td><span
                                            class="glyphicon glyphicon-phone"></span> <?= $parp->phone_num ?></td>
                                </tr>
                                <?php $c++; endforeach; ?>



                            <?php foreach ($vks->outsideParp as $parp) : ?>
                            <tr>
                                <td><?= $c ?></td>
                                <td><a class='btn btn-default btn-sm'
                                       href='<?= $parp->attendance_value ?>'>Внешний участник <span
                                            class="glyphicon glyphicon-file"></span> Ф.<?= $c ?></a></td>
                                <?php $c++;
                                endforeach; ?>
                            </tr>
                        </table>
                    </div>

                    <div role="tabpanel" class="tab-pane" id="techsupp">
                        <br>
                        <?php if ($vks->needTPSupport): ?>
                            <?php if ($vks->humanized->techMail): ?>
                                <div class="alert alert-info">Письма направлены в адрес технической
                                    поддержки: <?= $vks->humanized->techMail ?>
                                </div>
                            <?php else: ?>
                                <?php if (!is_null($vks->humanized->techMail)): ?>
                                    <div class="alert alert-warning">Письма для тех. поддержки не могут быть отправлены,
                                        т.к. не указаны email адреса в справочнике, обратитесь к администратору
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-warning">Письма для тех. поддержки не могут быть отправлены,
                                        т.к. для места проведения используется телефон
                                    </div>
                                <?php endif ?>
                            <?php endif ?>
                        <?php else: ?>
                            <div class="alert alert-info"> Для данной вкс техническая поддержка не запрашивалась</div>
                        <?php endif ?>
                    </div>

                    <div role="tabpanel" class="tab-pane" id="invites">
                        <br>
                        <?php if (empty($vks->relationLinks)): ?>
                            <div class="alert alert-info">Для этой вкс никаких приглашений для МБ не создано</div>
                        <?php else: ?>
                            <?php $att = new attendance_controller() ?>

                            <?php foreach ($vks->relationLinks as $rel) : ?>
                                <div class="well">
                                    <h4 class="text-success">
                                        Точка: <?= $att->makefullPathbyAttId($rel->attendance_id) ?></h4>
                                    Приглашения:
                                    <?php $verificators = AttendanceVerificator::where('attendance_id', $rel->attendance_id)->get(); ?>
                                    <table class="table table-hover">
                                        <?php $c = 1;
                                        foreach ($verificators as $verificator) : ?>

                                            <tr>
                                                <td class="col-lg-1"><?= $c ?></td>
                                                <td>
                                                    <span
                                                        class="glyphicon glyphicon-envelope"></span> <?= User::findOrFail($verificator->user_id)->login ?>
                                                </td>
                                            </tr>
                                            <?php $c++; endforeach; ?>
                                    </table>
                                </div>
                            <?php endforeach; ?>
                        <?php endif ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>