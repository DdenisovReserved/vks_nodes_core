<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Process\Exception\LogicException;

class Vks_controller extends Controller
{

    use sorterTrait;

    public function index($date)
    {
        $date = date_create($date);

        if (!isset($date) || !($date instanceof DateTime)) ST::routeToErrorPage('500');

        $vkses = Vks::approved()
            ->full()
            ->where('date', $date)
            ->take($this->getQlimit(30))
            ->skip($this->getQOffset())
            ->orderBy($this->getQOrder(), $this->getQVector())
            ->get();
        $recordsCount = Vks::approved()
            ->where('date', $date)
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
        $tbs = json_decode(Curl::get(ST::routeToCa('AttendanceNew/apiGetTbs')));

        $vks = ST::lookAtBackPack();
        $vks = $vks->request;
        if (!$vks->has('inner_participants') && !count($vks->get('inner_participants'))) {

            LocalStorage_controller::staticRemove('vks_participants_create');

        }

        $this->render('vks/create', compact('departments', 'initiators', 'tbs', 'vks'));
    }

    public function makeClone($id)
    {

        $strict = boolval(intval(Settings_controller::getOther("attendance_strict")));

        if (!Auth::isLogged(App::$instance)) {
            App::$instance->MQ->setMessage('Создавать заявки могут только зарегистрированные пользователи, пожалуйста, войдите в систему или зарегистрируйтесь');
            ST::redirectToRoute('AuthNew/login');
        }
        //can this user access it
        $vks = Vks::full()->findOrFail($id);
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
        $tbs = json_decode(Curl::get(ST::routeToCa('AttendanceNew/apiGetTbs')));
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
            $vks->date = null;
            $vks->start_date_time = null;
            $vks->end_date_time = null;
            $vks->inner_participants = $vks->participants;
            foreach ($vks->toArray() as $key => $val) {
                $this->request->request->set($key, $val);
            }
            $vks = $this->request->request;

        }

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
            'Название' => [$request->get('title'), 'required|max(255)'],
            'ФИО ответственного' => [$request->get('init_customer_fio'), 'required|max(255)'],
            'Почта ответственного' => [$request->get('init_customer_mail'), 'required|max(255)'],
            'Тел. ответственного' => [$request->get('init_customer_phone'), 'required|max(255)'],
            'Подразделение' => [$request->get('department'), 'required|int'],
            'Участники в ЦА' => [$request->get('ca_participants'), 'int|between(0,10)'],
            'Участники ВКС' => [$request->get('inner_participants'), 'array'],
            'Комментарий для Администратора' => [$request->get('comment_for_admin'), 'max(160)'],
        ]);
        //if no passes

        //any participants required
        if (!intval($request->get('in_place_participants_count')) && !count($request->get('inner_participants'))) {
            $this->putUserDataAtBackPack($this->request);
            App::$instance->MQ->setMessage('Вы не выбрали участников для ВКС', 'danger');
            ST::redirect("back");
        }

        if ($request->get('needTB')) {

            $participants = $request->has('participants') ? $request->get('participants') : array();

            $request->set('participants', array_merge($participants, [(string)App::$instance->tbId]));

//            if (count($request->get('participants')) <= 1) {
//                if ($silent) {
//                    $report->setErrors(['Вы не указали участников от ТБ']);
//                    $report->setResult(false);
//                    return $report;
//                } else {
//                    $this->putUserDataAtBackPack($this->request);
//                    App::$instance->MQ->setMessage('Вы не указали участников от ТБ', 'danger');
//                    ST::redirect("back");
//                }
//
//            } else { //can create virtual VK in CA
            $requestClone = clone($request);

            $trVks = $this->createTransitVksOnCA($requestClone);

            if (!isset($trVks->id)) {
                if ($silent) {
                    $report->setErrors(['В настоящий момент невозможно создать транзитную ВКС на ресурсах ЦА, все коды заняты или сервер ЦА перегружен, попробуйте позже']);
                    $report->setResult(false);
                    return $report;
                } else {
                    $this->putUserDataAtBackPack($this->request);
                    App::$instance->MQ->setMessage('В настоящий момент невозможно создать транзитную ВКС на ресурсах ЦА, все коды заняты или сервер ЦА перегружен, попробуйте позже', 'danger');
                    ST::redirect("back");
                }
            }
//            }
        }

        $vks = new Vks();

        if (!$request->get('ca_code')) $request->set('ca_code', Null);

        $vks->fill($request->all());

        $vks->is_private = $request->has('is_private') ? 1 : 0;

        $vks->record_required = $request->has('record_required') ? 1 : 0;

        $vks->date = date_create($vks->date)->format("Y-m-d");

        $vks->start_date_time
            = date_create($vks->date . " " . $request->get('start_time'))->format("Y-m-d H:i:s");
        $vks->end_date_time
            = date_create($vks->date . " " . $request->get('end_time'))->format("Y-m-d H:i:s");

        $this->timeBarrier($vks);

        if (isset($trVks)) {
            $vks->link_ca_vks_id = $trVks->id;
            $vks->link_ca_vks_type = VKS_NS;
            $vks->other_tb_required = 1;
        }

        $vks->comment_for_admin = $request->get('comment_for_admin');

        $vks->owner_id = App::$instance->user->id;
        $vks->from_ip = App::$instance->user->ip;
        $vks->save();

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

        $message = "Пользователь " . App::$instance->user->login . " создал ВКС " . ST::linkToVksPage($vks->id);

        $message .= $vks->other_tb_required ? " + создан транспорт в ЦА " . $vks->link_ca_vks_id : "";

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
            $this->sendAdminNotice($vks);
        }
