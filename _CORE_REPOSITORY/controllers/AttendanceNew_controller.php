<?php

class AttendanceNew_controller extends Controller
{
    use sorterTrait;

    //show branch, start from $rootId
    public function show($rootId = 1)
    {
        Auth::isAdminOrDie(App::$instance);
        if (self::isContainer($rootId)) {
            $points = Attendance::where('parent_id', $rootId)
                ->with('childs')
                ->take($this->getQlimit(30))
                ->skip($this->getQOffset())
                ->orderBy('container', 'desc')
                ->orderBy($this->getQOrder('name'), $this->getQVector('asc'))
                ->get();
            $recordsCount = Attendance::where('parent_id', $rootId)->count();
            $breadCrumps = self::fullParentInfo($rootId);
            $pages = RenderEngine::makePagination($recordsCount, $this->getQlimit(30), 'route');
            $this->render('attendance/v2/show', compact('points', 'breadCrumps', 'pages'));
        } else {
            die("this is not container");
        }

    } //function end

    public function create($rootId = 1)
    {
        $data['rootId'] = $rootId;
        $data['containers'] = $this->containersList();
//        dump($data['containers']);
        if (self::isContainer($data['rootId'])) {
            $this->render('attendance/v2/create', $data);
        } else {
            die("this is not container");
        }
    } //function end

    public function store()
    {


        $request = $this->request->request;
//        dump($request);
//        die;
        if (!$request->has('point')) {
            App::$instance->MQ->setMessage('bad params given', 'danger');
            ST::redirect("back");
        }
        $result = array();
        foreach ($request->get('point') as $point) {

            $this->validator->validate([
                'Имя' => [$point['name'], 'required|max(160)'],
                'Тип' => [$point['container'], 'between(0,1)'],
                'Родитель' => [$point['parent_id'], 'int'],

            ]);
            //if no passes
            if (!$this->validator->passes()) {
                $this->putUserDataAtBackPack($this->request);
                App::$instance->MQ->setMessage($this->validator->errors()->all());
                ST::redirect("back");
            }
            $att = new Attendance();
            $att->fill($point);
            $att->active = isset($point['active']) ? 1 : 0;
            $att->check = isset($point['check']) && !$att->container ? 1 : 0;
            $att->save();
            $result[] = "{$att->name} создана успешно";
        }


        App::$instance->MQ->setMessage($result);
        ST::redirect("?route=AttendanceNew/show/" . $att->parent_id);
    }

