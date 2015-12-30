<?php

/* mails/v2/vksWs-refuse.twig */
class __TwigTemplate_21dd16226c4ea25aed1376bfa87b075e0b64dddba1c84348eda6ad0379bd7097 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("mails/base.twig", "mails/v2/vksWs-refuse.twig", 1);
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
            <h2>Отказ, по вашей ВКС #";
        // line 5
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "id", array()), "html", null, true);
        echo "</h2>
        </td>
    </tr>
    <tr>
        <td width=\"30%\">Название</td>
        <td>";
        // line 10
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "title", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr >
        <td>Администратор принявший решение</td>
        <td>
            ";
        // line 15
        if ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "approver", array(), "any", true, true)) {
            // line 16
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "approver", array()), "login", array()), "html", null, true);
            echo ", тел.";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "approver", array()), "phone", array()), "html", null, true);
            echo "
            ";
        } else {
            // line 18
            echo "                Аднистратор пока не назначен
            ";
        }
        // line 20
        echo "        </td>
    </tr>
    <tr>
        <td>Комментарий пользователю</td>
        <td>
            ";
        // line 25
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "comment_for_user", array()))) {
            // line 26
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "comment_for_user", array()), "html", null, true);
            echo "
            ";
        } else {
            // line 28
            echo "                -
            ";
        }
        // line 30
        echo "
        </td>
    </tr>
    <tr >
        <td>Дата начала</td>
        <td>";
        // line 35
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "date", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr>
        <td>Время</td>
        <td>";
        // line 39
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "startTime", array()), "html", null, true);
        echo "-";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "endTime", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr >
        <td>Ответственный</td>
        <td>";
        // line 43
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "init_customer_fio", array()), "html", null, true);
        echo ", тел. ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "init_customer_phone", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr>
        <td>Подразделение</td>
        <td>";
        // line 47
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "department_rel", array()), "prefix", array()), "html", null, true);
        echo ". ";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "department_rel", array()), "name", array()), "html", null, true);
        echo "</td>
    </tr>
    ";
        // line 50
        echo "        ";
        // line 51
        echo "        ";
        // line 52
        echo "    ";
        // line 53
        echo "    <tr >
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
        // line 80
        if (($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "other_tb_required", array(), "any", true, true) && ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "other_tb_required", array()) != 0))) {
            // line 81
            echo "        <tr>
            <td>Подключать другой ТБ/ЦА</td>
            <td>Да</td>
        </tr>
    ";
        }
        // line 86
        echo "    <tr>
        <td>Запись ВКС</td>
        <td>
            ";
        // line 89
        if ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "record_required", array())) {
            // line 90
            echo "                Да
            ";
        } else {
            // line 92
            echo "                Нет
            ";
        }
        // line 94
        echo "        </td>
    </tr>
    <tr>
        <td>Владелец</td>
        <td>
            ";
        // line 99
        if ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array())) {
            // line 100
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array()), "login", array()), "html", null, true);
            echo " (";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array()), "fio", array()), "html", null, true);
            echo ", ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array()), "phone", array()), "html", null, true);
            echo ")
            ";
        } else {
            // line 102
            echo "                -
            ";
        }
        // line 104
        echo "        </td>
    </tr>
";
    }

    public function getTemplateName()
    {
        return "mails/v2/vksWs-refuse.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  228 => 104,  224 => 102,  214 => 100,  212 => 99,  205 => 94,  201 => 92,  197 => 90,  195 => 89,  190 => 86,  183 => 81,  181 => 80,  171 => 72,  167 => 70,  164 => 69,  155 => 66,  152 => 65,  147 => 64,  145 => 63,  139 => 60,  133 => 56,  129 => 53,  127 => 52,  125 => 51,  123 => 50,  116 => 47,  107 => 43,  98 => 39,  91 => 35,  84 => 30,  80 => 28,  74 => 26,  72 => 25,  65 => 20,  61 => 18,  53 => 16,  51 => 15,  43 => 10,  35 => 5,  31 => 3,  28 => 2,  11 => 1,);
    }
}
/* {% extends "mails/base.twig" %}*/
/* {% block body %}*/
/*     <tr class="table-head-declined">*/
/*         <td colspan="2">*/
/*             <h2>Отказ, по вашей ВКС #{{ vks.id }}</h2>*/
/*         </td>*/
/*     </tr>*/
/*     <tr>*/
/*         <td width="30%">Название</td>*/
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
/*     {% if vks.other_tb_required is defined and vks.other_tb_required != 0 %}*/
/*         <tr>*/
/*             <td>Подключать другой ТБ/ЦА</td>*/
/*             <td>Да</td>*/
/*         </tr>*/
/*     {% endif %}*/
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
