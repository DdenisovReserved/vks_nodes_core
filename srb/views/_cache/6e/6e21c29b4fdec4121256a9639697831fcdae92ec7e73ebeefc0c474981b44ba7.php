<?php

/* mails/v2/vksWs-report.twig */
class __TwigTemplate_975b8a5b82803484099845f3647a47288f2965861986943af2db37e2fa40e17d extends Twig_Template
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
    <tr>
        <td>Дата/Время | <a href=\"";
        // line 44
        echo twig_escape_filter($this->env, (isset($context["appHttpPath"]) ? $context["appHttpPath"] : null), "html", null, true);
        echo "?route=OutlookCalendarRequest/pushToStack/";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "id", array()), "html", null, true);
        echo "/forced\">Отправить в календарь Outlook</a></td>
        <td>";
        // line 45
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "date", array()), "html", null, true);
        echo ", ";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "startTime", array()), "html", null, true);
        echo " - ";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "endTime", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr>
        <td>Ответственный</td>
        <td>";
        // line 49
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "init_customer_fio", array()), "html", null, true);
        echo ", тел. ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "init_customer_phone", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr>
        <td>Подразделение</td>
        <td>";
        // line 53
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "department_rel", array()), "prefix", array()), "html", null, true);
        echo ". ";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "department_rel", array()), "name", array()), "html", null, true);
        echo "</td>
    </tr>
    ";
        // line 55
        if (($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "other_tb_required", array(), "any", true, true) && ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "other_tb_required", array()) != 0))) {
            // line 56
            echo "        <tr style=\"background-color: #FFC6A3; font-size: 20px;\">
            <td>Подключать другой ТБ/ЦА</td>
            <td>Да</td>
        </tr>
    ";
        }
        // line 61
        echo "    ";
        if ((isset($context["vksCa"]) ? $context["vksCa"] : null)) {
            // line 62
            echo "        <tr style=\"background-color: #FFC6A3; font-size: 20px;\">
            <td>Ссылка-приглашение на ВКС<br><span style=\"color: #e82000  ;\">Пожалуйста, перешлите эту ссылку в другие ТБ</span></td>
            <td>
                <b><a href='";
            // line 65
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
        // line 70
        echo "    ";
        // line 71
        echo "    ";
        // line 72
        echo "    ";
        // line 73
        echo "    ";
        // line 74
        echo "    <tr  >
        <td>Комментарий пользователю</td>
        <td>
            ";
        // line 77
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "comment_for_user", array()))) {
            // line 78
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "comment_for_user", array()), "html", null, true);
            echo "
            ";
        } else {
            // line 80
            echo "                -
            ";
        }
        // line 82
        echo "
        </td>
    </tr>

    <tr>
        <td>Участники
            <span class=\"badge\">";
        // line 88
        echo twig_escape_filter($this->env, (twig_length_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "participants", array())) + $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "in_place_participants_count", array())), "html", null, true);
        echo "</span>
        </td>
        <td class=\"small-td\">
            <ul>
                ";
        // line 92
        if ((isset($context["vksCa"]) ? $context["vksCa"] : null)) {
            // line 93
            echo "                    <li class=\"list-group-item-text\">Кол-во участников в ЦА: <span
                                class=\"label label-as-badge label-warning\">";
            // line 94
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vksCa"]) ? $context["vksCa"] : null), "ca_participants", array()), "html", null, true);
            echo "</span>
                    </li>
                    ";
            // line 96
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["vksCa"]) ? $context["vksCa"] : null), "participants", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["parp"]) {
                // line 97
                echo "                        <li class=\"list-group-item-text\">";
                echo twig_escape_filter($this->env, $this->getAttribute($context["parp"], "full_path", array()), "html", null, true);
                echo "</li>
                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['parp'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 99
            echo "                ";
        }
        // line 100
        echo "                <li class=\"list-group-item\">C рабочих мест (IP телефон, Lynс, CMA Desktop и
                    т.д.): ";
        // line 101
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "in_place_participants_count", array()), "html", null, true);
        echo "</li>
                <li>Точки ВКС:
                    ";
        // line 103
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "participants", array()))) {
            // line 104
            echo "                        ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "participants", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["parp"]) {
                // line 105
                echo "                            <ul>
                                <li class=\"list-group-item\">";
                // line 106
                echo twig_escape_filter($this->env, $this->getAttribute($context["parp"], "full_path", array()), "html", null, true);
                echo "</li>
                            </ul>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['parp'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 109
            echo "                    ";
        } else {
            // line 110
            echo "                        0
                    ";
        }
        // line 112
        echo "                </li>
            </ul>
        </td>
    </tr>
    <tr  >
        <td>Комментарий администратору</td>
        <td>
            ";
        // line 119
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "comment_for_admin", array()))) {
            // line 120
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "comment_for_admin", array()), "html", null, true);
            echo "
            ";
        } else {
            // line 122
            echo "                -
            ";
        }
        // line 124
        echo "        </td>
    </tr>
    <tr>
        <td>Запись ВКС</td>
        <td>
            ";
        // line 129
        if ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "record_required", array())) {
            // line 130
            echo "                Да
            ";
        } else {
            // line 132
            echo "                Нет
            ";
        }
        // line 134
        echo "        </td>
    </tr>
    <tr>
        <td>Владелец</td>
        <td>
            ";
        // line 139
        if ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array())) {
            // line 140
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array()), "fio", array()), "html", null, true);
            echo ", ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array()), "phone", array()), "html", null, true);
            echo "
            ";
        } else {
            // line 142
            echo "                -
            ";
        }
        // line 144
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
        return array (  346 => 144,  342 => 142,  334 => 140,  332 => 139,  325 => 134,  321 => 132,  317 => 130,  315 => 129,  308 => 124,  304 => 122,  298 => 120,  296 => 119,  287 => 112,  283 => 110,  280 => 109,  271 => 106,  268 => 105,  263 => 104,  261 => 103,  256 => 101,  253 => 100,  250 => 99,  241 => 97,  237 => 96,  232 => 94,  229 => 93,  227 => 92,  220 => 88,  212 => 82,  208 => 80,  202 => 78,  200 => 77,  195 => 74,  193 => 73,  191 => 72,  189 => 71,  187 => 70,  173 => 65,  168 => 62,  165 => 61,  158 => 56,  156 => 55,  149 => 53,  140 => 49,  129 => 45,  123 => 44,  118 => 41,  114 => 39,  111 => 38,  104 => 36,  99 => 35,  97 => 34,  90 => 29,  86 => 27,  82 => 25,  74 => 22,  68 => 20,  66 => 19,  62 => 18,  59 => 17,  55 => 16,  52 => 15,  50 => 14,  43 => 10,  35 => 5,  31 => 3,  28 => 2,  11 => 1,);
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
/*     <tr>*/
/*         <td>Дата/Время | <a href="{{ appHttpPath }}?route=OutlookCalendarRequest/pushToStack/{{ vks.id }}/forced">Отправить в календарь Outlook</a></td>*/
/*         <td>{{ vks.humanized.date }}, {{ vks.humanized.startTime }} - {{ vks.humanized.endTime }}</td>*/
/*     </tr>*/
/*     <tr>*/
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
/*             <td>Ссылка-приглашение на ВКС<br><span style="color: #e82000  ;">Пожалуйста, перешлите эту ссылку в другие ТБ</span></td>*/
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
/*                 {{ vks.owner.fio }}, {{ vks.owner.phone }}*/
/*             {% else %}*/
/*                 -*/
/*             {% endif %}*/
/*         </td>*/
/*     </tr>*/
/* {% endblock %}*/
