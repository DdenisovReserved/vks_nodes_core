<?php

/* mails/v2/tech_support/_about_request.twig */
class __TwigTemplate_d63979a589aec6f473814df17649bf767f88ffd03c490636116acb77ca3ae551 extends Twig_Template
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
    <td>Запрашиваетcя поддержка в точке</td>
    <td style=\"color: #44562a\">";
        // line 3
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["request"]) ? $context["request"] : null), "attendance", array()), "full_path", array()), "html", null, true);
        echo "</td>
</tr>

<tr>
    <td>Запрос создал</td>
    <td>
        ";
        // line 9
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["request"]) ? $context["request"] : null), "owner", array()), "login", array()), "html", null, true);
        echo "
        <br>";
        // line 10
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["request"]) ? $context["request"] : null), "owner", array()), "fio", array()), "html", null, true);
        echo ", ";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["request"]) ? $context["request"] : null), "owner", array()), "phone", array()), "html", null, true);
        echo ",
    </td>
</tr>
<tr>
    <td>Сообщение от пользователя</td>
    <td>
        ";
        // line 16
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["request"]) ? $context["request"] : null), "user_message", array()))) {
            // line 17
            echo "            ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["request"]) ? $context["request"] : null), "user_message", array()), "html", null, true);
            echo "
        ";
        } else {
            // line 19
            echo "            -
        ";
        }
        // line 21
        echo "    </td>
</tr>";
    }

    public function getTemplateName()
    {
        return "mails/v2/tech_support/_about_request.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  59 => 21,  55 => 19,  49 => 17,  47 => 16,  36 => 10,  32 => 9,  23 => 3,  19 => 1,);
    }
}
/* <tr>*/
/*     <td>Запрашиваетcя поддержка в точке</td>*/
/*     <td style="color: #44562a">{{ request.attendance.full_path }}</td>*/
/* </tr>*/
/* */
/* <tr>*/
/*     <td>Запрос создал</td>*/
/*     <td>*/
/*         {{ request.owner.login }}*/
/*         <br>{{ request.owner.fio }}, {{ request.owner.phone }},*/
/*     </td>*/
/* </tr>*/
/* <tr>*/
/*     <td>Сообщение от пользователя</td>*/
/*     <td>*/
/*         {% if request.user_message|length %}*/
/*             {{ request.user_message }}*/
/*         {% else %}*/
/*             -*/
/*         {% endif %}*/
/*     </td>*/
/* </tr>*/
