<?php

/* mails/v2/vks-delete.twig */
class __TwigTemplate_2a87fcbd21d2fa264540326af3b452feaeb06fa0f26af45850f70e94724d1ab1 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("mails/base.twig", "mails/v2/vks-delete.twig", 1);
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
        echo "    <tr class=\"table-head-declined\">
        <td colspan=\"2\">
            <h2>Ваша ВКС #";
        // line 5
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "id", array()), "html", null, true);
        echo " аннулирована</h2>
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
        <td>Администратор принявший решение</td>
        <td>
            ";
        // line 23
        if ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "approver", array(), "any", true, true)) {
            // line 24
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "approver", array()), "login", array()), "html", null, true);
            echo ", тел.";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "approver", array()), "phone", array()), "html", null, true);
            echo "
            ";
        } else {
            // line 26
            echo "                Аднистратор пока не назначен
            ";
        }
        // line 28
        echo "        </td>
    </tr>
    <tr>
        <td>Комментарий пользователю</td>
        <td>
            ";
        // line 33
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "comment_for_user", array()))) {
            // line 34
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "comment_for_user", array()), "html", null, true);
            echo "
            ";
        } else {
            // line 36
            echo "                -
            ";
        }
        // line 38
        echo "
        </td>
    </tr>
    <tr>
        <td>Дата/Время</td>
        <td>";
        // line 43
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "date", array()), "html", null, true);
        echo ", ";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "startTime", array()), "html", null, true);
        echo " - ";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "endTime", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr >
        <td>Ответственный</td>
        <td>";
        // line 47
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "init_customer_fio", array()), "html", null, true);
        echo ", тел. ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "init_customer_phone", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr>
        <td>Подразделение</td>
        <td>";
        // line 51
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "department_rel", array()), "prefix", array()), "html", null, true);
        echo ". ";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "department_rel", array()), "name", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr >
        <td>Участники
            ";
        // line 56
        echo "        </td>
        <td class=\"small-td\">
            <ul>
                <li class=\"list-group-item\">C рабочих мест (IP телефон, Lynс, CMA Desktop и
                    т.д.): ";
        // line 60
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "in_place_participants_count", array()), "html", null, true);
        echo "</li>
                <li>Точки:

                    ";
        // line 63
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "participants", array()))) {
            // line 64
            echo "                        ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "participants", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["parp"]) {
                // line 65
                echo "                            <ul>
                                <li class=\"list-group-item\">";
                // line 66
                echo twig_escape_filter($this->env, $this->getAttribute($context["parp"], "full_path", array()), "html", null, true);
                echo "</li>
                            </ul>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['parp'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 69
            echo "                    ";
        } else {
            // line 70
            echo "                        0
                    ";
        }
        // line 72
        echo "

                </li>


            </ul>
        </td>
    </tr>
    ";
        // line 81
        echo "        ";
        // line 82
        echo "        ";
        // line 83
        echo "    ";
        // line 84
        echo "    ";
        // line 85
        echo "        ";
        // line 86
        echo "        ";
        // line 87
        echo "    ";
        // line 88
        echo "    ";
        if (($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "other_tb_required", array(), "any", true, true) && ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "other_tb_required", array()) != 0))) {
            // line 89
            echo "        <tr>
            <td>Подключать другой ТБ/ЦА</td>
            <td>Да</td>
        </tr>
    ";
        }
        // line 94
        echo "    <tr>
        <td>Владелец</td>
        <td>
            ";
        // line 97
        if ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array())) {
            // line 98
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array()), "fio", array()), "html", null, true);
            echo ", ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array()), "phone", array()), "html", null, true);
            echo "
            ";
        } else {
            // line 100
            echo "                -
            ";
        }
        // line 102
        echo "        </td>
    </tr>
