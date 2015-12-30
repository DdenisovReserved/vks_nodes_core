<?php

class User_controller extends Controller
{
    use sorterTrait;

    public function index()
    {
        Auth::isAdminOrDie(App::$instance);
        //find all user
        $users = User::take($this->getQlimit(30))
            ->skip($this->getQOffset())
            ->orderBy($this->getQOrder(), $this->getQVector())
            ->get();
        foreach ($users as $user) {
            $user = self::humanize($user);
        }
        $this->render('users/v2/index', compact('users'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $this->render('login/register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
        //check token
        Token::checkToken();

        $this->validator->validate([
            'Логин' => [$request->request->get('login'), 'required|min(3)|max(45)|userFree'],
            'ФИО' => [$request->request->get('fio'), 'required|min(3)|max(160)'],
            'Телефон' => [$request->request->get('phone'), 'required|min(3)|max(45)'],
            'Пароль' => [$request->request->get('password1'), 'required|min(5)|max(25)|pwd'],
            'Подтверждение пароля' => [$request->request->get('password2'), 'required|matches(Пароль)'],
        ]);
        //if login not in user
//        dump($request->request->get('password1'));
        //if no passes
        if (!$this->validator->passes()) {
            $this->putUserDataAtBackPack($request);
            App::$instance->MQ->setMessage($this->validator->errors()->all());
//            dump($this->validator->errors()->all());
//            die('bad');
            ST::redirect('back');
        }
//        die('good');
        //create new
        $user = new User();
        $user->login = strtoupper($request->request->get('login'));
        $user->password = md5($request->request->get('password1'));
        $user->token = self::makeToken($request->request->get('login'));
        $user->fio = $request->request->get('fio');
        $user->phone = $request->request->get('phone');
        $user->status = USER_STATUS_NOTAPPROVED;
        $user->setOriginAttribute();
        $user->role = ROLE_USER;
//        $user->save();
        App::$instance->log->logWrite(LOG_USER_REGISTER, "User: {$user->login}, id: {$user->id}, fio: {$user->fio} registered", App::$instance->user->ip);

        $user->appPath = App::$instance->opt->appHttpPath;

//        App::$instance->MQ->setMessage("На адрес $user->login, отправлено письмо с подтверждением регистрации нового пользователя");

        $this->sendConfirmMail($user->id);

        ST::redirect(ST::route("User/RegWelcome/" . $user->id));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        Auth::isAdminOrDie(App::$instance);
        $user = User::findOrFail($id);
        $backPack = clone($user);
        $this->render("users/v2/edit", compact('user', 'backPack'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        Auth::isAdminOrDie(App::$instance);

        self::isDefaultUserIteractBlock($id);

        $user = User::findOrFail($id);

        $request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
        //check token
        Token::checkToken();

        $this->validator->validate([
            'Логин' => [$request->request->get('login'), 'required|min(3)|max(45)'],
            'ФИО' => [$request->request->get('fio'), 'required|min(3)|max(160)'],
            'Телефон' => [$request->request->get('phone'), 'required|min(3)|max(45)'],
        ]);

//        dump($this->validator->errors()->all());
//        die;

        //if no passes
        if (!$this->validator->passes()) {
            App::$instance->MQ->setMessage($this->validator->errors()->all());
            ST::redirect("back");
        }

        $user->fill($request->request->all());

        if ($request->request->get('password-reset')) {

            $defaultPassword = '123456';

            $user->password = md5($defaultPassword);

            $user->token = self::makeToken($request->request->get('login'));

            Mail::sendMailToStack($user->email, "Подтверждение регистрации", "Для вашей учетной записи {$user->login} в АС ВКС установлен пароль по умолчанию: {$defaultPassword}");

        }

        $user->save();

        App::$instance->MQ->setMessage("Пользователь успешно отредактирован");

        ST::redirect(ST::route("User/index"));

    }

    public function editMyData()
    {
        Auth::isLoggedOrDie(App::$instance);
        $user = User::findOrFail(App::$instance->user->id);
        $this->render("users/v2/editMydata", compact('user'));
    }

    public function storeMyData()
    {
        global $_TB_IDENTITY;
        Auth::isLoggedOrDie(App::$instance);
        $user = CAUser::findOrFail(App::$instance->user->id);
        $request = $this->request->request;
        $this->validator->validate([
            'fio' => [$request->get('fio'), 'required|min(3)|max(60)'],
            'phone' => [$request->get('phone'), 'required|min(3)|max(20)'],
            'email' => [$request->get('email'), 'required|min(3)|max(60)'],
            'origin' => [$request->get('origin'), 'int'],

        ]);


        //if no passes
        if (!$this->validator->passes()) {
            App::$instance->MQ->setMessage($this->validator->errors()->all(), 'danger');
            ST::redirect("back");
        }
        $user->fio = $request->get('fio');
        $user->phone = $request->get('phone');
        $user->email = $request->get('email');
        $originOld = $user->origin;

        if($request->get('origin') == CA_CA) {
            if($user->is_from_domain && explode("\\", $user->login)[0]  == 'ALPHA') {
                $user->origin = $request->get('origin');
            }else {
                $this->error('500');
            }
        } else {

            if (array_key_exists($request->get('origin'), $_TB_IDENTITY)) {

                $user->origin = intval($request->get('origin'));

            } else {
                $this->error('500');
            }
        }
        $user->save();

        if ($originOld != $user->origin) {
            setcookie(md5("logged" . $_TB_IDENTITY[$originOld]['serviceName']),
                null, -500, '/', Null, 0);
            setcookie(md5("logged" . $_TB_IDENTITY[$user->origin]['serviceName']),
                $user->token, time() + 24 * 60 * 365, '/', Null, 0);
            $location = $user->origin != 0 ? NODES_CORE_PATH.$_TB_IDENTITY[$user->origin]['serviceName'] : HTTP_BASE_PATH;
            header('location:'.$location);
        }

        App::$instance->MQ->setMessage('Информация успешно сохранена');
        ST::redirectToRoute("Index/index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        Auth::isAdminOrDie(App::$instance);

        self::isDefaultUserIteractBlock($id);

        $user = User::findOrFail($id);
        $user->status = USER_STATUS_BANNED;
        $user->save();
        App::$instance->MQ->setMessage("Пользователь переведен в неактивное состояние");

        ST::redirect(ST::route("User/index"));
    }

    public function find($searchString, $humanized = false)
    {
        Auth::isAdminOrDie(App::$instance);

        if (ST::isAjaxRequest()) {

            $users = User::where("login", 'LIKE', "%{$searchString}%")
                ->where("status", USER_STATUS_APPROVED)
                ->take(10)
                ->get();

            if ($humanized) {
                foreach ($users as $user) {
                    $user = self::humanize($user);
                }
            }
            print json_encode($users);

        }
    }

    public function apiFind() //pull data from request
    {
        $request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
        $users = User::where("login", 'LIKE', "%".mb_strtoupper($request->query->get('q'))."%")
            ->whereIn('role', [ROLE_USER, ROLE_VIP])
            ->where("status", USER_STATUS_APPROVED)
            ->take(10)->get();
//        dump($users);
        foreach ($users as $user) {
            self::humanize($user);
        }
        $result['items'] = $users;
        print json_encode($result);
    }

    public
    function ban($id)
    {
        Auth::isAdminOrDie(App::$instance);
        self::isDefaultUserIteractBlock($id);
        $user = User::findOrFail($id);
        $user->status = USER_STATUS_BANNED;
        $user->save();
        App::$instance->MQ->setMessage("Пользователь {$user->login} забанен");
        ST::redirect("back");

    }

    public function delete($id)
    {
//        Auth::isAdminOrDie(App::$instance);
//        self::isDefaultUserIteractBlock($id);
//        $user = User::findOrFail($id);
//        $user->delete();
        ST::redirectToRoute("Index/index");
    }

    public
    function unban($id)
    {
        Auth::isAdminOrDie(App::$instance);
        self::isDefaultUserIteractBlock($id);
        $user = User::findOrFail($id);
        $user->status = USER_STATUS_APPROVED;
        $user->save();
        App::$instance->MQ->setMessage("Пользователь {$user->login} успешно разбанен");
        ST::redirect("back");

    }

    public
    function approve($id)
    {
        Auth::isAdminOrDie(App::$instance);
        self::isDefaultUserIteractBlock($id);
        $user = User::findOrFail($id);
        $user->status = USER_STATUS_APPROVED;
        $user->save();
        App::$instance->MQ->setMessage("Пользователь {$user->login} успешно подтвержден");
        ST::redirect("back");

    }

    public
    function approveToken($userToken)
    {
        $user = User::where('token', $userToken)->first();
        if (empty($user)) throw new Exception('Bad user token delivered');
        $user->status = USER_STATUS_APPROVED;
        $user->save();

        App::$instance->MQ->setMessage("Спасибо за подтверждение, добро пожаловать.");
        ST::redirectToRoute("Index/index");

    }

    public
    static function humanize(User $user)
    {
        Auth::isAdminOrDie(App::$instance);
        $user->humanized = new stdClass();
        switch ($user->role) {
            case(ROLE_USER):
                $user->humanized->role = 'Пользователь';
                $user->humanized->role_label =
                    '<span class="glyphicon glyphicon-pawn" title="Пользователь"></span>';
                break;
            case(ROLE_VIP):
                $user->humanized->role = 'VIP';
                $user->humanized->role_label =
                    '<span class="glyphicon glyphicon-bishop text-info" title="VIP"></span>';
                break;
            case(ROLE_ADMIN):
                $user->humanized->role = 'Администратор';
                $user->humanized->role_label =
                    '<span class="glyphicon glyphicon-queen text-success" title="Администратор"></span>';
                break;
            case(ROLE_ADMIN_MODERATOR):
                $user->humanized->role = 'Супер Администратор';
                $user->humanized->role_label =
                    '<span class="glyphicon glyphicon-king text-danger" title="Супер Администратор"></span>';
                break;
            case(ROLE_ANONYMOUS):
                $user->humanized->role = 'Анонимный';
                $user->humanized->role_label =
                    '<span class="glyphicon glyphicon-user" title="Анонимный"></span>';
                break;
            default:
                $user->humanized->role = 'Роль не определена';
                $user->humanized->role_label =
                    '<span class="glyphicon glyphicon-user" title="Роль не определена"></span>';
                break;
        }

        switch ($user->status) {
            case(USER_STATUS_APPROVED):
                $user->humanized->status = 'Подтвержден';
                $user->humanized->status_label =
                    '<span class="glyphicon glyphicon-ok text-success" title="Подтвержден"></span>';
                break;
            case(USER_STATUS_NOTAPPROVED):
                $user->humanized->status = 'Не подтвержден';
                $user->humanized->status_label =
                    '<span class="glyphicon glyphicon-question-sign text-warning" title="Не подтвержден"></span>';
                break;
            case(USER_STATUS_BANNED):
                $user->humanized->status = 'Заблокирован';
                $user->humanized->status_label =
                    '<span class="glyphicon glyphicon-ban-circle text-danger" title="Заблокирован"></span>';
                break;
            default:
                $user->humanized->status = 'Статус не определен';
                break;
        }
        $user->humanized->created_at = date_create($user->created_at)->format("d.m.Y H:i");
        $user->humanized->last_visit = date_create($user->last_visit)->format("d.m.Y H:i");
        return $user;
    }

    public static function isDefaultUserIteractBlock($id)
    {
        Auth::isAdminOrDie(App::$instance);
        if (in_array($id, [1])) {
            App::$instance->MQ->setMessage('Нелья редактировать системных пользователей');
            ST::redirect("back");
        }
    }

    public static function makeToken($login)
    {

        return sha1(App::$instance->main->appkey . $login . rand(1, 999999) . date_create()->getTimestamp());
    }

    public function sendConfirmMail($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $message = App::$instance->twig->render('mails/v2/confirm-registration.twig', array(
                'user' => $user->toArray(),
                'http_path' => str_replace('index.php', '', NODE_HTTP_PATH),
                'appHttpPath' => NODE_HTTP_PATH
            ));
            Mail::sendMailToStack($user->email, "Подтверждение регистрации", $message);
        } else {
            $this->error('no-object');
        }

    }


    public function initColors($user)
    {
        $result = array();
        if (isset($user->id)) {
            if (strlen($user->colors))
                $result = json_decode($user->colors, true);
            else
                $result = $this->getDefaultColors();
        } else {
            $result = $this->getDefaultColors();
        }
        return $result;
    }

    private function getDefaultColors()
    {
        $result = array();
        $colors = (object)simplexml_load_file(CORE_REPOSITORY_REAL_PATH . "config/defaultColors.xml");
        foreach ($colors as $color) {
            $opt = array();
            $opt['description'] = (string)$color['description'];
            $opt['backgroundColor'] = (string)$color['backgroundColor'];
            $opt['borderColor'] = (string)$color['borderColor'];
            $opt['textColor'] = (string)$color['textColor'];
            $result[(string)$color['name']] = $opt;
        }
        return $result;
    }

    public function editColors()
    {
        $colors = App::$instance->user->colors;
        if (in_array(App::$instance->user->role, array(ROLE_USER, ROLE_VIP))) {
            $permittedColors = array("local_default", "local_im_owner", "local_simple");
        } else if (in_array(App::$instance->user->role, array(ROLE_ADMIN, ROLE_ADMIN_MODERATOR))) {
            foreach ($this->getDefaultColors() as $colorName => $color) {
                $permittedColors[] = $colorName;
            }
        }
        $this->render('users/editColors', compact('colors', 'permittedColors'));
    }

    public function storeColors()
    {
        Token::checkToken();
        if ($this->request->request->has('color')) {
//            dump($this->request->request->get('color'));

            foreach ($this->request->request->get('color') as $color) {
//                dump($color);
//                die;
                $this->validator->validate([
                    'Имя плашки для ' . $color['name'] => [$color['name'], 'required'],
                    'Описание для ' . $color['name'] => [$color['description'], 'required'],
                    'Фоновый цвет в ' . $color['name'] => [$color['backgroundColor'], 'required'],
                    'Цвет границы ' . $color['name'] => [$color['borderColor'], 'required'],
                    'Цвет текста ' . $color['name'] => [$color['textColor'], 'required'],
                ]);
                //if no passes
                if (!$this->validator->passes()) {
                    $this->putUserDataAtBackPack($this->request);
                    App::$instance->MQ->setMessage($this->validator->errors()->all());
                    ST::redirect("back");
                }
            }
            //prepare for json save
            $result = array();
            foreach ($this->request->request->get('color') as $color) {
                $result[$color['name']] = array(
                    'description' => $color['description'],
                    'backgroundColor' => "#" . $color['backgroundColor'],
                    'borderColor' => "#" . $color['borderColor'],
                    'textColor' => "#" . $color['textColor']
                );
            }
            $result = array_merge($this->getDefaultColors(), $result);
//            dump($result);
//            die;
            $user = User::find(App::$instance->user->id);
            $user->colors = json_encode($result);
            $user->save();
            App::$instance->user->colors = json_encode($result);
            App::$instance->MQ->setMessage('Цветовая схема обновлена');

        } else {
            App::$instance->MQ->setMessage('Не задан обязательный элемент, цветвоая схема не сохранена');
        }
        ST::redirect("back");
    }

    public function restoreDefaultsColors()
    {
        $user = User::find(App::$instance->user->id);
        $user->colors = Null;
        $user->save();
        App::$instance->MQ->setMessage('Цветовая схема сброшена');
        ST::redirect("back");
    }
}