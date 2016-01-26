<?php include_once(CORE_REPOSITORY_REAL_PATH . "views/main.php"); ?>
<div class="container">
    <div class="col-lg-12">
        <h3>Результат создания ВКС</h3>
        <hr>
        <?php
        foreach ($reports as $reportNum => $report): ?>
            <div class="well well-lg">
                <table class="table table-stripped">
                    <tr>
                        <td class="col-lg-4">Дата/Время ВКС</td>
                        <td class="col-lg-8">
                            <?= $report->getRequest()->get('date') ?>, <?= $report->getRequest()->get('start_time') ?>
                            - <?= $report->getRequest()->get('end_time') ?>
                        </td>
                    </tr>
                    <tr class="<?= $report->isResult() ? "alert alert-success" : "alert alert-danger" ?>">
                        <td>Результат</td>
                        <td><?= $report->isResult() ? "Успешно" : "Ошибка" ?></td>
                    </tr>
                    <?php if (!$report->isResult()): ?>
                        <tr class="alert alert-danger">
                            <td>Ошибки:</td>
                            <td>
                                <?php if (count($report->getErrors())): ?>
                                    <?php foreach ($report->getErrors() as $error): ?>
                                        <?php if (is_array($error)): ?>
                                            <?php foreach ($error as $err): ?>
                                                <li><?= $err ?></li>
                                            <?php endforeach ?>
                                        <?php else: ?>
                                            <li><?= $error ?></li>
                                        <?php endif ?>
                                    <?php endforeach ?>
                                <?php else: ?>
                                    <i>Система не выдала ошибок, обратитесь к разработчику</i>
                                <?php endif ?>
                            </td>
                        </tr>
                        <tr class="alert alert-danger">
                            <td colspan="2">
                                <h5>
                                    <span class="glyphicon glyphicon-info-sign"></span>
                                    К сожалению ваша заявка не создана из-за ошибок, вы можете <a
                                        href="<?= ST::route('Vks/reSubmitFromResults/' . $reportNum) ?>"
                                        class="like-href">исправить ошибки и отправить заявку еще раз</a>
                                </h5>
                            </td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td>#id созданной ВКС</td>
                            <td>
                                <?= ST::linkToVksPage($report->getObject()->id, true) ?>
                            </td>
                        </tr>
                        <tr class=" alert-info alert">
                            <td colspan="2">
                                <h5>
                                    <span class="glyphicon glyphicon-info-sign"></span>
                                    <?php if ($report->getObject()->status == VKS_STATUS_PENDING): ?>
                                        Ожидайте согласования администратором. Результат согласования и код подключения
                                        будет
                                        направлены на вашу электронную почту: <?= App::$instance->user->email ?>
                                    <?php elseif ($report->getObject()->status == VKS_STATUS_APPROVED
                                        && $report->getObject()->is_simple
                                    ): ?>
                                        Ваша ВКС по упрощенной схеме успешно создана, в течении 5 минут на вашу электронную почту:
                                        <b><?= App::$instance->user->email ?></b> поступит отчет (с кодом подключения) о созданной ВКС
                                    <?php endif ?>
                                </h5>
                            </td>
                        </tr>
                        <?php if (!$report->getObject()->is_simple): ?>
                            <tr>
                                <td colspan="2">
                                    <a href="<?= ST::route('TechSupport/addRequest/'.$report->getObject()->id) ?>" class="btn btn-info">Заказать тех. поддержку</a>
                                </td>
                            </tr>
                        <?php endif ?>
                    <?php endif ?>
                </table>
            </div>
        <?php endforeach; ?>
    </div>
</div>
