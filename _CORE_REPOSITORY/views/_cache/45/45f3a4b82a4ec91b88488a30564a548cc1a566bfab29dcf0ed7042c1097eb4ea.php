<?php

/* mails/v2/vksWs-report.twig */
class __TwigTemplate_8c855cdbf150dedaca29b9a4dae0cff572541493ee16b5392affa8a29f75e07a extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("mails/base.twig", "mails/v2/vksWs-report.twig", 1);
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
    <tr>
        <td width=\"30%\">Название</td>
        <td>";
        // line 10
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "title", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr  >
        <td>Вирт. комната (код подключения)</td>
        ";
        // line 14
        if (($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "connection_codes", array(), "any", true, true) && twig_length_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "connection_codes", array())))) {
            // line 15
            echo "            <td>
                ";
            // line 16
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "connection_codes", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["code"]) {
                // line 17
                echo "                    <p>
                <span class=\"code-connect-font\">";
                // line 18
                echo twig_escape_filter($this->env, $this->getAttribute($context["code"], "value_raw", array()), "html", null, true);
                echo "
                    ";
                // line 19
                if (twig_length_filter($this->env, $this->getAttribute($context["code"], "tip", array()))) {
                    // line 20
                    echo "                        (";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["code"], "tip", array()), "html", null, true);
                    echo ")
                    ";
                }
                // line 22
                echo "                    </span>
                    </p>
                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['code'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 25
            echo "            </td>
        ";
        } else {
            // line 27
            echo "            <td><span class=\"code-connect-font\">Код подключения не выдан или не требуется</span></td>
        ";
        }
        // line 29
        echo "    </tr>

    <tr>
        <td>Администратор принявший решение</td>
        <td>
            ";
        // line 34
        if ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "approver", array(), "any", true, true)) {
            // line 35
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "approver", array()), "login", array()), "html", null, true);
            echo "
                ";
            // line 36
            if (twig_length_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "approver", array()), "phone", array()))) {
                echo ", тел.";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "approver", array()), "phone", array()), "html", null, true);
                echo "
                ";
            }
            // line 38
            echo "            ";
        } else {
            // line 39
            echo "                Аднистратор пока не назначен
            ";
        }
        // line 41
        echo "        </td>
    </tr>
    <tr  >
        <td>Дата начала</td>
        <td>";
        // line 45
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "date", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr>
        <td>Время</td>
        <td>";
        // line 49
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "startTime", array()), "html", null, true);
        echo "-";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "endTime", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr  >
        <td>Ответственный</td>
        <td>";
        // line 53
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "init_customer_fio", array()), "html", null, true);
        echo ", тел. ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "init_customer_phone", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr>
        <td>Подразделение</td>
        <td>";
        // line 57
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "department_rel", array()), "prefix", array()), "html", null, true);
        echo ". ";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "department_rel", array()), "name", array()), "html", null, true);
        echo "</td>
    </tr>
    ";
        // line 59
        if (($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "other_tb_required", array(), "any", true, true) && ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "other_tb_required", array()) != 0))) {
            // line 60
            echo "        <tr style=\"background-color: #FFC6A3; font-size: 20px;\">
            <td>Подключать другой ТБ/ЦА</td>
            <td>Да</td>
        </tr>
    ";
        }
        // line 65
        echo "    ";
        if ((isset($context["vksCa"]) ? $context["vksCa"] : null)) {
            // line 66
            echo "        <tr style=\"background-color: #FFC6A3; font-size: 20px;\">
            <td>Ссылка-приглашение на ВКС</td>
            <td>
                <b><a href='";
            // line 69
            echo twig_escape_filter($this->env, (isset($context["http_path"]) ? $context["http_path"] : null), "html", null, true);
            echo "i.php?r=";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vksCa"]) ? $context["vksCa"] : null), "referral", array()), "html", null, true);
            echo "'>";
            echo twig_escape_filter($this->env, (isset($context["http_path"]) ? $context["http_path"] : null), "html", null, true);
            echo "i.php?r=";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vksCa"]) ? $context["vksCa"] : null), "referral", array()), "html", null, true);
            echo "</a></b>
            </td>
        </tr>

    ";
        }
        // line 74
        echo "    ";
        // line 75
        echo "    ";
        // line 76
        echo "    ";
        // line 77
        echo "    ";
        // line 78
        echo "    <tr  >
        <td>Комментарий пользователю</td>
        <td>
            ";
        // line 81
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "comment_for_user", array()))) {
            // line 82
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "comment_for_user", array()), "html", null, true);
            echo "
            ";
        } else {
            // line 84
            echo "                -
            ";
        }
        // line 86
        echo "
        </td>
    </tr>

    <tr>
        <td>Участники
            <span class=\"badge\">";
        // line 92
        echo twig_escape_filter($this->env, (twig_length_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "participants", array())) + $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "in_place_participants_count", array())), "html", null, true);
        echo "</span>
        </td>
        <td class=\"small-td\">
            <ul>
                ";
        // line 96
        if ((isset($context["vksCa"]) ? $context["vksCa"] : null)) {
            // line 97
            echo "                    <li class=\"list-group-item-text\">Кол-во участников в ЦА: <span
                                class=\"label label-as-badge label-warning\">";
            // line 98
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vksCa"]) ? $context["vksCa"] : null), "ca_participants", array()), "html", null, true);
            echo "</span>
                    </li>
                    ";
            // line 100
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["vksCa"]) ? $context["vksCa"] : null), "participants", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["parp"]) {
                // line 101
                echo "                        <li class=\"list-group-item-text\">";
                echo twig_escape_filter($this->env, $this->getAttribute($context["parp"], "full_path", array()), "html", null, true);
                echo "</li>
                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['parp'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 103
            echo "                ";
        }
        // line 104
        echo "                <li class=\"list-group-item\">C рабочих мест (IP телефон, Lynс, CMA Desktop и
                    т.д.): ";
        // line 105
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "in_place_participants_count", array()), "html", null, true);
        echo "</li>
                <li>Точки ВКС:
                    ";
        // line 107
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "participants", array()))) {
            // line 108
            echo "                        ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "participants", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["parp"]) {
                // line 109
                echo "                            <ul>
                                <li class=\"list-group-item\">";
                // line 110
                echo twig_escape_filter($this->env, $this->getAttribute($context["parp"], "full_path", array()), "html", null, true);
                echo "</li>
                            </ul>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['parp'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 113
            echo "                    ";
        } else {
            // line 114
            echo "                        0
                    ";
        }
        // line 116
        echo "                </li>
            </ul>
        </td>
    </tr>
    <tr  >
        <td>Комментарий администратору</td>
        <td>
            ";
        // line 123
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "comment_for_admin", array()))) {
            // line 124
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "comment_for_admin", array()), "html", null, true);
            echo "
            ";
        } else {
            // line 126
            echo "                -
            ";
        }
        // line 128
        echo "        </td>
    </tr>
    <tr>
        <td>Запись ВКС</td>
        <td>
            ";
        // line 133
        if ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "record_required", array())) {
            // line 134
            echo "                Да
            ";
        } else {
            // line 136
            echo "                Нет
            ";
        }
        // line 138
        echo "        </td>
    </tr>
    <tr>
        <td>Владелец</td>
        <td>
            ";
        // line 143
        if ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array())) {
            // line 144
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array()), "login", array()), "html", null, true);
            echo " (";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array()), "fio", array()), "html", null, true);
            echo ", ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array()), "phone", array()), "html", null, true);
            echo ")
            ";
        } else {
            // line 146
            echo "                -
            ";
        }
        // line 148
        echo "        </td>
    </tr>
