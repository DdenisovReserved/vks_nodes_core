<?php

/* mails/twig/vksWs-report.twig */
class __TwigTemplate_b0a3e239bb37e8a8d85e815df3e933594afda4e62b0ab69d5aa2a8285a7ee5e7 extends Twig_Template
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
                <td>Администиратор принявший решение:</td>
                <td>
                    ";
        // line 23
        if (array_key_exists("approver", $context)) {
            // line 24
            echo "                        ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["approver"]) ? $context["approver"] : null), "login", array()), "html", null, true);
            echo ", тел.";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["approver"]) ? $context["approver"] : null), "phone", array()), "html", null, true);
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
                <td>Ответственный:</td>
                <td>";
        // line 36
        echo twig_escape_filter($this->env, (isset($context["init_customer_fio"]) ? $context["init_customer_fio"] : null), "html", null, true);
        echo ", т.";
        echo twig_escape_filter($this->env, (isset($context["init_customer_phone"]) ? $context["init_customer_phone"] : null), "html", null, true);
        echo "</td>
            <tr>
                <td>Вн. Участники
                    <span class=\"badge\">";
        // line 39
        echo twig_escape_filter($this->env, (twig_length_filter($this->env, (isset($context["inside_parp"]) ? $context["inside_parp"] : null)) + twig_length_filter($this->env, (isset($context["in_place_participants_count"]) ? $context["in_place_participants_count"] : null))), "html", null, true);
        echo "</span>
                </td>
                <td>
                    <div class=\"list-group\">
                        <li class=\"list-group-item\">ip телефоны: ";
        // line 43
        echo twig_escape_filter($this->env, (isset($context["in_place_participants_count"]) ? $context["in_place_participants_count"] : null), "html", null, true);
        echo "</li>
                        ";
        // line 44
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["inside_parp"]) ? $context["inside_parp"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["parp"]) {
            // line 45
            echo "                            <li class=\"list-group-item\">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["parp"], "full_path", array()), "html", null, true);
            echo "</li>
                        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['parp'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 47
        echo "
                    </div>
                </td>
            </tr>
            <tr>
                <td>Комментарий администратору:</td>
                <td>";
        // line 53
        echo twig_escape_filter($this->env, (isset($context["comment_for_admin"]) ? $context["comment_for_admin"] : null), "html", null, true);
        echo " </td>
            <tr>
                <td>Комментарий пользователю:</td>
                <td>";
        // line 56
        echo twig_escape_filter($this->env, (isset($context["comment_for_user"]) ? $context["comment_for_user"] : null), "html", null, true);
        echo "</td>
            <tr>
                <td>Статус:</td>
                <td>";
        // line 59
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["humanized"]) ? $context["humanized"] : null), "status", array()), "html", null, true);
        echo "</td>
        </table>
        ";
        // line 62
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
        return array (  139 => 62,  134 => 59,  128 => 56,  122 => 53,  114 => 47,  105 => 45,  101 => 44,  97 => 43,  90 => 39,  82 => 36,  74 => 33,  68 => 30,  62 => 26,  54 => 24,  52 => 23,  45 => 19,  39 => 16,  27 => 7,  19 => 1,);
    }
}