//        dump($report);
//        die;
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
            App::$instance->MQ->setMessage($this->validator->errors()->all(), 'danger');
            $this->putUserDataAtBackPack($this->request);
            ST::redirect("back");
        }

        $vks = new Vks();

        $vks->fill($request->all());

        $vks->is_private = $request->has('is_private') ? 1 : 0;
        $vks->record_required = $request->has('record_required') ? 1 : 0;

        $vks->date = date_create($vks->date)->format("Y-m-d");
        //set proper time
        $vks->start_date_time
            = date_create($vks->date . " " . $request->get('start_time'))->format("Y-m-d H:i:s");
        $vks->end_date_time
            = date_create($vks->date . " " . $request->get('end_time'))->format("Y-m-d H:i:s");

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
            App::$instance->MQ->setMessage($blockErrors, 'danger');
            $this->putUserDataAtBackPack($this->request);
            ST::redirect("back");
        }


        $vks->save();

        if (!in_array($vks->in_place_participants_count, range(2, 10))) {
            $this->putUserDataAtBackPack($this->request);
            App::$instance->MQ->setMessage("Ошибка: недопустимое кол-во участников");
            ST::redirect('back');
        }
        //ask simple code
        if (!$simpleCode = $load->giveSimpleCode($vks->start_date_time, $vks->end_date_time)) {
            $this->putUserDataAtBackPack($this->request);
            App::$instance->MQ->setMessage("Ошибка: К сожалению сейчас нет свободных комнат для проведения упрощенных ВКС, воспользуйтесь <a href='" . ST::route('Vks/create') . "'>стандартной формой</a>");
            ST::redirect('back');
        }

        ConnectionCode::create([
            'vks_id' => $vks->id,
            'value' => $simpleCode
        ]);

//        NoticeObs_controller::put("Пользователь " . App::$instance->user->login . " создал упрощенную ВКС " . ST::linkToVksPage($vks->id));

//        App::$instance->MQ->setImportantMessage("Ваша ВКС #{$vks->id} успешно создана,<br> код подключения: {$simpleCode}<br> В течении 5 минут на ваш почтовый адрес поступит отчет о созданной ВКС");

        App::$instance->log->logWrite(LOG_VKSWS_CREATED, "simple VKS " . ST::linkToVksPage($vks->id) . " Created");

        if (!$load->isPassedByCapacity($vks->start_date_time, $vks->end_date_time, self::countParticipants($vks->id), 0)) {
            App::$instance->MQ->setMessage("Ошибка: Заявленное кол-во участников, превышает предельно допустимую нагрузку на сервер ВКС, обратитесь к администраторам системы");
            $this->putUserDataAtBackPack($this->request);
            ST::redirect("back");
        }

//        die($vks);
        Capsule::commit();

        $report->setObject($vks);
        $report->setResult(true);
        $result[] = $report;
        $_SESSION['savedResult_' . App::$instance->main->appkey] = $result;
        //redirect

        $vks = Vks::full()->find($vks->id);
        $this->humanize($vks);
        $this->sendSimpleMail($vks);

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
//        json_decode( $tbs, true, 9 );
//        $json_errors = array(
//            JSON_ERROR_NONE => 'No error has occurred',
//            JSON_ERROR_DEPTH => 'The maximum stack depth has been exceeded',
//            JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
//            JSON_ERROR_SYNTAX => 'Syntax error',
//        );
//        echo 'Last error : ', $json_errors[json_last_error()], PHP_EOL, PHP_EOL;
        //render
        $vks = ST::lookAtBackPack();
        $vks = $vks->request;
        if (!$vks->has('inner_participants') && !count($vks->get('inner_participants'))) {

            LocalStorage_controller::staticRemove('vks_participants_create');

        }

        $this->render('vks/admin/create', compact('departments', 'initiators', 'tbs'));

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
//            'Инициатор' => [$request->get('initiator'), 'required|int'],
            'Участники в ЦА' => [$request->get('ca_participants'), 'int|between(0,10)'],
            'Владелец' => [$request->get('owner_id'), 'required|int'],
