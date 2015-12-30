<?php
/**
 * Class mail_stack_controller
 * Обертка над оберткой над оберткой над оберткой, нет, я не опечатался
 */
class MailStack_controller extends Controller
{
    //void
    function put($adress, $theme, $message)
    {


        $this->validator->validate([
            'address' => [$adress, 'required|max(160)'],
            'theme' => [$theme, 'required|max(512)'],
            'message' => [$message, 'required'],

        ]);
        //if no passes
        if (!$this->validator->passes()) {
            foreach ($this->validator->errors()->all() as $message) {
                App::$instance->log->logWrite(LOG_MAIL_SENDED, $message);
            }

            return false;
        }

        $mailToStack = new MailStack();
        $mailToStack->address = $adress;
        $mailToStack->theme = ST::cleanUpText($theme);
        $mailToStack->message = ST::cleanUpText($message);
        $mailToStack->owner_ip = 0;
        $mailToStack->save();
        return true;
    }

    function getNotSend()
    {
        return MailStack::where('status',0)->get();
    }

    function sendSingleMail(MailStack $mail)
    {
        mail::sendMail($mail->address, $mail->theme, $mail->message);
        return $this->setSendedStatus($mail->id);

    }
    function showMessage($stackMailId)
    {
        $mail = MailStack::findOrFail($stackMailId);
        echo mail::showMail($mail->address, $mail->theme, $mail->message)['message'];

    }
    function setSendedStatus($stackMailId) {
        $mailInStack = $mail = MailStack::findOrFail($stackMailId);
        $mailInStack->status = 1;
        $mailInStack->save();
        return true;
    }

}