<?php

/* mails/v2/confirm-registration.twig */
class __TwigTemplate_07f035dcf06dae7b79f64dd4d7bc531e720a107be8ccafe936ff639ca85aaa69 extends Twig_Template
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
        // line 46
        echo twig_escape_filter($this->env, (isset($context["http_path"]) ? $context["http_path"] : null), "html", null, true);
        echo "images/vkslogo120.png\">
            </td>
        </tr>
        <tr class=\"table-head\">

            <td><h2> Благодарим вас за регистрацию в АС ВКС</h2></td>
        </tr>
        <tr>
            <td colspan=\"2\">


                <p>Просим подтвердить вашу регистрацию в АС ВКС,
                    для этого перейдите по
                    <a href='";
        // line 59
        echo twig_escape_filter($this->env, (isset($context["appHttpPath"]) ? $context["appHttpPath"] : null), "html", null, true);
        echo "?route=User/approveToken/";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["user"]) ? $context["user"] : null), "token", array()), "html", null, true);
        echo "'>ссылке</a>
                    или скопируйте ссылку в адресную строку браузера:
                <blockquote>";
        // line 61
        echo twig_escape_filter($this->env, (isset($context["appHttpPath"]) ? $context["appHttpPath"] : null), "html", null, true);
        echo "?route=User/approveToken/";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["user"]) ? $context["user"] : null), "token", array()), "html", null, true);
        echo "</blockquote>
                </p>
            </td>
        </tr>
        <tr>
            <td align=\"left\" colspan=\"2\">
                <i>*Сообщение создано автоматически, пожалуйста не отвечайте на него</i>
            </td>
        </tr>
        <tr>
            <td align=\"left\" colspan=\"2\">
                <a href=\"";
        // line 72
        echo twig_escape_filter($this->env, (isset($context["appHttpPath"]) ? $context["appHttpPath"] : null), "html", null, true);
        echo "\">Переход к АС ВКС</a>
            </td>
        </tr>
    </table>
</body>";
    }

    public function getTemplateName()
    {
        return "mails/v2/confirm-registration.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  105 => 72,  89 => 61,  82 => 59,  66 => 46,  19 => 1,);
    }
}
