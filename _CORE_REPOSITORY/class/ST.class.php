<?php

class ST
{
// дебагнутый вывод, обернутый в pre, я заколебался эти кавычки печатать, лол
//-------------------------------------start---------------------------------
    public static function makeDebug($var)
    {
        echo "<pre>";
        var_dump($var);
        echo "</pre>";
    }
//--------------------------------------end-------------------------------------

//загрузить юзерский js или css, обертка оберточка
//-------------------------------------start---------------------------------
    public static function setUserJs($jsFilepath)
    {
        print "<script src=\"".CORE_REPOSITORY_HTTP_PATH."js/" . $jsFilepath . "\"></script>";
    }

    public static function setUserCss($cssFilepath)
    {
        print "<link rel=\"stylesheet\" type=\"text/css\"
         href=\"".CORE_REPOSITORY_HTTP_PATH."css/" . $cssFilepath . "\">";
    }
//--------------------------------------end-------------------------------------

//подогнать файлик из папки /templates (загрузить шаблончик)
//-------------------------------------start---------------------------------
    public static function deployTemplate($templateFilename)
    {
        include_once(CORE_REPOSITORY_REAL_PATH."templates/" . $templateFilename);
    }
//--------------------------------------end-------------------------------------
//сгенерировать элемент формы, оборачивается в form-group
//-------------------------------------start---------------------------------
    public static function form_MakeElement(array $elemParams)
    {
        echo "<div class=\"form-group\">";
        echo "
                <label class='col-sm-2'>" . self::form_isSetted(@$elemParams['label']) . "</label>
                <div class='col-sm-5'>
                <" . self::form_isSetted(@$elemParams['element']) . " class=\"" . self::form_isSetted(@$elemParams['class']) . "\" type=\"" . self::form_isSetted(@$elemParams['type']) . "\" name=\"" . self::form_isSetted(@$elemParams['name']) . "\">" . self::form_isSetted(@$elemParams['content']) . "</" . self::form_isSetted(@$elemParams['element']) . ">
                    </div>
                </div>";
    }
//--------------------------------------end-------------------------------------

//сгенерировать элемент формы, оборачивается в form-group
//-------------------------------------start---------------------------------
    public static function form_isSetted($var, $echoElse = null)
    {
        if (isset($var)) {
            return $var;
        } else {
            return $echoElse;
        }
    }
//--------------------------------------end-------------------------------------

//----------------------------переместить переменную PHP в js
//-------------------------------------start---------------------------------
    public static function setVarPhptoJS($phpVar, $jsVarName, $comma = true, $preblock = true, $global = false)
    {
        if ($preblock) {
            echo "<script>";
        }

        if (!$comma) {
            if ($global)
                echo "{$jsVarName} = {$phpVar};";
            else
                echo "var {$jsVarName} = {$phpVar};";
        } else {
            if ($global)
                echo "{$jsVarName} = '{$phpVar}';";
            else
                echo "{$jsVarName} = '{$phpVar}';";
        }
        if ($preblock) {
            echo "</script>";
        }

    }
//--------------------------------------end-------------------------------------
//----------------------------Роутинг на страницу ошибок
//-------------------------------------start---------------------------------
    public static function routeToErrorPage($pageName)
    {
        header("location:?r=views/errors/{$pageName}");
        exit();
    }

//--------------------------------------end-------------------------------------

    public static function cleanUpText($text)
    {
        return trim(stripslashes(stripslashes(htmlspecialchars_decode(stripslashes($text)))));
    }

    public static function packUpText($text)
    {
        return str_replace("'", "''", $text);
    }


    //--------------------------------------end-------------------------------------
    public static function setUpErrorContainer()
    {
        echo "<div class='form-group'>
                <div class='errors-cnt'></div>
        </div>";
    }

    public static function makeArrayFromString($delimeter, $string, $killCharsFromEnd = null)
    {

//            if ($killCharsFromEnd) {
//                $string = mb_strcut($string, 0, -$killCharsFromEnd);
//            }
//
//
//

//        ST::makeDebug($string);
        if (!empty($string)) {
            $arr = explode($delimeter, $string);
        } else {
            return "null";
        }
        $newArr = array();
        foreach ($arr as $elem) {
            if (!empty($elem)) {
                $newArr[] = $elem;
            }
        }
        return $newArr;


    }
    //--------------------------------------end-------------------------------------
    //-------------------------------------date extractor func-------------------------------------
    static function dateExtractorFunction($fullDateTimeString, $whatYouWantToGet, $echo = false)
    {
        if ($echo) {
            echo date($whatYouWantToGet, strtotime($fullDateTimeString));
        } else {
            return date($whatYouWantToGet, strtotime($fullDateTimeString));
        }

    }

//--------------------------------------end-------------------------------------
    static function varExistOrDie($checkedVar, $retMessage = 'Bad Request')
    {
        if (!isset($checkedVar)) {
            die($retMessage);
        }

    }

    static function getVar($checkedVar)
    {
        return isset($checkedVar) ? $checkedVar : false;

    }

    //void
    static function redirect($path)
    {
        if ($path == 'back') {
            if (!isset($_SERVER['HTTP_REFERER']))
                $path = "?route=Index/index";
            else
                $path = $_SERVER['HTTP_REFERER'];
        }

        self::setVarPhptoJS($path, "redirect");
        echo "<script>
             document.location.href = redirect;
            </script>";
        exit;
    }



