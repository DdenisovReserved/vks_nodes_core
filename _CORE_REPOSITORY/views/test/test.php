<?php
ST::deployTemplate('heads/ui_timepicker.inc');
RenderEngine::MenuChanger();

dump(App::$instance);
//$negotiator = App::$instance->callService('vks_ca_negotiator');

?>

<div class="col-lg-3">
    <button type="button" id="askFreeCodes" class="btn btn-default" onclick="askFreeCodes()">aks</button>
</div>

<script>





    $(function () {


    });
</script>