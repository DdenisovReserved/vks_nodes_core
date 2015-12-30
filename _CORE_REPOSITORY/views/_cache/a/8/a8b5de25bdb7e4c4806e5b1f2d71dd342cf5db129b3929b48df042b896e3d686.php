<?php

/* mails/v2/vksWs-report.twig */
class __TwigTemplate_a8b5de25bdb7e4c4806e5b1f2d71dd342cf5db129b3929b48df042b896e3d686 extends Twig_Template
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
        echo " успешно создана</h2>
        </td>
    </tr>
    <tr>
        <td width=\"30%\">Название</td>
        <td>";
        // line 55
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "title", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr  >
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
        // line 79
        if ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "approver", array(), "any", true, true)) {
            // line 80
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "approver", array()), "login", array()), "html", null, true);
            echo "
                ";
            // line 81
            if (twig_length_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "approver", array()), "phone", array()))) {
                echo ", тел.";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "approver", array()), "phone", array()), "html", null, true);
                echo "
                ";
            }
            // line 83
            echo "            ";
        } else {
            // line 84
            echo "                Аднистратор пока не назначен
            ";
        }
        // line 86
        echo "        </td>
    </tr>
    <tr  >
        <td>Дата начала:</td>
        <td>";
        // line 90
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "date", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr>
        <td>Время:</td>
        <td>";
        // line 94
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "startTime", array()), "html", null, true);
        echo "-";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "endTime", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr  >
        <td>Ответственный:</td>
        <td>";
        // line 98
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "init_customer_fio", array()), "html", null, true);
        echo ", тел. ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "init_customer_phone", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr>
        <td>Подразделение:</td>
        <td>";
        // line 102
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "department_rel", array()), "prefix", array()), "html", null, true);
        echo ". ";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "department_rel", array()), "name", array()), "html", null, true);
        echo "</td>
    </tr>
    ";
        // line 104
        if (($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "other_tb_required", array(), "any", true, true) && ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "other_tb_required", array()) != 0))) {
            // line 105
            echo "        <tr style=\"background-color: #FFC6A3; font-size: 20px;\">
            <td>Подключать другой ТБ/ЦА</td>
            <td>Да</td>
        </tr>
    ";
        }
        // line 110
        echo "    ";
        if ((isset($context["vksCa"]) ? $context["vksCa"] : null)) {
            // line 111
            echo "        <tr style=\"background-color: #FFC6A3; font-size: 20px;\">
            <td>Код для передачи в другие ТБ</td>
            <td>
                <b><a href='";
            // line 114
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
            // line 120
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vksCa"]) ? $context["vksCa"] : null), "v_room_num", array()), "html", null, true);
            echo "</b>
            </td>
        </tr>
    ";
        }
        // line 124
        echo "    ";
        // line 125
        echo "    ";
        // line 126
        echo "    ";
        // line 127
        echo "    ";
        // line 128
        echo "    <tr  >
        <td>Комментарий пользователю:</td>
        <td>
            ";
        // line 131
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "comment_for_user", array()))) {
            // line 132
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "comment_for_user", array()), "html", null, true);
            echo "
            ";
        } else {
            // line 134
            echo "                -
            ";
        }
        // line 136
        echo "
        </td>
    </tr>

    <tr>
        <td>Участники
            <span class=\"badge\">";
        // line 142
        echo twig_escape_filter($this->env, (twig_length_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "inside_parp", array())) + $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "in_place_participants_count", array())), "html", null, true);
        echo "</span>
        </td>
        <td class=\"small-td\">
            <ul>
                ";
        // line 146
        if ((isset($context["vksCa"]) ? $context["vksCa"] : null)) {
            // line 147
            echo "                    <li class=\"list-group-item-text\">Кол-во участников в ЦА: <span
                                class=\"label label-as-badge label-warning\">";
            // line 148
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vksCa"]) ? $context["vksCa"] : null), "ca_participants", array()), "html", null, true);
            echo "</span>
                    </li>
                    ";
            // line 150
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["vksCa"]) ? $context["vksCa"] : null), "participants", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["parp"]) {
                // line 151
                echo "                        <li class=\"list-group-item-text\">";
                echo twig_escape_filter($this->env, $this->getAttribute($context["parp"], "full_path", array()), "html", null, true);
                echo "</li>
                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['parp'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 153
            echo "                ";
        }
        // line 154
        echo "                <li class=\"list-group-item\">C рабочих мест (IP телефон, Lynс, CMA Desktop и
                    т.д.): ";
        // line 155
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "in_place_participants_count", array()), "html", null, true);
        echo "</li>
                <li>Точки ВКС:
                    ";
        // line 157
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "inside_parp", array()))) {
            // line 158
            echo "                        ";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "inside_parp", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["parp"]) {
                // line 159
                echo "                            <ul>
                                <li class=\"list-group-item\">";
                // line 160
                echo twig_escape_filter($this->env, $this->getAttribute($context["parp"], "full_path", array()), "html", null, true);
                echo "</li>
                            </ul>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['parp'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 163
            echo "                    ";
        } else {
            // line 164
            echo "                        0
                    ";
        }
        // line 166
        echo "                </li>
            </ul>
        </td>
    </tr>
    <tr  >
        <td>Комментарий администратору:</td>
        <td>";
        // line 172
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "comment_for_admin", array()))) {
            // line 173
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "comment_for_admin", array()), "html", null, true);
            echo "
            ";
        } else {
            // line 175
            echo "                -
            ";
        }
        // line 176
        echo " </td>
    </tr>
    <tr>
        <td>Прямая ссылка на ВКС:</td>
        <td>";
        // line 180
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
        // line 188
        echo twig_escape_filter($this->env, (isset($context["appHttpPath"]) ? $context["appHttpPath"] : null), "html", null, true);
        echo "\">Переход к АС ВКС</a>
        </td>
    </tr>

</table>
</body>";
    }

    public function getTemplateName()
    {
        return "mails/v2/vksWs-report.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  370 => 188,  359 => 180,  353 => 176,  349 => 175,  343 => 173,  341 => 172,  333 => 166,  329 => 164,  326 => 163,  317 => 160,  314 => 159,  309 => 158,  307 => 157,  302 => 155,  299 => 154,  296 => 153,  287 => 151,  283 => 150,  278 => 148,  275 => 147,  273 => 146,  266 => 142,  258 => 136,  254 => 134,  248 => 132,  246 => 131,  241 => 128,  239 => 127,  237 => 126,  235 => 125,  233 => 124,  226 => 120,  211 => 114,  206 => 111,  203 => 110,  196 => 105,  194 => 104,  187 => 102,  178 => 98,  169 => 94,  162 => 90,  156 => 86,  152 => 84,  149 => 83,  142 => 81,  137 => 80,  135 => 79,  128 => 74,  124 => 72,  120 => 70,  112 => 67,  106 => 65,  104 => 64,  100 => 63,  97 => 62,  93 => 61,  90 => 60,  88 => 59,  81 => 55,  73 => 50,  65 => 45,  19 => 1,);
    }
}
