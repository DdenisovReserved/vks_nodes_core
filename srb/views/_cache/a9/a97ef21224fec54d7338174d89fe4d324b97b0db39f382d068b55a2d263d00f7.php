<?php

/* mails/v2/newVksAdminNotificate.twig */
class __TwigTemplate_e7f76259502945c3f3e8e126c370bf242df277db267044abf9e6aee3388493be extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("mails/base.twig", "mails/v2/newVksAdminNotificate.twig", 1);
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
            <h2>Создана новая ВКС #";
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
    <tr>
        <td>Дата начала</td>
        <td>";
        // line 14
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "date", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr>
        <td>Время</td>
        <td>";
        // line 18
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "startTime", array()), "html", null, true);
        echo "-";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "endTime", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr  >
        <td>Ответственный</td>
        <td>";
        // line 22
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "init_customer_fio", array()), "html", null, true);
        echo ", тел. ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "init_customer_phone", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr>
        <td>Подразделение</td>
        <td>";
        // line 26
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "department_rel", array()), "prefix", array()), "html", null, true);
        echo ". ";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "department_rel", array()), "name", array()), "html", null, true);
        echo "</td>
    </tr>
    ";
        // line 28
        if (($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "other_tb_required", array(), "any", true, true) && ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "other_tb_required", array()) != 0))) {
            // line 29
            echo "        <tr style=\"background-color: #FFC6A3; font-size: 20px;\">
            <td>Подключать другой ТБ/ЦА</td>
            <td>Да</td>
        </tr>
    ";
        }
        // line 34
        echo "    <tr>
        <td>Комментарий пользователю</td>
        <td>
            ";
        // line 37
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "comment_for_user", array()))) {
            // line 38
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "comment_for_user", array()), "html", null, true);
            echo "
            ";
        } else {
            // line 40
            echo "                -
            ";
        }
        // line 42
        echo "
        </td>
    </tr>

    <tr>
        <td>Участники
            ";
        // line 49
        echo "        </td>
        <td class=\"small-td\">
            <ul>
                ";
        // line 52
        if ((isset($context["vksCa"]) ? $context["vksCa"] : null)) {
            // line 53
            echo "                    <li class=\"list-group-item-text\">Кол-во участников в ЦА: <span
                                class=\"label label-as-badge label-warning\">";
            // line 54
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vksCa"]) ? $context["vksCa"] : null), "ca_participants", array()), "html", null, true);
            echo "</span>
                    </li>
                    ";
            // line 56
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["vksCa"]) ? $context["vksCa"] : null), "participants", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["parp"]) {
                // line 57
                echo "                        <li class=\"list-group-item-text\">";
                echo twig_escape_filter($this->env, $this->getAttribute($context["parp"], "full_path", array()), "html", null, true);
                echo "</li>
                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['parp'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 59
            echo "                ";
        }
        // line 60
        echo "                <li class=\"list-group-item\">C рабочих мест (IP телефон, Lynс, CMA Desktop и
                    т.д.): ";
        // line 61
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "in_place_participants_count", array()), "html", null, true);
        echo "</li>
                <li>Точки ВКС:
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
        echo "                </li>
            </ul>
        </td>
    </tr>
    <tr >
        <td>Комментарий администратору</td>
        <td>
            ";
        // line 79
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "comment_for_admin", array()))) {
            // line 80
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "comment_for_admin", array()), "html", null, true);
            echo "
            ";
        } else {
            // line 82
            echo "                -
            ";
        }
        // line 84
        echo "        </td>
    </tr>
    <tr>
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
            echo "               -
            ";
        }
        // line 104
        echo "        </td>
    </tr>
