<?php

/* mails/twig/verificationMail.twig */
class __TwigTemplate_12ed95210053913161a88a8a1572029067538cb2a1a26d4882cc58d9f72720f7 extends Twig_Template
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
    .datagrid table { border-collapse: collapse; text-align: left; width: 100%; } .datagrid {font: normal 12px/150% Georgia, Times New Roman, Times, serif; background: #fff; overflow: hidden; border: 1px solid #006699; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; }.datagrid table td, .datagrid table th { padding: 11px 3px; }.datagrid table thead th {background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #006699), color-stop(1, #00557F) );background:-moz-linear-gradient( center top, #006699 5%, #00557F 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#006699', endColorstr='#00557F');background-color:#006699; color:#FFFFFF; font-size: 15px; font-weight: bold; border-left: 1px solid #0070A8; } .datagrid table thead th:first-child { border: none; }.datagrid table tbody td { color: #00496B; border-left: 1px solid #E1EEF4;font-size: 16px;font-weight: normal; }.datagrid table tbody .alt td { background: #E1EEF4; color: #00496B; }.datagrid table tbody td:first-child { border-left: none; }.datagrid table tbody tr:last-child td { border-bottom: none; }.datagrid table tfoot td div { border-top: 1px solid #006699;background: #E1EEF4;} .datagrid table tfoot td { padding: 0; font-size: 12px } .datagrid table tfoot td div{ padding: 2px; }.datagrid table tfoot td ul { margin: 0; padding:0; list-style: none; text-align: right; }.datagrid table tfoot  li { display: inline; }.datagrid table tfoot li a { text-decoration: none; display: inline-block;  padding: 2px 8px; margin: 1px;color: #FFFFFF;border: 1px solid #006699;-webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #006699), color-stop(1, #00557F) );background:-moz-linear-gradient( center top, #006699 5%, #00557F 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#006699', endColorstr='#00557F');background-color:#006699; }.datagrid table tfoot ul.active, .datagrid table tfoot ul a:hover { text-decoration: none;border-color: #006699; color: #FFFFFF; background: none; background-color:#00557F;}div.dhtmlx_window_active, div.dhx_modal_cover_dv { position: fixed !important; }
</style>
<div class=\"datagrid\">
    <table>
        <tr class=\"alt\">
            <td>
                ";
        // line 8
        if ($this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array(), "any", false, true), "fio", array(), "any", true, true)) {
            // line 9
            echo "                    Уважаемый ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array()), "fio", array()), "html", null, true);
            echo "
                ";
        } else {
            // line 11
            echo "                    Сообщение от системы ВКС
                ";
        }
        // line 13
        echo "        </tr>
        <tr>
            <td>Напоминаем вам о проведении вашей ВКС <a target=\"_blank\"
                                                         href=\"";
        // line 16
        echo twig_escape_filter($this->env, (isset($context["path"]) ? $context["path"] : null), "html", null, true);
        echo "?route=Vks/show/";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "id", array()), "html", null, true);
        echo "\">
                    ";
        // line 17
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "id", array()), "html", null, true);
        echo "</a></td>
        </tr>
        <tr>
            <td>За течении 24 часов до начала вашей вкс вы должны подтвердить её проведение, в противном случае вкс будет удалена
            </td>
        </tr>
        <tr>
            <td>Для подтверждения ВКС - нажмите <a href=\"";
        // line 24
        echo twig_escape_filter($this->env, (isset($context["path"]) ? $context["path"] : null), "html", null, true);
        echo "?route=Vks/userVerificate/";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "id", array()), "html", null, true);
        echo "\" style=\"color: green;\">здесь</a>
            </td>
        </tr>
        <tr>
            <td>Для отказа от проведения - нажмите <a href=\"";
        // line 28
        echo twig_escape_filter($this->env, (isset($context["path"]) ? $context["path"] : null), "html", null, true);
        echo "?route=Vks/userVrificationRefuse/";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "id", array()), "html", null, true);
        echo "\" style=\"color: red;\">здесь</a>
            </td>
        </tr>
        <tr>
            <td>
            </td>
        </tr>
        <tr>
            <td>
                <i>Сообщение создано автоматически, пожалуйста не отвечайте на него</i>
            </td>
        </tr>
    </table>
</div>
";
    }

    public function getTemplateName()
    {
        return "mails/twig/verificationMail.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  70 => 28,  61 => 24,  51 => 17,  45 => 16,  40 => 13,  36 => 11,  30 => 9,  28 => 8,  19 => 1,);
    }
}
