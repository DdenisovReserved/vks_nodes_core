<?php

/* mails/twig/reset_pwd_mail.twig */
class __TwigTemplate_7831ac7c9030160f0d176676c2a8ea031526480b0ec2c4633a21ad26a2ff4314 extends Twig_Template
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
        if ($this->getAttribute((isset($context["user"]) ? $context["user"] : null), "fio", array(), "any", true, true)) {
            // line 9
            echo "                    Уважаемый ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["user"]) ? $context["user"] : null), "fio", array()), "html", null, true);
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
            <td>Кто-то c ip адресом ";
        // line 15
        echo twig_escape_filter($this->env, (isset($context["ip"]) ? $context["ip"] : null), "html", null, true);
        echo " запросил напоминание пароль для вашей учетной записи ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["user"]) ? $context["user"] : null), "login", array()), "html", null, true);
        echo " в АС ВКС</td>
        </tr>
        <tr>
            <td>Если это вы, то <a target=\"_blank\" href=\"";
        // line 18
        echo twig_escape_filter($this->env, (isset($context["linkToReset"]) ? $context["linkToReset"] : null), "html", null, true);
        echo "\">перейдите по ссылке для сброса пароля</a></td>
        </tr>
        <tr>
            <td>Если вы не запрашивали смену пароля, сообщите об этом администратору системы</td>
        </tr>

    </table>
</div>
";
    }

    public function getTemplateName()
    {
        return "mails/twig/reset_pwd_mail.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  52 => 18,  44 => 15,  40 => 13,  36 => 11,  30 => 9,  28 => 8,  19 => 1,);
    }
}
