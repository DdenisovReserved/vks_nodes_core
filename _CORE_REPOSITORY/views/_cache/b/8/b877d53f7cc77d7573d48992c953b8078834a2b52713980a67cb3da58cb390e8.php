<?php

/* mails/twig/vksWs-report.twig */
class __TwigTemplate_b877d53f7cc77d7573d48992c953b8078834a2b52713980a67cb3da58cb390e8 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "
<div class=\"container\">
    <div class=\"col-lg-12\">

    </div>
    <div class=\"col-md-offset-3 col-md-8\">
        <h3 class=\"text-primary\">Ваша ВКС # ";
        // line 7
        echo twig_escape_filter($this->env, (isset($context["id"]) ? $context["id"] : null), "html", null, true);
        echo " успешно создана</h3>
        <hr>

        <table class='table table-bordered table-striped'>
            <thead><h4 class=\"text-primary\">Данные ВКС</h4></thead>
            <!--    //голова таблицы-->
            <th class=\"col-md-4\" colspan=\"2\">Параметр</th>
            <tr>
                <td>Название</td>
                <td>";
        // line 16
        echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true);
        echo "</td>
            <tr>
                <td>Вирт. комната (код подключения)</td>
                <td><span class=\"code-connect-font\">";
        // line 19
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["connection_code"]) ? $context["connection_code"] : null), "value", array()), "html", null, true);
        echo " </span></td>
            <tr>
                <td>Администратор ВКС:</td>
                <td>
                    ";
        // line 23
        if ($this->getAttribute((isset($context["humanized"]) ? $context["humanized"] : null), "approver", array(), "any", true, true)) {
            // line 24
            echo "                        ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["humanized"]) ? $context["humanized"] : null), "approver", array()), "login", array()), "html", null, true);
            echo ", тел.";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["humanized"]) ? $context["humanized"] : null), "approver", array()), "phone", array()), "html", null, true);
            echo "
                    ";
        }
        // line 26
        echo "
                </td>
            <tr>
                <td>Дата начала:</td>
                <td>";
        // line 30
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["humanized"]) ? $context["humanized"] : null), "date", array()), "html", null, true);
        echo "</td>
            <tr>
                <td>Время:</td>
                <td>";
        // line 33
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["humanized"]) ? $context["humanized"] : null), "startTime", array()), "html", null, true);
        echo "-";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["humanized"]) ? $context["humanized"] : null), "endTime", array()), "html", null, true);
        echo "</td>
            <tr>
                <td>Место проведения:</td>
                <td>";
        // line 36
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["humanized"]) ? $context["humanized"] : null), "location", array()), "html", null, true);
        echo "</td>
            <tr>
                <td>Техническая поддержка:</td>
                <td>";
        // line 39
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["humanized"]) ? $context["humanized"] : null), "needTPSupport", array()), "html", null, true);
        echo "</td>
            <tr>
                <td>ФИО председальствующего:</td>
                <td>";
        // line 42
        echo twig_escape_filter($this->env, (isset($context["init_head_fio"]) ? $context["init_head_fio"] : null), "html", null, true);
        echo "</td>
            <tr>
                <td>Вн. Участники
                    <span class=\"badge\">";
        // line 45
        echo twig_escape_filter($this->env, (twig_length_filter($this->env, (isset($context["inside_parp"]) ? $context["inside_parp"] : null)) + twig_length_filter($this->env, (isset($context["phone_parp"]) ? $context["phone_parp"] : null))), "html", null, true);
        echo "</span>
                </td>
                <td>
                    <div class=\"list-group\">
                        ";
        // line 49
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["inside_parp"]) ? $context["inside_parp"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["parp"]) {
            // line 50
            echo "                            <li class=\"list-group-item\">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["parp"], "full_path", array()), "html", null, true);
            echo "</li>
                        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['parp'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 52
        echo "                        ";
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["phone_parp"]) ? $context["phone_parp"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["parp"]) {
            // line 53
            echo "                            <li class=\"list-group-item\">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["parp"], "phone_num", array()), "html", null, true);
            echo "</li>
                        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['parp'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 55
        echo "                    </div>
                </td>
            </tr>
            <tr>
                <td>Внешние Участники <span class=\"badge\">";
        // line 59
        echo twig_escape_filter($this->env, twig_length_filter($this->env, (isset($context["outside_parp"]) ? $context["outside_parp"] : null)), "html", null, true);
        echo "</span></td>
                <td>
                    <div class=\"list-group\">
                        ";
        // line 62
        $context["count"] = 1;
        // line 63
        echo "                        ";
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["outside_parp"]) ? $context["outside_parp"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["parp"]) {
            // line 64
            echo "                            <a href=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($context["parp"], "attendance_value", array()), "html", null, true);
            echo "\" class=\"list-group-item\"><span
                                        class=\"glyphicon glyphicon-file\"></span>Участник #";
            // line 65
            echo twig_escape_filter($this->env, (isset($context["count"]) ? $context["count"] : null), "html", null, true);
            echo "</a>
                            ";
            // line 66
            $context["count"] = ((isset($context["count"]) ? $context["count"] : null) + 1);
            // line 67
            echo "                        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['parp'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 68
        echo "
                    </div>
                </td>
            </tr>
            <tr>
                <td>Комментарий администратору:</td>
                <td>";
        // line 74
        echo twig_escape_filter($this->env, (isset($context["comment_for_admin"]) ? $context["comment_for_admin"] : null), "html", null, true);
        echo " </td>
            <tr>
                <td>Комментарий пользователю:</td>
                <td>";
        // line 77
        echo twig_escape_filter($this->env, (isset($context["comment_for_user"]) ? $context["comment_for_user"] : null), "html", null, true);
        echo "</td>
            <tr>
                <td>Статус:</td>
                <td>";
        // line 80
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["humanized"]) ? $context["humanized"] : null), "status", array()), "html", null, true);
        echo "</td>
        </table>
        ";
        // line 83
        echo "    </div>


</div>

<div class=\"clearfix\"></div>

</div>
";
    }

    public function getTemplateName()
    {
        return "mails/twig/vksWs-report.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  195 => 83,  190 => 80,  184 => 77,  178 => 74,  170 => 68,  164 => 67,  162 => 66,  158 => 65,  153 => 64,  148 => 63,  146 => 62,  140 => 59,  134 => 55,  125 => 53,  120 => 52,  111 => 50,  107 => 49,  100 => 45,  94 => 42,  88 => 39,  82 => 36,  74 => 33,  68 => 30,  62 => 26,  54 => 24,  52 => 23,  45 => 19,  39 => 16,  27 => 7,  19 => 1,);
    }
}
