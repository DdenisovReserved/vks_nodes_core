<?php

class OutlookCalendarRequest_controller extends Controller
{
    function pushToStack($vks_id, $force = false)
    {
        try {
            $vks = Vks::approved()->notEnded()->findOrFail($vks_id);
        } catch (Exception $e) {
            $this->error('404');
        }

        if (!OutlookCalendarRequest::where('user_id', App::$instance->user->id)->where('vks_id', $vks->id)->count()) {
            OutlookCalendarRequest::create(array(
                'user_id' => App::$instance->user->id,
                'vks_id' => $vks->id,
                'request_type' => OutlookCalendarRequest::REQUEST_TYPE_NEW,
                'send_status' => OutlookCalendarRequest::SEND_STATUS_REQUIRED
            ));
            App::$instance->log->logWrite(LOG_OTHER_EVENTS, "New Outlook request create for " . App::$instance->user->login . ', vks: ' . $vks->id);
            App::$instance->MQ->setMessage("Приглашение сформировано, ожидайте, отправка будет произведена в течении 2х минут");
        } else {
            if ($force) {
                $reSend = OutlookCalendarRequest::where('user_id', App::$instance->user->id)->where('vks_id', $vks->id)->first();
                $reSend->send_status = OutlookCalendarRequest::SEND_STATUS_REQUIRED;
                $reSend->save();
                App::$instance->log->logWrite(LOG_OTHER_EVENTS, "New Outlook request create for " . App::$instance->user->login . ', vks: ' . $vks->id);
                App::$instance->MQ->setMessage("Приглашение сформировано, ожидайте, отправка будет произведена в течении 2х минут");
            } else {
                App::$instance->MQ->setMessage("Приглашение уже отправлялось в ваш календарь, <a class='confirmation' href='" . ST::route('OutlookCalendarRequest/pushToStack/' . $vks->id . '/forced') . "'>Отправить еще раз</a>");
            }
        }

        ST::redirect('back');
    }

    public static function changeRequestTypeAndPutToResend($vks_id, $request_type)
    {
        if (in_array($request_type, [
            OutlookCalendarRequest::REQUEST_TYPE_NEW, OutlookCalendarRequest::REQUEST_TYPE_UPDATE
        ])) {
            $requests = OutlookCalendarRequest::where('vks_id', $vks_id)->get();
            if (count($requests)) {
                foreach ($requests as $request) {
                    $request->request_type = $request_type;
                    $request->send_status = OutlookCalendarRequest::SEND_STATUS_REQUIRED;
                    $request->save();
                }
            }
            return true;
        } else {
            return false;
        }
    }


    function pullAndSendFromStack()
    {
        $vc = new Vks_controller();
        $pullMails = OutlookCalendarRequest::notSended()->get();
        if (count($pullMails)) {
            foreach ($pullMails as $outlookRequest) {
                $vks = Vks::full()->find($outlookRequest->vks_id);
                if ($vks) {
                    $vc->humanize($vks);
                    Mail::sendIcalEvent($vks, $outlookRequest->request_type, $outlookRequest->user);
                    App::$instance->log->logWrite(LOG_MAIL_SENDED, 'Outlook event invite sended to: ' . $outlookRequest->user->email);
                    $outlookRequest->send_status = OutlookCalendarRequest::SEND_STATUS_COMPLETED;
                    $outlookRequest->save();
                } else {
                    App::$instance->log->logWrite(LOG_MAIL_SENDED, 'Outlook event invite can\'t be sended, vks not found, vks_id:' . $outlookRequest->vks_id . ', request_id:' . $outlookRequest->id);
                }
            }

        }
    }

}