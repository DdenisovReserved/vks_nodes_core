<?php

/* mails/twig/bottom-include.twig */
class __TwigTemplate_4711f53f73095a22358e854a02b9d223a40935ac60ac62ed0212c47dd0c27ee7 extends Twig_Template
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
    <h4><a href=\"<?= App::\$instance->opt->appHttpPath ?>\" class=\"like-href\">Перейти к АС ВКС</a></h4>
</p>";
    }

    public function getTemplateName()
    {
        return "mails/twig/bottom-include.twig";
    }

    public function getDebugInfo()
    {
        return array (  19 => 1,);
    }
}