";
    }

    public function getTemplateName()
    {
        return "mails/v2/newVksAdminNotificate.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  242 => 104,  238 => 102,  228 => 100,  226 => 99,  219 => 94,  215 => 92,  211 => 90,  209 => 89,  202 => 84,  198 => 82,  192 => 80,  190 => 79,  181 => 72,  177 => 70,  174 => 69,  165 => 66,  162 => 65,  157 => 64,  155 => 63,  150 => 61,  147 => 60,  144 => 59,  135 => 57,  131 => 56,  126 => 54,  123 => 53,  121 => 52,  116 => 49,  108 => 42,  104 => 40,  98 => 38,  96 => 37,  91 => 34,  84 => 29,  82 => 28,  75 => 26,  66 => 22,  57 => 18,  50 => 14,  43 => 10,  35 => 5,  31 => 3,  28 => 2,  11 => 1,);
    }
}
/* {% extends "mails/base.twig" %}*/
/* {% block body %}*/
/*     <tr class="table-head">*/
/*         <td colspan="2">*/
/*             <h2>Создана новая ВКС #{{ vks.id }}</h2>*/
/*         </td>*/
/*     </tr>*/
/*     <tr>*/
/*         <td width="30%">Название</td>*/
/*         <td>{{ vks.title }}</td>*/
/*     </tr>*/
/*     <tr>*/
/*         <td>Дата начала</td>*/
/*         <td>{{ vks.humanized.date }}</td>*/
/*     </tr>*/
/*     <tr>*/
/*         <td>Время</td>*/
/*         <td>{{ vks.humanized.startTime }}-{{ vks.humanized.endTime }}</td>*/
/*     </tr>*/
/*     <tr  >*/
/*         <td>Ответственный</td>*/
/*         <td>{{ vks.init_customer_fio }}, тел. {{ vks.init_customer_phone }}</td>*/
/*     </tr>*/
/*     <tr>*/
/*         <td>Подразделение</td>*/
/*         <td>{{ vks.department_rel.prefix }}. {{ vks.department_rel.name }}</td>*/
/*     </tr>*/
/*     {% if vks.other_tb_required is defined and vks.other_tb_required != 0 %}*/
/*         <tr style="background-color: #FFC6A3; font-size: 20px;">*/
/*             <td>Подключать другой ТБ/ЦА</td>*/
/*             <td>Да</td>*/
/*         </tr>*/
/*     {% endif %}*/
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
/* */
/*     <tr>*/
/*         <td>Участники*/
/*             {#<span class="badge">{{ vks.participants|length + vks.in_place_participants_count }}</span>#}*/
/*         </td>*/
/*         <td class="small-td">*/
/*             <ul>*/
/*                 {% if vksCa %}*/
/*                     <li class="list-group-item-text">Кол-во участников в ЦА: <span*/
/*                                 class="label label-as-badge label-warning">{{ vksCa.ca_participants }}</span>*/
/*                     </li>*/
/*                     {% for parp in vksCa.participants %}*/
/*                         <li class="list-group-item-text">{{ parp.full_path }}</li>*/
/*                     {% endfor %}*/
/*                 {% endif %}*/
/*                 <li class="list-group-item">C рабочих мест (IP телефон, Lynс, CMA Desktop и*/
/*                     т.д.): {{ vks.in_place_participants_count }}</li>*/
/*                 <li>Точки ВКС:*/
/*                     {% if  vks.participants|length %}*/
/*                         {% for parp in vks.participants %}*/
/*                             <ul>*/
/*                                 <li class="list-group-item">{{ parp.full_path }}</li>*/
/*                             </ul>*/
/*                         {% endfor %}*/
/*                     {% else %}*/
/*                         0*/
/*                     {% endif %}*/
/*                 </li>*/
/*             </ul>*/
/*         </td>*/
/*     </tr>*/
/*     <tr >*/
/*         <td>Комментарий администратору</td>*/
/*         <td>*/
/*             {% if  vks.comment_for_admin|length %}*/
/*                 {{ vks.comment_for_admin }}*/
/*             {% else %}*/
/*                 -*/
/*             {% endif %}*/
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
/*                -*/
/*             {% endif %}*/
/*         </td>*/
/*     </tr>*/
/* {% endblock %}*/
/* */
