<?php
setcookie('remMeVks',0,time()-24*60*365);
ST::redirectToRoute("Index/index");