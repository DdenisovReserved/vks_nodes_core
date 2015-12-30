<?php

/* mails/v2/vkssimple-report.twig */
class __TwigTemplate_cd3d9c20d259b2cc8e1d9386ecc4cc151bf151754845c556b797edbac1e0fc29 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("mails/base.twig", "mails/v2/vkssimple-report.twig", 1);
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
            <h2>Ваша ВКС #";
        // line 5
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
        // line 18
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "title", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr >
        <td>Вирт. комната (код подключения)</td>
        <td>
            ";
        // line 23
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "connection_codes", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["code"]) {
            // line 24
            echo "                <p>
                <span class=\"code-connect-font\">";
            // line 25
            echo twig_escape_filter($this->env, $this->getAttribute($context["code"], "value_raw", array()), "html", null, true);
            echo "
                    ";
            // line 26
            if (twig_length_filter($this->env, $this->getAttribute($context["code"], "tip", array()))) {
                // line 27
                echo "                        (";
                echo twig_escape_filter($this->env, $this->getAttribute($context["code"], "tip", array()), "html", null, true);
                echo ")
                    ";
            }
            // line 29
            echo "                    </span>
                </p>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['code'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 32
        echo "        </td>


    </tr>
    <tr >
        <td>Дата начала</td>
        <td>";
        // line 38
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "date", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr>
        <td>Время</td>
        <td>";
        // line 42
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "startTime", array()), "html", null, true);
        echo "-";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "endTime", array()), "html", null, true);
        echo "</td>
    </tr>

    <tr>
        <td>Участники
            ";
        // line 48
        echo "        </td>
        <td class=\"small-td\">
            C рабочих мест (IP телефон, Lynс, CMA Desktop и т.д.): ";
        // line 50
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "in_place_participants_count", array()), "html", null, true);
        echo "

        </td>
    </tr>
    <tr>
        <td>Запись ВКС</td>
        <td>
            ";
        // line 57
        if ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "record_required", array())) {
            // line 58
            echo "                Да
            ";
        } else {
            // line 60
            echo "                Нет
            ";
        }
        // line 62
        echo "        </td>
    </tr>
    <tr>
        <td>Владелец</td>
        <td>
            ";
        // line 67
        if ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array())) {
            // line 68
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array()), "login", array()), "html", null, true);
            echo " (";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array()), "fio", array()), "html", null, true);
            echo ", ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array()), "phone", array()), "html", null, true);
            echo ")
            ";
        } else {
            // line 70
            echo "                -
            ";
        }
        // line 72
        echo "        </td>
    </tr>
";
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
        return array (  158 => 72,  154 => 70,  144 => 68,  142 => 67,  135 => 62,  131 => 60,  127 => 58,  125 => 57,  115 => 50,  111 => 48,  101 => 42,  94 => 38,  86 => 32,  78 => 29,  72 => 27,  70 => 26,  66 => 25,  63 => 24,  59 => 23,  51 => 18,  35 => 5,  31 => 3,  28 => 2,  11 => 1,);
    }
}
/* {% extends "mails/base.twig" %}*/
/* {% block body %}*/
/*     <tr class="table-head">*/
/*         <td colspan="2">*/
/*             <h2>Ваша ВКС #{{ vks.id }} успешно создана</h2>*/
/*         </td>*/
/*     </tr>*/
/*     <tr >*/
/*         <td width="30%">*/
/*             Параметр*/
/*         </td>*/
/*         <td>*/
/*             Значение*/
/*         </td>*/
/*     </tr>*/
/*     <tr>*/
/*         <td>Название</td>*/
/*         <td>{{ vks.title }}</td>*/
/*     </tr>*/
/*     <tr >*/
/*         <td>Вирт. комната (код подключения)</td>*/
/*         <td>*/
/*             {% for code in vks.connection_codes %}*/
/*                 <p>*/
/*                 <span class="code-connect-font">{{ code.value_raw }}*/
/*                     {% if  code.tip|length %}*/
/*                         ({{ code.tip }})*/
/*                     {% endif %}*/
/*                     </span>*/
/*                 </p>*/
/*             {% endfor %}*/
/*         </td>*/
/* */
/* */
/*     </tr>*/
/*     <tr >*/
/*         <td>Дата начала</td>*/
/*         <td>{{ vks.humanized.date }}</td>*/
/*     </tr>*/
/*     <tr>*/
/*         <td>Время</td>*/
/*         <td>{{ vks.humanized.startTime }}-{{ vks.humanized.endTime }}</td>*/
/*     </tr>*/
/* */
/*     <tr>*/
/*         <td>Участники*/
/*             {#<span class="badge">{{ vks.participants|length + vks.in_place_participants_count }}</span>#}*/
/*         </td>*/
/*         <td class="small-td">*/
/*             C рабочих мест (IP телефон, Lynс, CMA Desktop и т.д.): {{ vks.in_place_participants_count }}*/
/* */
/*         </td>*/
/*     </tr>*/
/*     <tr>*/
/*         <td>Запись ВКС</td>*/
/*         <td>*/
/*             {% if  vks.record_required %}*/
/*                 Да*/
/*             {% else %}*/
/*                 Нет*/
/*             {% endif %}*/
/*         </td>*/
/*     </tr>*/
/*     <tr>*/
/*         <td>Владелец</td>*/
/*         <td>*/
/*             {% if  vks.owner %}*/
/*                 {{ vks.owner.login }} ({{ vks.owner.fio }}, {{ vks.owner.phone }})*/
/*             {% else %}*/
/*                 -*/
/*             {% endif %}*/
/*         </td>*/
/*     </tr>*/
/* {% endblock %}*/
