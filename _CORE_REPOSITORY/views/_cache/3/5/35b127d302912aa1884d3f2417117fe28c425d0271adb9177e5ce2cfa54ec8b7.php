<?php

/* mails/twig/bottom-include.twig */
class __TwigTemplate_35b127d302912aa1884d3f2417117fe28c425d0271adb9177e5ce2cfa54ec8b7 extends Twig_Template
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
        echo "<hr>
<p>
    <i>Сообщение создано автоматически, если вы ничего не знаете о АС ВКС и не заполняли никаких форм, просто
        проигнорируйте его.</i>
</p>
<p>
    <h4><a href=\"";
        // line 7
        echo twig_escape_filter($this->env, (isset($context["appHttpPath"]) ? $context["appHttpPath"] : null), "html", null, true);
        echo "\" class=\"like-href\">Перейти к АС ВКС</a></h4>
</p>";
    }

    public function getTemplateName()
    {
        return "mails/twig/bottom-include.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  27 => 7,  19 => 1,);
    }
}
