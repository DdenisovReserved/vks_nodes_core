<?php
setcookie("remMeVks", false, time() - 3600,'/', Null, 0);
header('location: ?route=Index/index');
