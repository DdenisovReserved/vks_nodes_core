<?php

/* mails/v2/vks-delete.twig */
class __TwigTemplate_e2d0c0875415f948dc4f9bc4f4631eeb30920387cf64a1c578d35d2bb845fd5b extends Twig_Template
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
    <tr >
        <td>Дата начала</td>
        <td>";
        // line 43
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "date", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr>
        <td>Время</td>
        <td>";
        // line 47
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "startTime", array()), "html", null, true);
        echo "-";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "endTime", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr >
        <td>Ответственный</td>
        <td>";
        // line 51
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "init_customer_fio", array()), "html", null, true);
        echo ", тел. ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "init_customer_phone", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr>
        <td>Подразделение</td>
        <td>";
        // line 55
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "department_rel", array()), "prefix", array()), "html", null, true);
        echo ". ";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "department_rel", array()), "name", array()), "html", null, true);
        echo "</td>
    </tr>
    ";
        // line 58
        echo "        ";
        // line 59
        echo "        ";
        // line 60
        echo "    ";
        // line 61
        echo "    <tr >
        <td>Участники
            ";
        // line 64
        echo "        </td>
        <td class=\"small-td\">
            <ul>
                <li class=\"list-group-item\">C рабочих мест (IP телефон, Lynс, CMA Desktop и
                    т.д.): ";
        // line 68
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "in_place_participants_count", array()), "html", null, true);
        echo "</li>
                <li>Точки:

                    ";
        // line 71
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "participants", array()))) {
            // line 72
            echo "                        ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "participants", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["parp"]) {
                // line 73
                echo "                            <ul>
                                <li class=\"list-group-item\">";
                // line 74
                echo twig_escape_filter($this->env, $this->getAttribute($context["parp"], "full_path", array()), "html", null, true);
                echo "</li>
                            </ul>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['parp'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 77
            echo "                    ";
        } else {
            // line 78
            echo "                        0
                    ";
        }
        // line 80
        echo "

                </li>


            </ul>
        </td>
    </tr>
    ";
        // line 89
        echo "        ";
        // line 90
        echo "        ";
        // line 91
        echo "    ";
        // line 92
        echo "    ";
        // line 93
        echo "        ";
        // line 94
        echo "        ";
        // line 95
        echo "    ";
        // line 96
        echo "    ";
        if (($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "other_tb_required", array(), "any", true, true) && ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "other_tb_required", array()) != 0))) {
            // line 97
            echo "        <tr>
            <td>Подключать другой ТБ/ЦА</td>
            <td>Да</td>
        </tr>
    ";
        }
        // line 102
        echo "    <tr>
        <td>Владелец</td>
        <td>
            ";
        // line 105
        if ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array())) {
            // line 106
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array()), "login", array()), "html", null, true);
            echo " (";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array()), "fio", array()), "html", null, true);
            echo ", ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array()), "phone", array()), "html", null, true);
            echo ")
            ";
        } else {
            // line 108
            echo "                -
            ";
        }
        // line 110
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
        return array (  234 => 110,  230 => 108,  220 => 106,  218 => 105,  213 => 102,  206 => 97,  203 => 96,  201 => 95,  199 => 94,  197 => 93,  195 => 92,  193 => 91,  191 => 90,  189 => 89,  179 => 80,  175 => 78,  172 => 77,  163 => 74,  160 => 73,  155 => 72,  153 => 71,  147 => 68,  141 => 64,  137 => 61,  135 => 60,  133 => 59,  131 => 58,  124 => 55,  115 => 51,  106 => 47,  99 => 43,  92 => 38,  88 => 36,  82 => 34,  80 => 33,  73 => 28,  69 => 26,  61 => 24,  59 => 23,  51 => 18,  35 => 5,  31 => 3,  28 => 2,  11 => 1,);
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
/*     <tr >*/
/*         <td>Дата начала</td>*/
/*         <td>{{ vks.humanized.date }}</td>*/
/*     </tr>*/
/*     <tr>*/
/*         <td>Время</td>*/
/*         <td>{{ vks.humanized.startTime }}-{{ vks.humanized.endTime }}</td>*/
/*     </tr>*/
/*     <tr >*/
/*         <td>Ответственный</td>*/
/*         <td>{{ vks.init_customer_fio }}, тел. {{ vks.init_customer_phone }}</td>*/
/*     </tr>*/
/*     <tr>*/
/*         <td>Подразделение</td>*/
/*         <td>{{ vks.department_rel.prefix }}. {{ vks.department_rel.name }}</td>*/
/*     </tr>*/
/*     {#<tr >#}*/
/*         {#<td>Инициатор:</td>#}*/
/*         {#<td>{{ vks.initiator_rel.name }}</td>#}*/
/*     {#</tr>#}*/
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
/*                 {{ vks.owner.login }} ({{ vks.owner.fio }}, {{ vks.owner.phone }})*/
/*             {% else %}*/
/*                 -*/
/*             {% endif %}*/
/*         </td>*/
/*     </tr>*/
/* {% endblock %}*/
