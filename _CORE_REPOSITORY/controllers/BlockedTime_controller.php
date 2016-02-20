<?php

class BlockedTime_controller extends Controller
{

    public function index()
    {
        return $this->render('settings/blockedtime/index');
    }

    public function feed()
    {
        $start = $this->request->query->get('start');
        $end = $this->request->query->get('end');
        $blocks = BlockedTime::where('start_at', ">=", $start)
            ->where('start_at', '<=', $end)
            ->get();

        foreach ($blocks as $block) {
            $start = date_create($block->start_at);
            $end = date_create($block->end_at);
            $block->date = $start->format("d.m.Y");
            $block->start = $start->format("Y-m-d H:i");
            $block->end = $end->format("Y-m-d H:i");
            $block->start_hours = $start->format("H:i");
            $block->end_hours = $end->format("H:i");
            if ($block->vks_type_blocked) { //if simple vks block
                $block->backgroundColor = '#E87C58';
                $block->borderColor = '#E87C58';
                $block->textColor = '#ffffff';
            }
        }
        print json_encode($blocks); //output
    }

    public function copy($blocked_time_id)
    {
        $block = BlockedTime::findOrFail($blocked_time_id);
        $request = $this->request->request;
        $request->set('start_at_date', date_create($block->start_at)->format("d.m.Y"));
        $request->set('end_at_date', date_create($block->end_at)->format("d.m.Y"));
        $request->set('start_at_time', date_create($block->start_at)->format("H:i"));
        $request->set('end_at_time', date_create($block->end_at)->format("H:i"));
        $request->set('vks_type_blocked', VKS::TYPE_SIMPLE);
        $request->set('description', $block->description);

        return $this->render('settings/blockedtime/create', compact('block'));
    }

    public function create($date = null)
    {

        if ($date) {
            $request = $this->request->request;
            $request->set('start_at_date', date_create($date)->format("d.m.Y"));
            $request->set('end_at_date', date_create($date)->format("d.m.Y"));
        }


        return $this->render('settings/blockedtime/create', compact('date'));
    }

    public function store()
    {
        $request = $this->request->request;
        $this->validator->validate([
            'Дата начала блокировки' => [$request->get('start_at_date'), 'required|date'],
            'Дата окончания блокировки' => [$request->get('end_at_date'), 'required|date'],
            'Время начала блокировки' => [$request->get('start_at_time'), 'required'],
            'Время окончания блокировки' => [$request->get('end_at_time'), 'required'],
//            'Блокируем ВКС' => [$request->get('vks_type_blocked'), 'between(0,1)'],
            'Описание' => [$request->get('description'), 'required|max(360)'],
        ]);
        //if no passes
        if (!$this->validator->passes()) {
            $this->putUserDataAtBackPack($this->request);
            App::$instance->MQ->setMessage($this->validator->errors()->all());
            ST::redirect("back");
        }
        //prepare dates
        $start_at = date_create($request->get('start_at_date') . " " . $request->get('start_at_time') . ":00");
        $end_at = date_create($request->get('end_at_date') . " " . $request->get('end_at_time') . ":00");
        if ($end_at <= $start_at) {
            $this->putUserDataAtBackPack($this->request);
            App::$instance->MQ->setMessage('Дата начала не может быть больше или равна окончанию', 'danger');
            ST::redirect("back");
        }

        $block = new BlockedTime();
        $block->start_at = $start_at;
        $block->end_at = $end_at;
        $block->vks_type_blocked = VKS::TYPE_SIMPLE;
        $block->description = $request->get('description');
        $block->save();

        App::$instance->log->logWrite(LOG_CONFIG_CHANGE, "Добавлена блокировка времени работы");
        App::$instance->MQ->setMessage("Блокировка создана");

        ST::redirectToRoute('BlockedTime/index');

    }

    public function edit($blocked_time_id)
    {

        $block = BlockedTime::findOrFail($blocked_time_id);
        $request = $this->request->request;
        $request->set('start_at_date', date_create($block->start_at)->format("d.m.Y"));
        $request->set('end_at_date', date_create($block->end_at)->format("d.m.Y"));
        $request->set('start_at_time', date_create($block->start_at)->format("H:i"));
        $request->set('end_at_time', date_create($block->end_at)->format("H:i"));
        $request->set('vks_type_blocked', VKS::TYPE_SIMPLE);
        $request->set('description', $block->description);

        return $this->render('settings/blockedtime/edit', compact('block'));
    }

    public function update($blocked_time_id)
    {

        $request = $this->request->request;
        $this->validator->validate([
            'Дата начала блокировки' => [$request->get('start_at_date'), 'required|date'],
            'Дата окончания блокировки' => [$request->get('end_at_date'), 'required|date'],
            'Время начала блокировки' => [$request->get('start_at_time'), 'required'],
            'Время окончания блокировки' => [$request->get('end_at_time'), 'required'],
//            'Блокируем ВКС' => [$request->get('vks_type_blocked'), 'between(0,1)'],
            'Описание' => [$request->get('description'), 'required|max(360)'],
        ]);
        //if no passes
        if (!$this->validator->passes()) {
            $this->putUserDataAtBackPack($this->request);
            App::$instance->MQ->setMessage($this->validator->errors()->all());
            ST::redirect("back");
        }
        //prepare dates
        $start_at = date_create($request->get('start_at_date') . " " . $request->get('start_at_time') . ":00");
        $end_at = date_create($request->get('end_at_date') . " " . $request->get('end_at_time') . ":00");
        if ($end_at <= $start_at) {
            $this->putUserDataAtBackPack($this->request);
            App::$instance->MQ->setMessage('Дата начала не может быть больше или равна окончанию', 'danger');
            ST::redirect("back");
        }
//        die;
        $block = BlockedTime::findOrFail($blocked_time_id);
        $block->start_at = $start_at;
        $block->end_at = $end_at;
        $block->vks_type_blocked = VKS::TYPE_SIMPLE;
        $block->description = $request->get('description');
        $block->save();

        App::$instance->log->logWrite(LOG_CONFIG_CHANGE,
            "Блокировка {$block->start_at} - {$block->end_at}, {$block->blocked_type_named} изменена");
        App::$instance->MQ->setMessage("Блокировка изменена");
        ST::redirectToRoute('BlockedTime/index');
    }

    public function delete($blocked_time_id)
    {
        $block = BlockedTime::findOrFail($blocked_time_id);
        $block->delete();
        App::$instance->log->logWrite(LOG_CONFIG_CHANGE,
            "Блокировка {$block->start_at} - {$block->end_at}, {$block->blocked_type_named} удалена");
        App::$instance->MQ->setMessage("Блокировка удалена");
        ST::redirectToRoute('BlockedTime/index');
    }

    public function askAtDate($date, $vks_blocked_type) {
        $dateStart = date_create($date)->setTime(0,0);
        $blocks = BlockedTime::where('end_at',">=", $dateStart)
            ->where('vks_type_blocked', intval($vks_blocked_type))
            ->whereDate('start_at', '<=', $dateStart)
            ->get();

        if (ST::isAjaxRequest())
            print json_encode($blocks);
        else
            return $blocks;

    }

    public function askAtDateTime(DateTime $startDateTime,DateTime $endDateTime, $vks_blocked_type) {

        $blocks = BlockedTime::where('end_at',">=", $startDateTime)
            ->where('start_at', '<=', $endDateTime)
            ->where('vks_type_blocked', intval($vks_blocked_type))
            ->get();

        if (ST::isAjaxRequest())
            print json_encode($blocks);
        else
            return $blocks;
    }
}