//            'Код ЦА' => [$request->get('ca_code'), 'int'],
            'Комментарий для пользователя' => [$request->get('comment_for_user'), 'max(160)'],
            'Участники ВКС' => [$request->get('inside_participants'), 'array'],
            'Кол-во участников с рабочих мест (IP телефоны)' => [$request->get('in_place_participants_count'), 'int'],
        ]);

        //if no passes
        if (!$this->validator->passes()) {
            $this->putUserDataAtBackPack($this->request);
            App::$instance->MQ->setMessage($this->validator->errors()->all());
            ST::redirect("back");
        }

        if ($request->get('needTB')) {

            $participants = $request->has('participants') ? $request->get('participants') : array();

            $request->set('participants', array_merge($participants, [(string)App::$instance->tbId]));

//            if (count($request->get('participants')) <= 1) {
//                $this->putUserDataAtBackPack($this->request);
//                App::$instance->MQ->setMessage('Вы не указали участников от ТБ', 'danger');
//                ST::redirect("back");
//            } else { //can create virtual VK in CA
                $trVks = $this->createTransitVksOnCA($request);
//                 die;
                if (!isset($trVks->id)) {
                    $this->putUserDataAtBackPack($this->request);
                    App::$instance->MQ->setMessage('В настоящий момент невозможно создать транзитную ВКС на ресурсах ЦА, все коды заняты или сервер ЦА перегружен, попробуйте позже', 'danger');
                    ST::redirect("back");
                }
//            }
        }

        //any participants required
        if (!intval($request->get('in_place_participants_count')) && !count($request->get('inner_participants'))) {
            $this->putUserDataAtBackPack($this->request);
            App::$instance->MQ->setMessage('Вы не выбрали участников для ВКС', 'danger');
            ST::redirect("back");
        }

        $vks = new Vks();

        if (!$request->get('ca_code')) $request->set('ca_code', Null);

        $vks->fill($request->all());
        $vks->is_private = $request->has('is_private') ? 1 : 0;
        $vks->record_required = $request->has('record_required') ? 1 : 0;
        $vks->date = date_create($vks->date)->format("Y-m-d");
        //set proper time
        $vks->start_date_time
            = date_create($vks->date . " " . $request->get('start_time'))->format("Y-m-d H:i:s");
        $vks->end_date_time
            = date_create($vks->date . " " . $request->get('end_time'))->format("Y-m-d H:i:s");

        $this->timeBarrier($vks);
        if (isset($trVks)) {
            $vks->link_ca_vks_id = $trVks->id;
            $vks->link_ca_vks_type = VKS_NS;
            $vks->other_tb_required = 1;
        }

        $vks->comment_for_admin = $request->get('comment_for_admin');

        $vks->owner_id = $request->get('owner_id');
        $vks->from_ip = App::$instance->user->ip;
        $vks->status = VKS_STATUS_APPROVED;
        $vks->approved_by = App::$instance->user->id;

        $vks->save();
        //parse inner participants

        //check if vks not in past time
        if (!Auth::isAdmin(App::$instance)) {
            if (self::isVksInPastTime($vks)) throw new LogicException('bad vks date, it on past time');
        }
        //create participants
        $this->createInnerOrPhoneParp($request->get('inner_participants'), $vks, true);

        $message = "Администратор " . App::$instance->user->login . " напрямую добавил ВКС " . ST::linkToVksPage($vks->id) . " в расписание";

        $message .= $vks->other_tb_requited ? " + создан транспорт в ЦА " . $vks->link_ca_vks_id : "";

//        NoticeObs_controller::put($message);

        App::$instance->MQ->setMessage("ВКС " . ST::linkToVksPage($vks->id) . " добавлена в расписание");

        App::$instance->log->logWrite(LOG_VKSWS_CREATED, "VKS " . ST::linkToVksPage($vks->id) . " Created by admin, direct insert");