    public function edit($id)
    {
        $backPack = Attendance::findOrFail($id);
        $containers = $this->containersList();
        $this->render('attendance/v2/edit', compact('backPack', 'containers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
        $att = Attendance::findOrFail($id);
        $this->validator->validate([
            'Имя' => [$request->request->get('name'), 'required|max(160)'],
            'Тип' => [$request->request->get('container'), 'between(0,1)'],
            'Родитель' => [$request->request->get('parent_id'), 'int'],
        ]);
        //if no passes
        if (!$this->validator->passes()) {
            $this->putUserDataAtBackPack($this->request);
            App::$instance->MQ->setMessage($this->validator->errors()->all());
            ST::redirect("back");
        }
        $att->fill($request->request->all());
        $att->active = ($request->request->has('active')) ? 1 : 0;
        $att->check = ($request->request->has('check')) ? 1 : 0;
        $att->save();
        App::$instance->MQ->setMessage("Успешно отредактировано");
        ST::redirect("?route=AttendanceNew/show/" . $att->parent_id);
    }

    public function delete($id)
    {
        $att = Attendance::findOrFail($id);
        $att->delete();
        App::$instance->MQ->setMessage("Успешно удалено");
        ST::redirect("?route=AttendanceNew/show/" . $att->parent_id);
    }

    public static function isContainer($id)
    {
        //if root return true
        if ($id == 0) return true;
        //try find it
        $point = Attendance::findOrFail($id);
        return ($point->container) ? true : false;
    }

    public static function getTbs()
    {
        //if root return true

        $tbs = Attendance::where("is_tb", 1)->where('active', 1)->get();

        return $tbs;
    }

    private function containersList()
    {

        $containers = Attendance::where('container', 1)->get(['id', 'name']);

        foreach ($containers as $container) {
            $container->name = $container->full_path;
        }
        return $containers;
    }

    public static function fullParentInfo($startId)
    {

        $cacheName = "att.".App::$instance->tbId.".parentInfo.{$startId}";
        $result = App::$instance->cache->get($cacheName);

        if (!$result) {
            $result = [];

            if ($startId == 1) {
                return false;
            } else {
                $att = Attendance::find($startId);
                $result[] = $att;
            }
            while ($att->parent_id != 1) {
                $att = Attendance::find($att->parent_id);
                $result[] = $att;
            }

            $cachedObj = new CachedObject($result, ['tag.' . $cacheName]);

            App::$instance->cache->set($cacheName, $cachedObj, 3600*24*7);
        }

        return array_reverse($result);
    }

    public static function isLocationString($locationVal)
    {
        return !intval($locationVal) != 0 ? true : false;
    }

    public function browseLocation()
    {
        return $this->render('attendance/v2/browse-location');
    }

    public function browseParticipants()
    {
        return $this->render('attendance/v2/browse-participants');
    }

    public function apiGetTree($cid = 1, $cOrder = 'name_to_asc', $pOrder = 'name_to_asc')
    {
        $cacheName = "att.".App::$instance->tbId.".tree.{$cid}.{$cOrder}.{$pOrder}";

        $result = App::$instance->cache->get($cacheName);
        if (!$result) {

            $result['path'] = self::fullParentInfo($cid);

            $result['containers'] = Attendance::where('parent_id', $cid)->where('active', 1)->where('container', 1)->with(['childs' => function ($query) {
                $query->where('active', 1);
            }])->orderBy(explode("_to_", $cOrder)[0], explode("_to_", $cOrder)[1])->take(200)->get();

            $result['points'] = Attendance::where('parent_id', $cid)->where('active', 1)->where('container', 0)->orderBy(explode("_to_", $pOrder)[0], explode("_to_", $pOrder)[1])->take(200)->get();

            //put orders
            $result['cOrder'] = $cOrder;
            $result['pOrder'] = $pOrder;


            $cachedObj = new CachedObject($result, ['tag.' . $cacheName, 'tag.att.tree', "tag.att.".App::$instance->tbId.".tree"]);
            App::$instance->cache->set($cacheName, $cachedObj, 3600 * 24 * 3);
        }

        print json_encode($result);
    }


    public function apiGetPointBusyAt($render = false)
    {
        $result = [];
        $cid = $this->request->request->has("id") ? $this->request->request->get("id") : 1;
        $except = $this->request->request->has("except") ? $this->request->request->get("except") : [];
        $dateTimeforCheck = $this->request->request->has("dateTimeforCheck") ? $this->request->request->get("dateTimeforCheck") : [];

        $point = Attendance::where('id', $cid)
            ->where('active', 1)->first();

//        dump($dateTimeforCheck);
        if ($point) {
            if ($point->check) {
                if (count($dateTimeforCheck)) {
                    foreach ($dateTimeforCheck as $dateTimeArr) {
                        $start = date_create($dateTimeArr['date'] . " " . $dateTimeArr['start_time']);
                        $end = date_create($dateTimeArr['date'] . " " . $dateTimeArr['end_time']);
//                        dump($point->id, $start, $end);
                        $result[] = $this->busyAt($point->id, $start, $end, $except);

                    }
                }
            }
        }

        if ($render) {
            $resp['html'] = "<div><h3>Вкс в которых участвует <b>{$point->name}</b></h3><ul>";
            $vc = new Vks_controller();
            foreach($result as $resset) {
                foreach($resset as $vks) {
                    $vc->humanize($vks);
                    $resp['html'] .= "<li>".ST::linkToVksPage($vks->id, true)." {$vks->title} <br>{$vks->humanized->date} c {$vks->humanized->startTime} до {$vks->humanized->endTime}</li>";
                }
            }

            $resp['html'] .= '</ul></div>';
//            print $resp['html'];
            print json_encode($resp['html']);
        } else{
            print json_encode($result);
        }



    }


    public function apiGetFastTree()
    {
        $cid = $this->request->request->has("id") ? $this->request->request->get("id") : 1;
        $except = $this->request->request->has("except") ? $this->request->request->get("except") : [];
        $dateTimeforCheck = $this->request->request->has("dateTimeforCheck") ? $this->request->request->get("dateTimeforCheck") : [];
        $strict_option = intval(Settings_controller::getOther('attendance_strict'));
        $result['path'] = $this->fullParentInfo($cid);

        $cacheName = "tag.att.".App::$instance->tbId.".tree.{$cid}.".implode(",", $except);

        $points = App::$instance->cache->get($cacheName);

        if (!$points) {
            $points = Attendance::where('parent_id', $cid)
                ->where('active', 1)
                ->whereNotIn("id", $except)
                ->take(500)
                ->orderBy('container', 'desc')
                ->orderBy('name', 'asc')
                ->get();

            $cachedObj = new CachedObject($points, ['tag.' . $cacheName, "tag.att.".App::$instance->tbId.".tree"]);
            App::$instance->cache->set($cacheName, $cachedObj, 3600*24*3);
        }



        foreach ($points as $point) {
            $point->free = true;
            $point->selectable = true;
            $point->path = $this->makePathString($this->fullParentInfo($point->id));
            if ($point->container) {
                $result['containers'][] = $point;
            } else {
                if ($point->check) {
                    if (count($dateTimeforCheck)) {
                        foreach ($dateTimeforCheck as $dateTimeArr) {
                            $start = date_create($dateTimeArr['date'] . " " . $dateTimeArr['start_time']);
                            $end = date_create($dateTimeArr['date'] . " " . $dateTimeArr['end_time']);
//                            dump($point->id, $start, $end);

                            $check = $this->isFree($point->id, $start, $end);
//                            die;
                            if (!$check) {
                                $point->free = false;
                                if ($strict_option) {
                                    if (!Auth::isAdmin(App::$instance)) {
                                        $point->selectable = false;
                                    }
                                }
                                break;
                            }
                        }
                    }
                }
                $result['points'][] = $point;
            }
        }
        print json_encode($result);
    }

    public function apiSearchTree($phrase)
    {

        $result['containers'] = Attendance::where("name", "like", "%{$phrase}%")->where('container', 1)->with('childs')->take(10)->get();

        $result['points'] = Attendance::where("name", "like", "%{$phrase}%")->where('container', 0)->take(10)->get();

        print json_encode($result);
    }

    public function apiSearchFastTree($phrase, $except = [], $dateTimeforCheck = [])
    {
        if (!is_array($except) && strlen($except) > 0) {
            $except = explode(",", $except);
        }
        if (!is_array($except) && strlen($except) > 0) {
            $except = explode(",", $except);
        }

        $strict_option = intval(Settings_controller::getOther('attendance_strict'));

        $result = [];
        $points = Attendance::where("name", "like", "%{$phrase}%")
            ->where('active', 1)
            ->whereNotIn("id", $except)
            ->take(500)->get();


        foreach ($points as $point) {
            $point->free = true;
            $point->selectable = true;
            $point->path = $this->makePathString($this->fullParentInfo($point->id));
            if ($point->container) {
                $result['containers'][] = $point;
            } else {
                if ($point->check) {
                    if (count($dateTimeforCheck)) {
                        foreach ($dateTimeforCheck as $dateTimeArr) {
                            $start = date_create($dateTimeArr['date'] . " " . $dateTimeArr['start_time']);
                            $end = date_create($dateTimeArr['date'] . " " . $dateTimeArr['end_time']);
//                            dump($point->id, $start, $end);

                            $check = $this->isFree($point->id, $start, $end);
//                            die;
                            if (!$check) {
                                $point->free = false;
                                if ($strict_option) {
                                    if (!Auth::isAdmin(App::$instance)) {
                                        $point->selectable = false;
                                    }
                                }
                                break;
                            }
                        }
                    }
                }
                $result['points'][] = $point;
            }
        }

        print json_encode($result);
    }


    static public function makeStackName($stackType = STACK_SINGLE)
    {
        Auth::isLoggedOrDie(App::$instance);
        $result = '';
        try {
            $result = sha1($stackType . date_create()->format("Y-m-d") . App::$instance->user->login . App::$instance->main->appkey);
        } catch (Exception $e) {
            ST::routeToErrorPage('500');
        }
        return $result;

    }

    public function getStackData($stackName)
    {
        $result = [];
        $c = 0;
        if (isset($_COOKIE[$stackName]) && !empty($_COOKIE[$stackName])) {
            foreach (explode("@@DELIM@@", $_COOKIE[$stackName]) as $id) {
                //if it's phone
                if (!AttendanceNew_controller::isLocationString($id)) {
                    $result[$c]['path'] = self::fullParentInfo($id);
                    $result[$c]['pathString'] = $this->makePathString(self::fullParentInfo($id));
                } else {
                    $result[$c]['pathString'] = $id;
                }

                $result[$c]['id'] = $id;
                $c++;
            }
        }

//        ST::makeDebug($result);
        print json_encode($result);
    }

    //return boolean

    public function getStackArray($stackName)
    {
        $result = [];
        $c = 0;
        if (isset($_COOKIE[$stackName]) && !empty($_COOKIE[$stackName])) {
            foreach (explode("@@DELIM@@", $_COOKIE[$stackName]) as $id) {
                if (!AttendanceNew_controller::isLocationString($id)) {
                    $result[$c]['path'] = self::fullParentInfo($id);
                    $result[$c]['pathString'] = $this->makePathString(self::fullParentInfo($id));
                } else {
                    $result[$c]['pathString'] = $id;
                }
                $result[$c]['id'] = $id;
                $c++;
            }
        }
        return $result;
    }

    static public function fillStackWithData(array $list, $stackName)
    {
        if (!$stackName) throw new \Symfony\Component\Process\Exception\LogicException("bad stack name, or it's empty");
        setcookie($stackName, implode("@@DELIM@@", $list));

    }

    static public function clearStackData($stackName)
    {
        if (isset($_COOKIE[$stackName]) && !empty($_COOKIE[$stackName])) {
            setcookie($stackName, false, time() - 3600);
        }
    }

    public function makePathString(array $path)
    {

        $result = '';
        foreach ($path as $point) {
            $result .= $point->name . " - ";
        }
        return substr($result, 0, -3);
    }

    public function browseTest()
    {
        return $this->render('attendance/v2/browse-test');
    }

    public static function makeFullPath($startAttendanceId)
    {
        $cacheName = "att.".App::$instance->tbId.".fullpath.{$startAttendanceId}";
        $path = App::$instance->cache->get($cacheName);

        if (!$path) {
            if (self::isLocationString($startAttendanceId)) return $startAttendanceId;

            if ($startAttendanceId == 1) return false;
            try {
                $att = Attendance::findOrFail($startAttendanceId);
            } catch (Exception $e) {
                return 'Место проведения не найдено.';
            }

            $result[] = $att->name;

            while ($att->parent_id != 1) {
                $att = Attendance::findOrFail($att->parent_id);
                $result[] = $att->name;

            }

            $path = implode(" - ", array_reverse($result));

            $cachedObj = new CachedObject($path, ['tag.' . $cacheName]);
            App::$instance->cache->set($cacheName, $cachedObj, 3600 * 24 * 3);
        }

        return $path;
    }

    public function isFree($attId, $vksStart, $vksEnd, $exceptVksIds = [])
    {
        $vkses = Vks::where('start_date_time', "<=", $vksEnd)
            ->where('end_date_time', '>=', $vksStart)
            ->whereNotIn('id', $exceptVksIds)
            ->whereIn('status', [VKS_STATUS_APPROVED, VKS_STATUS_PENDING])
            ->with("participants")->get();
        if (count($vkses)) {
            foreach ($vkses as $vks) {
                if (count($vks->participants)) {
                    foreach ($vks->participants as $parp) {
                        if (intval($attId) === intval($parp->id)) return false;
                    }
                }
            }
        }

        return true;
    }

    public function busyAt($attId, $vksStart, $vksEnd, $exceptVksIds = [])
    {
//        dump($vksStart, $vksEnd, $exceptVksIds);
        $result = [];
        $vkses = Vks::where('start_date_time', "<=", $vksEnd)
            ->where('end_date_time', '>=', $vksStart)
            ->whereNotIn('id', $exceptVksIds)
            ->whereIn('status', [VKS_STATUS_APPROVED, VKS_STATUS_PENDING])
            ->with("participants")->get();
//        dump($vkses);
        if (count($vkses)) {
            foreach ($vkses as $vks) {
                if (count($vks->participants)) {
                    foreach ($vks->participants as $parp) {
                        if (intval($attId) === intval($parp->id)) {
                            $result[] = $vks;
                            break;
                        }
                    }
                }
            }
        }

        return $result;
    }
}