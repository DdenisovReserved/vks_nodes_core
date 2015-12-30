<?php
use Symfony\Component\HttpFoundation\Request;

class RenderEngine
{
    use sorterTrait;

    static function MenuChanger()
    {
        if (Auth::isAdmin(App::$instance)) {
            ST::deployTemplate('menus/admin.inc');
        } else {
            ST::deployTemplate('menus/user.inc');
        }

        //под меню добавить слушателя сообщений
        ExceptionHandler::messageException();
        //выдать путь для js, $init должен быть инициирован выше
        ST::setVarPhptoJS(App::$instance->opt->appHttpPath, "appHttpPath");
    } //func end

    static function makeVksDiffTable($diffArr)
    {

        if (empty($diffArr)) return false;

        $result = "<table class='vks-diff-table'>";
        $result .= "<th>#</th><th>Параметр</th><th>Было</th><th>Стало</th>";
        $cou = 1;
        foreach ($diffArr as $changedKey => $diffSet) {
            $result .= "<tr><td>{$cou}</td>";
            switch ($changedKey) {
                case ("title"):
                    $result .= "<td>Заголовок</td>
                                <td>{$diffSet['old']}</td>
                                <td>{$diffSet['new']}</td>";
                    break;
                case ("date"):
                    $result .= "<td>Дата</td>";
                    $result .= "<td>" . date_create($diffSet['old'])->format("d.m.Y") . "</td>
                                <td>" . date_create($diffSet['new'])->format("d.m.Y") . "</td>";
                    break;
                case ("start_date_time"):
                    $result .= "<td>Начало</td>";
                    $result .= "<td>" . date_create($diffSet['old'])->format("H:i") . "</td>
                                <td>" . date_create($diffSet['new'])->format("H:i") . "</td>";
                    break;
                case ("end_date_time"):
                    $result .= "<td>Окончание</td>";
                    $result .= "<td>" . date_create($diffSet['old'])->format("H:i") . "</td>
                                <td>" . date_create($diffSet['new'])->format("H:i") . "</td>";
                    break;
                case ("init_head_fio"):
                    $result .= "<td>Председательствующий</td>";
                    $result .= "<td>" . $diffSet['old'] . "</td>
                                <td>" . $diffSet['new'] . "</td>";
                    break;
                case ("init_customer_fio"):
                    $result .= "<td>ФИО Заказчика</td>";
                    $result .= "<td>" . $diffSet['old'] . "</td>
                                <td>" . $diffSet['new'] . "</td>";
                    break;
                case ("init_customer_phone"):
                    $result .= "<td>ФИО Заказчика</td>";
                    $result .= "<td>" . $diffSet['old'] . "</td>
                                <td>" . $diffSet['new'] . "</td>";
                    break;
                case ("participants_amount"):
                    $result .= "<td>Число участников</td>";
                    $result .= "<td>" . $diffSet['old'] . "</td>
                                <td>" . $diffSet['new'] . "</td>";
                    break;
                case ("approved_by"):
                    $result .= "<td>Администратор ВКС</td>";
                    $result .= "<td>" . $diffSet['old'] . "</td>
                                <td>" . $diffSet['new'] . "</td>";
                    break;
                case ("comment_for_user"):
                    $result .= "<td>Комментарий для пользователя</td>";
                    $result .= "<td>" . $diffSet['old'] . "</td>
                                <td>" . $diffSet['new'] . "</td>";
                    break;
                case ("presentation"):
                    $result .= "<td>Презентация</td>";
                    $diffSet['old'] = $diffSet['old'] == 1 ? "Да" : "Нет";
                    $diffSet['new'] = $diffSet['new'] == 1 ? "Да" : "Нет";

                    $result .= "<td>" . $diffSet['old'] . "</td>
                                <td>" . $diffSet['new'] . "</td>";
                    break;
                case ("needTPSupport"):
                    $result .= "<td>Техническая поддержка</td>";
                    $diffSet['old'] = $diffSet['old'] == 1 ? "Да" : "Нет";
                    $diffSet['new'] = $diffSet['new'] == 1 ? "Да" : "Нет";
                    $result .= "<td>" . $diffSet['old'] . "</td>
                                <td>" . $diffSet['new'] . "</td>";
                    break;
                case ("location"):
                    $result .= "<td>Место проведения</td>";
                    $result .= "<td>" . $diffSet['old'] . "</td>
                                <td>" . $diffSet['new'] . "</td>";
                    break;
                case ("participants_inside"):
                    $result .= "<td>Вн. Участники</td>";
                    $result .= "<td>" . join(",", $diffSet['old']) . "</td>
                                <td>" . join(",", $diffSet['new']) . "</td>";
                    break;
                case ("link_codes"):
                    $result .= "<td>Номер Вк. Комнаты</td>";
                    $result .= "<td>" . join(",", $diffSet['old']) . "</td>
                                <td>" . join(",", $diffSet['new']) . "</td>";
                    break;
            }

            $cou++;
        }
        return $result;
    } // func end

