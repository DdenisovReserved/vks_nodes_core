<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Exception\LogicException;

class Vks_controller extends Controller
{

    use sorterTrait;

    public function index($date)
    {
        $date = date_create($date);

        if (!isset($date) || !($date instanceof DateTime)) ST::routeToErrorPage('500');

        $vkses = Vks::full()
            ->where('date', $date)
            ->whereIn('status', array(VKS_STATUS_APPROVED, VKS_STATUS_PENDING))
            ->take($this->getQlimit(30))
            ->skip($this->getQOffset())
            ->orderBy($this->getQOrder('start_date_time'), $this->getQVector('asc'))
            ->get();
        $recordsCount = Vks::where('date', $date)
            ->count();
        //pages
        foreach ($vkses as $vks) {
            $this->humanize($vks);
        }
        $pages = RenderEngine::makePagination($recordsCount, $this->getQlimit(30), 'route');
        $date = $date->format("d.m.Y");
        $this->render('vks\index', compact('vkses', 'date', 'pages'));
    }

    public function select()
    {
        global $_TB_IDENTITY;
        if (!Auth::isLogged(App::$instance)) {
            App::$instance->MQ->setMessage('Создавать заявки могут только зарегистрированные пользователи, пожалуйста, войдите в систему используя логин для <b>' . $_TB_IDENTITY[MY_NODE]['humanName'] . '</b> или зарегистрируйтесь');
            ST::redirect(HTTP_PATH . "?route=AuthNew/login&return=Vks/select");
        }
        $this->render('vks/select');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        if (!Auth::isLogged(App::$instance)) {
            App::$instance->MQ->setMessage('Создавать заявки могут только зарегистрированные пользователи, пожалуйста, войдите в систему или зарегистрируйтесь');
            ST::redirectToRoute('AuthNew/login');
        }
        //clear stack
        //pull departments & initiators
        $departments = Department::orderBy('prefix')->get();
        $initiators = Initiator::all();;
        $tbs = CAAttendance::tbs()->get();

        $vks = ST::lookAtBackPack();
        $vks = $vks->request;
        if (!$vks->has('inner_participants') && !count($vks->get('inner_participants'))) {
            LocalStorage_controller::staticRemove('vks_participants_create');
        }
        $available_points = Attendance::techSupportable()->get()->toArray();
        array_walk($available_points, function (&$e) {
            $e['selectable'] = true;
        });

        if (!count($tbs)) {
            App::$instance->MQ->setMessage('Не удалось получить список ТБ, создать ВКС ТБ-ТБ не получится. Возможно это временный сбой.', 'danger');
        }
        $this->render('vks/create', compact('departments', 'initiators', 'tbs', 'vks', 'available_points'));
    }

    public function makeClone($id)
    {
        $strict = boolval(intval(Settings_controller::getOther("attendance_strict")));
        if (!Auth::isLogged(App::$instance)) {
            App::$instance->MQ->setMessage('Создавать заявки могут только зарегистрированные пользователи, пожалуйста, войдите в систему или зарегистрируйтесь');
            ST::redirectToRoute('AuthNew/login');
        }
        //can this user access it
        try {
            $vks = Vks::full()->findOrFail($id);;
        } catch (Exception $e) {
            $this->error('404');
        }
        $this->humanize($vks);
        if (!$vks->humanized->isCloneable)
            $this->error('no_manipulable');
        //get vksdata
        if ($vks->is_simple) {
            App::$instance->MQ->setMessage("Упрощенные ВКС клонировать запрещено, можно только аннулировать");
            ST::redirect('back');
        }
        $vks = $this->humanize($vks);
        if (!$vks->humanized->isCloneable) {
            App::$instance->MQ->setMessage("Данную ВКС клонировать запрещено");
            ST::redirectToRoute("Vks/show/" . $vks->id);
        }
        //refill stack
        $tbs = CAAttendance::tbs()->get();
        $departments = Department::orderBy('prefix')->get();
        LocalStorage_controller::staticRemove('vks_participants_create');
        if (!$strict) {
            $this->fillCookieParticipants('vks_participants_create', $vks);
        } else {
            $vks->in_place_participants_count = 0;
            $vks->participants = null;
        }
        $vksbp = ST::lookAtBackPack();
        $vks = count($vksbp->request) ? $vksbp->request : $vks;

        if ($vks instanceof Vks) {
            $vks->inner_participants = $vks->participants;
            foreach ($vks->toArray() as $key => $val) {
                if (!in_array($key, array("date", "start_date_time", "end_date_time")))
                    $this->request->request->set($key, $val);
            }
            $vks = $this->request->request;
        }
//        dump($vks);
        $this->render('vks/create', compact('vks', 'departments', 'tbs'));
    }

    public function store()
    {
        Token::checkToken();
        $request = $this->request->request;
        //deploy participants
        $this->fillParticipants("vks_participants_create", $this->request);

        //compare given dates, must be unique
        $unique = true;
        if ($request->has('dates')) {
            if (count($request->get('dates')) > 1) {
                $clonedArray = $request->get('dates');
                while (!empty($clonedArray)) {
                    $popElement = array_pop($clonedArray);
                    if (in_array($popElement, $clonedArray)) {
                        $unique = false;
                    }
                }
            }
        }
        if (!$unique) {
            $this->putUserDataAtBackPack($this->request);
            App::$instance->MQ->setMessage("Ошибка: заявляемые даты ВКС должны быть уникальны", 'danger');
            ST::redirect("back");
        }

        if (count($request->get('dates')) > 1) {
            $result = array();
            //create new stack
            $stack = VksStack::create([]);
            foreach ($request->get('dates') as $date) {
                $requestFiltered = new Request();
                foreach ($request as $key => $param) {
                    $requestFiltered->request->set($key, $param);
                    $requestFiltered->request->set('date', $date['date']);
                    $requestFiltered->request->set('start_time', $date['start_time']);
                    $requestFiltered->request->set('end_time', $date['end_time']);
                    $requestFiltered->request->set('vks_stack_id', $stack->id); //inject stack
                }
                $result[] = $this->saveRegularVks($requestFiltered, true);
            }
        } else {
            $requestFiltered = new Request();
            foreach ($request as $key => $param) {
                $requestFiltered->request->set($key, $param);
                $requestFiltered->request->set('date', $request->get('dates')[1]['date']);
                $requestFiltered->request->set('start_time', $request->get('dates')[1]['start_time']);
                $requestFiltered->request->set('end_time', $request->get('dates')[1]['end_time']);
            }
            $result[] = $this->saveRegularVks($requestFiltered, true);
        }
        //deploy result
        $_SESSION['savedResult_' . App::$instance->main->appkey] = $result;
        //redirect
        ST::redirectToRoute('vks/checkout');
    }

    public function saveRegularVks(Request $request, $silent = false)
    { //if error occure, save stopped
        $request = $request->request;
        $report = new VksReport($request);

        Capsule::beginTransaction();
        $this->validator->validate([
            'Дата' => [$request->get('date'), 'required|date'],
            'Время начала' => [$request->get('start_time'), 'required'],
            'Время окончания' => [$request->get('end_time'), 'required'],
            'Название' => [$request->get('title'), 'required|max(205)'],
            'Код в ЦА' => [$request->get('title'), 'max(40)'],
            'ФИО ответственного' => [$request->get('init_customer_fio'), 'required|max(255)'],
            'Почта ответственного' => [$request->get('init_customer_mail'), 'required|max(255)'],
            'Тел. ответственного' => [$request->get('init_customer_phone'), 'required|max(255)'],
            'Подразделение' => [$request->get('department'), 'required|int'],
            'Участники в ЦА' => [$request->get('ca_participants'), 'int|between(0,10)'],
            'Участники ВКС' => [$request->get('inner_participants'), 'array'],
            'Комментарий для Администратора' => [$request->get('comment_for_admin'), 'max(160)'],
            'Точка для технической поддержки' => [$request->get('tech_support_att_id'), "int|attendance_is_tech_supportable"],
            'Комментарий для Тех. поддержки' => [$request->get('user_message'), 'max(255)'],
        ]);
        //if no passes

        //any participants required
        if (!intval($request->get('in_place_participants_count'))
            && !count($request->get('inner_participants'))
        ) $this->backWithData('Вы не выбрали внутренних участников (в вашем ТБ) для ВКС');

        //create new entity
        $vks = new Vks();

        if (!$request->get('ca_code')) $request->set('ca_code', Null);

        $vks->fill($request->all());
        $vks->is_private = $request->has('is_private') ? 1 : 0;
        $vks->other_tb_required = $request->get('needTB') ? 1 : 0;
        $vks->record_required = $request->has('record_required') ? 1 : 0;
        $vks->start_date_time = $request->get('date') . " " . $request->get('start_time');
        $vks->end_date_time = $request->get('date') . " " . $request->get('end_time');

        $this->timeBarrier($vks);

        $vks->comment_for_admin = $request->get('comment_for_admin');
        $vks->owner_id = App::$instance->user->id;
        $vks->from_ip = App::$instance->user->ip;
        $vks->save();
        //relation models
        $message = "Пользователь " . App::$instance->user->login . " создал ВКС " . ST::linkToVksPage($vks->id);

        if ($request->get('needTB')) {
            App::$instance->callService('vks_ca_negotiator')->fillRequestWithInputData($this->request);
            App::$instance->callService('vks_ca_negotiator')->validateCaParticipants($this->request, $this);
            App::$instance->callService('vks_ca_negotiator')->fillRelationEntity($this->request, $vks);

            if (strlen($request->get('i_know_ca_code'))) {
                $vks->title = $vks->title . " |&* код в ЦА: " . $request->get('i_know_ca_code');
                $vks->save();
            }

            $message .= ", и запрашивает участие других ТБ или ЦА";
        }

        if ($request->get('tech_support_required'))
            TechSupportRequest::create(array(
                'att_id' => $request->get('tech_support_att_id'),
                'vks_id' => $vks->id,
                'owner_id' => App::$instance->user->id,
                'user_message' => $request->get('user_message'),
                'status' => TechSupportRequest::STATUS_WAIT_VKS_DECISION
            ));

        //parse inner participants
        //check if vks not in past time
        if (!Auth::isAdmin(App::$instance)) {
            if (self::isVksInPastTime($vks)) {
                $report->setErrors(['ВКС не может быть проведена в прошлом']);
                $report->setResult(false);
                return $report;
            }
        }
        //create participants
        $this->createInnerOrPhoneParp($request->get('inner_participants'), $vks);

        NoticeObs_controller::put($message);

        if ($silent) {
            $report->setResult(true);
            $report->setObject($vks);
        } else {
            App::$instance->MQ->setImportantMessage("Ваша ВКС #{$vks->id} успешно создана, ожидайте согласования администратором, после согласования информация о подключении будет направлена на вашу электронную почту (" . App::$instance->user->login . ")");
        }

        App::$instance->log->logWrite(LOG_VKSWS_CREATED, "VKS " . ST::linkToVksPage($vks->id) . " Created");

        Capsule::commit();

        //if admins must be notified
        if (boolval(intval(Settings_controller::getOther('notify_admins')))) {
            $vks = Vks::full()->find($vks->id);
            $this->humanize($vks);
            App::$instance->callService('vks_report_builder')->sendAdminNotice($vks);
        }

        if ($silent) {
            return $report;
        } else {
            return true;
        }
    }

