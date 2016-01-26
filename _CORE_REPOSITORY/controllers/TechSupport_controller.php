<?php
use Illuminate\Database\Capsule\Manager as Capsule;


class TechSupport_controller extends Controller
{
    public function showRequests($vks_id)
    {

        try {
            $vks = Vks::with("tech_support_requests")->findOrFail($vks_id);
        } catch (Exception $e) {
            $this->error('404');
        }
        if ($vks->is_tech_supportable)
            return $this->render('techsupport/requests', compact('vks'));
        else
            $this->error("404");
    }

    public function addRequest($vks_id)
    {
        try {
            $vks = Vks::with('tech_support_requests')->findOrFail($vks_id);
        } catch (Exception $e) {
            $this->error('404');
        }

        $available_points = Attendance::techSupportable()->get()->toArray();
        array_walk($available_points, function (&$e) {
            $e['selectable'] = true;
        });

        if (count($vks->tech_support_requests))
            foreach ($vks->tech_support_requests as $request) {
                foreach ($available_points as &$point) {
                    if ($request->att_id == $point['id']) {
                        $point['selectable'] = false;
                    }
                }
            }

        return $this->render('techsupport/addRequest', compact('vks', 'available_points'));
    }

    public function storeRequest($vks_id)
    {
        try {
            $vks = Vks::with('tech_support_requests')->findOrFail($vks_id);
        } catch (Exception $e) {
            $this->error('404');
        }

        $request = $this->request->request;

        $this->validator->validate([
            'Сообщение' => [$request->get('user_message'), 'max(255)'],
            'Точка' => [$request->get('att_id'), 'required|int'],
        ]);
        //if no passes
        if (!$this->validator->passes()) {
            $this->backWithData($this->validator->errors()->all());
        }

        $request_new = new TechSupportRequest(array(
            'att_id' => $request->get('att_id'),
            'vks_id' => $vks->id,
            'owner_id' => App::$instance->user->id,
            'user_message' => $request->get('user_message'),
            'status' => $vks->status == VKS_STATUS_APPROVED ?
                TechSupportRequest::STATUS_READY_FOR_SEND : TechSupportRequest::STATUS_WAIT_VKS_DECISION
        ));
        $flag = false;
        if (count($vks->tech_support_requests))
            foreach ($vks->tech_support_requests as $request) {
                if ($request_new->att_id == $request->att_id) {
                    $flag = true;
                }
            }

        if (!$flag)
            $request_new->save();

        App::$instance->MQ->setMessage("Запрос успешно создан");

        ST::redirectToRoute('TechSupport/showRequests/' . $vks->id);

    }


    public function pullAndSendRequests()
    {
        $vc = new Vks_controller();
        $requests = TechSupportRequest::where("status", TechSupportRequest::STATUS_READY_FOR_SEND)
            ->with('attendance', 'owner')
            ->get();

        $temp_mails = 'Tomarov1-iv@mail.ca.sbrf.ru; DenisovDE@ab.srb.local';
        if (count($requests))
            foreach ($requests as $request) {
                $vks = Vks::full()->find($request->vks_id);
                $vc->humanize($vks);
                $appHttpPath = NODE_HTTP_PATH;
                $message = App::$instance->twig->render('mails/v2/tech_support/new.twig', compact('request', 'vks', 'appHttpPath'));
                Mail::sendMailToStack($temp_mails, 'Запрос тех. поддержки для точки ' . $request->attendance->name . ' на ВКС #' . $vks->id, $message);
                $request->status = TechSupportRequest::STATUS_DELIVERED;
                $request->save();
            }
    }

}