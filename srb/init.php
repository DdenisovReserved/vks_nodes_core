<?php
//init ORM
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher as Dispatcher;

App::$instance->capsule = new Capsule;
//user params
App::$instance->capsule->addConnection(array(
    'driver'    => App::$instance->db->type,
    'host'      => App::$instance->db->host,
    'database'  => App::$instance->db->dbname,
    'username'  => App::$instance->db->user,
    'password'  => App::$instance->db->pass,
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => App::$instance->db->prefix
),'default');

require_once(CORE_APP_PATH.'config/config.php');
global $params;
App::$instance->capsule->addConnection(array(
    'driver'    => $params['db']['type'],
    'host'      => $params['db']['host'],
    'database'  => $params['db']['dbname'],
    'username'  => $params['db']['user'],
    'password'  => $params['db']['pass'],
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => $params['db']['prefix'],
),'coreCaDb');


App::$instance->capsule->setEventDispatcher(new Dispatcher());

App::$instance->capsule->setAsGlobal();

App::$instance->capsule->bootEloquent();

session_start(App::$instance->main->appkey);
//init twig
$mv = new Middleware();

Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem(CORE_REPOSITORY_REAL_PATH.'/views');
$app->twig = new Twig_Environment($loader, array(
    'cache' => 'views/_cache',
    'auto_reload' => true,
    'debug' => true,
));

$app->twig->addExtension(new Twig_Extension_Debug());

$app->twig->addGlobal('session',@$_SESSION);
//init logger system
App::$instance->log = new VksLogger();

App::$instance->cache = new Cache();

App::$instance->routes = $mv->getRoutes();
//session_save_path('/home/inilotic/public_html/sessions');
ini_set('session.gc_probability', 1);


