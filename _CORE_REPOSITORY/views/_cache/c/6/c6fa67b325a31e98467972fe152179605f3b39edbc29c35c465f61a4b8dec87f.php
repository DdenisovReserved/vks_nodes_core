<?php

/* mails/twig/vksNoSupport-report.twig */
class __TwigTemplate_c6fa67b325a31e98467972fe152179605f3b39edbc29c35c465f61a4b8dec87f extends Twig_Template
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
        echo "
<div class=\"container\">

    <h4 class=\"text-primary\">Ваша ВКС #";
        // line 4
        echo twig_escape_filter($this->env, (isset($context["id"]) ? $context["id"] : null), "html", null, true);
        echo " успешно создана</h4>
    <hr>
    <div class=\"col-md-offset-1 col-md-10 text-primary\"><h3>Тема: ";
        // line 6
        echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true);
        echo "</h3>

        <h4>Данные ВКС</h4>
        <hr>
        <div class=\"col-md-12\">
            <table class='table table-bordered table-striped'>
                <!--    //голова таблицы-->
                <th class=\"col-md-3\">Параметр</th><th>Значение</th>
                <tr>
                    <td>Вирт. комната<br> (код подключения)</td>
                    <td><span class=\"code-connect-font\">";
        // line 16
        echo twig_escape_filter($this->env, (isset($context["v_room_num"]) ? $context["v_room_num"] : null), "html", null, true);
        echo " </span></td>
                <tr>
                    <td>Дата начала:</td>
                    <td>";
        // line 19
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["humanized"]) ? $context["humanized"] : null), "date", array()), "html", null, true);
        echo "</td>
                <tr>
                    <td>Время начала: </td>
                    <td>";
        // line 22
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["humanized"]) ? $context["humanized"] : null), "start_date_time", array()), "html", null, true);
        echo "</td>
                <tr>
                    <td>Время окончания: </td>
                    <td>";
        // line 25
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["humanized"]) ? $context["humanized"] : null), "end_date_time", array()), "html", null, true);
        echo "</td>
                <tr>
                    <td>Место проведения: </td>
                    <td>";
        // line 28
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["humanized"]) ? $context["humanized"] : null), "location", array()), "html", null, true);
        echo "</td>
                <tr>
                    <td>Техническая поддержка: </td>
                    <td>

                        ";
        // line 33
        if (((isset($context["needTPSupport"]) ? $context["needTPSupport"] : null) == 0)) {
            // line 34
            echo "                            нет
                        ";
        } else {
            // line 36
            echo "                            да
                        ";
        }
        // line 38
        echo "                    </td>
            </table>
        </div>
    </div>
    <div class=\"clearfix\"></div>
</div>
";
    }

    public function getTemplateName()
    {
        return "mails/twig/vksNoSupport-report.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  84 => 38,  80 => 36,  76 => 34,  74 => 33,  66 => 28,  60 => 25,  54 => 22,  48 => 19,  42 => 16,  29 => 6,  24 => 4,  19 => 1,);
    }
}
