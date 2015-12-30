<?php

class NotesCa_controller extends Controller
{

    public function apiCheckFlag($caVksId)
    {
        print json_encode(AdminCaNote::where('vks_id', $caVksId)->count() ? ['response' => 1] : ['response'=>0]);
    }

    public function checkFlag($caVksId)
    {
        return AdminCaNote::where('vks_id', $caVksId)->count() ? true : false;
    }

    public function mark($caVksId)
    {
        Auth::isAdminOrDie(App::$instance);
        $notes = AdminCaNote::where('vks_id', $caVksId)->get();
        if (count($notes)) {
            foreach ($notes as $caVks)
                $caVks->delete();
        }

        AdminCaNote::create([
            'vks_id' => $caVksId,
            'owner_id' => App::$instance->user->id,
            'note' => 'Нет'
        ]);

        App::$instance->MQ->setMessage("Для ВКС ЦА {$caVksId} выдан флаг");
        ST::redirect('back');
    }

    public function unmark($caVksId)
    {
        Auth::isAdminOrDie(App::$instance);
        foreach (AdminCaNote::where('vks_id', $caVksId)->get() as $caVks)
            $caVks->delete();

        App::$instance->MQ->setMessage("У ВКС ЦА {$caVksId} удален флаг");
        ST::redirect('back');
    }
}