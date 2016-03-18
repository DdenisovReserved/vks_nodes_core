<?php

class Settings_controller extends Controller
{

    public function editServersLoad()
    {
        Auth::isAdminOrDie(App::$instance);

        if (!is_file('config/servers-load-cfg.xml')) {
            file_put_contents('config/servers-load-cfg.xml', "<root><server alias='Основной (автогенерация)' capacity='100'></server>
    </root>");
        }

        $servers = (object)simplexml_load_file("config/servers-load-cfg.xml");

        $this->render('settings/server_load/edit', compact('servers'));
    }

    public function storeServersLoad()
    {
        Token::checkToken();

        $xml = new SimpleXMLElement('<root/>');

        if ($this->request->request->has('server'))
            foreach ($this->request->request->get('server') as $server) {
                $this->validator->validate([
                    'Имя сервера' => [$server['alias'], 'required|max(255)'],
                    'Производительность' => [$server['capacity'], 'required|int'],

                ]);
                //if no passes
                if (!$this->validator->passes()) {
                    $this->putUserDataAtBackPack($this->request);
                    App::$instance->MQ->setMessage($this->validator->errors()->all());
                    ST::redirect("back");
                }
                $srvXml = $xml->addChild('server');
                $srvXml->addAttribute('alias', $server['alias']);
                $srvXml->addAttribute('capacity', $server['capacity']);
            }


        $xml->asXML("config/servers-load-cfg.xml");

        App::$instance->MQ->setMessage('Список обновлен');

        App::$instance->log->logWrite(LOG_CONFIG_CHANGE, 'изменен список серверов ВКС');

        ST::redirect("back");
    }

    public function getServerParam($serverNum)
    {

        $servers = simplexml_load_file("config/servers-load-cfg.xml");

        return $servers->server[intval($serverNum)];

    }

    public function editSimpleVksCodeSet()
    {
        Auth::isAdminOrDie(App::$instance);


        $code = $this->getSimpleVksCodeParams();

        $this->render('settings/simple_vks_code/edit', compact('code'));
    }

    public function storeSimpleVksCodeSet()
    {
        Token::checkToken();
        $request = $this->request;
        $this->validator->validate([
            'Стартовый параметр ' => [$request->get('start'), 'required'],
            'Параметр окончания' => [$request->get('end'), 'required'],

        ]);
        if (floatval($request->get('end')) - floatval($request->get('start')) < 0 || floatval($request->get('end')) - floatval($request->get('start')) > 100) {
            App::$instance->MQ->setMessage('Некорректная конфигурация пула, проверьте вводимые значения, пул не может быть меньше 0 и больше 100', 'danger');
            ST::redirect("back");
        }
        //if no passes
        if (!$this->validator->passes()) {
            App::$instance->MQ->setMessage($this->validator->errors()->all());
            ST::redirect("back");
        }
        $xml = new SimpleXMLElement('<root/>');
        $srvXml = $xml->addChild('code');
        $srvXml->addAttribute('start', ($request->get('start')));
        $srvXml->addAttribute('end', ($request->get('end')));

        $xml->asXML("config/simple-vks-codes-cfg.xml");

        App::$instance->MQ->setMessage('Список обновлен');

        App::$instance->log->logWrite(LOG_CONFIG_CHANGE, 'изменен список кодов для простых ВКС');

        ST::redirect("back");

        ST::redirect("back");
    }

    public function getSimpleVksCodeParams()
    {

        if (!is_file('config/simple-vks-codes-cfg.xml')) {
            file_put_contents('config/simple-vks-codes-cfg.xml', "<root><code start='0' end='10'></code>
            </root>");
        }

        return (object)simplexml_load_file("config/simple-vks-codes-cfg.xml");

    }

    public function editCodeDelivery()
    {
        Auth::isAdminOrDie(App::$instance);
        $this->castCodeDelivery();
        $codes = (object)simplexml_load_file("config/code-delivery-cfg.xml");
        $this->render('settings/code_delivery/edit', compact('codes'));
    }

    public function storeCodeDelivery()
    {
        Auth::isAdminOrDie(App::$instance);
        Token::checkToken();


        $xml = new SimpleXMLElement('<root/>');

        if ($this->request->request->has('option')) {
            foreach ($this->request->request->get('option') as $option) {
                $this->validator->validate([
                    'Имя' => [$option['name'], 'required|max(100)'],
                    'основа' => [$option['core'], 'required|max(120)'],
                    'Хвост' => [$option['mean'], 'required|int|between(1,10)'],
                    'Начинать с' => [$option['start'], 'required|int'],
                    'До' => [$option['end'], 'required|int'],
                ]);
                //if no passes
                if (!$this->validator->passes()) {
                    $this->putUserDataAtBackPack($this->request);
                    App::$instance->MQ->setMessage($this->validator->errors()->all());
                    ST::redirect("back");
                }
                //compare range
                if (intval($option['mean']) < strlen(intval($option['start'])) ||
                    intval($option['mean']) < strlen(intval($option['end']))
                ) {
                    $this->putUserDataAtBackPack($this->request);
                    App::$instance->MQ->setMessage('Начальная или конечная цифра диапазона длиннее заданного хвоста', 'danger');
                    ST::redirect("back");
                }

                if (intval($option['start']) > intval($option['end'])) {
                    $this->putUserDataAtBackPack($this->request);
                    App::$instance->MQ->setMessage('Начальное значение диапазона меньше конечного', 'danger');
                    ST::redirect("back");
                }

                if (count(range($option['start'], $option['end'])) > 100) {
                    $this->putUserDataAtBackPack($this->request);
                    App::$instance->MQ->setMessage('Ошибка: Диапазон не может содержать более 100 кодов', 'danger');
                    ST::redirect("back");
                }

                $srvXml = $xml->addChild('option');

                $srvXml->addAttribute('uid', md5(rand() . App::$instance->main->appkey . $option['core']));
                $srvXml->addAttribute('core', $option['core']);
                $srvXml->addAttribute('mean', $option['mean']);
                $srvXml->addAttribute('name', $option['name']);
                $srvXml->addAttribute('start', $option['start']);
                $srvXml->addAttribute('end', $option['end']);

            }

            $xml->asXML("config/code-delivery-cfg.xml");

            App::$instance->MQ->setMessage('Список обновлен');

            App::$instance->log->logWrite(LOG_CONFIG_CHANGE, 'изменены настройки в выдаче кодов');
        } else {
            App::$instance->MQ->setMessage('Не сохранено из-за ошибки');
        }
        ST::redirect("back");
    }

    public function getCodeDelivery($build = false, $uid = false)
    {
        Auth::isAdminOrDie(App::$instance);
        $this->castCodeDelivery();
        $codes = (object)simplexml_load_file("config/code-delivery-cfg.xml");
        $result = [];

        foreach ($codes as $option) {
            $opt = new stdClass();
            $opt->uid = (string)$option['uid'];
            $opt->core = (string)$option['core'];
            $opt->name = (string)$option['name'];
            $opt->mean = (string)$option['mean'];
            $opt->start = (string)$option['start'];
            $opt->end = (string)$option['end'];
            $result[] = $opt;

        }

        if ($build)
            foreach ($result as $key => $codeTemplate) {
                $tailCodes = [];
                foreach (range($codeTemplate->start, $codeTemplate->end) as $code) {
                    $codehead = Null;
                    $castNulls = $codeTemplate->mean - strlen($code);
                    while ($castNulls != 0) {
                        $codehead .= "0";
                        $castNulls--;
                    }
                    $tailCodes[] = $codehead . $code;
                }
                $codeTemplate->tail_codes = $tailCodes;
            }

        if ($uid) {
            $r = [];
            foreach ($result as $res) {
//                dump($uid,$res->uid,$uid == $res->uid);
                if ($uid == $res->uid) {
                    $r = $res;
                    break;
                }

            }
            $result = $r;
        }

//        dump($result);
        if (ST::isAjaxRequest()) {
            print json_encode($result);
        } else {
            return $result;
        }


    }

    public function getCodesPostfixSet()
    {
        Auth::isAdminOrDie(App::$instance);
        $codes = (object)simplexml_load_file("config/code-delivery-cfg.xml");
        $result = [];
        foreach ($codes as $option) {
            $start = (string)$option['start'];
            $end = (string)$option['end'];
            $range = range(intval($start), intval($end));
            foreach ($range as $num) {
                if (!in_array($num, $result)) {
                    $result[] = $num;
                }
            }
        }
        sort($result);
        return $result;
    }

    public function getCodesCores()
    {
        Auth::isAdminOrDie(App::$instance);
        $this->castCodeDelivery();
        $codes = (object)simplexml_load_file("config/code-delivery-cfg.xml");
        $result = [];

        foreach ($codes as $option) {

            if (!in_array((string)$option['core'], $result)) {
                $result[] = (string)$option['core'];
            }
        }

        if (ST::isAjaxRequest()) {
            print json_encode($result);
        } else {
            return $result;
        }

    }

    private function castCodeDelivery()
    {
        Auth::isAdminOrDie(App::$instance);
        if (!is_file('config/code-delivery-cfg.xml')) {
            $content = "<root>";
            $content .= "<code uid='" . md5(rand() . App::$instance->main->appkey) . "' name='Основной (автогенерация)' core='1234566' mean='3' start='1' end='10'/>";
            $content .= "</root>";
            file_put_contents('config/code-delivery-cfg.xml', $content);
        }

    }

    public function editOther()
    {
        Auth::isAdminOrDie(App::$instance);
        $this->castOtherConfigIfNotExist();
        $options = (object)simplexml_load_file("config/other-cfg.xml");
        $this->render('settings/others/edit', compact('options'));

    }

    public function storeOther()
    {
        Auth::isAdminOrDie(App::$instance);
        Token::checkToken();
        $xml = new SimpleXMLElement('<root/>');

        if ($this->request->request->has('option')) {
            foreach ($this->request->request->get('option') as $option) {
                if ($option['name'] == 'help-phone') {
                    $this->validator->validate([
                        $option['description'] => [$option['value'], 'max(15)'],
                    ]);
                } elseif (in_array($option['name'], [
                    'attendance_strict',
                    'attendance_check_enable',
                    'notify_admins'
                ])) {
                    $this->validator->validate([
                        $option['description'] => [$option['value'], 'between(0,1)'],
                    ]);
                } else {
                    $this->validator->validate([
                        $option['description'] => [$option['value'], 'required'],
                    ]);
                }

                //if no passes
                if (!$this->validator->passes()) {
                    $this->putUserDataAtBackPack($this->request);
                    App::$instance->MQ->setMessage($this->validator->errors()->all());
                    ST::redirect("back");
                }
                $srvXml = $xml->addChild('option');
                $srvXml->addAttribute('description', $option['description']);
                $srvXml->addAttribute('name', $option['name']);
                $srvXml->addAttribute('value', $option['value']);

            }

            $xml->asXML("config/other-cfg.xml");

            App::$instance->MQ->setMessage('Список обновлен');

            App::$instance->log->logWrite(LOG_CONFIG_CHANGE, 'изменены общие настройки');
        }
        ST::redirect("back");
    }

    public static function getOther($key = false, $json = false)
    {
        $sc = new self();
        $sc->castOtherConfigIfNotExist();
        $options = (object)simplexml_load_file(NODE_REAL_PATH . "config/other-cfg.xml");
//        dump($options);
        $result = [];
        foreach ($options as $option) {
            $opt = new stdClass();
            $opt->description = $option['description'];
            $opt->name = $option['name'];
            $opt->value = $option['value'];
            $result[] = $opt;
        }
        if ($key) {
            $search = false;
            foreach ($result as $option)
                if ($option->name == $key)
                    $search = $option;
            if ($json) {
                print json_encode($search->value);
            } else {
                return $search->value;
            }

        } else {
            if ($json) {
                print json_encode($result);
            } else {
                return $result;
            }
        }

    }


    private function castOtherConfigIfNotExist()
    {
        if (!is_file(NODE_REAL_PATH . 'config/other-cfg.xml')) {
            file_put_contents(NODE_REAL_PATH . 'config/other-cfg.xml', "<root>
<option name='pause_gap' value='30' description='Технологическая пауза между ВКС, мин. Используется при проверке кодов'/>
<option name='help-phone' value='' description='Телефон поддержки ВКС'/>
<option name='attendance_check_enable' value='1' description='Проверять точки ВКС на занятость в других ВКС'/>
<option name='attendance_strict' value='0' description='Строгий режим проверки занятости точек ВКС'/>
<option name='notify_admins' value='1' description='Уведомлять админов по почте о новых ВКС'/>
<option name='global_vks_limit' value='30' description=\"Кол-во учасников у ВКС для получения флага 'Глобальная'\"/>
</root>");
        }
    }


    public function getPublicMessage()
    {
        if (file_exists(NODE_REAL_PATH . "config/pm-cfg.xml")) {
            $pmSettings = (object)simplexml_load_file(NODE_REAL_PATH . "config/pm-cfg.xml");
            $pm['content'] = strval($pmSettings->pm->content);
            $pm['active'] = intval($pmSettings->pm->active);

        } else {
            $pm['content'] = '';
            $pm['active'] = 0;
        }
//        dump($pm);
        return $pm;
    }


    public function managePublicMessage()
    {
        if (file_exists(NODE_REAL_PATH . "config/pm-cfg.xml")) {
            $pmSettings = (object)simplexml_load_file(NODE_REAL_PATH . "config/pm-cfg.xml");
            $pm['content'] = strval($pmSettings->pm->content);
            $pm['active'] = intval($pmSettings->pm->active);
        } else {
            $pm['content'] = '';
            $pm['active'] = 0;
        }

        $request = $this->request->request;
        $request->set('content', $pm['content']);
        $request->set('active', $pm['active']);
        return $this->render('settings/pm/manage');
    }

    public function storePublicMessage()
    {
        Token::checkToken();
        $request = $this->request->request;
        $xml = new SimpleXMLElement('<root/>');
        $this->validator->validate([
            'Содержание объявления' => [$request->get('content'), 'max(1200)'],
        ]);

        //if no passes
        if (!$this->validator->passes()) {
            $this->putUserDataAtBackPack($this->request);
            App::$instance->MQ->setMessage($this->validator->errors()->all());
            ST::redirect("back");
        }
        $srvXml = $xml->addChild('pm');
        $srvXml->addChild('content', $request->get('content'));
        $srvXml->addChild('active', $request->has('active') ? 1 : 0);
        $xml->asXML("config/pm-cfg.xml");
        App::$instance->log->logWrite(LOG_CONFIG_CHANGE, "Изменено пубуличное сообщение");

        App::$instance->MQ->setMessage('Успешно сохранено');
        ST::redirectToRoute("Index/index");
    }


    public function manageHelp()
    {
        if (file_exists(NODE_REAL_PATH . "config/help_standart.xml")) {
            $helpMessages = (object)simplexml_load_file(NODE_REAL_PATH . "config/help_standart.xml");
        } else {
            App::$instance->log->logWrite(LOG_OTHER_EVENTS, "Файл с сообщениями помощи не найден");
            $this->error('500');
        }
        $helps = [];
        $c = 1;
        foreach($helpMessages as $help) {
            $helps[$c]['name'] = trim(strval($help->name));
            $helps[$c]['content'] = trim(strval($help->content));
            $helps[$c]['humanized'] = trim(strval($help->humanized));
            $c++;
        }

        return $this->render('settings/help/manage', compact('helps'));
    }

    public function storeHelp()
    {
        Token::checkToken();
        $xml = new SimpleXMLElement('<root/>');

        if ($this->request->request->has('help')) {
            foreach ($this->request->request->get('help') as $help) {
                    $this->validator->validate([
                        $help['humanized'] => [$help['content'], 'required|max(320)'],
                    ]);

                //if no passes
                if (!$this->validator->passes()) {
                    $this->putUserDataAtBackPack($this->request);
                    App::$instance->MQ->setMessage($this->validator->errors()->all());
                    ST::redirect("back");
                }
                $srvXml = $xml->addChild('element');
                $srvXml->addChild('humanized', $help['humanized']);
                $srvXml->addChild('name', $help['name']);
                $srvXml->addChild('content', $help['content']);
            }

            $xml->asXML("config/help_standart.xml");

            App::$instance->MQ->setMessage('Список обновлен');

            App::$instance->log->logWrite(LOG_CONFIG_CHANGE, 'изменены сообщения помощи');
        } else {
            App::$instance->MQ->setMessage('Нет обязательного параметра');
        }

        return ST::redirectToRoute('index/index');
    }


}