<?php

/* mails/v2/vksWs-refuse.twig */
class __TwigTemplate_67aac4f97269e257b7a9746347b608ebf599c67bc9876ecdb2dae66266de572c extends Twig_Template
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

    .table-head-declined td {
        font-family: \"lucida console\", monospace;
        background-color: #aa4834;
        color: #242222 !important;
    }
</style>
<body>

<table class=\"mail-table\">
    <tr>
        <td colspan=\"2\">
            <img src=\"";
        // line 50
        echo twig_escape_filter($this->env, (isset($context["http_path"]) ? $context["http_path"] : null), "html", null, true);
        echo "images/vkslogo120.png\">
        </td>
    <tr class=\"table-head-declined\">
        <td colspan=\"2\">
            <h2>Отказ, по вашей ВКС #";
        // line 54
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "id", array()), "html", null, true);
        echo "</h2>
        </td>
    </tr>
    <tr >
        <td width=\"30%\">
            Параметр
        </td>
        <td>
            Значение
        </td>
    </tr>
    <tr>
        <td>Название</td>
        <td>";
        // line 67
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "title", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr >
        <td>Администратор принявший решение:</td>
        <td>
            ";
        // line 72
        if ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "approver", array(), "any", true, true)) {
            // line 73
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "approver", array()), "login", array()), "html", null, true);
            echo ", тел.";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "approver", array()), "phone", array()), "html", null, true);
            echo "
            ";
        } else {
            // line 75
            echo "                Аднистратор пока не назначен
            ";
        }
        // line 77
        echo "        </td>
    </tr>
    <tr>
        <td>Комментарий пользователю:</td>
        <td>
            ";
        // line 82
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "comment_for_user", array()))) {
            // line 83
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "comment_for_user", array()), "html", null, true);
            echo "
            ";
        } else {
            // line 85
            echo "                -
            ";
        }
        // line 87
        echo "
        </td>
    </tr>
    <tr >
        <td>Дата начала:</td>
        <td>";
        // line 92
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "date", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr>
        <td>Время:</td>
        <td>";
        // line 96
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "startTime", array()), "html", null, true);
        echo "-";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "endTime", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr >
        <td>Ответственный:</td>
        <td>";
        // line 100
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "init_customer_fio", array()), "html", null, true);
        echo ", тел. ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "init_customer_phone", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr>
        <td>Подразделение:</td>
        <td>";
        // line 104
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "department_rel", array()), "prefix", array()), "html", null, true);
        echo ". ";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "department_rel", array()), "name", array()), "html", null, true);
        echo "</td>
    </tr>
    ";
        // line 107
        echo "        ";
        // line 108
        echo "        ";
        // line 109
        echo "    ";
        // line 110
        echo "    <tr >
        <td>Участники
            <span class=\"badge\">";
        // line 112
        echo twig_escape_filter($this->env, (twig_length_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "inside_parp", array())) + $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "in_place_participants_count", array())), "html", null, true);
        echo "</span>
        </td>
        <td class=\"small-td\">
            <ul>
                <li class=\"list-group-item\">C рабочих мест (IP телефон, Lynс, CMA Desktop и
                    т.д.): ";
        // line 117
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "in_place_participants_count", array()), "html", null, true);
        echo "</li>
                <li>Точки:

                    ";
        // line 120
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "inside_parp", array()))) {
            // line 121
            echo "                        ";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "inside_parp", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["parp"]) {
                // line 122
                echo "                            <ul>
                                <li class=\"list-group-item\">";
                // line 123
                echo twig_escape_filter($this->env, $this->getAttribute($context["parp"], "full_path", array()), "html", null, true);
                echo "</li>
                            </ul>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['parp'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 126
            echo "                    ";
        } else {
            // line 127
            echo "                        0
                    ";
        }
        // line 129
        echo "

                </li>


            </ul>
        </td>
    </tr>
    ";
        // line 138
        echo "        ";
        // line 139
        echo "        ";
        // line 140
        echo "    ";
        // line 141
        echo "    <tr >
        <td>Прямая ссылка на ВКС:</td>
        <td>";
        // line 143
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "link", array()), "html", null, true);
        echo "</td>
    </tr>
    ";
        // line 145
        if ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "other_tb_required", array(), "any", true, true)) {
            // line 146
            echo "        <tr>
            <td>Подключать другой ТБ/ЦА</td>
            <td>Да</td>
        </tr>
    ";
        }
        // line 151
        echo "    ";
        if ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "ca_code", array(), "any", true, true)) {
            // line 152
            echo "        <tr>
            <td>Код ЦА</td>
            <td>
                ";
            // line 155
            if (twig_length_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "ca_code", array()))) {
                // line 156
                echo "                    ";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "ca_code", array()), "html", null, true);
                echo "
                ";
            } else {
                // line 158
                echo "                    -
                ";
            }
            // line 160
            echo "            </td>
        </tr>
    ";
        }
        // line 163
        echo "    <tr >
        <td colspan=\"2\" align=\"left\"><i>*Сообщение создано в автоматическом режиме</i>
        </td>
    </tr>
    <tr>
        <td align=\"left\" colspan=\"2\">
            <a href=\"";
        // line 169
        echo twig_escape_filter($this->env, (isset($context["appHttpPath"]) ? $context["appHttpPath"] : null), "html", null, true);
        echo "\">Переход к АС ВКС</a>
        </td>
    </tr>
</table>
</body>";
    }

    public function getTemplateName()
    {
        return "mails/v2/vksWs-refuse.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  290 => 169,  282 => 163,  277 => 160,  273 => 158,  267 => 156,  265 => 155,  260 => 152,  257 => 151,  250 => 146,  248 => 145,  243 => 143,  239 => 141,  237 => 140,  235 => 139,  233 => 138,  223 => 129,  219 => 127,  216 => 126,  207 => 123,  204 => 122,  199 => 121,  197 => 120,  191 => 117,  183 => 112,  179 => 110,  177 => 109,  175 => 108,  173 => 107,  166 => 104,  157 => 100,  148 => 96,  141 => 92,  134 => 87,  130 => 85,  124 => 83,  122 => 82,  115 => 77,  111 => 75,  103 => 73,  101 => 72,  93 => 67,  77 => 54,  70 => 50,  19 => 1,);
    }
}
