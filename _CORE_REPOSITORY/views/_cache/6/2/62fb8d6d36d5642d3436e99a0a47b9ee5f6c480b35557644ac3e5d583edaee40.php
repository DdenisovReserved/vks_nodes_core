<?php

/* mails/v2/reset-pwd.twig */
class __TwigTemplate_62fb8d6d36d5642d3436e99a0a47b9ee5f6c480b35557644ac3e5d583edaee40 extends Twig_Template
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
    <table class=\"mail-table\">

        <tr>
            <td colspan=\"2\">
                <img src=\"";
        // line 47
        echo twig_escape_filter($this->env, (isset($context["http_path"]) ? $context["http_path"] : null), "html", null, true);
        echo "images/vkslogo120.png\" class=\"logo\">
            </td>
        </tr>
        <tr class=\"table-head\">
            <td><h2>Запрос на сброс пароля</h2></td>
        </tr>        <tr class=\"\">
            <td>
                ";
        // line 54
        if ($this->getAttribute((isset($context["user"]) ? $context["user"] : null), "fio", array(), "any", true, true)) {
            // line 55
            echo "                    Уважаемый ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["user"]) ? $context["user"] : null), "fio", array()), "html", null, true);
            echo "
                ";
        } else {
            // line 57
            echo "                    Сообщение от системы ВКС
                ";
        }
        // line 59
        echo "        </tr>
        <tr>
            <td>Кто-то c ip адресом ";
        // line 61
        echo twig_escape_filter($this->env, (isset($context["ip"]) ? $context["ip"] : null), "html", null, true);
        echo " запросил сброс пароля для вашей учетной записи ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["user"]) ? $context["user"] : null), "login", array()), "html", null, true);
        echo " в АС ВКС</td>
        </tr>
        <tr>
            <td>Если это вы, то <a target=\"_blank\" href=\"";
        // line 64
        echo twig_escape_filter($this->env, (isset($context["linkToReset"]) ? $context["linkToReset"] : null), "html", null, true);
        echo "\">перейдите по ссылке для сброса пароля</a></td>
        </tr>
        <tr>
            <td>Если вы не запрашивали смену пароля, сообщите об этом администратору системы</td>
        </tr>
        <tr>
            <td align=\"left\" colspan=\"2\">
                <i>*Сообщение создано автоматически</i>
            </td>
        </tr>
        <tr>
            <td align=\"left\" colspan=\"2\">
                <a href=\"";
        // line 76
        echo twig_escape_filter($this->env, (isset($context["appHttpPath"]) ? $context["appHttpPath"] : null), "html", null, true);
        echo "\">Переход к АС ВКС</a>
            </td>
        </tr>
    </table>
</body>";
    }

    public function getTemplateName()
    {
        return "mails/v2/reset-pwd.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  116 => 76,  101 => 64,  93 => 61,  89 => 59,  85 => 57,  79 => 55,  77 => 54,  67 => 47,  19 => 1,);
    }
}