";
    }

    public function getTemplateName()
    {
        return "mails/v2/vksWs-report.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  348 => 148,  344 => 146,  334 => 144,  332 => 143,  325 => 138,  321 => 136,  317 => 134,  315 => 133,  308 => 128,  304 => 126,  298 => 124,  296 => 123,  287 => 116,  283 => 114,  280 => 113,  271 => 110,  268 => 109,  263 => 108,  261 => 107,  256 => 105,  253 => 104,  250 => 103,  241 => 101,  237 => 100,  232 => 98,  229 => 97,  227 => 96,  220 => 92,  212 => 86,  208 => 84,  202 => 82,  200 => 81,  195 => 78,  193 => 77,  191 => 76,  189 => 75,  187 => 74,  173 => 69,  168 => 66,  165 => 65,  158 => 60,  156 => 59,  149 => 57,  140 => 53,  131 => 49,  124 => 45,  118 => 41,  114 => 39,  111 => 38,  104 => 36,  99 => 35,  97 => 34,  90 => 29,  86 => 27,  82 => 25,  74 => 22,  68 => 20,  66 => 19,  62 => 18,  59 => 17,  55 => 16,  52 => 15,  50 => 14,  43 => 10,  35 => 5,  31 => 3,  28 => 2,  11 => 1,);
    }
}
/* {% extends "mails/base.twig" %}*/
/* {% block body %}*/
/*     <tr class="table-head">*/
/*         <td colspan="2">*/
/*             <h2>Ваша ВКС #{{ vks.id }} успешно создана</h2>*/
/*         </td>*/
/*     </tr>*/
/*     <tr>*/
/*         <td width="30%">Название</td>*/
/*         <td>{{ vks.title }}</td>*/
/*     </tr>*/
/*     <tr  >*/
/*         <td>Вирт. комната (код подключения)</td>*/
/*         {% if vks.connection_codes is defined and vks.connection_codes|length %}*/
/*             <td>*/
/*                 {% for code in vks.connection_codes %}*/
/*                     <p>*/
/*                 <span class="code-connect-font">{{ code.value_raw }}*/
/*                     {% if  code.tip|length %}*/
/*                         ({{ code.tip }})*/
/*                     {% endif %}*/
/*                     </span>*/
/*                     </p>*/
/*                 {% endfor %}*/
/*             </td>*/
/*         {% else %}*/
/*             <td><span class="code-connect-font">Код подключения не выдан или не требуется</span></td>*/
/*         {% endif %}*/
/*     </tr>*/
/* */
/*     <tr>*/
/*         <td>Администратор принявший решение</td>*/
/*         <td>*/
/*             {% if vks.approver is defined %}*/
/*                 {{ vks.approver.login }}*/
/*                 {% if vks.approver.phone|length %}, тел.{{ vks.approver.phone }}*/
/*                 {% endif %}*/
/*             {% else %}*/
/*                 Аднистратор пока не назначен*/
/*             {% endif %}*/
/*         </td>*/
/*     </tr>*/
/*     <tr  >*/
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
/*     {% if vksCa %}*/
/*         <tr style="background-color: #FFC6A3; font-size: 20px;">*/
/*             <td>Ссылка-приглашение на ВКС</td>*/
/*             <td>*/
/*                 <b><a href='{{ http_path }}i.php?r={{ vksCa.referral }}'>{{ http_path }}i.php?r={{ vksCa.referral }}</a></b>*/
/*             </td>*/
/*         </tr>*/
/* */
/*     {% endif %}*/
/*     {#<tr  >#}*/
/*     {#<td>Инициатор:</td>#}*/
/*     {#<td>{{ vks.initiator_rel.name }}</td>#}*/
/*     {#</tr>#}*/
/*     <tr  >*/
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
/*             <span class="badge">{{ vks.participants|length + vks.in_place_participants_count }}</span>*/
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
/*     <tr  >*/
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
/*                 -*/
/*             {% endif %}*/
/*         </td>*/
/*     </tr>*/
/* {% endblock %}*/
