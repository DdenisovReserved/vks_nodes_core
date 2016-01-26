<?php

/* mails/v2/tech_support/new.twig */
class __TwigTemplate_1ad4197d14306274cb31198597677b782e7999dbaa6f70d6dc6ffb3e31f5e18f extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("mails/base.twig", "mails/v2/tech_support/new.twig", 1);
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
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["request"]) ? $context["request"] : null), "attendance", array()), "name", array()), "html", null, true);
        echo "</h2>
        </td>
    </tr>
    ";
        // line 8
        $this->loadTemplate("mails/v2/tech_support/_about_request.twig", "mails/v2/tech_support/new.twig", 8)->display($context);
        // line 9
        echo "    ";
        $this->loadTemplate("mails/v2/tech_support/_about_vks.twig", "mails/v2/tech_support/new.twig", 9)->display($context);
    }

    public function getTemplateName()
    {
        return "mails/v2/tech_support/new.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  45 => 9,  43 => 8,  35 => 5,  31 => 3,  28 => 2,  11 => 1,);
    }
}
/* {% extends "mails/base.twig" %}*/
/* {% block body %}*/
/*     <tr class="table-head">*/
/*         <td colspan="2">*/
/*             <h2>Запрос Тех. поддержки для ВКС #{{ vks.id }} в {{ request.attendance.name}}</h2>*/
/*         </td>*/
/*     </tr>*/
/*     {% include "mails/v2/tech_support/_about_request.twig" %}*/
/*     {% include "mails/v2/tech_support/_about_vks.twig" %}*/
/* {% endblock %}*/
