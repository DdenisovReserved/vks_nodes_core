<?php

namespace App\Console;

use DB;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;


class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\test'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {

            $vksList = Vks::where('is_verified_by_user', 0)->with('owner', 'location')->get();
//
            foreach ($vksList as $vks) {
                if ($vks->is_verified_by_user == 0
                    && date_create($vks->start_date_time) > date_create($vks->update_at)
                    && date_create($vks->start_date_time)->getTimestamp() - date_create($vks->update_at)->getTimestamp() <= 86400
                ) {
                    $t = view('verificationMail', ['vks' => $vks]);
                    \Mail::sendMailToStack($vks->owner->login, "Просим подтвердить проведение ВКС #{$vks->id}", $t->render());
                    $vks->is_verified_by_user = USER_VERIFICATION_MAIL_SENDED;
                    $vks->save();
                    \App::$instance->log->logWrite(LOG_MAIL_SENDED, "Верификационное письмо по ВКС {$vks->id}, отправлено {$vks->owner->login}");
                }
            }

        })->everyMinute()->sendOutputTo('verification_log.txt');
    }
}