    public function createSimple()
    {

        if (!Auth::isLogged(App::$instance)) {
            App::$instance->MQ->setMessage('Создавать заявки могут только зарегистрированные пользователи, пожалуйста, войдите в систему или зарегистрируйтесь');
            ST::redirectToRoute('AuthNew/login');
        }

        $this->render('vks/createSimple');
    }

    public function storeSimple()
    {
        Token::checkToken();
        $blctrl = new BlockedTime_controller();
        $load = new Load_controller();
        $request = $this->request->request;
        $report = new VksReport($request);
        Capsule::beginTransaction();
        $this->validator->validate([
            'Дата' => [$request->get('date'), 'required|date'],
            'Время начала' => [$request->get('start_time'), 'required'],
            'Время окончания' => [$request->get('end_time'), 'required'],
            'Название' => [$request->get('title'), 'required|max(255)']
        ]);
        //if no passes
        if (!$this->validator->passes()) {
            $this->backWithData($this->validator->errors()->all());
        }

        $vks = new Vks();
        $vks->fill($request->all());
        $vks->is_private = $request->has('is_private') ? 1 : 0;
        $vks->record_required = $request->has('record_required') ? 1 : 0;
        $vks->start_date_time = $request->get('date') . " " . $request->get('start_time');
        $vks->end_date_time = $request->get('date') . " " . $request->get('end_time');
        $vks->owner_id = App::$instance->user->id;
        $vks->from_ip = App::$instance->user->ip;

        $this->timeBarrier($vks);
        $vks->is_simple = 1;
        $vks->status = VKS_STATUS_APPROVED;

        //check blocks
        $blocks = $blctrl->askAtDateTime($vks->start_date_time, $vks->end_date_time, 1);
        if (count($blocks)) {
            $blockErrors = array();
            foreach ($blocks as $block) {
                $blockErrors[] = "Создание ВКС запрещено, {$block->start_at} -  {$block->end_at}, основание: {$block->description}";
            }

            $this->backWithData($blockErrors);
        }

        $vks->save();

        if (!in_array($vks->in_place_participants_count, range(2, 10))) {
            $this->backWithData("Ошибка: недопустимое кол-во участников");
        }
        //ask simple code
        if (!$simpleCode = $load->giveSimpleCode($vks->start_date_time, $vks->end_date_time)) {
            $this->backWithData("Ошибка: К сожалению сейчас нет свободных комнат для проведения упрощенных ВКС, воспользуйтесь <a href='" . ST::route('Vks/create') . "'>стандартной формой</a>");
        }

        ConnectionCode::create([
            'vks_id' => $vks->id,
            'value' => $simpleCode
        ]);

        App::$instance->log->logWrite(LOG_VKSWS_CREATED, "simple VKS " . ST::linkToVksPage($vks->id) . " Created");

        if (!$load->isPassedByCapacity($vks->start_date_time, $vks->end_date_time, self::countParticipants($vks->id), 0)) {
            $this->backWithData("Ошибка: Заявленное кол-во участников, превышает предельно допустимую нагрузку на сервер ВКС, обратитесь к администраторам системы");
        }

        Capsule::commit();

        $report->setObject($vks);
        $report->setResult(true);
        $result[] = $report;
        $_SESSION['savedResult_' . App::$instance->main->appkey] = $result;
        //redirect
        $vks = Vks::full()->find($vks->id);
        $this->humanize($vks);
        App::$instance->callService('vks_report_builder')->sendSimpleMail($vks);

        ST::redirectToRoute('vks/checkout');

    }

