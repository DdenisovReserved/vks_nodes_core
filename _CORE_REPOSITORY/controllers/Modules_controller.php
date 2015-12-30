<?php

class Modules_controller extends Controller
{

    public function edit()
    {
        $this->castModulesConfigIfNotExist();
        $modules = (object)simplexml_load_file("config/modules-cfg.xml");
//        dump(Self::askIsModuleEnabled('simple_vks'));
        $this->render('settings/modules/edit', compact('modules'));
    }

    public function store()
    {
        Token::checkToken();
        $xml = new SimpleXMLElement('<root/>');
//        dump($this->request->request->get('modules'));
//        die;
        if ($this->request->request->has('modules'))
            foreach ($this->request->request->get('modules') as $module) {
                $this->validator->validate([
                    'name' => [$module['name'], 'required|max(255)'],
                    'description' => [$module['description'], 'required|max(560)'],
                    'help' => [$module['help'], 'required|max(1000)'],
                    'value' => [$module['value'], 'between(0,1)']
                ]);
                //if no passes
                if (!$this->validator->passes()) {
                    $this->putUserDataAtBackPack($this->request);
                    App::$instance->MQ->setMessage($this->validator->errors()->all());
                    ST::redirect("back");
                }
                $srvXml = $xml->addChild('module');
                $srvXml->addAttribute('name', $module['name']);
                $srvXml->addAttribute('description', $module['name']);
                $srvXml->addAttribute('help', $module['help']);
                $srvXml->addAttribute('value', intval($module['value']));
            }


        $xml->asXML("config/modules-cfg.xml");

        App::$instance->MQ->setMessage('Список обновлен');

        App::$instance->log->logWrite(LOG_CONFIG_CHANGE, 'Изменен список модулей системы');

        ST::redirect("back");
    }

    private function castModulesConfigIfNotExist()
    {
        if (!is_file(NODE_REAL_PATH . 'config/modules-cfg.xml')) {
            file_put_contents(NODE_REAL_PATH. 'config/modules-cfg.xml', "<root>
<module name='simple_vks' value='1' description='Простые ВКС' help='Возможность работы с простыми ВКС'/>
<module name='test_module' value='1' description='Тестовый модуль' help='Ни на что не влияет'/>
</root>");
        }
    }

    public static function askIsModuleEnabled($moduleName)
    {

        $modules = (object)simplexml_load_file("config/modules-cfg.xml");
        $result = false;
        foreach ($modules as $module) {
            if (mb_strtolower($module['name']) == mb_strtolower($moduleName)) {
                if (intval($module['value']))
                    $result = true;
            }
        }
        return $result;
    }
}