";
    }

    public function getTemplateName()
    {
        return "mails/v2/vks-delete.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  219 => 102,  215 => 100,  207 => 98,  205 => 97,  200 => 94,  193 => 89,  190 => 88,  188 => 87,  186 => 86,  184 => 85,  182 => 84,  180 => 83,  178 => 82,  176 => 81,  166 => 72,  162 => 70,  159 => 69,  150 => 66,  147 => 65,  142 => 64,  140 => 63,  134 => 60,  128 => 56,  119 => 51,  110 => 47,  99 => 43,  92 => 38,  88 => 36,  82 => 34,  80 => 33,  73 => 28,  69 => 26,  61 => 24,  59 => 23,  51 => 18,  35 => 5,  31 => 3,  28 => 2,  11 => 1,);
    }
}
/* {% extends "mails/base.twig" %}*/
/* {% block body %}*/
/*     <tr class="table-head-declined">*/
/*         <td colspan="2">*/
/*             <h2>Ваша ВКС #{{ vks.id }} аннулирована</h2>*/
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
/*         <td>Администратор принявший решение</td>*/
/*         <td>*/
/*             {% if vks.approver is defined %}*/
/*                 {{ vks.approver.login }}, тел.{{ vks.approver.phone }}*/
/*             {% else %}*/
/*                 Аднистратор пока не назначен*/
/*             {% endif %}*/
/*         </td>*/
/*     </tr>*/
/*     <tr>*/
/*         <td>Комментарий пользователю</td>*/
/*         <td>*/
/*             {% if  vks.comment_for_user|length %}*/
/*                 {{ vks.comment_for_user }}*/
/*             {% else %}*/
/*                 -*/
/*             {% endif %}*/
/* */
/*         </td>*/
/*     </tr>*/
/*     <tr>*/
/*         <td>Дата/Время</td>*/
/*         <td>{{ vks.humanized.date }}, {{ vks.humanized.startTime }} - {{ vks.humanized.endTime }}</td>*/
/*     </tr>*/
/*     <tr >*/
/*         <td>Ответственный</td>*/
/*         <td>{{ vks.init_customer_fio }}, тел. {{ vks.init_customer_phone }}</td>*/
/*     </tr>*/
/*     <tr>*/
/*         <td>Подразделение</td>*/
/*         <td>{{ vks.department_rel.prefix }}. {{ vks.department_rel.name }}</td>*/
/*     </tr>*/
/*     <tr >*/
/*         <td>Участники*/
/*             {#<span class="badge">{{ vks.participants|length + vks.in_place_participants_count }}</span>#}*/
/*         </td>*/
/*         <td class="small-td">*/
/*             <ul>*/
/*                 <li class="list-group-item">C рабочих мест (IP телефон, Lynс, CMA Desktop и*/
/*                     т.д.): {{ vks.in_place_participants_count }}</li>*/
/*                 <li>Точки:*/
/* */
/*                     {% if  vks.participants|length %}*/
/*                         {% for parp in vks.participants %}*/
/*                             <ul>*/
/*                                 <li class="list-group-item">{{ parp.full_path }}</li>*/
/*                             </ul>*/
/*                         {% endfor %}*/
/*                     {% else %}*/
/*                         0*/
/*                     {% endif %}*/
/* */
/* */
/*                 </li>*/
/* */
/* */
/*             </ul>*/
/*         </td>*/
/*     </tr>*/
/*     {#<tr >#}*/
/*         {#<td>Комментарий администратору:</td>#}*/
/*         {#<td>{{ vks.comment_for_admin }} </td>#}*/
/*     {#</tr>#}*/
/*     {#<tr >#}*/
/*         {#<td>Прямая ссылка на ВКС:</td>#}*/
/*         {#<td>{{ vks.link }}</td>#}*/
/*     {#</tr>#}*/
/*     {% if vks.other_tb_required is defined and vks.other_tb_required != 0 %}*/
/*         <tr>*/
/*             <td>Подключать другой ТБ/ЦА</td>*/
/*             <td>Да</td>*/
/*         </tr>*/
/*     {% endif %}*/
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
