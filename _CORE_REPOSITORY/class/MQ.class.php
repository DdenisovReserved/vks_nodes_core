<?php

/*
 * class for view results after requests
 */

class MQ
{
    function MQ()
    {

    }

    //void
    function setMessage($inpString, $class = 'success')
    {

        $_SESSION['resultMessage']['message'] = $inpString;
        $_SESSION['resultMessage']['class'] = $class;


    }

    function setImportantMessage($inpString)
    {

        $_SESSION['importantMessage']['message'] = $inpString;

    }

    function getMessage()
    {
        if (isset($_SESSION['resultMessage'])) {
            //get message
            $message = $_SESSION['resultMessage'];
            unset($_SESSION['resultMessage']);
            return $message;
        } else {
            return false;
        }
    }

    function getImportantMessage()
    {
        if (isset($_SESSION['importantMessage'])) {
            //get message
            $message = $_SESSION['importantMessage'];
            unset($_SESSION['importantMessage']);
            return $message;
        } else {
            return false;
        }
    }

    function showMessage()
    {

        $m = $this->getMessage();
//        dump($m);
//        $exitElem = "<span class='close-me text-info label label-default'><span class='glyphicon glyphicon-remove '></span> Закрыть</span>";
        $exitElem = "<a class='close' data-dismiss='alert'>×</a>";

        if ($m['message']) {
            if (is_array($m['message'])) {
                echo "<div class='alert alert-" . $m['class'] . " text-center'>{$exitElem}<ul class='list-unstyled'>";
                foreach ($m['message'] as $k => $val) {
                    if (is_array($val)) {
                        foreach ($val as $k1 => $val1) {
                            if (is_array($val1)) {
                                foreach ($val1 as $k2 => $val2) {
                                    echo "<li>{$val2}</li>";
                                }
                            } else {
                                echo "<li>{$val1}</li>";
                            }
                        }
                    } else {
                        echo "<li>{$val}</li>";
                    }
                }
                echo "</ul><div class='clearfix'></div></div>";
            } else {
                echo "<div class='alert alert-" . $m['class'] . " text-center'>{$exitElem}{$m['message']}<div class='clearfix'></div></div>";
            }
        }
    }

    function showImportantMessage()
    {
        $m = $this->getImportantMessage();
        if ($m['message']) {
                $modal = new ModalWindow_controller();
                $modal->instantShow('<h4><h3 class="text-primary text-center">Сообщение системы</h3><hr><h4>'.$m['message'].'</h4>');
        }
    }

    public static function cleanUp()
    {

        unset($_SESSION['resultMessage']);
    }
} // class end
