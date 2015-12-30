<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('show', function () {
//    $vksList = Vks::where('is_verified_by_user',0)->with('owner','location')->get();
//
//    foreach ($vksList as $vks) {
//        $t = view('verificationMail',['vks'=>$vks]);
//        Mail::sendMailToStack($vks->owner->login, "Просим подтвердить проведение ВКС #{$vks->id}", $t->render());
//    }
    $vks = Vks::find(532);

  if ($vks->is_verified_by_user == 0
      && date_create($vks->start_date_time) > date_create($vks->update_at)
      && date_create($vks->start_date_time)->getTimestamp()-date_create($vks->update_at)->getTimestamp() <= 86400
  ) {
      $t = view('verificationMail',['vks'=>$vks]);
        Mail::sendMailToStack($vks->owner->login, "Просим подтвердить проведение ВКС #{$vks->id}", $t->render());
      $vks->is_verified_by_user = USER_VERIFICATION_MAIL_SENDED;
      $vks->save();
      App::$instance->log->logWrite(LOG_MAIL_SENDED, "Верификационное письмо по ВКС {$vks->id}, отправлено {$vks->owner->login}");
  } 

});
