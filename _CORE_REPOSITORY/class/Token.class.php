<?php
use Symfony\Component\HttpFoundation\Request as Request;

class Token
{
    static function makeToken()
    {

        self::shrinkToken();

        $token = sha1(App::$instance->main->appkey . uniqid(rand(), TRUE));
        $_SESSION['token'][rand()] = $token;
        return $token;
    }

    static function checkToken()
    {
        return true;
        if (!isset($_SESSION['token']) || is_null($_SESSION['token']) || is_null(Request::createFromGlobals()->get('csrf_token'))) throw new RuntimeException("Bad Token initialization");
        $flag = false;
        foreach ($_SESSION['token'] as $key => $token) {
            if ($token === Request::createFromGlobals()->get('csrf_token')) {
                $flag = true;
                //drop this token
                unset($_SESSION['token'][$key]);
            }
        }

        if ($flag)
            return true;
        else
            ST::routeToErrorPage('bad-token');


    }

    static function castTokenField()
    {
        return "<input class='hidden' name='csrf_token' value='" . self::makeToken() . "'/>";
    }

    static function shrinkToken() {
        $result = [];

        if (isset($_SESSION['token']))
            $tCount = count($_SESSION['token']);
        else
            $tCount = 0;

        if ($tCount > 52) {
            $counter = 0;
            foreach ( $_SESSION['token'] as $key=>$token) {
                if ($counter <= 25) {
                    $counter++;
                    continue;
                } else {
                    $result[$key] = $token;
                }
            }
            $_SESSION['token'] = $result;
        }
    }


}