    /**
     * make pagination
     */
    public static function makePagination($recordsCount, $limit, $linkto = 'r')
    {

        $realOffset = isset($_GET['offset']) ? $_GET['offset'] : 0;
        $limit = @$_REQUEST['limit'] ? $_REQUEST['limit'] : $limit;
        $result = '<br><span class="pagination">';
        $getPages = (ceil($recordsCount / $limit));

        if ($getPages > 1) {
            if (isset($_GET['order']))
                $order = "&order={$_GET['order']}";
            else
                $order = "";
            if ($realOffset == 0)
                $result .= "Страницы: <a class='btn btn-success'
    href='?${linkto}={$_GET[$linkto]}&offset=0'>1</a>";
            else
                $result .= "Страницы: <a class='btn btn-default'
    href='?{$linkto}={$_GET[$linkto]}&offset=0'>1</a>";


            $buttons = [];
            $limitPositive = $realOffset + ($limit * 10);
            $limitNegative = $realOffset ? $realOffset - ($limit * 10) : 0;
//            dump($limitPositive, $limitNegative);
            for ($i = 1; $i <= $getPages - 1; $i++) {
                $j = $i + 1;
                $off = $limit * $i;

                if ($i == $getPages - 1) {
                    $btype = $off == $realOffset ? 'btn-success' : 'btn-default';
                    $end = "<a class='btn {$btype}'
                    href='?{$linkto}={$_GET[$linkto]}{$order}&offset=" . $off . "&limit={$limit}'>{$j}</a>";
                } else {
                    $buttons[$i]['num'] = $j;
                    $buttons[$i]['type'] = $off == $realOffset ? 'btn-success' : 'btn-default';
                    $buttons[$i]['off'] = $off;
                }
            }

            foreach ($buttons as $button) {
//
//                if ($realOffset != 0) {
                if ($button['off'] < $limitPositive && $button['off'] > $limitNegative) {
                    $result .= "<a class='btn  {$button['type']}' href='?{$linkto}={$_GET[$linkto]}{$order}&offset=" . $button['off'] . "&limit={$limit}'>{$button['num']}</a>";
                }
//                }

            }
        }
        if (isset($end))
            $result .= $end;
        $result .= "<div class='clearfix'></div></span>";
        return $result;
    } // func end

    public static function makeOrderLink($field)
    {
        $request = Request::createFromGlobals();
        $direction = 'asc';
        if ($request->query->has('order')) {
            $ordId = sorterTrait::parseOrderInput($request->query->get('order'));
            foreach ($ordId as $k => $val) {
                if ($k == $field) {
                    if ($val == $direction) {
                        $direction = 'desc';
                    }
                }
            }
        }
        $path = '';
        $request = Request::createFromGlobals();
        if ($request->query->has('r')) {
            $path .= "?r={$request->query->get('r')}&order={$field} {$direction}";
        } else if ($request->query->has('route')) {
            $path .= "?route={$request->query->get('route')}&order={$field} {$direction}";
        }
        return $path;
    }
} //class end