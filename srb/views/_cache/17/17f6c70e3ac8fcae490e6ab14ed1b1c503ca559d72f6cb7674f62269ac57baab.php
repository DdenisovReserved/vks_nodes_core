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
    <tr>
        <td>Дата/Время | <a href=\"";
        // line 37
        echo twig_escape_filter($this->env, (isset($context["appHttpPath"]) ? $context["appHttpPath"] : null), "html", null, true);
        echo "?route=OutlookCalendarRequest/pushToStack/";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "id", array()), "html", null, true);
        echo "/forced\">Отправить в календарь Outlook</a></td>
        <td>";
        // line 38
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "date", array()), "html", null, true);
        echo ", ";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "startTime", array()), "html", null, true);
        echo " - ";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "endTime", array()), "html", null, true);
        echo "</td>
    </tr>

    <tr>
        <td>Участники
            ";
        // line 44
        echo "        </td>
        <td class=\"small-td\">
            C рабочих мест (IP телефон, Lynс, CMA Desktop и т.д.): ";
        // line 46
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "in_place_participants_count", array()), "html", null, true);
        echo "

        </td>
    </tr>
    <tr>
        <td>Запись ВКС</td>
        <td>
            ";
        // line 53
        if ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "record_required", array())) {
            // line 54
            echo "                Да
            ";
        } else {
            // line 56
            echo "                Нет
            ";
        }
        // line 58
        echo "        </td>
    </tr>
    <tr>
        <td>Владелец</td>
        <td>
            ";
        // line 63
        if ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array())) {
            // line 64
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array()), "fio", array()), "html", null, true);
            echo ", ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array()), "phone", array()), "html", null, true);
            echo "
            ";
        } else {
            // line 66
            echo "                -
            ";
        }
        // line 68
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
        return array (  156 => 68,  152 => 66,  144 => 64,  142 => 63,  135 => 58,  131 => 56,  127 => 54,  125 => 53,  115 => 46,  111 => 44,  99 => 38,  93 => 37,  86 => 32,  78 => 29,  72 => 27,  70 => 26,  66 => 25,  63 => 24,  59 => 23,  51 => 18,  35 => 5,  31 => 3,  28 => 2,  11 => 1,);
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
/*     <tr>*/
/*         <td>Дата/Время | <a href="{{ appHttpPath }}?route=OutlookCalendarRequest/pushToStack/{{ vks.id }}/forced">Отправить в календарь Outlook</a></td>*/
/*         <td>{{ vks.humanized.date }}, {{ vks.humanized.startTime }} - {{ vks.humanized.endTime }}</td>*/
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
/*                 {{ vks.owner.fio }}, {{ vks.owner.phone }}*/
/*             {% else %}*/
/*                 -*/
/*             {% endif %}*/
/*         </td>*/
/*     </tr>*/
/* {% endblock %}*/
