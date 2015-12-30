<?php

class Auth
{
    //userData
    public $isLogged = false;

    function __construct()
    {
        global $_TB_IDENTITY;
        $this->isLogged = false;

        if (isset($_COOKIE[md5("logged".$_TB_IDENTITY[MY_NODE]['serviceName'])])
            && $_COOKIE[md5("logged".$_TB_IDENTITY[MY_NODE]['serviceName'])]) {

            $user = User::where('token', $_COOKIE[md5("logged".$_TB_IDENTITY[MY_NODE]['serviceName'])])->approved()->first();

            if ($user) {
                if ($user->origin == MY_NODE) {
                    $this->isLogged = true;
                    $_SESSION['user'] = $user;
                    foreach ($user['attributes'] as $key => $value) {
                        $this->$key = $value;
                    }
                }
            }
        }

        $this->ip = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : "127.0.0.1";
        $user = new User_controller();
        $colors = $user->initColors($this);
        $this->colors = $colors;
        $_SESSION['my_home'] = NODE_HTTP_PATH;

    }

    static function isLogged($init)
    {
        return $init->user->isLogged ? true : false;
    }

    static function isVip($init)
    {
        if (self::isLogged($init))
            return $init->user->role == ROLE_VIP ? true : false;
        else

            return false;
    }

    static function isAdmin($init)
    {
//        ST::makeDebug(isset($init->Auth));
        if ($init->user->isLogged && ($init->user->role == ROLE_ADMIN || $init->user->role == ROLE_ADMIN_MODERATOR)) {
            return true;
        } else {

            return false;
        }
    }

    static function isLoggedOrDie($init)
    {
        global $_TB_IDENTITY;
        if ($init->user->isLogged) {
            return true;
        } else {
            App::$instance->log->logWrite(LOG_SECURITY, "Restricted access: Try to enter logged only allowed zone");

            ST::redirectToCaRoute("AuthNew/login&return=" . FrontController::whereIm()."/".FrontController::getParams());

//            echo ExceptionHandler::showEmptyMessage("Страница доступна только зарегистрированным пользователям, пожалуйста, войдите в систему  <a href='" . HTTP_PATH."?route=AuthNew/login&return=" . FrontController::whereIm()."/".FrontController::getParams() . "' '>здесь</a>  используя логин для <b>ТБ ".$_TB_IDENTITY[MY_NODE]['humanName']."</b>");
//            exit;
        }
    }

    static function isAdminOrDie($init)
    {
        if ($init->user->isLogged && ($init->user->role == ROLE_ADMIN || $init->user->role == ROLE_ADMIN_MODERATOR)) {
            return true;
        } else {
            App::$instance->log->logWrite(LOG_SECURITY, "Restricted access: Try to enter admin only allowed zone");
            ST::routeToErrorPage('only-admin');
            exit;
        }
    }

    static function isAdminModerator($init) {
        if ($init->user->isLogged &&  $init->user->role == ROLE_ADMIN_MODERATOR) {
            return true;
        } else {
            return false;
        }
    }
    //проверить запрашиваемое id с залоггированным
    static function getMyRole($init) {
        if ($init->user->isLogged) {
            return $init->user->role;
        } else {
            return null;
        }
    }
    static function compareIds($askedId, $init)
    {
        if ($init->user->isLogged && isset($init->user->id)) {
            return ((int)$init->user->id == (int)$askedId) ? true : false;
        } else {
            return false;
        }
    }
}