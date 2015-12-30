<?php

/* mails/twig/registration-confirm.twig */
class __TwigTemplate_4a900888be5add25524bfd1d6272cee75d4d7325265eedb085d6529dc6fd5a95 extends Twig_Template
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
        <h3 class=\"text-primary\">Благодарим за регистрацию в АС ВКС</h3>
        <hr>
        <h4 class=\"text-primary\">Уважаемый(ая) ";
        // line 6
        echo twig_escape_filter($this->env, (isset($context["fio"]) ? $context["fio"] : null), "html", null, true);
        echo "</h4>
        <hr>
        <p>Просим подтвердить вашу регистрацию в АС ВКС</p>
        <p>
            Для подтверждения перейдите по
            <a href='";
        // line 11
        echo twig_escape_filter($this->env, (isset($context["appHttpPath"]) ? $context["appHttpPath"] : null), "html", null, true);
        echo "?route=User/approveToken/";
        echo twig_escape_filter($this->env, (isset($context["token"]) ? $context["token"] : null), "html", null, true);
        echo "'>ссылке</a>
            (или скопируйте её в адресную строку браузера):</p>
        <blockquote>";
        // line 13
        echo twig_escape_filter($this->env, (isset($context["appHttpPath"]) ? $context["appHttpPath"] : null), "html", null, true);
        echo "?route=User/approveToken/";
        echo twig_escape_filter($this->env, (isset($context["token"]) ? $context["token"] : null), "html", null, true);
        echo "</blockquote>
<!--        bottom! -->
        ";
        // line 15
        $this->loadTemplate("mails/twig/bottom-include.twig", "mails/twig/registration-confirm.twig", 15)->display($context);
        // line 16
        echo "    </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "mails/twig/registration-confirm.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  50 => 16,  48 => 15,  41 => 13,  34 => 11,  26 => 6,  19 => 1,);
    }
}
