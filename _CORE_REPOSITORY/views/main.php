<?php
ST::deployTemplate('heads/ui_timepicker.inc');
RenderEngine::MenuChanger();
?>

<?php
if (strlen(PUBLIC_MESSAGE) > 0) {
    echo "<hr><div class='text-danger'><h4>
     <div class='col-lg-8 col-lg-offset-2 text-center'><h4><span class='glyphicon glyphicon-alert text-danger' style='font-size: 2em;'></span></h4> " . PUBLIC_MESSAGE . "</h4></div><div class='clearfix'></div><hr>
</div>";
}
?>
<?php
if (isset($pm) && $pm['active'] == 1 && strlen($pm['content'])) {

        echo "<hr><div class='text-primary'><h4>
     <div class='col-lg-8 col-lg-offset-2 text-center'><h4><span class='glyphicon glyphicon-alert text-danger' style='font-size: 2em;'></span></h4> " . $pm['content'] . "</h4></div><div class='clearfix'></div><hr>
</div>";

}

?>

<?php App::$instance->MQ->showMessage(); ?>
<?php App::$instance->MQ->showImportantMessage(); ?>
<body>
<div class="container">
