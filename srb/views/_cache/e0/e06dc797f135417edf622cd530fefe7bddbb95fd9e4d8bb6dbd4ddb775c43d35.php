<?php

/* mails/v2/ts_request.twig */
class __TwigTemplate_af29cd63aa4ca481a6dd086462e0569db277bdfce6c236f66a7242e3edb5f1d8 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("mails/base.twig", "mails/v2/ts_request.twig", 1);
        $this->blocks = array(
            'body' => array($this, 'block_body'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "mails/base.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_body($context, array $blocks = array())
    {
        // line 3
        echo "    <tr class=\"table-head\">
        <td colspan=\"2\">
            <h2>Запрос Тех. поддержки для ВКС #";
        // line 5
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "id", array()), "html", null, true);
        echo " в ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["attendance"]) ? $context["attendance"] : null), "name", array()), "html", null, true);
        echo "</h2>
        </td>
    </tr>
    <tr>
        <td width=\"30%\">Название ВКС</td>
        <td>";
        // line 10
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "title", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr>
        <td>Дата/Время | <a href=\"";
        // line 13
        echo twig_escape_filter($this->env, (isset($context["appHttpPath"]) ? $context["appHttpPath"] : null), "html", null, true);
        echo "?route=OutlookCalendarRequest/pushToStack/";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "id", array()), "html", null, true);
        echo "/forced\">Отправить в календарь Outlook</a></td>
        <td>";
        // line 14
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "date", array()), "html", null, true);
        echo ", ";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "startTime", array()), "html", null, true);
        echo " - ";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "endTime", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr>
        <td>Ответственный</td>
        <td>";
        // line 18
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "init_customer_fio", array()), "html", null, true);
        echo ", тел. ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "init_customer_phone", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr>
        <td>Запрашиваетcя поддержка в точке</td>
        <td style=\"color: #44562a\">";
        // line 22
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["attendance"]) ? $context["attendance"] : null), "full_path", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr>
        <td>Владелец</td>
        <td>
            ";
        // line 27
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array()))) {
            // line 28
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array()), "login", array()), "html", null, true);
            echo " (";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array()), "fio", array()), "html", null, true);
            echo ", ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array()), "phone", array()), "html", null, true);
            echo ")
            ";
        } else {
            // line 30
            echo "                -
            ";
        }
        // line 32
        echo "        </td>
    </tr>
";
    }

    public function getTemplateName()
    {
        return "mails/v2/ts_request.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  101 => 32,  97 => 30,  87 => 28,  85 => 27,  77 => 22,  68 => 18,  57 => 14,  51 => 13,  45 => 10,  35 => 5,  31 => 3,  28 => 2,  11 => 1,);
    }
}
/* {% extends "mails/base.twig" %}*/
/* {% block body %}*/
/*     <tr class="table-head">*/
/*         <td colspan="2">*/
/*             <h2>Запрос Тех. поддержки для ВКС #{{ vks.id }} в {{ attendance.name}}</h2>*/
/*         </td>*/
/*     </tr>*/
/*     <tr>*/
/*         <td width="30%">Название ВКС</td>*/
/*         <td>{{ vks.title }}</td>*/
/*     </tr>*/
/*     <tr>*/
/*         <td>Дата/Время | <a href="{{ appHttpPath }}?route=OutlookCalendarRequest/pushToStack/{{ vks.id }}/forced">Отправить в календарь Outlook</a></td>*/
/*         <td>{{ vks.humanized.date }}, {{ vks.humanized.startTime }} - {{ vks.humanized.endTime }}</td>*/
/*     </tr>*/
/*     <tr>*/
/*         <td>Ответственный</td>*/
/*         <td>{{ vks.init_customer_fio }}, тел. {{ vks.init_customer_phone }}</td>*/
/*     </tr>*/
/*     <tr>*/
/*         <td>Запрашиваетcя поддержка в точке</td>*/
/*         <td style="color: #44562a">{{ attendance.full_path }}</td>*/
/*     </tr>*/
/*     <tr>*/
/*         <td>Владелец</td>*/
/*         <td>*/
/*             {% if vks.owner|length %}*/
/*                 {{ vks.owner.login }} ({{ vks.owner.fio }}, {{ vks.owner.phone }})*/
/*             {% else %}*/
/*                 -*/
/*             {% endif %}*/
/*         </td>*/
/*     </tr>*/
/* {% endblock %}*/
