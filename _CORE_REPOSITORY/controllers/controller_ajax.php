<?php
if (isset($_POST['checkCaptcha'])) {
    //test case
//    print json_encode(array("cCheck" => true));
//    die;
    include_once CORE_REPOSITORY_REAL_PATH.'securimage/securimage.php';
    $securimage = new Securimage();
    if ($securimage->check($_POST['captcha_code']) == false) {
        print json_encode(array("cCheck" => false));
    } else {
        print json_encode(array("cCheck" => true));
    }
}
