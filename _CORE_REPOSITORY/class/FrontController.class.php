<?php

class FrontController extends Controller
{
    const DEFAULT_CONTROLLER = "IndexController";
    const DEFAULT_ACTION = "index";

    protected $controller = self::DEFAULT_CONTROLLER;
    protected $action = self::DEFAULT_ACTION;
    protected $params = array();


    public function __construct(array $options = array())
    {

        if (empty($options)) {
            $this->parseUri();

        } else {
            if (isset($options["controller"])) {
                $this->setController($options["controller"]);

            }
            if (isset($options["action"])) {
                $this->setAction($options["action"]);
            }
            if (isset($options["params"])) {
                $this->setParams($options["params"]);
            }
        }

    }

    protected function parseUri()
    {
        $path = isset($_GET['route']) ? $_GET['route'] : ST::redirectToRoute('Index/index');
        $path = trim(parse_url($path, PHP_URL_PATH), "/");

        @list($controller, $action, $params) = explode("/", $path, 3);


        $mv = new Middleware();

        $mv->ProcessCheck($mv->CheckRequestedRoute(strtolower($controller),strtolower($action)));

        if (isset($controller)) {
            $this->setController($controller);
        }
        if (isset($action)) {
            $this->setAction($action);

        }
        if (isset($params)) {
            $this->setParams(explode("/", $params));
        }

    }

    public function setController($controller)
    {
        $controller = $controller . "_controller";
        if (!class_exists($controller)) {
            $this->error('404');
        }
        $this->controller = $controller;
        return $this;
    }

    public function setAction($action)
    {

        $reflector = new ReflectionClass($this->controller);
        if (!$reflector->hasMethod($action)) {
            $this->error('404');
        }
        $this->action = $action;
        return $this;
    }

    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    public function run()
    {
        //add counter
        $params = count($this->params) ? "/".implode("/", $this->params): "";
        $mskTime = date_create()->setTimezone(new DateTimeZone(App::$instance->opt->ca_timezone));
        Counter::create([
            'origin'=>MY_NODE,
            'ip'=>App::$instance->user->ip,
            'request'=>$this->controller."/".$this->action . urlencode($params),
            'type'=>ST::isAjaxRequest() ? REQUEST_AJAX : REQUEST_REGULAR,
            'created_at'=>$mskTime,
            'updated_at'=>$mskTime
        ]);

        call_user_func_array(array(new $this->controller, $this->action), $this->params);



    }
    public static function getParams() {
        @list($controller, $action, $params) = explode("/", $_REQUEST['route'], 3);
        return $params;
    }
    public static function getController() {
        @list($controller, $action, $params) = explode("/", $_REQUEST['route'], 3);
        return $controller;
    }
    public static function getAction() {
        @list($controller, $action, $params) = explode("/", $_REQUEST['route'], 3);
        return $action;
    }
    public static function whereIm() {
        return mb_strtolower(self::getController()."/".self::getAction());
    }
}
