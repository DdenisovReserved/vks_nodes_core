<?php

/* mails/v2/tech_support/_about_vks.twig */
class __TwigTemplate_eff9c7e486ffb175b19a6be88989e160b7b893f17e3876d9b6424a4b9552cd80 extends Twig_Template
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
        echo "<tr>
    <td width=\"30%\">Название ВКС</td>
    <td>";
        // line 3
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "title", array()), "html", null, true);
        echo "</td>
</tr>
<tr>
    <td>Дата/Время | <a href=\"";
        // line 6
        echo twig_escape_filter($this->env, (isset($context["appHttpPath"]) ? $context["appHttpPath"] : null), "html", null, true);
        echo "?route=OutlookCalendarRequest/pushToStack/";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "id", array()), "html", null, true);
        echo "/forced\">Отправить
            в календарь Outlook</a></td>
    <td>";
        // line 8
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
        // line 12
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "init_customer_fio", array()), "html", null, true);
        echo ", тел. ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "init_customer_phone", array()), "html", null, true);
        echo "</td>
</tr>
<tr>
    <td>Владелец</td>
    <td>
        ";
        // line 17
        if ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array())) {
            // line 18
            echo "            ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array()), "login", array()), "html", null, true);
            echo " (";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array()), "fio", array()), "html", null, true);
            echo ", ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array()), "phone", array()), "html", null, true);
            echo ")
        ";
        } else {
            // line 20
            echo "            -
        ";
        }
        // line 22
        echo "    </td>
</tr>
";
    }

    public function getTemplateName()
    {
        return "mails/v2/tech_support/_about_vks.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  73 => 22,  69 => 20,  59 => 18,  57 => 17,  47 => 12,  36 => 8,  29 => 6,  23 => 3,  19 => 1,);
    }
}
/* <tr>*/
/*     <td width="30%">Название ВКС</td>*/
/*     <td>{{ vks.title }}</td>*/
/* </tr>*/
/* <tr>*/
/*     <td>Дата/Время | <a href="{{ appHttpPath }}?route=OutlookCalendarRequest/pushToStack/{{ vks.id }}/forced">Отправить*/
/*             в календарь Outlook</a></td>*/
/*     <td>{{ vks.humanized.date }}, {{ vks.humanized.startTime }} - {{ vks.humanized.endTime }}</td>*/
/* </tr>*/
/* <tr>*/
/*     <td>Ответственный</td>*/
/*     <td>{{ vks.init_customer_fio }}, тел. {{ vks.init_customer_phone }}</td>*/
/* </tr>*/
/* <tr>*/
/*     <td>Владелец</td>*/
/*     <td>*/
/*         {% if vks.owner %}*/
/*             {{ vks.owner.login }} ({{ vks.owner.fio }}, {{ vks.owner.phone }})*/
/*         {% else %}*/
/*             -*/
/*         {% endif %}*/
/*     </td>*/
/* </tr>*/
/* */
