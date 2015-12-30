<?php
class ExceptionHandler {
    static function CatchResultFromSQL($inpData, $successText='Успешно сохранено', $redirectUrl = false) {

    if (!is_int($inpData)) {
        $inpData = stripcslashes($inpData);
        return "Операция завершена с ошибкой, текст ошибки {$inpData}";
    } else {

        return "$successText";
    }

}
    static function showEmptyMessage ($text) {
        return "<div class='alert alert-warning col-md-offset-2 col-md-8'>$text</div>";
    }

    /*
     * Делает редирект на себя (показываемая данный моент страница) и прицепляет
     * сообщение
     */
    static function selfRedirAndShowMessage($message){

        session_start();
        $_SESSION['pm']=$message;
        header("location:".$_SERVER['REQUEST_URI']);
    } //func end
    /*
     * Контролируемый переход и отображение сообщения
     */
    static function ctrRedirAndShowMessage($location,$message){

//        @session_start();
        $_SESSION['pm']=$message;

        ST::redirect($location);
    } //func end
    /*
     * Функция проверяет установлена
     * ли переменная сообщения и выдает
     * его в виде алерта
     */
    static function messageException () {
        @session_start();
//        ST::makeDebug($_SESSION);
        if (isset($_SESSION['pm']))
                    echo "<br><div class='alert alert-info col-md-offset-2 col-md-8'>{$_SESSION['pm']}</div>";
        unset($_SESSION['pm']);

    } //func end
} //class end