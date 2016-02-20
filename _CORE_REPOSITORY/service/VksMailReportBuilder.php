<?php

class VksMailReportBuilder
{

    public function sendReportMail(Vks $vks, $toRequester = true)
    {

        $vks->link = ST::linkToVksPage($vks->id, false, true);
        $vksArray = $vks->toArray();
        $vksCa = ($vks->other_tb_required && !empty($vks->link_ca_vks_id)) ? CAVksNoSupport::with('participants')->find($vks->link_ca_vks_id) : false;

        $message = App::$instance->twig->render('mails/v2/vksWs-report.twig', array(
            'vks' => $vksArray,
            'http_path' => HTTP_BASE_PATH,
            'appHttpPath' => NODE_HTTP_PATH,
            'vksCa' => $vksCa
        ));
        if (!$toRequester) {
            Mail::sendMailToStack($vks->owner->email, "ВКС #{$vks['id']} | {$vks['title']}", $message);
        } else {
            Mail::sendMailToStack(App::$instance->user->email, "ВКС #{$vks['id']} | {$vks['title']}", $message);
        }
        if (mb_strtolower($vks->owner->email) != mb_strtolower($vks->init_customer_mail) && mb_strtolower($vks->init_customer_mail) != 'не указано') {
            Mail::sendMailToStack($vks->init_customer_mail, "ВКС #{$vks->id} | {$vks['title']}, в которой вы заявлены как ответственный, одобрена", $message);
        }
        App::$instance->log->logWrite(LOG_MAIL_SENDED, "VKS WS #{$vks['id']} | {$vks['title']}, одобрена");
    }

    public function sendSimpleMail($vks, $toRequester = true)
    {
        $vks->link = ST::linkToVksPage($vks->id, false, true);
        $message = App::$instance->twig->render('mails/v2/vkssimple-report.twig', array(
            'vks' => $vks,
            'http_path' => HTTP_BASE_PATH,
            'appHttpPath' => NODE_HTTP_PATH
        ));
        if (!$toRequester) {
            Mail::sendMailToStack($vks->owner->email, "ВКС #{$vks['id']} | {$vks['title']}, создана", $message);
        } else {
            Mail::sendMailToStack(App::$instance->user->email, "ВКС #{$vks['id']} | {$vks['title']}, создана", $message);
        }

        App::$instance->log->logWrite(LOG_MAIL_SENDED, "VKS WS #{$vks['id']} | {$vks['title']}, создана");
    }

    public function sendDeclineMail($vks, $toRequester = true)
    {

        $vks->link = ST::linkToVksPage($vks->id, false, true);
        $vksArray = $vks->toArray();
        $message = App::$instance->twig->render('mails/v2/vksWs-refuse.twig', array(
            'vks' => $vksArray,
            'http_path' => HTTP_BASE_PATH,
            'appHttpPath' => NODE_HTTP_PATH
        ));
        if (!$toRequester) {
            Mail::sendMailToStack($vks->owner->email, "ВКС #{$vks['id']} отказ", $message);
        } else {
            Mail::sendMailToStack(App::$instance->user->email, "ВКС #{$vks['id']} отказ", $message);
        }
        App::$instance->log->logWrite(LOG_MAIL_SENDED, "VKS WS #{$vks['id']} отказ");
    }

    public function sendEditedMail($vks, $toRequester = true)
    {

        $vks->link = ST::linkToVksPage($vks->id, false, true);
        $vksArray = $vks->toArray();
        $vksCa = ($vks->other_tb_required && !empty($vks->link_ca_vks_id)) ? CAVksNoSupport::with('participants')->find($vks->link_ca_vks_id) : false;
        $message = App::$instance->twig->render('mails/v2/vksWs-edited.twig', array(
            'vks' => $vksArray,
            'http_path' => HTTP_BASE_PATH,
            'appHttpPath' => NODE_HTTP_PATH,
            'vksCa' => $vksCa
        ));
        if (!$toRequester) {
            Mail::sendMailToStack($vks->owner->email, "ВКС #{$vks['id']} | {$vks['title']}, отредактирована администратором", $message);
        } else {
            Mail::sendMailToStack(App::$instance->user->email, "ВКС #{$vks['id']} | {$vks['title']}, отредактирована администратором", $message);
        }

        if (mb_strtolower($vks->owner->email) != mb_strtolower($vks->init_customer_mail)) {
            Mail::sendMailToStack($vks->init_customer_mail, "ВКС #{$vks->id} | {$vks['title']}, в которой вы заявлены как ответственный, отредактирована администратором", $message);
        }

        App::$instance->log->logWrite(LOG_MAIL_SENDED, "VKS WS #{$vks['id']} | {$vks['title']}, отредактирована администратором");
    }

    public function sendDeleteMail($vks, $toRequester = true)
    {
        $vks->link = ST::linkToVksPage($vks->id, false, true);
        $vksArray = $vks->toArray();
        $message = App::$instance->twig->render('mails/v2/vks-delete.twig', array(
            'vks' => $vksArray,
            'http_path' => HTTP_BASE_PATH,
            'appHttpPath' => NODE_HTTP_PATH
        ));
        if (!$toRequester) {
            Mail::sendMailToStack($vks->owner->email, "Ваша ВКС #{$vks['id']} аннулирована", $message);
        } else {
            Mail::sendMailToStack(App::$instance->user->email, "ВКС #{$vks['id']} аннулирована", $message);
        }
        App::$instance->log->logWrite(LOG_MAIL_SENDED, "VKS WS #{$vks['id']} аннулирована");
    }

    public function sendAdminNotice(Vks $vks)
    {
        $vksArray = $vks->toArray();

        $admins = User::whereIn('role', [ROLE_ADMIN, ROLE_ADMIN_MODERATOR])->get(['login', 'email']);
        if (count($admins))
            foreach ($admins as $admin) {
                $message = App::$instance->twig->render('mails/v2/newVksAdminNotificate.twig', array(
                    'vks' => $vksArray,
                    'http_path' => HTTP_BASE_PATH,
                    'appHttpPath' => NODE_HTTP_PATH
                ));

                Mail::sendMailToStack($admin->email, "Новая заявка на ВКС #{$vks['id']}", $message);
                App::$instance->log->logWrite(LOG_MAIL_SENDED, "Новая заявка на ВКС #{$vks['id']}");
            }
    }
}