<div>
    <h5>Вы просматривали:</h5><hr>
    <?php if(count($last_seen)): ?>
        <ul>
            <?php foreach($last_seen as $attendance): ?>
                <li><a href="<?= ST::route('AttendanceNew/'.FrontController::getAction()."/0/".$date.'/'.$attendance['id']) ?>"><?= $attendance['name'] ?></a></li>
            <?php endforeach ?>
        </ul>
    <?php else: ?>
        <i>Список пуст</i>
    <?php endif ?>
</div>