//        if (!$load->isPassedByCapacity($vks->start_date_time, $vks->end_date_time, self::countParticipants($vks->id), 0)) {
//            App::$instance->MQ->setMessage("Ошибка: Заявленное кол-во участников, превышает предельно допустимую нагрузку на сервер ВКС");
//            $this->putUserDataAtBackPack($this->request);
//            ST::redirect("back");
//        }

        if (!$request->has('no-codes')) {
            foreach ($request->get('code') as $code) {
                $compiledCode = $code['prefix'] . $code['postfix'];

                if ($request->has('no-check')) {
                    $checkVks = false;
                } else {
                    $checkVks = $connCtrl->isCodeInUse($compiledCode, $vks->start_date_time, $vks->end_date_time);
                }

                if ($checkVks) {
                    App::$instance->MQ->setMessage('Ошибка: Код ' . $compiledCode . ' уже используется в ' . ST::linkToVksPage($checkVks->id), 'danger');
                    ST::redirect("back");
                } else {
                    $newCodes[] = ConnectionCode::create([
                        'vks_id' => $vks->id,
                        'value' => $compiledCode,
                        'tip' => $code['tip']
                    ]);

                }
            }
        }

        Capsule::commit();

        ST::redirectToRoute('Index/index');

    }

    public function checkout()
    {
        if (!isset($_SESSION['savedResult_' . App::$instance->main->appkey])) $this->error('no-object');

        $reports = $_SESSION['savedResult_' . App::$instance->main->appkey];

        $this->render('vks/checkout', compact("reports"));

    }

    public function show($id)
    {
        try {
            $vks = Vks::with('participants', 'department_rel', 'connection_codes', 'initiator_rel', 'owner', 'approver')
                ->findOrFail($id);

//            foreach($vks->participants as $parp) {
//                dump($parp->id ."-". $parp->name);
//            };
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
//        dump($vks->owner_id);
//        dump(User::where('id',$vks->owner_id)->first());
        $vks = $this->humanize($vks);
        if (!$vks->is_simple) {
            $this->render('vks/show', compact('vks'));
        } else {
            $this->render('vks/simple-info', compact('vks'));
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
            'Кол-во участников с рабочих мест (IP телефоны)' => [$request->get('in_place_participants_count'), 'int'],
            'Участники ВКС' => [$request->get('inner_participants'), 'array'],
        ]);
        //if no passes
        if (!$this->validator->passes()) {
            App::$instance->MQ->setMessage($this->validator->errors()->all());
            ST::redirect("back");
        }

        //any participants required
        if (!intval($request->get('in_place_participants_count')) && !count($request->get('inner_participants'))) {
            $this->putUserDataAtBackPack($this->request);
            App::$instance->MQ->setMessage('Вы не выбрали участников для ВКС', 'danger');
            ST::redirect("back");
        }

        $vks = Vks::full()->findOrFail($id);
        $vCSys = new VksVersion_controller();
        if ($vCSys->create($vks)) {
            if (!$request->get('ca_code')) $request->set('ca_code', Null);

            $vks->fill($request->all());
            $vks->is_private = $request->has('is_private') ? 1 : 0;
            $vks->record_required = $request->has('record_required') ? 1 : 0;
            $vks->date = date_create($request->get('date'))->format(("Y-m-d"));
            $vks->start_date_time = $vks->date . " " . date_create($request->get('start_time'))->format("H:i:s");
            $vks->end_date_time = $vks->date . " " . date_create($request->get('end_time'))->format("H:i:s");
            $this->timeBarrier($vks);


            //recreate participants
            $this->createInnerOrPhoneParp($request->get("inner_participants"), $vks, true);
            //flush old connection code

            $vks->status = VKS_STATUS_PENDING;
            $vks->approved_by = Null;

            $vks->save();
        } else {
            App::$instance->MQ->setMessage("Ошибка: Не удалось создать версию ВКС", 'danger');
            ST::redirect('back');
        }

//        NoticeObs_controller::put("Пользователь " . App::$instance->user->login . " отредактировал ВКС " . ST::linkToVksPage($vks->id));

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
            'Кол-во участников с рабочих мест (IP телефоны)' => [$request->get('in_place_participants_count'), 'int'],
        ]);
        if (!count($request->get('inner_participants')) && $request->get('in_place_participants_count') == 0) {
            App::$instance->MQ->setMessage("В ВКС обязательно должны быть участники", 'danger');
            ST::redirect("back");
        }
        //if no passes
        if (!$this->validator->passes()) {
            App::$instance->MQ->setMessage($this->validator->errors()->all());
            ST::redirect("back");
        }

        //any participants required
        if (!intval($request->get('in_place_participants_count')) && !count($request->get('inner_participants'))) {
            $this->putUserDataAtBackPack($this->request);
            App::$instance->MQ->setMessage('Вы не выбрали участников для ВКС', 'danger');
            ST::redirect("back");
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
            $vks->date = date_create($request->get('date'))->format(("Y-m-d"));
            $vks->start_date_time = $vks->date . " " . date_create($request->get('start_time'))->format("H:i:s");
            $vks->end_date_time = $vks->date . " " . date_create($request->get('end_time'))->format("H:i:s");

            $this->timeBarrier($vks);

            //recreate participants
            $this->createInnerOrPhoneParp($request->get("inner_participants"), $vks, true);
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

//        NoticeObs_controller::put("Админ " . App::$instance->user->login . " отредактировал ВКС " . ST::linkToVksPage($vks->id));

        App::$instance->MQ->setMessage("Успешно отредактировано");

        App::$instance->log->logWrite(LOG_VKSWS_CREATED, "VKS " . ST::linkToVksPage($vks->id) . " edited by admin " . App::$instance->user->login . "");

        Capsule::commit();

        if ($request->get('notify') == 1) {
            $vks = Vks::full()->find($vks->id);
            $this->humanize($vks);
            $this->sendEditedMail($vks, false);
            App::$instance->MQ->setMessage("Успешно отредактировано, пользователю отправлено уведомление");
        }

        //revoke all outlook requests requests
        OutlookCalendarRequest_controller::changeRequestTypeAndPutToResend($vks->id, OutlookCalendarRequest::REQUEST_TYPE_UPDATE);

        ST::redirectToRoute("Vks/show/" . $vks->id);

    }

    public function apiGet($vksId)
    {
//        $s = ST::microtime_float();
        $cacheName = App::$instance->tbId . ".vks.controller.api.get.{$vksId}";
        $vks = App::$instance->cache->get($cacheName);

        if (!$vks) {
            $vks = Vks::full()->findOrFail($vksId);
            $cachedObj = new CachedObject($vks, ['tag.' . $cacheName]);
            App::$instance->cache->set($cacheName, $cachedObj, 3600 * 24 * 3);
        }


        //ask for VksfromCA
        $caVks = Null;

        if ($vks->link_ca_vks_id) {
            if ($vks->link_ca_vks_type == 0) {
                $caVks = Curl::get(ST::routeToCaApi('getVksWasById/' . $vks->link_ca_vks_id));
            } else {
                $caVks = Curl::get(ST::routeToCaApi('getVksNsById/' . $vks->link_ca_vks_id));
            }
//            dump($caVks);
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

    protected function createInnerOrPhoneParp($parpList, $vks, $flush = false)
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
//                    die();
                    $this->putUserDataAtBackPack($this->request);
                    App::$instance->MQ->setMessage("Ошибка в данном режиме работы нельзя создать ВКС с участниками которые уже участвуют в других ВКС, выберите участников заново и повторите отправку", 'danger');
                    ST::redirect("back");
                }
            } else {
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
        $vksStartTime = date_create($vksObject->start_date_time)->getTimestamp();
        //check end time
        $vksEndTime = date_create($vksObject->end_date_time)->getTimestamp();

        return (($vksStartTime - $now) || ($vksEndTime - $now)) < 0 ? true : false;
    }

    protected function isThisUserCanEdit(Vks $vks)
    {
        Auth::isLoggedOrDie(App::$instance);
        return ($vks->owner_id === App::$instance->user->id || Auth::isAdmin(App::$instance)) ? true : false;
    }

    public function humanize(&$vks)
    {

        $vks->humanized = new stdClass();
        $vks->humanized->date = date_create($vks->date)->format("d.m.Y");
        $vks->humanized->startTime = date_create($vks->start_date_time)->format("H:i");
        $vks->humanized->endTime = date_create($vks->end_date_time)->format("H:i");

        $vks->humanized->isEditable = $this->isEditable($vks);
        $vks->humanized->isCloneable = $this->isCloneable($vks);
        $vks->humanized->isDeletable = $this->isDeletable($vks);
        $vks->humanized->isManipulatable = VKSTimeAnalizator::isManipulatable($vks);
        $vks->humanized->isCodePublicable = $this->isCodePublicable($vks);
        $vks->humanized->isOutlookable = $this->isOutlookable($vks);

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
//        dump($vks->connection_codes);
        return $vks;

    }

    public function annulate($vksId)
    {

        $vks = Vks::findorFail($vksId);
        $this->humanize($vks);
        $this->render('Vks/admin/annulate', compact('vks'));
        //revoke all outlook requests requests
        OutlookCalendarRequest_controller::changeRequestTypeAndPutToResend($vks->id, OutlookCalendarRequest::REQUEST_TYPE_DELETE);

    }

    public function cancel($id) //by user
    {
        $vks = Vks::findorFail($id);
        if (!Auth::isAdmin(App::$instance)
            && !$this->isThisUserCanEdit($vks)
            && !VKSTimeAnalizator::isManipulatable($vks)
        ) {
            ST::routeToErrorPage('no_manipulable');
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

        $vks = Vks::findorFail($id);
        $request = $this->request->request;
        $this->validator->validate([
            'Комментарий для пользователя' => [$request->get('comment_for_user'), 'max(160)'],
        ]);

        //if no passes
        if (!$this->validator->passes()) {
            $this->putUserDataAtBackPack($this->request);
            App::$instance->MQ->setMessage($this->validator->errors()->all());
            ST::redirect("back");
        }

        if ($vks->other_tb_required) {
            if (!$vks->other_tb_required || $vks->link_ca_vks_type != 1 || !isset($vks->link_ca_vks_id)) {
                App::$instance->MQ->setMessage("Возникла ошибка, ВКС представлена как использующая транспортную ВКС, однако при проверке этого не обнаружено, обратитесь к разработчику");
                ST::redirect('back');
            }
            //pull from CA
            $caVks = CAVksNoSupport::findOrFail($vks->link_ca_vks_id);
            if ($caVks->status != VKS_STATUS_TRANSPORT_FOR_TB) {
                App::$instance->MQ->setMessage("Возникла ошибка, ВКС в ТБ {$id} ссылается на ВКС в ЦА {$vks->link_ca_vks_id}, однако ВКС в ЦА, не имеет транспортного статуса, обратитесь к разработчику");
                ST::redirect('back');
            }
            $caVks->delete();
            App::$instance->log->logWrite(LOG_VKSWS_CREATED, "VKS + transport" . $vks->id . "({$caVks->id}) deleted by admin " . App::$instance->user->login . "");
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
            $this->sendDeleteMail($vks, false);
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
        $vksList = Vks::notApproved()->take($this->getQlimit())
            ->skip($this->getQOffset())
            ->orderBy($this->getQOrder(), $this->getQVector())
            ->full()->get();
        foreach ($vksList as $vks) {
            $this->humanize($vks);
            $vksLasVersion = $vcc->pullLastVersion($vks->id);
            $vks->again = $vksLasVersion && ($vks->owner->id == $vksLasVersion->changed_by) ? true : false;
        }
        $recordsCount = Vks::notApproved()->count();
        //pages
        $pages = RenderEngine::makePagination($recordsCount, $this->getQlimit(), 'route');

        $this->render("vksSubmissions/index", compact('vksList', 'pages'));
    }

    function showNaVks($id)
    {
        Auth::isAdminOrDie(App::$instance);
        $versionCtrl = new VksVersion_controller();
        $load = new Load_controller();
        $sc = new Settings_controller();
        $connCtrl = new ConnectionCode_controller();

        $vks = Vks::NotSimple()->full()->findOrFail($id);
        $vks = $this->humanize($vks);

        $att = new AttendanceNew_controller();
        $check = boolval(intval(Settings_controller::getOther("attendance_check_enable")));
//        $strict = boolval(intval(Settings_controller::getOther("attendance_strict")));

        foreach ($vks->participants as $parp) {
            if ($check) {
                $parp->free = $att->isFree($parp->id, $vks->start_date_time, $vks->end_date_time, [$vks->id]);
            } else {
                $parp->free = true;
            }
        }

        $codesLoadSet = [];
        foreach ($sc->getCodesPostfixSet() as $postfixCode) {
            $vksSearch = $connCtrl->isCodeInUse($postfixCode, $vks->start_date_time, $vks->end_date_time);
            $codesLoadSet[$postfixCode] = isset($vksSearch->id) ? ST::linkToVksPage($vksSearch->id, true) : false;
        }
        if (!self::isVksCanBeApproved($vks))
            throw new Exception('this VKS can\'t be approved or declined,change it status to PENDING first');
//        $graphUrl = $load->drawLoadImage($vks->date);

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


        $graph = $load->pullLoadDataForJs($vks->humanized->date);


//        dump($caVks);
        $this->render('vksSubmissions/approvePage', compact('vks', 'graphUrl', 'caVks', 'versions', 'codes', 'codesLoadSet', 'last_version','graph'));
    }

    function process($vksId)
    {

        Auth::isAdminOrDie(App::$instance);
        $connCtrl = new ConnectionCode_controller();

        $vks = Vks::findOrFail($vksId);

        Token::checkToken();

        if (!self::isVksCanBeApproved($vks))
            throw new Exception('this VKS can\'t be approved or declined,change it status to PENDING first');

        $request = $this->request->request;
        //validate
        $this->validator->validate([
            'Комментарий для пользователя' => [$request->get('comment_for_user'), 'max(160)'],
            'Статус ВКС' => [$request->get('status'), 'required|between(1,2)'],
        ]);
        //if no passes
        if (!$this->validator->passes()) {
            App::$instance->MQ->setMessage($this->validator->errors()->all());
            ST::redirect("back");
        }

        /*
         * if declined
         */
        if ($request->get('status') == 2) {
            $vks->comment_for_user = $request->get('comment_for_user');
            $vks->status = $request->get('status');
            $vks->approved_by = App::$instance->user->id;
            $vks->save();

            if ($vks->other_tb_required && $vks->link_ca_vks_type == 1 && isset($vks->link_ca_vks_id)) {
                $caVks = CAVksNoSupport::find($vks->link_ca_vks_id);
                if ($caVks->status == VKS_STATUS_TRANSPORT_FOR_TB) {
                    $caVks->delete();
                }
            }

            $vks = Vks::full()->find($vks->id);
            $this->humanize($vks);
            $this->sendDeclineMail($vks, false);
            //revoke all outlook requests requests
            OutlookCalendarRequest_controller::changeRequestTypeAndPutToResend($vks->id, OutlookCalendarRequest::REQUEST_TYPE_DELETE);

            App::$instance->MQ->setMessage("Вкс #{$vks->id}, отказ в проведении");
//            NoticeObs_controller::put("Администратор выдал отказ по вкс " . ST::linkToVksPage($vks->id));
            ST::redirect(ST::route("Vks/notApproved"));
        }

        if (!$request->has('no-codes') && !count($request->get('code')))
            throw new LogicException('bad params combined');


        /*
         * if no codes
         *
         */
        if ($request->has('no-codes')) {
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

            $vks = Vks::full()->find($vks->id);
            $this->humanize($vks);
            $this->sendReportMail($vks, false);

            App::$instance->MQ->setMessage("Вкс #{$vks->id} согласована без кода подключения");
//            NoticeObs_controller::put("Администратор принял решение по вкс " . ST::linkToVksPage($vks->id));

            ST::redirect(ST::route("Vks/notApproved"));
        }

        /*
         * standart process
         */

        //validate codes
        if (!count($request->get('code')))
            throw new LogicException('no code was passed, error!');

//        //all codes must be unique
//        $uniqueCodes = [];
//
//        foreach ($request->get('code') as $code) {
//            $compiledCode = $code['postfix'];
//            if (!in_array($compiledCode, $uniqueCodes)) {
//                $uniqueCodes[] = $compiledCode;
//            } else {
//                //if no passes
//                App::$instance->MQ->setMessage('Ошибка: Все коды в наборе должны быть уникальны', 'danger');
//                ST::redirect("back");
//            }
//        }

        foreach ($request->get('code') as $code) {

            $this->validator->validate([
                'Префикс' => [$code['prefix'], 'required|max(160)'],
                'Постфикс' => [$code['postfix'], 'required|max(4)'],
                'Подсказка' => [$code['tip'], 'max(255)'],
            ]);
            //if no passes
            if (!$this->validator->passes()) {
                App::$instance->MQ->setMessage($this->validator->errors()->all());
                ST::redirect("back");
            }
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
//            dump($checkVks);
//            die;
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


        Capsule::commit();

        //now send mails

        //send mail to user, if it not anon user
        if ($vks->status == VKS_STATUS_APPROVED) {
            $vks = Vks::full()->find($vks->id);
            $this->humanize($vks);
            $this->sendReportMail($vks, false);
            //revoke all outlook requests requests
            OutlookCalendarRequest_controller::changeRequestTypeAndPutToResend($vks->id, OutlookCalendarRequest::REQUEST_TYPE_UPDATE);
        }

        if ($vks->status == VKS_STATUS_APPROVED)
            App::$instance->MQ->setMessage("ВКС {$vks->id} согласована ");
        else
            App::$instance->MQ->setMessage("Отказ по ВКС {$vks->id}");

//        NoticeObs_controller::put("Администратор принял решение по ВКС " . ST::linkToVksPage($vks->id));

        ST::redirect(ST::route("Vks/notApproved"));
    }

    public function timeBarrier(Vks $vks)
    {
        if ($vks->start_date_time == $vks->end_date_time) {
            $this->putUserDataAtBackPack($this->request);
            App::$instance->MQ->setMessage('Ошибка: ВКС не может начаться и тут же закончиться');
            ST::redirect("back");
        }
        if ($vks->start_date_time > $vks->end_date_time) {
            $this->putUserDataAtBackPack($this->request);
            App::$instance->MQ->setMessage('Ошибка: ВКС не может начаться раньше времени окончания');
            ST::redirect("back");
        }
    }

    static function isVksCanBeApproved(Vks $vks)
    {
        return (in_array($vks->status, [VKS_STATUS_PENDING])) ? true : false;
    }

    public static function countParticipants($id)
    {
        $vks = Vks::findOrFail($id);
//        dump($vks->participants()->count());
        return $vks->in_place_participants_count + $vks->participants()->count();
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

        if ($this->isAlreadyEnd($caVks->end_date_time)) {
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


        $this->render('vks/joinCa', compact('vks', 'caVks', 'departments', 'referral', 'flag'));

    }

    public function  joinCaStore($referral)
    {

        $att = new AttendanceNew_controller();
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
        ]);
        //if no passes
        if (!$this->validator->passes()) {
            $this->putUserDataAtBackPack($this->request);
            App::$instance->MQ->setMessage($this->validator->errors()->all());
            ST::redirect("back");
        }

        //any participants required
        if (!intval($request->get('in_place_participants_count')) && !count($request->get('inner_participants'))) {
            $this->putUserDataAtBackPack($this->request);
            App::$instance->MQ->setMessage('Вы не выбрали участников для ВКС', 'danger');
            ST::redirect("back");
        }

        Capsule::beginTransaction();

        $caWS = CAVks::where('referral', $referral)->with('connection_codes')->first();
        $caNS = CAVksNoSupport::where('referral', $referral)->first();

        if (!$caWS && !$caNS) {
            App::$instance->MQ->setMessage("Приглашение {$request->get('referrer')}  не действительно");
            ST::redirect("back");
        }
        $caVKSType = $caWS ? VKS_WAS : VKS_NS;
        $caVks = $caWS ? $caWS : $caNS;

        $vks = new Vks();
        $vks->is_private = $request->has('is_private') ? 1 : 0;
        $vks->record_required = $request->has('record_required') ? 1 : 0;
        $vks->title = $caVks->title;
        $vks->date = date_create($caVks->date, new DateTimeZone(App::$instance->opt->ca_timezone))->setTimezone(new DateTimeZone(App::$instance->opt->timezone))->format("Y-m-d");
        $vks->start_date_time = date_create($caVks->start_date_time, new DateTimeZone(App::$instance->opt->ca_timezone))->setTimezone(new DateTimeZone(App::$instance->opt->timezone))->format("Y-m-d H:i:s");
        $vks->end_date_time = date_create($caVks->end_date_time, new DateTimeZone(App::$instance->opt->ca_timezone))->setTimezone(new DateTimeZone(App::$instance->opt->timezone))->format("Y-m-d H:i:s");
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
        $vks->save();

        if (!Auth::isAdmin(App::$instance)) {
            if (self::isVksInPastTime($vks)) throw new LogicException('bad vks date, it on past time');
        }
        //create participants
        $this->createInnerOrPhoneParp($request->get('inner_participants'), $vks);

        App::$instance->log->logWrite(LOG_VKSWS_CREATED, "VKS " . ST::linkToVksPage($vks->id) . " Created");

        Capsule::commit();
        //refill for report
        $this->request->request->set("date", date_create($vks->date)->format("d.m.Y"));
        $this->request->request->set("start_time", date_create($vks->start_date_time)->format("H:i"));
        $this->request->request->set("end_time", date_create($vks->end_date_time)->format("H:i"));
        $report->setObject($vks);
        $report->setResult(true);
        $result[] = $report;
        $_SESSION['savedResult_' . App::$instance->main->appkey] = $result;
        ST::redirectToRoute('vks/checkout');
    }

    public function mark($vksId)
    {
        Auth::isAdminOrDie(App::$instance);
        $vks = Vks::findOrFail($vksId);
        $vks->flag = 1;
        $vks->save();
        App::$instance->MQ->setMessage("Вкс {$vks->id} отмечена флагом");
        ST::redirect('back');
    }

    public function unmark($vksId)
    {
        Auth::isAdminOrDie(App::$instance);
        $vks = Vks::findOrFail($vksId);
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

    public function publicStatusChange($VksId)
    {
        $vks = Vks::findOrFail($VksId);
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

    private function inPast($vksStartDate)
    {
        return date_create() > date_create($vksStartDate, new DateTimeZone(App::$instance->opt->ca_timezone))->setTimezone(new DateTimeZone(App::$instance->opt->timezone)) ? true : false;

    }

    private function isAlreadyEnd($vksEndDate)
    {

        return date_create() > date_create($vksEndDate, new DateTimeZone(App::$instance->opt->ca_timezone))->setTimezone(new DateTimeZone(App::$instance->opt->timezone)) ? true : false;

    }

    private function isEditable($vksObject)
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

    private function isDeletable($vksObject)
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

    private function isCloneable($vksObject)
    {
        if (!Auth::isLogged(App::$instance)
            || $vksObject->is_simple
            || ($vksObject->link_ca_vks_id && !$vksObject->other_tb_required
//                || $vksObject->owner_id != App::$instance->user->id
            )
        ) {
            return false;
        } else {
            return true;
        }
    }

    private function isOutlookable($vksObject) {

        if (Auth::isLogged(App::$instance)
            && date_create($vksObject->end_date_time) > date_create()
            && $vksObject->status == VKS_STATUS_APPROVED
        ) {

            return true;
        }
        else {

            return false;
        }

    }



private
function isCodePublicable($vksObject)
{
    if (Auth::isAdmin(App::$instance)
        && date_create($vksObject->start_date_time)->setTime(0, 0) >= date_create()->setTime(0, 0)
    ) {
        return true;
    }

    if (Auth::isLogged(App::$instance)
        && $vksObject->status == VKS_STATUS_APPROVED
        && date_create($vksObject->start_date_time)->setTime(0, 0) >= date_create()->setTime(0, 0)
        && $vksObject->owner_id == App::$instance->user->id
    ) {
        return true;
    } else {
        return false;
    }
}

private
function sendReportMail(Vks $vks, $toRequester = true)
{

    $vks->link = ST::linkToVksPage($vks->id, false, true);
    $vksArray = $vks->toArray();
    $vksCa = ($vks->other_tb_required && !empty($vks->link_ca_vks_id)) ? CAVksNoSupport::with('participants')->find($vks->link_ca_vks_id) : false;
//        dump($vksCa);
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

private
function sendSimpleMail($vks, $toRequester = true)
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

private
function sendDeclineMail($vks, $toRequester = true)
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

private
function sendEditedMail($vks, $toRequester = true)
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

private
function sendDeleteMail($vks, $toRequester = true)
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

private
function sendAdminNotice(Vks $vks)
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


private
function createTransitVksOnCA($request)
{
    $tmp = [];
    $tmp['title'] = $request->get('title');
    $tmp['participants'] = $request->get('participants');
//        dump($request);
    $tmp['date'] = date_create($request->get('date') . "" . $request->get('start_time'))->setTimezone(new DateTimeZone(App::$instance->opt->ca_timezone))->format("d.m.Y");
    $tmp['start_time'] = date_create($request->get('start_time'))->setTimezone(new DateTimeZone(App::$instance->opt->ca_timezone))->format("H:i");
    $tmp['end_time'] = date_create($request->get('end_time'))->setTimezone(new DateTimeZone(App::$instance->opt->ca_timezone))->format("H:i");
    $tmp['tb'] = MY_NODE;
    $tmp['ip'] = App::$instance->user->ip;
    $tmp['location'] = TB_PATTERN . " банк";
    $tmp['ca_participants'] = $request->get('ca_participants');
    $tmp['owner_id'] = App::$instance->user->id;
//        dump($tmp);

    $tmp = http_build_query($tmp);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_URL, HTTP_PATH . "?route=VksNoSupport/apiMakeTransportVks");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $tmp);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("X-Requested-With: XMLHttpRequest"));
    $ask = curl_exec($curl);
    curl_close($curl);

    if (0 === strpos(bin2hex($ask), 'efbbbf')) {
        $ask = substr($ask, 3);
    }
    $ask = json_decode($ask);
    return $ask;
}

static public function convertToLocalTime($vksObject)
{
    $vksObject->local = new stdClass();
    $vksObject->local->start_date_time = date_create($vksObject->start_date_time, new DateTimeZone(App::$instance->opt->ca_timezone))->setTimezone(new DateTimeZone(App::$instance->opt->timezone));
    $vksObject->local->end_date_time = date_create($vksObject->end_date_time, new DateTimeZone(App::$instance->opt->ca_timezone))->setTimezone(new DateTimeZone(App::$instance->opt->timezone));
}

    public function test()
{
    $cc = new ConnectionCode_controller();

    $vks = Vks::full()->find(55);
    dump($cc->codesToString($vks->connection_codes));

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
            if ($parp->type == 3) //if in place
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

}