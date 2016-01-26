<?php include_once(CORE_REPOSITORY_REAL_PATH . "views/main.php"); ?>

<div class="col-lg-12">
    <h4>Запросы на техническую поддержку ВКС <?= ST::linkToVksPage($vks->id, true) ?>
        <span class="pull-right">
<!--            --><?php if ($vks->is_applyable): ?>
                <a href="<?= ST::route('TechSupport/addRequest/' . $vks->id) ?>" class="modalled btn btn-success">Создать
                    запрос</a>
            <?php endif ?>

        </span>
    </h4>
    <hr>
    <?php include_once(CORE_REPOSITORY_REAL_PATH . "views/techsupport/tpl/_call_table.php") ?>
</div>
