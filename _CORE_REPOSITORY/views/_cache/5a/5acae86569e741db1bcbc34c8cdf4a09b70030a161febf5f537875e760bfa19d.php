<?php

/* mails/v2/vksWs-edited.twig */
class __TwigTemplate_62d9b0b2282c21d12bc374f9513ab5d1cc100da3f514f2bad6f049ba3dece7d3 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("mails/base.twig", "mails/v2/vksWs-edited.twig", 1);
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
        echo "<tr class=\"table-head\">
        <td colspan=\"2\">
            <h2>Ваша ВКС #";
        // line 5
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "id", array()), "html", null, true);
        echo " отредактирована администратором</h2>
        </td>
    </tr>
    <tr >
        <td width=\"30%\">Название</td>
        <td>";
        // line 10
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "title", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr >
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
        <td>Администратор</td>
        <td>
            ";
        // line 33
        if ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "approver", array(), "any", true, true)) {
            // line 34
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "approver", array()), "login", array()), "html", null, true);
            if (twig_length_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "approver", array()), "phone", array()))) {
                echo ", тел.";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "approver", array()), "phone", array()), "html", null, true);
                echo "
                ";
            }
            // line 36
            echo "            ";
        } else {
            // line 37
            echo "                Аднистратор пока не назначен
            ";
        }
        // line 39
        echo "        </td>
    </tr>
    <tr>
        <td>Комментарий пользователю</td>
        <td>
            ";
        // line 44
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "comment_for_user", array()))) {
            // line 45
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "comment_for_user", array()), "html", null, true);
            echo "
            ";
        } else {
            // line 47
            echo "                -
            ";
        }
        // line 49
        echo "
        </td>
    </tr>
    <tr >
        <td>Дата начала</td>
        <td>";
        // line 54
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "date", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr>
        <td>Время</td>
        <td>";
        // line 58
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "startTime", array()), "html", null, true);
        echo "-";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "humanized", array()), "endTime", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr >
        <td>Ответственный</td>
        <td>";
        // line 62
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "init_customer_fio", array()), "html", null, true);
        echo ", тел. ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "init_customer_phone", array()), "html", null, true);
        echo "</td>
    </tr>
    <tr>
        <td>Подразделение</td>
        <td>";
        // line 66
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "department_rel", array()), "prefix", array()), "html", null, true);
        echo ". ";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "department_rel", array()), "name", array()), "html", null, true);
        echo "</td>
    </tr>
    ";
        // line 68
        if (($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "other_tb_required", array(), "any", true, true) && ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "other_tb_required", array()) != 0))) {
            // line 69
            echo "        <tr style=\"background-color: #FFC6A3; font-size: 20px;\">
            <td>Подключать другой ТБ/ЦА</td>
            <td>Да</td>
        </tr>
    ";
        }
        // line 74
        echo "    ";
        if ((isset($context["vksCa"]) ? $context["vksCa"] : null)) {
            // line 75
            echo "        <tr style=\"background-color: #FFC6A3; font-size: 20px;\">
            <td>Ссылка-приглашение на ВКС</td>
            <td>
                <b><a href='";
            // line 78
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
        // line 83
        echo "
    <tr >
        <td>Участники
            ";
        // line 87
        echo "        </td>
        <td class=\"small-td\">
            <ul>
                ";
        // line 90
        if ((isset($context["vksCa"]) ? $context["vksCa"] : null)) {
            // line 91
            echo "                    <li class=\"list-group-item-text\">Кол-во участников в ЦА: <span
                                class=\"label label-as-badge label-warning\">";
            // line 92
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vksCa"]) ? $context["vksCa"] : null), "ca_participants", array()), "html", null, true);
            echo "</span>
                    </li>
                    ";
            // line 94
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["vksCa"]) ? $context["vksCa"] : null), "participants", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["parp"]) {
                // line 95
                echo "                        <li class=\"list-group-item-text\">";
                echo twig_escape_filter($this->env, $this->getAttribute($context["parp"], "full_path", array()), "html", null, true);
                echo "</li>
                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['parp'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 97
            echo "                ";
        }
        // line 98
        echo "                <li class=\"list-group-item\">C рабочих мест (IP телефон, Lynс, CMA Desktop и
                    т.д.): ";
        // line 99
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "in_place_participants_count", array()), "html", null, true);
        echo "</li>
                <li>Точки ВКС:
                    ";
        // line 101
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "participants", array()))) {
            // line 102
            echo "                        ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "participants", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["parp"]) {
                // line 103
                echo "                            <ul>
                                <li class=\"list-group-item\">";
                // line 104
                echo twig_escape_filter($this->env, $this->getAttribute($context["parp"], "full_path", array()), "html", null, true);
                echo "</li>
                            </ul>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['parp'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 107
            echo "                    ";
        } else {
            // line 108
            echo "                        0
                    ";
        }
        // line 110
        echo "                </li>
            </ul>
        </td>
    </tr>
    <tr>
        <td>Комментарий администратору</td>
        <td>";
        // line 116
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "comment_for_admin", array()))) {
            // line 117
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "comment_for_admin", array()), "html", null, true);
            echo "
            ";
        } else {
            // line 119
            echo "                -
            ";
        }
        // line 120
        echo " </td>
    </tr>
    <tr>
        <td>Запись ВКС</td>
        <td>
            ";
        // line 125
        if ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "record_required", array())) {
            // line 126
            echo "                Да
            ";
        } else {
            // line 128
            echo "                Нет
            ";
        }
        // line 130
        echo "        </td>
    </tr>
    ";
        // line 133
        echo "        ";
        // line 134
        echo "        ";
        // line 135
        echo "    ";
        // line 136
        echo "    <tr>
        <td>Владелец</td>
        <td>
            ";
        // line 139
        if ($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array())) {
            // line 140
            echo "                ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array()), "login", array()), "html", null, true);
            echo " (";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array()), "fio", array()), "html", null, true);
            echo ", ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vks"]) ? $context["vks"] : null), "owner", array()), "phone", array()), "html", null, true);
            echo ")
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
        return "mails/v2/vksWs-edited.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  341 => 144,  337 => 142,  327 => 140,  325 => 139,  320 => 136,  318 => 135,  316 => 134,  314 => 133,  310 => 130,  306 => 128,  302 => 126,  300 => 125,  293 => 120,  289 => 119,  283 => 117,  281 => 116,  273 => 110,  269 => 108,  266 => 107,  257 => 104,  254 => 103,  249 => 102,  247 => 101,  242 => 99,  239 => 98,  236 => 97,  227 => 95,  223 => 94,  218 => 92,  215 => 91,  213 => 90,  208 => 87,  203 => 83,  189 => 78,  184 => 75,  181 => 74,  174 => 69,  172 => 68,  165 => 66,  156 => 62,  147 => 58,  140 => 54,  133 => 49,  129 => 47,  123 => 45,  121 => 44,  114 => 39,  110 => 37,  107 => 36,  98 => 34,  96 => 33,  90 => 29,  86 => 27,  82 => 25,  74 => 22,  68 => 20,  66 => 19,  62 => 18,  59 => 17,  55 => 16,  52 => 15,  50 => 14,  43 => 10,  35 => 5,  31 => 3,  28 => 2,  11 => 1,);
    }
}
/* {% extends "mails/base.twig" %}*/
/* {% block body %}*/
/* <tr class="table-head">*/
/*         <td colspan="2">*/
/*             <h2>Ваша ВКС #{{ vks.id }} отредактирована администратором</h2>*/
/*         </td>*/
/*     </tr>*/
/*     <tr >*/
/*         <td width="30%">Название</td>*/
/*         <td>{{ vks.title }}</td>*/
/*     </tr>*/
/*     <tr >*/
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
/*     <tr>*/
/*         <td>Администратор</td>*/
/*         <td>*/
/*             {% if vks.approver is defined %}*/
/*                 {{ vks.approver.login }}{% if vks.approver.phone|length %}, тел.{{ vks.approver.phone }}*/
/*                 {% endif %}*/
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
/* */
/*     <tr >*/
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
/*     <tr>*/
/*         <td>Комментарий администратору</td>*/
/*         <td>{% if  vks.comment_for_admin|length %}*/
/*                 {{ vks.comment_for_admin }}*/
/*             {% else %}*/
/*                 -*/
/*             {% endif %} </td>*/
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
/*     {#<tr >#}*/
/*         {#<td>Прямая ссылка на ВКС:</td>#}*/
/*         {#<td>{{ vks.link }}</td>#}*/
/*     {#</tr>#}*/
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
