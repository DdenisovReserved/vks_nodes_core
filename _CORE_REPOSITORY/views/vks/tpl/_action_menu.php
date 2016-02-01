<div class="btn-group" role="group" aria-label="...">
    <?php if (Auth::isLogged(App::$instance) && Auth::isAdmin(App::$instance)): ?>
        <?php if ($vks->status == 0): ?>
            <a class='btn btn-warning btn-sm' title='Согласование' href='<?= ST::route('Vks/showNaVks/' . $vks->id) ?>'><span
                    class='glyphicon glyphicon-ok-sign'></span> </a>
            <?php else: ?>
            <a class='btn btn-default btn-sm' disabled title='Согласование' href=''><span
                    class='glyphicon glyphicon-ok-sign'></span> </a>
        <?php endif ?>
        <?php if ($vks->flag): ?>
            <a class='btn btn-default btn-sm' title='Снять флаг'
               href='<?= ST::route('Vks/unmark/' . $vks->id) ?>'><span
                    class='glyphicon glyphicon-warning-sign'></span></a>
        <?php else: ?>
            <a class='btn btn-default btn-sm'
               title='Выдать флаг'
               href='<?= ST::route('Vks/mark/' . $vks->id) ?>'><span
                    class='glyphicon glyphicon-alert'></span></a>
        <?php endif ?>

    <?php endif ?>
<!--    --><?php //if ($vks->humanized->isTechSupportable): ?>
<!--        <a class='btn btn-default btn-sm'-->
<!--           href='?route=TechSupport/showRequests/--><?php // echo $vks->id ?><!--'-->
<!--           title='Показать запросы на тех. поддержку'><span-->
<!--                class='glyphicon glyphicon-screenshot'></span></a>-->
<!--    --><?php //else: ?>
<!--        <span class='btn btn-default btn-sm' href='' disabled title='Показать запросы на тех. поддержку'><span-->
<!--                class='glyphicon glyphicon-screenshot'></span></span>-->
<!--    --><?php //endif ?>

    <?php if ($vks->humanized->isOutlookable): ?>
        <a class='btn btn-default btn-sm'
           href='?route=OutlookCalendarRequest/pushToStack/<?= $vks->id ?>'
           title='Отправить приглашение в мой календарь Outlook'><span
                class='glyphicon glyphicon-calendar'></span></a>
    <?php else: ?>
        <span class='btn btn-default btn-sm' href='' disabled
              title='Отправить приглашение в мой календарь Outlook'><span
                class='glyphicon glyphicon-calendar'></span></span>
    <?php endif ?>

    <?php if ($vks->humanized->isCloneable): ?>
        <a class='btn btn-default btn-sm' href='?route=Vks/makeClone/<?= $vks->id ?>'
           title='Клонировать'><span class='glyphicon glyphicon-duplicate'></span></a>
    <?php else: ?>
        <span class='btn btn-default btn-sm' href='' disabled title='Клонировать'><span
                class='glyphicon glyphicon-duplicate'></span></span>
    <?php endif; ?>

    <?php if ($vks->humanized->isCodePublicable): ?>
        <a class='btn btn-default btn-sm' href='?route=Vks/publicStatusChange/<?= $vks->id ?>'
           title='Изменить видимость кода'><span
                class='glyphicon glyphicon-eye-open'></span></a>
    <?php else: ?>
        <span class='btn btn-default btn-sm' href='' disabled
              title='Изменить видимость кода'><span class='glyphicon glyphicon-eye-open'></span></span>
    <?php endif; ?>

    <?php if ($vks->humanized->isEditable): ?>
        <a class='btn btn-default btn-sm' href='?route=Vks/edit/<?= $vks->id ?>'
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
            <a class='btn btn-danger btn-sm confirmation'
               href='?route=Vks/cancel/<?= $vks->id ?>' title='Аннулировать'><span
                    class='glyphicon glyphicon-remove-sign'></span></a>
        <?php endif; ?>
    <?php else: ?>
        <span class='btn btn-default btn-sm' href='' disabled title='Аннулировать'><span
                class='glyphicon glyphicon-remove-sign'></span></span>
    <?php endif; ?>
</div>