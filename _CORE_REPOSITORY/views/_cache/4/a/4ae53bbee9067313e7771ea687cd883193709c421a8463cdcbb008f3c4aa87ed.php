<?php

/* mails/v2/vkssimple-report.twig */
class __TwigTemplate_4ae53bbee9067313e7771ea687cd883193709c421a8463cdcbb008f3c4aa87ed extends Twig_Template
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
        // line 63
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "title", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr >
        <td>Вирт. комната (код подключения)</td>
        <td>
            ";
        // line 68
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "connectionCode", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["code"]) {
            // line 69
            echo "                <p>
                <span class=\"code-connect-font\">";
            // line 70
            echo twig_escape_filter($this->env, $this->getAttribute($context["code"], "value", array()), "html", null, true);
            echo "
                    ";
            // line 71
            if (twig_length_filter($this->env, $this->getAttribute($context["code"], "tip", array()))) {
                // line 72
                echo "                        (";
                echo twig_escape_filter($this->env, $this->getAttribute($context["code"], "tip", array()), "html", null, true);
                echo ")
                    ";
            }
            // line 74
            echo "                    </span>
                </p>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['code'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 77
        echo "        </td>


    </tr>
    <tr >
        <td>Дата начала:</td>
        <td>";
        // line 83
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "date", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr>
        <td>Время:</td>
        <td>";
        // line 87
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "startTime", array()), "html", null, true);
        echo "-";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "endTime", array()), "html", null, true);
        echo "</td>
    </tr>

    <tr>
        <td>Участники
            <span class=\"badge\">";
        // line 92
        echo twig_escape_filter($this->env, (twig_length_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "inside_parp", array())) + $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "in_place_participants_count", array())), "html", null, true);
        echo "</span>
        </td>
        <td class=\"small-td\">
            C рабочих мест (IP телефон, Lynс, CMA Desktop и т.д.): ";
        // line 95
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "in_place_participants_count", array()), "html", null, true);
        echo "

        </td>
    </tr>

    <tr>
        <td>Прямая ссылка на ВКС:</td>
        <td>";
        // line 102
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "link", array()), "html", null, true);
        echo "</td>
    </tr>

    <tr>
        <td colspan=\"2\" align=\"left\"><i>*Сообщение создано в автоматическом режиме</i>
        </td>
    </tr>
    <tr>
        <td align=\"left\" colspan=\"2\">
            <a href=\"";
        // line 111
        echo twig_escape_filter($this->env, (isset($context["appHttpPath"]) ? $context["appHttpPath"] : null), "html", null, true);
        echo "\">Переход к АС ВКС</a>
        </td>
    </tr>

</table>
</body>";
    }

    public function getTemplateName()
    {
        return "mails/v2/vkssimple-report.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  177 => 111,  165 => 102,  155 => 95,  149 => 92,  139 => 87,  132 => 83,  124 => 77,  116 => 74,  110 => 72,  108 => 71,  104 => 70,  101 => 69,  97 => 68,  89 => 63,  73 => 50,  65 => 45,  19 => 1,);
    }
}