    public function adminCreate()
    {
        Auth::isAdminOrDie(App::$instance);
        //pull departments & initiators
        $departments = Department::orderBy('prefix')->get();
        $initiators = Initiator::all();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_URL, HTTP_PATH . "?route=AttendanceNew/apiGetTbs");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("X-Requested-With: XMLHttpRequest"));
        $tbs = curl_exec($curl);
        curl_close($curl);

        if (0 === strpos(bin2hex($tbs), 'efbbbf')) {
            $tbs = substr($tbs, 3);
        }

        $tbs = json_decode($tbs);
        //render
        $vks = ST::lookAtBackPack();
        $vks = $vks->request;
        if (!$vks->has('inner_participants') && !count($vks->get('inner_participants'))) {
            LocalStorage_controller::staticRemove('vks_participants_create');
        }
        $available_points = Attendance::techSupportable()->get()->toArray();
        array_walk($available_points, function (&$e) {
            $e['selectable'] = true;
        });
        $ca_pool_codes = App::$instance->callService("vks_ca_negotiator")->askForPool();

        $this->render('vks/admin/create', compact('departments', 'initiators', 'tbs', 'available_points', 'ca_pool_codes', 'vks'));

    }

    public function adminStore()
    {
        Auth::isAdminOrDie(App::$instance);
        Token::checkToken();

        $connCtrl = new ConnectionCode_controller();
        $request = $this->request->request;
        $this->fillParticipants("vks_participants_create", $this->request);
        Capsule::beginTransaction();
        $this->validator->validate([
            'Дата' => [$request->get('date'), 'required|date'],
            'Время начала' => [$request->get('start_time'), 'required'],
            'Время окончания' => [$request->get('end_time'), 'required'],
            'Название' => [$request->get('title'), 'required|max(255)'],
            'ФИО ответственного' => [$request->get('init_customer_fio'), 'required|max(255)'],
            'Почта ответственного' => [$request->get('init_customer_mail'), 'required|max(255)'],
            'Тел. ответственного' => [$request->get('init_customer_phone'), 'required|max(255)'],
            'Подразделение' => [$request->get('department'), 'required|int'],
            'Участники в ЦА' => [$request->get('ca_participants'), 'int|between(0,10)'],
            'Владелец' => [$request->get('owner_id'), 'required|int'],
            'Комментарий для пользователя' => [$request->get('comment_for_user'), 'max(160)'],
            'Комментарий для Администратора' => [$request->get('comment_for_admin'), 'max(160)'],
            'Участники ВКС' => [$request->get('inside_participants'), 'array'],
            'Кол-во участников с рабочих мест (IP телефоны)' => [$request->get('in_place_participants_count'), 'int'],
            'Точка для технической поддержки' => [$request->get('tech_support_att_id'), "int|attendance_is_tech_supportable"],
            'Комментарий для Тех. поддержки' => [$request->get('user_message'), 'max(255)'],
        ]);

        //if no passes
        if (!$this->validator->passes()) {
            $this->backWithData($this->validator->errors()->all());
        }

        //any participants required
        if (!intval($request->get('in_place_participants_count')) && !count($request->get('inner_participants'))) {
            $this->backWithData('Вы не выбрали участников для ВКС');
        }

        $vks = new Vks();

        if (!$request->get('ca_code')) $request->set('ca_code', Null);

        $vks->fill($request->all());
        $vks->is_private = $request->has('is_private') ? 1 : 0;
        $vks->record_required = $request->has('record_required') ? 1 : 0;
        $vks->other_tb_required = $request->get('needTB') ? 1 : 0;

        $vks->start_date_time = $request->get('date') . " " . $request->get('start_time');
        $vks->end_date_time = $request->get('date') . " " . $request->get('end_time');

        $this->timeBarrier($vks);

        $vks->comment_for_admin = $request->get('comment_for_admin');
        $vks->owner_id = $request->get('owner_id');
        $vks->from_ip = App::$instance->user->ip;
        $vks->status = VKS_STATUS_APPROVED;
        $vks->approved_by = App::$instance->user->id;
        $vks->save();
        //relation models
        if ($request->get('needTB')) {
            App::$instance->callService('vks_ca_negotiator')->fillRequestWithInputData($this->request);
            App::$instance->callService('vks_ca_negotiator')->validateCaParticipants($this->request, $this);
            App::$instance->callService('vks_ca_negotiator')->fillRelationEntity($this->request, $vks);
        }


        if ($request->get('tech_support_required'))
            TechSupportRequest::create(array(
                'att_id' => $request->get('tech_support_att_id'),
                'vks_id' => $vks->id,
                'owner_id' => App::$instance->user->id,
                'user_message' => $request->get('user_message'),
                'status' => TechSupportRequest::STATUS_WAIT_VKS_DECISION
            ));
        //check if vks not in past time
        if (!Auth::isAdmin(App::$instance)) {
            if (self::isVksInPastTime($vks)) throw new LogicException('bad vks date, it on past time');
        }
        //create participants
        $this->createInnerOrPhoneParp($request->get('inner_participants'), $vks, true);

        App::$instance->log->logWrite(LOG_VKSWS_CREATED, "VKS " . ST::linkToVksPage($vks->id) . " Created by admin, direct insert");

        if (!$request->has('no-codes')) {
            foreach ($request->get('code') as $code) {
                $compiledCode = $code['prefix'] . $code['postfix'];

                if ($request->has('no-check')) {
                    $checkVks = false;
                } else {
                    $checkVks = $connCtrl->isCodeInUse($compiledCode, $vks->start_date_time, $vks->end_date_time);
                }
                if ($checkVks) {
                    $this->backWithData('Ошибка: Код ' . $compiledCode . ' уже используется в ' . ST::linkToVksPage($checkVks->id));
                } else {
                    $newCodes[] = ConnectionCode::create([
                        'vks_id' => $vks->id,
                        'value' => $compiledCode,
                        'tip' => $code['tip']
                    ]);
                }
            }
        }

        //vks ca process
        $transportVksMessage =
            App::$instance->callService('vks_ca_negotiator')->transportVksProcessor($this->request, $vks);

        if ($transportVksMessage->getStatusCode() != Response::HTTP_OK) {
            $this->backWithData($transportVksMessage->getContent());
        }

        App::$instance->MQ->setMessage("ВКС " . ST::linkToVksPage($vks->id) . " добавлена в расписание" . "<p>" . $transportVksMessage->getContent() . "</p>");

        Capsule::commit();

        ST::redirectToRoute('Index/index');

    }

    public function checkout()
    {
        if (!isset($_SESSION['savedResult_' . App::$instance->main->appkey])) $this->error('no-object');

        $reports = $_SESSION['savedResult_' . App::$instance->main->appkey];

        $this->render('vks/checkout', compact("reports"));

    }

    public function show($id, $partial = false)
    {
        try {
            $vks = Vks::with('participants', 'department_rel', 'connection_codes', 'initiator_rel', 'owner', 'approver', 'tech_support_requests')
                ->findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->error("model-not-found");
        }

        if (!in_array($vks->status, [VKS_STATUS_APPROVED, VKS_STATUS_PENDING])) {
            if (!(Auth::isLogged(App::$instance)
                && (Auth::isAdmin(App::$instance) || (App::$instance->user->id == $vks->owner->id)))
            ) {
                $this->error("model-not-found");
            }
        }

        //ask for VksfromCA
        $caVks = Null;
        if ($vks->link_ca_vks_id) {
            if ($vks->link_ca_vks_type == 0) {
                $caVks = Curl::get(ST::routeToCaApi('getVksWasById/' . $vks->link_ca_vks_id));
            } else {
                $caVks = Curl::get(ST::routeToCaApi('getVksNsById/' . $vks->link_ca_vks_id));
            }
            $caVks = json_decode($caVks);
            if ($caVks->status == 200) {
                $caVks = $caVks->data;
            } else {
                $caVks = Null;
            }
        }

        if ($caVks) {
            $caVks->startTime = date_create($caVks->start_date_time)->format("H:i");
            $caVks->endTime = date_create($caVks->end_date_time)->format("H:i");
            if ($vks->other_tb_required)
                $vks->tb_parp = isset($caVks->insideParp) ? $caVks->insideParp : $caVks->participants;
            $vks->ca_linked_vks = $caVks;

        }

        $vks->CaIdParticipants;
        $vks->CaInPlaceParticipantCount;

        $vks = $this->humanize($vks);

//        dump($vks);
        if (!$vks->is_simple) {
            $this->render('vks/show', compact('vks', 'partial'));
        } else {
            $this->render('vks/simple-info', compact('vks', 'partial'));
        }

    }

    public function edit($id)
    {
        //can this user access it
        $vks = Vks::full()->findOrFail($id);
        $check = boolval(intval(Settings_controller::getOther("attendance_check_enable")));
        $strict = boolval(intval(Settings_controller::getOther("attendance_strict")));

        if (!Auth::isAdmin(App::$instance)
            && (!$this->isThisUserCanEdit($vks)
                || !VKSTimeAnalizator::isManipulatable($vks))
        ) {
            $this->error('no_manipulable');
        }
        //get vksdata

        if ($vks->is_simple) {
            App::$instance->MQ->setMessage("Упрощенные ВКС редактировать запрещено, можно только аннулировать");
            ST::redirectToRoute("Vks/show/" . $vks->id);
        }
        if ($vks->other_tb_required) {
            App::$instance->MQ->setMessage("Вкс для которых создана транзитная ВКС на ресурсах ЦА, нельзя редактировать, только аннулировать");
            ST::redirectToRoute("Vks/show/" . $vks->id);
        }

        $vks = $this->humanize($vks);
        if (!Auth::isAdmin(App::$instance) && !$vks->humanized->isEditable) {
            App::$instance->MQ->setMessage("Данную ВКС редактировать запрещено");
            ST::redirectToRoute("Vks/show/" . $vks->id);
        }


        $departments = Department::orderBy('prefix')->get();
        $initiators = Initiator::all();

        $this->fillCookieParticipants('vks_participants_edit', $vks);
//        vks_participants_edit


        if (Auth::isAdmin(App::$instance)) {
            $versSys = new VksVersion_controller();
            $this->render('vks/admin/edit', ['vks' => $vks, 'versions' => $versSys->getVersionsList($vks->id), 'departments' => $departments, 'initiators' => $initiators]);
        } else {

            $this->render('vks/edit-ws', compact('vks', 'departments', 'initiators', 'check', 'strict'));
        }

    }

    public function update($id)
    {
        Token::checkToken();
        $request = $this->request->request;

        $this->fillParticipants("vks_participants_edit", $this->request);
        Capsule::beginTransaction();
        $this->validator->validate([
            'Дата' => [$request->get('date'), 'required|date'],
            'Время начала' => [$request->get('start_time'), 'required'],
            'Время окончания' => [$request->get('end_time'), 'required'],
            'Название' => [$request->get('title'), 'required|max(255)'],
            'ФИО ответственного' => [$request->get('init_customer_fio'), 'required|max(255)'],
            'Почта ответственного' => [$request->get('init_customer_mail'), 'required|max(255)'],
            'Тел. ответственного' => [$request->get('init_customer_phone'), 'required|max(255)'],
            'Подразделение' => [$request->get('department'), 'required|int'],
            'Комментарий для Администратора' => [$request->get('comment_for_admin'), 'max(160)'],
            'Кол-во участников с рабочих мест (IP телефоны)' => [$request->get('in_place_participants_count'), 'int'],
            'Участники ВКС' => [$request->get('inner_participants'), 'array'],
        ]);
        //if no passes
        if (!$this->validator->passes()) {
            $this->backWithData($this->validator->errors()->all());
        }

        //any participants required
        if (!intval($request->get('in_place_participants_count')) && !count($request->get('inner_participants'))) {
            $this->backWithData('Вы не выбрали участников для ВКС');
        }

        $vks = Vks::full()->findOrFail($id);

        $vCSys = new VksVersion_controller();
        if ($vCSys->create($vks)) {
            if (!$request->get('ca_code')) $request->set('ca_code', Null);

            $vks->fill($request->all());
            $vks->is_private = $request->has('is_private') ? 1 : 0;
            $vks->record_required = $request->has('record_required') ? 1 : 0;
            $vks->start_date_time = $request->get('date') . " " . $request->get('start_time');
            $vks->end_date_time = $request->get('date') . " " . $request->get('end_time');

            $this->timeBarrier($vks);
            //recreate participants
            $this->createInnerOrPhoneParp($request->get("inner_participants"), $vks, true);
            //flush old connection code
            $vks->status = VKS_STATUS_PENDING;
            $vks->approved_by = Null;
            $vks->save();
        } else {
            $this->backWithData("Ошибка: Не удалось создать версию ВКС");
        }

        App::$instance->MQ->setMessage("Успешно отредактировано, вкс передана на согласование администраторам");
        App::$instance->log->logWrite(LOG_VKSWS_CREATED, "VKS " . ST::linkToVksPage($vks->id) . " edited by user " . App::$instance->user->login . "");
        Capsule::commit();
        //revoke all outlook requests requests
        OutlookCalendarRequest_controller::changeRequestTypeAndPutToResend($vks->id, OutlookCalendarRequest::REQUEST_TYPE_UPDATE);
        ST::redirectToRoute('vks/show/' . $vks->id);
    }

    public function adminUpdate($id)
    {
        Token::checkToken();
        $request = $this->request->request;
        $this->fillParticipants("vks_participants_edit", $this->request);

        Capsule::beginTransaction();
        $this->validator->validate([
            'Дата' => [$request->get('date'), 'required|date'],
            'Время начала' => [$request->get('start_time'), 'required'],
            'Время окончания' => [$request->get('end_time'), 'required'],
            'Название' => [$request->get('title'), 'required|max(255)'],
            'ФИО ответственного' => [$request->get('init_customer_fio'), 'required|max(255)'],
            'Почта ответственного' => [$request->get('init_customer_mail'), 'required|max(255)'],
            'Тел. ответственного' => [$request->get('init_customer_phone'), 'required|max(255)'],
            'Подразделение' => [$request->get('department'), 'required|int'],
            'Владелец' => [$request->get('owner_id'), 'required|int'],
            'Участники ВКС' => [$request->get('inner_participants'), 'array'],
            'Комментарий для Администратора' => [$request->get('comment_for_admin'), 'max(160)'],
            'Комментарий для Пользователя' => [$request->get('comment_for_user'), 'max(160)'],
            'Кол-во участников с рабочих мест (IP телефоны)' => [$request->get('in_place_participants_count'), 'int'],
        ]);

        if (!count($request->get('inner_participants')) && $request->get('in_place_participants_count') == 0) {
            $this->backWithData("В ВКС обязательно должны быть участники");
        }
        //if no passes
        if (!$this->validator->passes()) {
            $this->backWithData($this->validator->errors()->all());
        }

        //any participants required
        if (!intval($request->get('in_place_participants_count')) && !count($request->get('inner_participants'))) {
            $this->backWithData('Вы не выбрали участников для ВКС');
        }

        $vks = vks::full()->findOrFail($id);
        $vCSys = new VksVersion_controller();
//        dump($request);
//        die;
        if ($vCSys->create($vks)) {

            if (!$request->get('ca_code')) $request->set('ca_code', Null);

            $vks->fill($request->all());
            $vks->is_private = $request->has('is_private') ? 1 : 0;
            $vks->record_required = $request->has('record_required') ? 1 : 0;
            $vks->start_date_time = $request->get('date') . " " . $request->get('start_time');
            $vks->end_date_time = $request->get('date') . " " . $request->get('end_time');

            $this->timeBarrier($vks);
            //recreate participants
            $this->createInnerOrPhoneParp($request->get("inner_participants"), $vks, true);
            //check if user change participants list
            //flush old connection code
            $oldCode = ConnectionCode::where('vks_id', $id)->get();
            if (count($oldCode))
                foreach ($oldCode as $code)
                    $code->delete();
            if ($vks->status == VKS_STATUS_APPROVED) {
                if (count($request->get('code'))) {
                    foreach ($request->get('code') as $code)
                        ConnectionCode::create([
                            'vks_id' => $vks->id,
                            'value' => $code['value'],
                            'tip' => $code['tip']
                        ]);
                }
            }
            $vks->save();
        }

        App::$instance->MQ->setMessage("Успешно отредактировано");

        App::$instance->log->logWrite(LOG_VKSWS_CREATED, "VKS " . ST::linkToVksPage($vks->id) . " edited by admin " . App::$instance->user->login . "");

        Capsule::commit();

        if ($request->get('notify') == 1) {
            $vks = Vks::full()->find($vks->id);
            $this->humanize($vks);
            App::$instance->callService('vks_report_builder')->sendEditedMail($vks, false);
            App::$instance->MQ->setMessage("Успешно отредактировано, пользователю отправлено уведомление");
        }

        //revoke all outlook requests requests
        OutlookCalendarRequest_controller::changeRequestTypeAndPutToResend($vks->id, OutlookCalendarRequest::REQUEST_TYPE_UPDATE);

        ST::redirectToRoute("Vks/show/" . $vks->id);

    }

    public function apiGet($vksId)
    {
        $cacheName = App::$instance->tbId . ".vks.controller.api.get.{$vksId}";
        $vks = App::$instance->cache->get($cacheName);

        if (!$vks) {
            $vks = Vks::full()->findOrFail($vksId);
            $cachedObj = new CachedObject($vks, ['tag.' . $cacheName]);
            App::$instance->cache->set($cacheName, $cachedObj, 3600 * 24 * 3);
        }
        $caVks = Null;
        if ($vks->link_ca_vks_id) {
            if ($vks->link_ca_vks_type == 0) {
                $caVks = Curl::get(ST::routeToCaApi('getVksWasById/' . $vks->link_ca_vks_id));
            } else {
                $caVks = Curl::get(ST::routeToCaApi('getVksNsById/' . $vks->link_ca_vks_id));
            }
            $caVks = json_decode($caVks);
            if ($caVks->status == 200) {
                $caVks = $caVks->data;
            } else {
                $caVks = Null;
            }
        }

        if ($caVks) {
            $caVks->startTime = date_create($caVks->start_date_time)->format("H:i");
            $caVks->endTime = date_create($caVks->end_date_time)->format("H:i");
            if ($vks->other_tb_required)
                $vks->tb_parp = isset($caVks->insideParp) ? $caVks->insideParp : $caVks->participants;
            $vks->ca_linked_vks = $caVks;
        }

        $vks = $this->humanize($vks);
        print $vks->toJson();

    }

    protected function createInnerOrPhoneParp($parpList, Vks $vks, $flush = false)
    {
        //init attendance old controller
        if ($flush) {
            Vks_participant::where("vks_id", $vks->id)->delete();
        }
        $att = new AttendanceNew_controller();
        $check = boolval(intval(Settings_controller::getOther("attendance_check_enable")));
        $strict = boolval(intval(Settings_controller::getOther("attendance_strict")));
        $compiledData = array();
        foreach ($parpList as $parp) {
            if ($check && $strict && !$parp->type && !Auth::isAdmin(App::$instance)) {
                if ($att->isFree(intval($parp->id), $vks->start_date_time, $vks->end_date_time, [$vks->id])) {
                    $compiledData[] = array(
                        'vks_id' => $vks->id,
                        'attendance_id' => intval($parp->id),
                    );
                } else {
                    $this->backWithData("Ошибка в данном режиме работы нельзя создать ВКС с участниками которые уже участвуют в других ВКС, выберите участников заново и повторите отправку");
                }
            } else {
                if (isset($parp->id))
                    $compiledData[] = array(
                        'vks_id' => $vks->id,
                        'attendance_id' => intval($parp->id),
                    );
            }
        }
        Vks_participant::insert($compiledData);
    }

    protected function isVksInPastTime(Vks $vksObject)
    {
        $now = date_create()->getTimestamp();
        //check start time
        $vksStartTime = $vksObject->start_date_time->getTimestamp();
        //check end time
        $vksEndTime = $vksObject->end_date_time->getTimestamp();

        return (($vksStartTime - $now) || ($vksEndTime - $now)) < 0 ? true : false;
    }

    protected function isThisUserCanEdit(Vks $vks)
    {
        Auth::isLoggedOrDie(App::$instance);
        return ($vks->owner_id === App::$instance->user->id || Auth::isAdmin(App::$instance)) ? true : false;
    }

    public function humanize(Vks &$vks)
    {
        $vks->humanized = new stdClass();
        $vks->humanized->date = $vks->date->format("d.m.Y");
        $vks->humanized->startTime = $vks->start_date_time->format("H:i");
        $vks->humanized->endTime = $vks->end_date_time->format("H:i");

        $vks->humanized->isEditable = $this->isEditable($vks);
        $vks->humanized->isCloneable = $this->isCloneable($vks);
        $vks->humanized->isDeletable = $this->isDeletable($vks);
        $vks->humanized->isManipulatable = VKSTimeAnalizator::isManipulatable($vks);
        $vks->humanized->isCodePublicable = $this->isCodePublicable($vks);
        $vks->humanized->isOutlookable = $this->isOutlookable($vks);
        $vks->humanized->isTechSupportable = $this->isTechSupportable($vks);

        $vks->humanized->im_owner = Auth::isLogged(App::$instance) && $vks->owner_id == App::$instance->user->id || Auth::isAdmin(App::$instance) ? 1 : 0;
        //define status
        switch ($vks->status) {
            case (VKS_STATUS_PENDING):
                $vks->humanized->status = 'На согласовании';
                $vks->humanized->status_label = "<span class='label label-info label-as-badge' title='Согласование'>Согласование</span>";
                break;
            case (VKS_STATUS_APPROVED):
                $vks->humanized->status = 'Утверждена';
                $vks->humanized->status_label = "<span class='label label-success label-as-badge' title='Утверждена'>Утверждена</span>";
                break;
            case (VKS_STATUS_DECLINE):
                $vks->humanized->status = 'Отклонена';
                $vks->humanized->status_label = "<span class='label label-warning label-as-badge' title='Отказ'>Отказ</span>";
                break;
            case (VKS_STATUS_RESERVED_NO_DATA):
                $vks->humanized->status = 'Зарезервированная служебная ВКС';
                $vks->humanized->status_label = "<span class='label label-default label-as-badge' title='Аннулирована'>{$vks->humanized->status}</span>";
                break;
            case (VKS_STATUS_DROP_BY_USER):
                $vks->humanized->status = 'Удалена';
                $vks->humanized->status_label = "<span class='label label-danger label-as-badge' title='Удалена владельцем'>{$vks->humanized->status}</span>";
                break;
            case (VKS_STATUS_DELETED):
                $vks->humanized->status = 'Аннулирована';
                $vks->humanized->status_label = "<span class='label label-danger label-as-badge' title='Аннулирована администратором'>{$vks->humanized->status}</span>";
                break;
            case (VKS_STATUS_VKS_BATCH_INPUT):
                $vks->humanized->status = 'ВКС Массовой вставки';
                $vks->humanized->status_label = "<span class='label label-default label-as-badge' title='Аннулирована'>{$vks->humanized->status}</span>";
                break;
            default:
                $vks->humanized->status = 'Не определено';
                $vks->humanized->status_label = "<span class='label label-default label-as-badge' title='Не определено'>{$vks->humanized->status}</span>";
                break;
        }

        if ($vks->status != VKS_STATUS_APPROVED) {
            $vks->setRelation('connection_codes', null);
        }

        if (count($vks->connection_codes)) {
            if ($vks->is_private) {
                foreach ($vks->connection_codes as $code) {
                    @$code->value_raw = $code->value;
                    if ($vks->humanized->im_owner || Auth::isAdmin(App::$instance)) {
                        @$code->value = (' <span class="glyphicon glyphicon-eye-close text-danger" title="код скрыт"></span> ') . $code->value;
                    } else {
                        $code->value = 'Код скрыт владельцем';
                        $code->tip = null;
                    }
                }
            } else {
                if (count($vks->connection_codes)) {

                    foreach ($vks->connection_codes as $code) {
                        @$code->value_raw = $code->value;
                        if ($vks->humanized->im_owner || Auth::isAdmin(App::$instance)) {
                            @$code->value = (' <span class="glyphicon glyphicon-eye-open text-success" title="код виден всем"></span> ') . $code->value;
                        }
                    }
                }
            }
        }

        $vks->humanized->presentation = $vks->presentation ? "Да" : "Нет";
        return $vks;
    }

    public function annulate($vksId)
    {
        try {
            $vks = Vks::findorFail($vksId);
        } catch (Exception $e) {
            $this->error("404", $e->getMessage());
        }

        $this->humanize($vks);
        $this->render('Vks/admin/annulate', compact('vks'));
        //revoke all outlook requests requests
        OutlookCalendarRequest_controller::changeRequestTypeAndPutToResend($vks->id, OutlookCalendarRequest::REQUEST_TYPE_DELETE);

    }

    public function cancel($id) //by user
    {
        try {
            $vks = Vks::findorFail($id);
        } catch (Exception $e) {
            $this->error("404", $e->getMessage());
        }

        if (!Auth::isAdmin(App::$instance)
            && !$this->isThisUserCanEdit($vks)
            && !VKSTimeAnalizator::isManipulatable($vks)
        ) {
            $this->error("500", 'Манипулирование этой ВКС запрещено');
        }
        $vks->status = VKS_STATUS_DROP_BY_USER;
        $vks->save();

//        NoticeObs_controller::put("Пользователь " . App::$instance->user->login . " аннулировал ВКС " . $vks->id);

        App::$instance->MQ->setMessage("Успешно аннулировано");

        App::$instance->log->logWrite(LOG_VKSWS_CREATED, "VKS " . $vks->id . " annulate by user " . App::$instance->user->login . "");

        Capsule::commit();

        //revoke all outlook requests requests
        OutlookCalendarRequest_controller::changeRequestTypeAndPutToResend($vks->id, OutlookCalendarRequest::REQUEST_TYPE_DELETE);

        ST::redirectToRoute('Index/index');


    }

    public function dropByAdmin($id)
    {
        try {
            $vks = Vks::findorFail($id);
        } catch (Exception $e) {
            $this->error("404", $e->getMessage());
        }

        $request = $this->request->request;
        $this->validator->validate([
            'Комментарий для пользователя' => [$request->get('comment_for_user'), 'max(160)'],
        ]);

        //if no passes
        if (!$this->validator->passes()) {
            $this->backWithData($this->validator->errors()->all());
        }

        if ($vks->other_tb_required) {
            if ($vks->other_tb_required && $vks->link_ca_vks_type == 1 && isset($vks->link_ca_vks_id)) {
                //pull from CA
                $caVks = CAVksNoSupport::findOrFail($vks->link_ca_vks_id);
                if ($caVks->status != VKS_STATUS_TRANSPORT_FOR_TB) {
                    App::$instance->MQ->setMessage("Возникла ошибка, ВКС в ТБ {$id} ссылается на ВКС в ЦА {$vks->link_ca_vks_id}, однако ВКС в ЦА, не имеет транспортного статуса, обратитесь к разработчику");
                    ST::redirect('back');
                }
                $caVks->delete();
                App::$instance->log->logWrite(LOG_VKSWS_CREATED, "VKS + transport" . $vks->id . "({$caVks->id}) deleted by admin " . App::$instance->user->login . "");
            }
        } else {
            App::$instance->log->logWrite(LOG_VKSWS_CREATED, "VKS " . $vks->id . " deleted by admin " . App::$instance->user->login . "");
        }

        $vks->status = VKS_STATUS_DELETED;
        $vks->comment_for_user = $request->get('comment_for_user');
        $vks->approved_by = App::$instance->user->id;
        $vks->save();

        App::$instance->MQ->setMessage("ВКС успешно аннулирована");

        Capsule::commit();

        if ($request->has('notificate')) {
            $vks = Vks::full()->find($vks->id);
            $this->humanize($vks);
            App::$instance->callService('vks_report_builder')->sendDeleteMail($vks, false);
        }

        //revoke all outlook requests requests
        OutlookCalendarRequest_controller::changeRequestTypeAndPutToResend($vks->id, OutlookCalendarRequest::REQUEST_TYPE_DELETE);

        ST::redirectToRoute('Index/index');


    } //by admin

    function countNotApprovedVks()
    {
        Auth::isAdminOrDie(App::$instance);

        return Vks::notApproved()->count();

    }

    function notApproved()
    {
        Auth::isAdminOrDie(App::$instance);
        $vcc = new VksVersion_controller();
        $vksList = Vks::notApproved()->take($this->getQlimit(30))
            ->skip($this->getQOffset())
            ->orderBy($this->getQOrder(), $this->getQVector())
            ->full()
            ->get();
        foreach ($vksList as $vks) {
            $this->humanize($vks);
            $vksLasVersion = $vcc->pullLastVersion($vks->id);
            $vks->again = $vksLasVersion && ($vks->owner->id == $vksLasVersion->changed_by) ? true : false;
        }
        $recordsCount = Vks::notApproved()->count();
        //pages
        $pages = RenderEngine::makePagination($recordsCount, $this->getQlimit(30), 'route');

        $this->render("vksSubmissions/index", compact('vksList', 'pages'));
    }

    function showNaVks($id)
    {

        $versionCtrl = new VksVersion_controller();
        $load = new Load_controller();
        $sc = new Settings_controller();
        try {
            $vks = Vks::NotSimple()->full()->findOrFail($id);
        } catch (Exception $e) {
            $this->error('404', $e->getMessage());
        }

        $vks = $this->humanize($vks);

        $att = new AttendanceNew_controller();

        $check = boolval(intval(Settings_controller::getOther("attendance_check_enable")));

        foreach ($vks->participants as $parp) {
            if ($check) {
                $parp->free = $att->isFree($parp->id, $vks->start_date_time, $vks->end_date_time, [$vks->id]);
            } else {
                $parp->free = true;
            }
        }

        if (!self::isVksCanBeApproved($vks))
            $this->error('500', 'Открыть экран согласования по этой ВКС невозможно, переведите эту ВКС в статус "на согласовании" и попробуйте еще раз');

        $vks->isPassebByCapacity = $load->isPassedByCapacity($vks->start_date_time, $vks->end_date_time, $this->countParticipants($vks->id), 0);

        $caVks = Null;
        if ($vks->link_ca_vks_id) {
            if ($vks->link_ca_vks_type == 0) {
                $caVks = Curl::get(ST::routeToCaApi('getVksWasById/' . $vks->link_ca_vks_id));
            } else {
                $caVks = Curl::get(ST::routeToCaApi('getVksNsById/' . $vks->link_ca_vks_id));
            }
            $caVks = json_decode($caVks);
            if ($caVks->status == 200) {
                $caVks = $caVks->data;
            } else {
                $caVks = Null;
            }
        }
        $codes = $sc->getCodeDelivery(true);
        $versions = $versionCtrl->getVersionsList($vks->id);
        $last_version = null;
        if (count($versions))
            $last_version = $versionCtrl->pullVersion($vks->id, $versionCtrl->findLastVersion($vks->id));


        if ($vks->stack) {
            foreach ($vks->stack->vkses as $stVks) {
                $this->humanize($stVks);
                $stVks->connection_codes = $stVks->connection_codes()->get();
            }
        }

        if ($vks->other_tb_required) {
            $vks->CaInPlaceParticipantCount;
            $vks->CaIdParticipants;
        }
        $codesFiltrated = array();
        $ca_pool_codes = App::$instance->callService("vks_ca_negotiator")->askForPool();

        $currentVksStart = clone($vks->start_date_time);
        $currentVksEnd = clone($vks->end_date_time);

        if (count($ca_pool_codes)) {
            $ca_transport_vkses = App::$instance->callService("vks_ca_negotiator")->aksTransportVksInPeriod($currentVksStart, $currentVksEnd, $ca_pool_codes);

            foreach ($ca_pool_codes as $code) {
                $codesFiltrated[$code] = array();
                if (count($ca_transport_vkses))
                    foreach ($ca_transport_vkses as $ca_tr_vks) {
                        if ($code == $ca_tr_vks->v_room_num)
                            $codesFiltrated[$code][] = $ca_tr_vks;
                    }
            }
        }

        $graph = $load->pullLoadDataForJs($vks->humanized->date);

        $this->render('vksSubmissions/approvePage', compact('vks', 'graphUrl', 'caVks', 'versions', 'codes', 'last_version', 'graph', 'codesFiltrated'));
    }

    function process($vksId)
    {

        Token::checkToken();
        $connCtrl = new ConnectionCode_controller();
        try {
            $vks = Vks::findOrFail($vksId);
        } catch (Exception $e) {
            $this->error('404');
        }

        if (!self::isVksCanBeApproved($vks))
            $this->error('500', "Эта ВКС не может быть согласована, т.к. не имеет соответвующего статуса");

        $request = $this->request->request;

        //validate
        $this->validator->validate([
            'Комментарий для пользователя' => [$request->get('comment_for_user'), 'max(160)'],
            'Статус ВКС' => [$request->get('status'), 'required|between(1,2)'],
        ]);
        //if no passes
        if (!$this->validator->passes())
            $this->backWithData($this->validator->errors()->all());

        /*
         * if declined
         */
        if ($request->get('status') == VKS::APPROVE_STATUS_DECLINED) {
            $backMessage = "<p>Вкс #{$vks->id}, отказ в проведении</p>";
            $vks->comment_for_user = $request->get('comment_for_user');
            $vks->status = $request->get('status');
            $vks->approved_by = App::$instance->user->id;
            $vks->save();

            //delete related VKS in CA
            if ($vks->other_tb_required && $vks->link_ca_vks_type == 1 && isset($vks->link_ca_vks_id)) {
                $caVks = CAVksNoSupport::find($vks->link_ca_vks_id);
                if ($caVks->status == VKS_STATUS_TRANSPORT_FOR_TB) {
                    App::$instance->log->logWrite(LOG_OTHER_EVENTS, "Транспортная ВКС " . $caVks->id . " удалена");
                    $backMessage .= "<p>Транспортная ВКС " . $caVks->id . " удалена</p>";
                    $caVks->delete();
                }
            }

            $vks = Vks::full()->find($vks->id);
            $this->humanize($vks);
            App::$instance->callService('vks_report_builder')->sendDeclineMail($vks, false);
            //revoke all outlook requests requests
            OutlookCalendarRequest_controller::changeRequestTypeAndPutToResend($vks->id, OutlookCalendarRequest::REQUEST_TYPE_DELETE);

            if (count($vks->tech_support_requests))
                foreach ($vks->tech_support_requests as $request) {
                    if ($request->status == TechSupportRequest::STATUS_WAIT_VKS_DECISION) {
                        $request->status = TechSupportRequest::STATUS_VKS_REFUSED;
                        $request->save();
                    }
                }
            App::$instance->MQ->setMessage($backMessage);
            ST::redirect(ST::route("Vks/notApproved"));
        }

        if (!$request->has('no-codes') && !count($request->get('code')))
            $this->error('500', 'bad params combined');

        /*
         * if no codes
         *
         */
        if ($request->has('no-codes')) {
            $backMessage = "<p>Вкс #{$vks->id} согласована без кода подключения</p>";
            //delete old
            $oldCodes = ConnectionCode::where('vks_id', $vks->id)->get();
            if (count($oldCodes))
                foreach ($oldCodes as $oldCode)
                    $oldCode->delete();

            $vks->comment_for_user = $request->get('comment_for_user');
            $vks->status = $request->get('status');
            //set admin id
            $vks->approved_by = App::$instance->user->id;
            $vks->save();
            //vks ca process
            $transportVksMessage = App::$instance->callService('vks_ca_negotiator')->transportVksProcessor($this->request, $vks);
            if ($transportVksMessage->getStatusCode() != Response::HTTP_OK) {
                $this->backWithData($transportVksMessage->getContent());
            }

            $vks = Vks::full()->find($vks->id);
            $this->humanize($vks);
            App::$instance->callService('vks_report_builder')->sendReportMail($vks, false);
            App::$instance->MQ->setMessage($backMessage . $transportVksMessage->getContent());
            ST::redirect(ST::route("Vks/notApproved"));

        }

        /*
         * standart process
         */

        //validate codes
        if (!count($request->get('code')))
            $this->error('500', 'no code was passed, error!');

        foreach ($request->get('code') as $code) {
            $this->validator->validate([
                'Префикс' => [$code['prefix'], 'required|max(160)'],
                'Постфикс' => [$code['postfix'], 'required|max(4)'],
                'Подсказка' => [$code['tip'], 'max(255)'],
            ]);
            //if no passes
            if (!$this->validator->passes())
                $this->backWithData($this->validator->errors()->all());
        }

        Capsule::beginTransaction();
        $oldCodes = ConnectionCode::where('vks_id', $vks->id)->get();
        if (count($oldCodes))
            foreach ($oldCodes as $oldCode)
                $oldCode->delete();

        foreach ($request->get('code') as $code) {
            $compiledCode = $code['prefix'] . $code['postfix'];

            if ($request->has('no-check')) {
                $checkVks = false;
            } else {
                $checkVks = $connCtrl->isCodeInUse($code['postfix'], $vks->start_date_time, $vks->end_date_time);
            }
            if ($checkVks) {
                App::$instance->MQ->setMessage('Ошибка: Код ' . $code['postfix'] . ' уже используется в ' . ST::linkToVksPage($checkVks->id), 'danger');
                ST::redirect("back");
            } else {
                $newCodes[] = ConnectionCode::create([
                    'vks_id' => $vks->id,
                    'value' => $compiledCode,
                    'tip' => $code['tip']
                ]);
            }
        }

        $vks->comment_for_user = $request->get('comment_for_user');
        $vks->status = $request->get('status');
        //set admin id
        $vks->approved_by = App::$instance->user->id;
        $vks->save();
        //vks ca process
        $transportVksMessage = App::$instance->callService('vks_ca_negotiator')->transportVksProcessor($this->request, $vks);
        if ($transportVksMessage->getStatusCode() != Response::HTTP_OK) {
            $this->backWithData($transportVksMessage->getContent());
        }

        Capsule::commit();

        //now send mails

        //send mail to user, if it not anon user
        if ($vks->status == VKS_STATUS_APPROVED) {
            $vks = Vks::full()->find($vks->id);
            $this->humanize($vks);
            App::$instance->callService('vks_report_builder')->sendReportMail($vks, false);
            //revoke all outlook requests requests
            OutlookCalendarRequest_controller::changeRequestTypeAndPutToResend($vks->id, OutlookCalendarRequest::REQUEST_TYPE_UPDATE);
        }

        if ($vks->status == VKS_STATUS_APPROVED) {
            App::$instance->MQ->setMessage("<p>ВКС {$vks->id} согласована </p>" . $transportVksMessage->getContent());
            if (count($vks->tech_support_requests))
                foreach ($vks->tech_support_requests as $request) {
                    if ($request->status == TechSupportRequest::STATUS_WAIT_VKS_DECISION) {
                        $request->status = TechSupportRequest::STATUS_READY_FOR_SEND;
                        $request->save();
                    }
                }
        } else {
            App::$instance->MQ->setMessage("Отказ по ВКС {$vks->id}");
            if (count($vks->tech_support_requests))
                foreach ($vks->tech_support_requests as $request) {
                    if ($request->status == TechSupportRequest::STATUS_WAIT_VKS_DECISION) {
                        $request->status = TechSupportRequest::STATUS_VKS_REFUSED;
                        $request->save();
                    }
                }
        }

        ST::redirect(ST::route("Vks/notApproved"));
    }

    public function timeBarrier(Vks $vks)
    {
        if ($vks->start_date_time == $vks->end_date_time) {
            $this->backWithData('Ошибка: ВКС не может начаться и тут же закончиться');
        }
        if ($vks->start_date_time > $vks->end_date_time) {
            $this->backWithData('Ошибка: ВКС не может начаться раньше времени окончания');
        }
    }

    static function isVksCanBeApproved(Vks $vks)
    {
        return (in_array($vks->status, [VKS_STATUS_PENDING])) ? true : false;
    }

    public static function countParticipants($id)
    {
        $vks = Vks::findOrFail($id);
        return $vks->participants_count;
    }

    public function parseVksSavedResult(array $result, $message = "Cоздана ВКС")
    {
        $resultStr = '';
        $resultStr .= "{$message} #{$result['vksId']}";
        if (isset($result['vksLCode']) && strlen($result['vksLCode']) > 0)
            $resultStr .= ", Код подключения: {$result['vksLCode']}";
        if (isset($result['emptyCode']))
            $resultStr .= ", Код подключения: не требуется или не задан администратором";
        if (isset($result['relations']) && $result['relations'] != 0)
            $resultStr .= ", отправлено приглашений секретарям " . $result['relations'] . "";
        if (@$result['techMails'] && isset($result['techMails']) && count(@$result['techMails']) > 0)
            $resultStr .= ", отправлена заявка на тех. поддержку: " . implode(", ", $result['techMails']) . "";
        //if approved, give link
        $resultStr .= ", ссылка на страницу ВКС: <a class='btn btn-default btn-sm' target='_blank' href='" . ST::route("Vks/show/" . $result['vksId']) . "'><span class='glyphicon glyphicon-link'></span> нажмите здесь</a>";


        return $resultStr;
    }

    public function  joinCaCreate($referral = false)
    {
        Token::checkToken();
        $request = $this->request->request;

        if ($referral)
            $request->set('referrer', $referral);

        $this->validator->validate([
            'Приглашение от ЦА' => [$request->get('referrer'), 'required'],
        ]);
        //if no passes
        if (!$this->validator->passes()) {
            App::$instance->MQ->setMessage($this->validator->errors()->all());
            ST::redirect("back");
        }
        //check codes
        $caWS = CAVks::where('referral', $request->get('referrer'))->with('owner', 'connection_codes', 'insideParp', 'phoneParp', 'outsideParp')->first();
        $caNS = CAVksNoSupport::where('referral', $request->get('referrer'))->first();

        if (!$caWS && !$caNS) {
            App::$instance->MQ->setMessage("Приглашение {$request->get('referrer')}  не действительно");
            ST::redirectToRoute("Vks/select");
        }

        $caVks = $caWS ? $caWS : $caNS;
        $this->convertToLocalTime($caVks);
        $valid = true;
        if (isset($caVks->status)) {
            if (!in_array($caVks->status, [VKS_STATUS_APPROVED, VKS_STATUS_TRANSPORT_FOR_TB])) {
                $valid = false;
            }
        } else {
            if (!in_array($caVks->status, [VKS_STATUS_APPROVED, VKS_STATUS_TRANSPORT_FOR_TB])) {
                $valid = false;
            }
        }
        if (!$valid) {
            App::$instance->MQ->setMessage("Приглашение {$request->get('referrer')}  не действительно, в ЦА поменяли статус этой ВКС и теперь она недоступна");
            ST::redirectToRoute("Vks/select");
        }

        //my tb is can be accepted?
        $participants = isset($caVks->insideParp) ? $caVks->insideParp : $caVks->participants;
        $flag = false;
        foreach ($participants as $parp) {
            if ($parp->attendance_id == App::$instance->tbId)
                $flag = true;
        }

        $referral = $request->get('referrer');

        if ($this->isAlreadyEnd(date_create($caVks->end_date_time))) {
            App::$instance->MQ->setMessage("Приглашение {$request->get('referrer')}  не действительно, ВКС уже закончилась", 'danger');
            ST::redirectToRoute("Vks/select");
        }

        if (!$flag && ($caVks->local->start_date_time->getTimestamp() - date_create()->getTimestamp() < 1800)) {
            App::$instance->MQ->setMessage("Ваш ТБ не заявлен на эту ВКС и до начала ВКС осталось менее 30 минут, заявиться на такую ВКС уже не получится, обратитесь к администратору системы", 'danger');
            ST::redirectToRoute("Vks/select");
        }

        $departments = Department::orderBy('prefix')->get();

        $vks = ST::lookAtBackPack();
        $vks = $vks->request;
        if (!$vks->has('inner_participants') && !count($vks->get('inner_participants'))) {
            LocalStorage_controller::staticRemove('vks_participants_create');
        }

        $available_points = Attendance::techSupportable()->get()->toArray();

        array_walk($available_points, function (&$e) {
            $e['selectable'] = true;
        });

        $this->render('vks/joinCa', compact('vks', 'caVks', 'departments', 'referral', 'flag', 'available_points'));
    }

    public function  joinCaStore($referral)
    {

        $request = $this->request->request;
        $this->fillParticipants("vks_participants_create", $this->request);
        $report = new VksReport($request);


        $this->validator->validate([

            'ФИО ответственного' => [$request->get('init_customer_fio'), 'required|max(255)'],
            'Почта ответственного' => [$request->get('init_customer_mail'), 'required|max(255)'],
            'Тел. ответственного' => [$request->get('init_customer_phone'), 'required|max(255)'],
            'Подразделение' => [$request->get('department'), 'required|int'],
            'Кол-во участников с рабочих мест (IP телефоны)' => [$request->get('in_place_participants_count'), 'int'],
            'Участники ВКС' => [$request->get('inner_participants'), 'array'],
            'Точка для технической поддержки' => [$request->get('tech_support_att_id'), "int|attendance_is_tech_supportable"],
            'Комментарий для Тех. поддержки' => [$request->get('user_message'), 'max(255)'],
            'Комментарий для Администратора' => [$request->get('comment_for_admin'), 'max(160)'],
        ]);
        //if no passes
        if (!$this->validator->passes()) {
            $this->backWithData($this->validator->errors()->all());
        }

        //any participants required
        if (!intval($request->get('in_place_participants_count')) && !count($request->get('inner_participants'))) {
            $this->backWithData('Вы не выбрали участников для ВКС');
        }

        Capsule::beginTransaction();

        $caWS = CAVks::where('referral', $referral)->with('connection_codes')->first();
        $caNS = CAVksNoSupport::where('referral', $referral)->first();

        if (!$caWS && !$caNS) {
            $this->backWithData("Приглашение {$request->get('referrer')}  не действительно");
        }
        $caVKSType = $caWS ? VKS_WAS : VKS_NS;
        $caVks = $caWS ? $caWS : $caNS;
        $this->convertToLocalTime($caVks);

        $vks = new Vks();
        $vks->is_private = $request->has('is_private') ? 1 : 0;
        $vks->record_required = $request->has('record_required') ? 1 : 0;
        $vks->title = $caVks->title;

        $vks->date = $caVks->local->start_date_time->format("Y-m-d");
        $vks->start_date_time = $caVks->local->start_date_time;
        $vks->end_date_time = $caVks->local->end_date_time;;

        $vks->presentation = isset($caVks->presentation) ? $caVks->presentation : 0;
        $vks->department = $request->get('department');
        $vks->initiator = 1; //always CA
        $vks->init_customer_fio = $request->get('init_customer_fio');
        $vks->init_customer_phone = $request->get('init_customer_phone');
        $vks->init_customer_mail = $request->get('init_customer_mail');
        $vks->comment_for_admin = $request->get('comment_for_admin');
        $vks->link_ca_vks_id = $caVks->id;
        $vks->link_ca_vks_type = $caVKSType;
        $vks->owner_id = App::$instance->user->id;
        $vks->from_ip = App::$instance->user->ip;
        $vks->ca_code = isset($caVks->connectionCode->value) ? $caVks->connectionCode->value : $caVks->v_room_num;
        $vks->in_place_participants_count = $request->get('in_place_participants_count');

//        die(dump($vks));
        $vks->save();

        if ($request->get('tech_support_required'))
            TechSupportRequest::create(array(
                'att_id' => $request->get('tech_support_att_id'),
                'vks_id' => $vks->id,
                'owner_id' => App::$instance->user->id,
                'user_message' => $request->get('user_message'),
                'status' => TechSupportRequest::STATUS_WAIT_VKS_DECISION
            ));

        if (!Auth::isAdmin(App::$instance)) {
            if (self::isVksInPastTime($vks)) throw new LogicException('bad vks date, it on past time');
        }
        //create participants
        $this->createInnerOrPhoneParp($request->get('inner_participants'), $vks);

        App::$instance->log->logWrite(LOG_VKSWS_CREATED, "VKS " . ST::linkToVksPage($vks->id) . " Created");

        Capsule::commit();
        //refill for report
        $this->request->request->set("date", $vks->date->format("d.m.Y"));
        $this->request->request->set("start_time", $vks->start_date_time->format("H:i"));
        $this->request->request->set("end_time", $vks->end_date_time->format("H:i"));
        $report->setObject($vks);
        $report->setResult(true);
        $result[] = $report;
        $_SESSION['savedResult_' . App::$instance->main->appkey] = $result;
        ST::redirectToRoute('vks/checkout');
    }

    public function mark($vksId)
    {
        Auth::isAdminOrDie(App::$instance);
        try {
            $vks = Vks::findorFail($vksId);
        } catch (Exception $e) {
            $this->error("404", $e->getMessage());
        }
        $vks->flag = 1;
        $vks->save();
        App::$instance->MQ->setMessage("Вкс {$vks->id} отмечена флагом");
        ST::redirect('back');
    }

    public function unmark($vksId)
    {
        Auth::isAdminOrDie(App::$instance);
        try {
            $vks = Vks::findorFail($vksId);
        } catch (Exception $e) {
            $this->error("404", $e->getMessage());
        }
        $vks->flag = 0;
        $vks->save();
        App::$instance->MQ->setMessage("Вкс {$vks->id}, флаг снят");
        ST::redirect('back');
    }

    public function hasLocalVks($caVksId)
    {
        $result = Vks::where('link_ca_vks_id', $caVksId)->count();
        if (ST::isAjaxRequest()) {
            print json_encode($result ? ['response' => 1] : ['response' => 0]);
        } else {
            return $result;
        }
    }

    public function publicStatusChange($vksId)
    {
        try {
            $vks = Vks::findorFail($vksId);
        } catch (Exception $e) {
            $this->error("404", $e->getMessage());
        }

        if (!$this->isThisUserCanEdit($vks))
            $this->error('403');

        $vks->is_private = $vks->is_private ? 0 : 1;
        $vks->save();
        $m = $vks->is_private ? 'Скрыт' : 'Виден всем';
        App::$instance->MQ->setMessage("Вкс {$vks->id}, видимость кода изменена, теперь код - " . $m);

        OutlookCalendarRequest_controller::changeRequestTypeAndPutToResend($vks->id, OutlookCalendarRequest::REQUEST_TYPE_UPDATE);

        ST::redirect('back');
    }

    public function showLocalVks($caVksId)
    {
        Auth::isAdminOrDie(App::$instance);
        $vkses = Vks::with('participants')->where('link_ca_vks_id', $caVksId)->get();
        foreach ($vkses as $vks) {
            $this->humanize($vks);
        }
        $this->render('Vks/showLocalRelativeToCa', compact('vkses', 'caVksId'));
    }

    private function inPast(DateTime $vksStartDate)
    {
        return date_create() > $vksStartDate->setTimezone(new DateTimeZone(App::$instance->opt->timezone)) ? true : false;
    }

    private function isAlreadyEnd(DateTime $vksEndDate)
    {
        return date_create() > $vksEndDate->setTimezone(new DateTimeZone(App::$instance->opt->timezone)) ? true : false;
    }

    private function isEditable(Vks $vksObject)
    {
        if (!Auth::isLogged(App::$instance)) {
            return false;
        }

        if (Auth::isAdmin(App::$instance)
            && !$vksObject->is_simple
            && !$vksObject->other_tb_required
            && !$this->inPast($vksObject->start_date_time)
            && !$vksObject->link_ca_vks_id
        ) {
            return true;
        }
        if (!Auth::isLogged(App::$instance)
            || $vksObject->is_simple
            || $vksObject->status != VKS_STATUS_APPROVED
            || $vksObject->other_tb_required
            || $this->inPast($vksObject->start_date_time)
            || $vksObject->owner_id != App::$instance->user->id
            || $vksObject->link_ca_vks_id

        ) {
            return false;
        } else {
            return true;
        }
    }

    private function isDeletable(Vks $vksObject)
    {

        $result = $this->isEditable($vksObject);

        if (!Auth::isLogged(App::$instance)) {
            return false;
        }

        if (Auth::isAdmin(App::$instance)
            && !$this->inPast($vksObject->start_date_time)
            && (in_array($vksObject->status, array(VKS_STATUS_APPROVED, VKS_STATUS_PENDING)))
        ) {
            return true;
        }

        if (!$this->inPast($vksObject->start_date_time)
            && ($vksObject->status == VKS_STATUS_APPROVED)
            && $vksObject->owner_id == App::$instance->user->id
        ) {
            $result = true;
        }
        return $result;
    }

    private function isCloneable(Vks $vksObject)
    {
        if (!Auth::isLogged(App::$instance)
            || $vksObject->is_simple
            || ($vksObject->link_ca_vks_id
                && !$vksObject->other_tb_required
//                || $vksObject->owner_id != App::$instance->user->id
            )
        ) {
            return false;
        } else {
            return true;
        }
    }

    private function isOutlookable(Vks $vksObject)
    {

        if (Auth::isLogged(App::$instance)
            && $vksObject->end_date_time > date_create()
            && $vksObject->status == VKS_STATUS_APPROVED
        ) {

            return true;
        } else {

            return false;
        }

    }

    private function isTechSupportable(Vks $vksObject)
    {
        if (Auth::isLogged(App::$instance)
            && $vksObject->end_date_time > date_create()
            && in_array($vksObject->status, array(VKS_STATUS_APPROVED, VKS_STATUS_PENDING))
            && !$vksObject->is_simple
        ) {
            return true;
        } else {

            return false;
        }
    }


    private function isCodePublicable(Vks $vksObject)
    {
        if (Auth::isAdmin(App::$instance)
            && $vksObject->start_date_time->setTime(0, 0) >= date_create()->setTime(0, 0)
        ) {
            return true;
        }

        if (Auth::isLogged(App::$instance)
            && $vksObject->status == VKS_STATUS_APPROVED
            && $vksObject->start_date_time->setTime(0, 0) >= date_create()->setTime(0, 0)
            && $vksObject->owner_id == App::$instance->user->id
        ) {
            return true;
        } else {
            return false;
        }
    }

    static public function convertToLocalTime($vksObject)
    {
        $vksObject->local = new stdClass();
        $vksObject->local->start_date_time =
            date_create($vksObject->start_date_time, new DateTimeZone(App::$instance->opt->ca_timezone))
                ->setTimezone(new DateTimeZone(App::$instance->opt->timezone));
        $vksObject->local->end_date_time =
            date_create($vksObject->end_date_time, new DateTimeZone(App::$instance->opt->ca_timezone))
                ->setTimezone(new DateTimeZone(App::$instance->opt->timezone));
    }

    public function reSubmitFromResults($reportNum)
    {
        if (!isset($_SESSION['savedResult_' . App::$instance->main->appkey]) || !isset($_SESSION['savedResult_' . App::$instance->main->appkey][$reportNum])) {
            $this->error('no-object');
        } else {
            $pulledObject = $_SESSION['savedResult_' . App::$instance->main->appkey][$reportNum];
            if ($pulledObject->isResult()) {
                $this->error('no-object');
            }
            $req = new Request();

            foreach ($pulledObject->getRequest() as $key => $val) {

                $req->request->set($key, $val);
            }
            //set dates to one
            $req->request->set('dates', array(
                array(
                    'date' => $pulledObject->getRequest()->get('date'),
                    'start_time' => $pulledObject->getRequest()->get('start_time'),
                    'end_time' => $pulledObject->getRequest()->get('end_time')
                )
            ));
//            dump($req);
//            die;
            $this->putUserDataAtBackPack($req);
            ST::redirectToRoute('Vks/create');
        }
    }

    public function fillParticipants($cookieName, Request $request)
    {
        $inner_parp = [];
        $lsc = new LocalStorage_controller();
        $request->request->set('in_place_participants_count', 0); //init
        if ($lsc->isExist($cookieName)) {
            foreach ($lsc->get($cookieName, false) as $parp) {
                if (isset($parp->type) && $parp->type == 3) //if in place
                    $request->request->set('in_place_participants_count', $parp->counter);
                else {
                    $inner_parp[] = $parp;
                }
            }
        }
        $request->request->set("inner_participants", $inner_parp);

    }

    public function fillCookieParticipants($cookieName, Vks $vks)
    {
        $lsc = new LocalStorage_controller();
        $att = new AttendanceNew_controller();
        $inner_parp = [
            array(
                "type" => 3,
                "counter" => $vks->in_place_participants_count
            )];
        foreach ($vks->participants as $parp) {

            $inner_parp[] = array(
                'id' => $parp->id,
                'parent_id' => $parp->parent_id,
                'name' => $parp->name,
                'path' => $parp->full_path,
                "type" => $parp->container,
                "free" => $att->isFree($parp->id, $vks->start_date_time, $vks->end_date_time, [$vks->id])
            );
        }
        $lsc->setValue($cookieName, $inner_parp);

        return $inner_parp;

    }

    /*
     * receive data from ajax, set backpack object to form
     */
    public function backPackDispatcher()
    {
        $this->putUserDataAtBackPack($this->request);
        print true;
    }

    public function day($date)
    {
        return $this->render("vks/dayGraph", compact('date'));
    }
}