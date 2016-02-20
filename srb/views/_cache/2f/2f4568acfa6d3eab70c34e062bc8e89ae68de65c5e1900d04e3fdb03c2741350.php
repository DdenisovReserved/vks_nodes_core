<?php

/* mails/v2/vksWs-refuse.twig */
class __TwigTemplate_0041acc2d930349051b7aa9a693ffbcbd4ff250dd95c6c4dbc8d8e6c0c6f685b extends Twig_Template
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
    <tr>
        <td>Дата/Время</td>
        <td>";
        // line 35
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
        // line 39
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "init_customer_fio", array()), "html", null, true);
        echo ", тел. ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "init_customer_phone", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr>
        <td>Подразделение</td>
        <td>";
        // line 43
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "department_rel", array()), "prefix", array()), "html", null, true);
        echo ". ";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "department_rel", array()), "name", array()), "html", null, true);
        echo "</td>
    </tr>
    ";
        // line 46
        echo "        ";
        // line 47
        echo "        ";
        // line 48
        echo "    ";
        // line 49
        echo "    <tr >
        <td>Участники
            ";
        // line 52
        echo "        </td>
        <td class=\"small-td\">
            <ul>
                <li class=\"list-group-item\">C рабочих мест (IP телефон, Lynс, CMA Desktop и
                    т.д.): ";
        // line 56
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "in_place_participants_count", array()), "html", null, true);
        echo "</li>
                <li>Точки:

                    ";
        // line 59
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "participants", array()))) {
            // line 60
            echo "                        ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "participants", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["parp"]) {
                // line 61
                echo "                            <ul>
                                <li class=\"list-group-item\">";
                // line 62
                echo twig_escape_filter($this->env, $this->getAttribute($context["parp"], "full_path", array()), "html", null, true);
                echo "</li>
                            </ul>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['parp'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 65
            echo "                    ";
        } else {
            // line 66
            echo "                        0
                    ";
        }
        // line 68
        echo "

                </li>


            </ul>
        </td>
    </tr>
    ";
        // line 76
        if (($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "other_tb_required", array(), "any", true, true) && ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "other_tb_required", array()) != 0))) {
            // line 77
            echo "        <tr>
            <td>Подключать другой ТБ/ЦА</td>
            <td>Да</td>
        </tr>
    ";
        }
        // line 82
        echo "    <tr>
        <td>Запись ВКС</td>
        <td>
            ";
        // line 85
        if ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "record_required", array())) {
            // line 86
            echo "                Да
            ";
        } else {
            // line 88
            echo "                Нет
            ";
        }
        // line 90
        echo "        </td>
    </tr>
    <tr>
        <td>Владелец</td>
        <td>
            ";
        // line 95
        if ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array())) {
            // line 96
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array()), "fio", array()), "html", null, true);
            echo ", ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array()), "phone", array()), "html", null, true);
            echo "
            ";
        } else {
            // line 98
            echo "                -
            ";
        }
        // line 100
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
        return array (  221 => 100,  217 => 98,  209 => 96,  207 => 95,  200 => 90,  196 => 88,  192 => 86,  190 => 85,  185 => 82,  178 => 77,  176 => 76,  166 => 68,  162 => 66,  159 => 65,  150 => 62,  147 => 61,  142 => 60,  140 => 59,  134 => 56,  128 => 52,  124 => 49,  122 => 48,  120 => 47,  118 => 46,  111 => 43,  102 => 39,  91 => 35,  84 => 30,  80 => 28,  74 => 26,  72 => 25,  65 => 20,  61 => 18,  53 => 16,  51 => 15,  43 => 10,  35 => 5,  31 => 3,  28 => 2,  11 => 1,);
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
/*                 {{ vks.owner.fio }}, {{ vks.owner.phone }}*/
/*             {% else %}*/
/*                 -*/
/*             {% endif %}*/
/*         </td>*/
/*     </tr>*/
/* {% endblock %}*/
