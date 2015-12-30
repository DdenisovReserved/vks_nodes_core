<?php

class Middleware extends Controller
{
    public function getRoutes()
    {
        $cacheName = app::$instance->tbId.'_routes';
        $result = App::$instance->cache->get($cacheName);
        if (!$result) {

            $routes = (object)simplexml_load_file(CORE_REPOSITORY_REAL_PATH."config/routes.xml");
            $result = [];

            foreach ($routes as $route) {

                $opt = new stdClass();
                $opt->controller = strtolower((String)$route->controller);
                $opt->action = explode(", ", strtolower((String)$route->action));
                $opt->methods = explode(", ", strtolower((String)$route->methods));
                $access_tmp = explode(", ", (String)$route->access);
                $opt->access = [];
                foreach($access_tmp as $stringRole) {
                    switch($stringRole){

                        case('*'):
                            $opt->access[] = '*';
                            break;
                        case('@'):
                            $opt->access[] = '@';
                            break;
                        case('ROLE_USER'):
                            $opt->access[] = ROLE_USER;
                            break;
                        case('ROLE_VIP'):
                            $opt->access[] = ROLE_VIP;
                            break;
                        case('ROLE_ADMIN'):
                            $opt->access[] = ROLE_ADMIN;
                            break;
                        case('ROLE_ADMIN_MODERATOR'):
                            $opt->access[] = ROLE_ADMIN_MODERATOR;
                            break;
                    }
                }

                $opt->ajax = strtolower($route->ajax) === 'true' ? true : false;
                $result[] = $opt;
            }

            $cachedObj = new CachedObject($result, ['tag.' . $cacheName]);
            App::$instance->cache->set($cacheName, $cachedObj, 3600*24*3);

        }

        return $result;
    }

    public function CheckRequestedRoute($controller, $action)
    {
        $routes = App::$instance->routes;
        $result = new stdClass();
        $result->controller = false;
        $result->action = false;
        $result->method = false;
        $result->access = false;
        $result->ajax = false;
//        dump($controller, $action);
        foreach ($routes as $route) {
            //is ajax?

            if (ST::isAjaxRequest() && $route->ajax) {
                $result->ajax = true;
            }
//            dump($result);
            //check requested controller
            if ($route->controller === $controller) {

                $result->controller = True;
                //check requested action
                foreach ($route->action as $founded_action) {

                    if ($founded_action === '*' || $founded_action === $action) {
                        $result->action = True;
                        //check requested method
                        foreach ($route->methods as $founded_method) {

                            if ($founded_method === '*' || $founded_method === strtolower($_SERVER['REQUEST_METHOD'])) {
                                $result->method = True;
                                //check requested access
                                foreach ($route->access as $founded_access) {

                                    if ($founded_access === '*') {
                                        $result->access = True;
                                        break;
                                    }

                                    if (Auth::isLogged(App::$instance) && $founded_access === '@') {
                                        $result->access = True;
                                        break;
                                    }

                                    if (Auth::isLogged(App::$instance)) {
                                        if ($founded_access === App::$instance->user->role) {
                                            $result->access = True;
                                            //check requested access
                                        }
                                    }
                                }
                            }
                        }
//                        break;
                    }
                }
//                break;
            }
        }
//        dump($result);
        return $result;
    }

    public function ProcessCheck(stdClass $requestCheckResult)
    {



        if (ST::isAjaxRequest() && !$requestCheckResult->ajax) {
            print json_encode(['response' => 'false']);
            die;
        }

        if (!$requestCheckResult->controller) {
            $this->error('404');
        }
        if (!$requestCheckResult->action) {
            $this->error('404');
        }
        if (!$requestCheckResult->method) {
            $this->error('router-method-restricted');
        }
        if (!$requestCheckResult->access) {
            $this->error('router-role-access-restricted');
        }
    }
}