    static function redirectToCA($path)
    {
        self::setVarPhptoJS(HTTP_BASE_PATH . $path, "redirect");
        echo "<script>
             document.location.href = redirect;
            </script>";
        exit;
    }

    static function redirectToRoute($route)
    {

        self::setVarPhptoJS(App::$instance->opt->appHttpPath . "?route=" . $route, "redirect");
        echo "<script>
             document.location.href = redirect;
            </script>";
        die;
    }

    static function redirectToCaRoute($path)
    {
        self::setVarPhptoJS(HTTP_PATH . "?route=" . $path, "redirect");
        echo "<script>
             document.location.href = redirect;
            </script>";
        die;
    }

    static function routeToCARaw($path)
    {
        return HTTP_BASE_PATH . $path;
    }

    static function routeToCaApi($path) {
        $path = urlencode($path);

        return HTTP_BASE_PATH . "api/?m={$path}";
    }

    /**
     * @return bool
     * check is Ajax request or not
     */
    static function isAjaxRequest()
    {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ? true : false;
    }

    static function route($path)
    {
        return App::$instance->opt->appHttpPath . "?route={$path}";
    }

    static function routeToCa($path)
    {
        return HTTP_PATH . "?route={$path}";
    }

    static function lookAtBackPack()
    {
        $result = isset($_SESSION['backPack']) ? $_SESSION['backPack'] : \Symfony\Component\HttpFoundation\Request::createFromGlobals();

        if (isset($_SESSION['backPack'])) {
            unset($_SESSION['backPack']);
        }
        return $result;
    }

    static function linkToVksPage($vksId, $modal = false, $raw = false, $addClass = '')
    {
        if ($raw)
            return ST::route('Vks/show/' . $vksId);
        if ($modal)
            $result = "<a target='_blank' class='show-as-modal {$addClass}' data-type='local'  data-id='{$vksId}' href='" . ST::route('Vks/show/' . $vksId) . "'>#{$vksId}</a>";
        else
            $result = "<a class='{$addClass}' target='_blank' href='" . ST::route('Vks/show/' . $vksId) . "'>#{$vksId}</a>";

        return $result;
    }

    static function linkToVksNSPage($vksId, $modal = false, $raw = false)
    {
        if ($raw)
            return ST::route('VksNoSupport/show/' . $vksId);
        if ($modal)
            $result = "<a target='_blank' class='show-as-modal' data-type='local'  data-id='{$vksId}' href='" . ST::route('VksNoSupport/show/' . $vksId) . "'>#{$vksId}</a>";
        else
            $result = "<a target='_blank' href='" . ST::route('VksNoSupport/show/' . $vksId) . "'>#{$vksId}</a>";

        return $result;
    }

    static function linkToCaVksPage($vksId, $modal = false, $raw = false)
    {

        if ($raw)
            return HTTP_PATH . "?route=Vks/show/" . $vksId;
        if ($modal)
            $result = "<a target='_blank' class='show-as-modal' data-type='ca-was' data-id='{$vksId}' href='" . HTTP_PATH . "?route=Vks/show/" . $vksId . "'>#{$vksId}</a>";
        else
            $result = "<a target='_blank' href='" . HTTP_PATH . "?route=Vks/show/" . $vksId . "'>#{$vksId}</a>";

        return $result;
    }

    static function linkToCaNsVksPage($vksId, $modal = false, $raw = false, $classes = [])
    {
        if ($raw)
            return HTTP_PATH . "?route=VksNoSupport/show/" . $vksId;
        if ($modal)
            $result = "<a target='_blank' class='show-as-modal " . implode(", ", $classes) . "' data-type='ca-ns' data-id='{$vksId}' href='" . HTTP_PATH . "?route=VksNoSupport/show/" . $vksId . "'>#{$vksId}</a>";
        else
            $result = "<a target='_blank' class='" . implode(", ", $classes) . "' href='" . HTTP_PATH . "?route=VksNoSupport/show/" . $vksId . "'>#{$vksId}</a>";

        return $result;
    }

    static function addNull($num)
    {
        if ($num < 10)
            $num = "0" . $num;
        return $num;
    }


    public function makeInviteLink($vks)
    {
        if ($vks->referral) {
            return "<input value='" . ST::routeToInviteCompressor($vks->referral) . "' id='invite-code'/> <a title='перейти по ссылке' class='btn btn-sm btn-default' href='" . ST::routeToInviteCompressor($vks->referral) . "'><span class='glyphicon glyphicon-link'></span></a>";
        } else {
            return false;
        }
    }

    static function routeToInviteCompressor($referrer)
    {
        return HTTP_BASE_PATH . "i.php?r=" . $referrer;
    }

    public static function ifActiveMarkIt(array $matchPattern, $allInThisControllers = [])
    {
        $currentPage = FrontController::whereIm();
        $currentPage = mb_strtolower($currentPage);
        $selected = Null;
        $selected = in_array($currentPage, $matchPattern) ? "active" : Null;
        if (count($allInThisControllers)) {
            foreach ($allInThisControllers as $controller) {
                if (mb_strtolower(FrontController::getController()) == mb_strtolower($controller)) {
                    $selected = "active";

                }
            }
        }
        return $selected;
    }

    public static function deployColorScheme($event, array $scheme)
    {
        $event->backgroundColor = $scheme['backgroundColor'];
        $event->borderColor = $scheme['borderColor'];
        $event->textColor = $scheme['textColor'];
    }

    public static function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

} //_______________class end_______________________