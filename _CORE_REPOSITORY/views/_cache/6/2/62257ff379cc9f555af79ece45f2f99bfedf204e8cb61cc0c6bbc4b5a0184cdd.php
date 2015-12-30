<?php

/* mails/v2/vksWs-edited.twig */
class __TwigTemplate_62257ff379cc9f555af79ece45f2f99bfedf204e8cb61cc0c6bbc4b5a0184cdd extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<style>
    body {
        /*background-color: #cff4cb;*/
        font: \"Georgia\", \"Times New Roman\", serif;
        font-size: 14px;
    }

    .mail-table {
        width: 100%;
        margin: 0 auto;
        /*border-left: 1px solid #1B2024;*/
        background-color: #FFFFFF;
    }

    .mail-table td {
        padding: 10px;
        font-size: 16px;
        font-family: verdana, arial, helvetica, sans-serif;
        color: #1B2024;
    }

    .alt {
        background-color: #F0F0F0;
    }

    .code-connect-font {
        font-family: \"lucida console\", monospace;
        font-size: 18px;
        font-weight: bold;
        color: #FF5346;
    }

    .table-head td {
        font-family: \"lucida console\", monospace;
        background-color: #81AA21;
        color: #242222 !important;
    }
</style>
<body>

<table class=\"mail-table\">

    <tr>
        <td colspan=\"2\">
            <img src=\"";
        // line 45
        echo twig_escape_filter($this->env, (isset($context["http_path"]) ? $context["http_path"] : null), "html", null, true);
        echo "images/vkslogo120.png\">
        </td>
    </tr>
    <tr class=\"table-head\">
        <td colspan=\"2\">
            <h2>Ваша ВКС #";
        // line 50
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "id", array()), "html", null, true);
        echo " отредактирована администратором</h2>
        </td>
    </tr>
    <tr >
        <td width=\"30%\">Название</td>
        <td>";
        // line 55
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "title", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr >
        <td>Вирт. комната (код подключения)</td>
        ";
        // line 59
        if (($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "connection_code", array(), "any", true, true) && twig_length_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "connection_code", array())))) {
            // line 60
            echo "            <td>
                ";
            // line 61
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "connection_code", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["code"]) {
                // line 62
                echo "                    <p>
                <span class=\"code-connect-font\">";
                // line 63
                echo twig_escape_filter($this->env, $this->getAttribute($context["code"], "value", array()), "html", null, true);
                echo "
                    ";
                // line 64
                if (twig_length_filter($this->env, $this->getAttribute($context["code"], "tip", array()))) {
                    // line 65
                    echo "                        (";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["code"], "tip", array()), "html", null, true);
                    echo ")
                    ";
                }
                // line 67
                echo "                    </span>
                    </p>
                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['code'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 70
            echo "            </td>
        ";
        } else {
            // line 72
            echo "            <td><span class=\"code-connect-font\">Код подключения не выдан или не требуется</span></td>
        ";
        }
        // line 74
        echo "    </tr>
    <tr>
        <td>Администратор принявший решение:</td>
        <td>
            ";
        // line 78
        if ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "approver", array(), "any", true, true)) {
            // line 79
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "approver", array()), "login", array()), "html", null, true);
            echo "
                ";
            // line 80
            if (twig_length_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "approver", array()), "phone", array()))) {
                echo ", тел.";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "approver", array()), "phone", array()), "html", null, true);
                echo "
                ";
            }
            // line 82
            echo "            ";
        } else {
            // line 83
            echo "                Аднистратор пока не назначен
            ";
        }
        // line 85
        echo "        </td>
    </tr>
    <tr >
        <td>Дата начала:</td>
        <td>";
        // line 89
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "date", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr>
        <td>Время:</td>
        <td>";
        // line 93
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "startTime", array()), "html", null, true);
        echo "-";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "endTime", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr >
        <td>Ответственный:</td>
        <td>";
        // line 97
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "init_customer_fio", array()), "html", null, true);
        echo ", тел. ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "init_customer_phone", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr>
        <td>Подразделение:</td>
        <td>";
        // line 101
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "department_rel", array()), "prefix", array()), "html", null, true);
        echo ". ";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "department_rel", array()), "name", array()), "html", null, true);
        echo "</td>
    </tr>
    ";
        // line 103
        if (($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "other_tb_required", array(), "any", true, true) && ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "other_tb_required", array()) != 0))) {
            // line 104
            echo "        <tr style=\"background-color: #FFC6A3; font-size: 20px;\">
            <td>Подключать другой ТБ/ЦА</td>
            <td>Да</td>
        </tr>
    ";
        }
        // line 109
        echo "    ";
        if ((isset($context["vksCa"]) ? $context["vksCa"] : null)) {
            // line 110
            echo "        <tr style=\"background-color: #FFC6A3; font-size: 20px;\">
            <td>Код для передачи в другие ТБ</td>
            <td>
                <b><a href='";
            // line 113
            echo twig_escape_filter($this->env, (isset($context["appHttpPath"]) ? $context["appHttpPath"] : null), "html", null, true);
            echo "?route=Gateways/invite/";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vksCa"]) ? $context["vksCa"] : null), "referral", array()), "html", null, true);
            echo "'>";
            echo twig_escape_filter($this->env, (isset($context["appHttpPath"]) ? $context["appHttpPath"] : null), "html", null, true);
            echo "?route=Gateways/invite/";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vksCa"]) ? $context["vksCa"] : null), "referral", array()), "html", null, true);
            echo "</a></b>
            </td>
        </tr>
        <tr style=\"background-color: #FFC6A3; font-size: 20px;\">
            <td>Код для поключения в ЦА</td>
            <td>
                <b>";
            // line 119
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vksCa"]) ? $context["vksCa"] : null), "v_room_num", array()), "html", null, true);
            echo "</b>
            </td>
        </tr>
    ";
        }
        // line 123
        echo "    ";
        // line 124
        echo "    ";
        // line 125
        echo "    ";
        // line 126
        echo "    ";
        // line 127
        echo "    <tr>
        <td>Комментарий пользователю:</td>
        <td>
            ";
        // line 130
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "comment_for_user", array()))) {
            // line 131
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "comment_for_user", array()), "html", null, true);
            echo "
            ";
        } else {
            // line 133
            echo "                -
            ";
        }
        // line 135
        echo "
        </td>
    </tr>
    <tr >
        <td>Участники
            <span class=\"badge\">";
        // line 140
        echo twig_escape_filter($this->env, (twig_length_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "inside_parp", array())) + $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "in_place_participants_count", array())), "html", null, true);
        echo "</span>
        </td>
        <td class=\"small-td\">
            <ul>
                ";
        // line 144
        if ((isset($context["vksCa"]) ? $context["vksCa"] : null)) {
            // line 145
            echo "                    <li class=\"list-group-item-text\">Кол-во участников в ЦА: <span
                                class=\"label label-as-badge label-warning\">";
            // line 146
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vksCa"]) ? $context["vksCa"] : null), "ca_participants", array()), "html", null, true);
            echo "</span>
                    </li>
                    ";
            // line 148
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["vksCa"]) ? $context["vksCa"] : null), "participants", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["parp"]) {
                // line 149
                echo "                        <li class=\"list-group-item-text\">";
                echo twig_escape_filter($this->env, $this->getAttribute($context["parp"], "full_path", array()), "html", null, true);
                echo "</li>
                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['parp'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 151
            echo "                ";
        }
        // line 152
        echo "                <li class=\"list-group-item\">C рабочих мест (IP телефон, Lynс, CMA Desktop и
                    т.д.): ";
        // line 153
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "in_place_participants_count", array()), "html", null, true);
        echo "</li>
                <li>Точки ВКС:
                    ";
        // line 155
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "inside_parp", array()))) {
            // line 156
            echo "                        ";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "inside_parp", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["parp"]) {
                // line 157
                echo "                            <ul>
                                <li class=\"list-group-item\">";
                // line 158
                echo twig_escape_filter($this->env, $this->getAttribute($context["parp"], "full_path", array()), "html", null, true);
                echo "</li>
                            </ul>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['parp'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 161
            echo "                    ";
        } else {
            // line 162
            echo "                        0
                    ";
        }
        // line 164
        echo "                </li>
            </ul>
        </td>
    </tr>
    <tr>
        <td>Комментарий администратору:</td>
        <td>";
        // line 170
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "comment_for_admin", array()))) {
            // line 171
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "comment_for_admin", array()), "html", null, true);
            echo "
            ";
        } else {
            // line 173
            echo "                -
            ";
        }
        // line 174
        echo " </td>
    </tr>
    <tr >
        <td>Прямая ссылка на ВКС:</td>
        <td>";
        // line 178
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "link", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr >
        <td colspan=\"2\" align=\"left\"><i>*Сообщение создано в автоматическом режиме</i>
        </td>
    </tr>
    <tr>
        <td align=\"left\" colspan=\"2\">
            <a href=\"";
        // line 186
        echo twig_escape_filter($this->env, (isset($context["appHttpPath"]) ? $context["appHttpPath"] : null), "html", null, true);
        echo "\">Переход к АС ВКС</a>
        </td>
    </tr>

</table>
</body>";
    }

    public function getTemplateName()
    {
        return "mails/v2/vksWs-edited.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  368 => 186,  357 => 178,  351 => 174,  347 => 173,  341 => 171,  339 => 170,  331 => 164,  327 => 162,  324 => 161,  315 => 158,  312 => 157,  307 => 156,  305 => 155,  300 => 153,  297 => 152,  294 => 151,  285 => 149,  281 => 148,  276 => 146,  273 => 145,  271 => 144,  264 => 140,  257 => 135,  253 => 133,  247 => 131,  245 => 130,  240 => 127,  238 => 126,  236 => 125,  234 => 124,  232 => 123,  225 => 119,  210 => 113,  205 => 110,  202 => 109,  195 => 104,  193 => 103,  186 => 101,  177 => 97,  168 => 93,  161 => 89,  155 => 85,  151 => 83,  148 => 82,  141 => 80,  136 => 79,  134 => 78,  128 => 74,  124 => 72,  120 => 70,  112 => 67,  106 => 65,  104 => 64,  100 => 63,  97 => 62,  93 => 61,  90 => 60,  88 => 59,  81 => 55,  73 => 50,  65 => 45,  19 => 1,);
    }
}
