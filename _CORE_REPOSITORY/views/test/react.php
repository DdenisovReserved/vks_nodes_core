<?php
include_once(CORE_REPOSITORY_REAL_PATH . "views/main.php");



ST::setUserJs('react/react.js');
ST::setUserJs('react/react-dom.js');
ST::setUserJs('react/react-with-addons.js');

ST::setUserJs('react/marked.min.js');

ST::setUserJs('react/browser-polyfill.js');
ST::setUserJs('react/browser.min.js');

?>
<div class='col-lg-6 col-lg-offset-3'>
<div id="content"></div>

<script type="text/babel" src="<?= CORE_REPOSITORY_HTTP_PATH ?>/js/react-tutorial-master/public/scripts/tutorial1.js"></script>

<script type="text/babel" src="<?= CORE_REPOSITORY_HTTP_PATH ?>/js/react-tutorial-master/public/scripts/tutorial2.js"></script>
    </div>
