<?php

/* mails/v2/confirm-registration.twig */
class __TwigTemplate_0174f99e9c232dc28c43feeae386196d7044edf0c77e1397bd11bdb4664f1694 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("mails/base.twig", "mails/v2/confirm-registration.twig", 1);
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
        echo "        <tr class=\"table-head\">
            <td><h2> Благодарим вас за регистрацию в АС ВКС</h2></td>
        </tr>
        <tr>
            <td colspan=\"2\">
                <p>Просим подтвердить вашу регистрацию в АС ВКС,
                    для этого перейдите по
                    <a href='";
        // line 10
        echo twig_escape_filter($this->env, (isset($context["appHttpPath"]) ? $context["appHttpPath"] : null), "html", null, true);
        echo "?route=User/approveToken/";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["user"]) ? $context["user"] : null), "token", array()), "html", null, true);
        echo "'>ссылке</a>
                    или скопируйте ссылку в адресную строку браузера:
                <blockquote>";
        // line 12
        echo twig_escape_filter($this->env, (isset($context["appHttpPath"]) ? $context["appHttpPath"] : null), "html", null, true);
        echo "?route=User/approveToken/";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["user"]) ? $context["user"] : null), "token", array()), "html", null, true);
        echo "</blockquote>
                </p>
            </td>
        </tr>
";
    }

    public function getTemplateName()
    {
        return "mails/v2/confirm-registration.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  47 => 12,  40 => 10,  31 => 3,  28 => 2,  11 => 1,);
    }
}
/* {% extends "mails/base.twig" %}*/
/* {% block body %}*/
/*         <tr class="table-head">*/
/*             <td><h2> Благодарим вас за регистрацию в АС ВКС</h2></td>*/
/*         </tr>*/
/*         <tr>*/
/*             <td colspan="2">*/
/*                 <p>Просим подтвердить вашу регистрацию в АС ВКС,*/
/*                     для этого перейдите по*/
/*                     <a href='{{ appHttpPath }}?route=User/approveToken/{{ user.token }}'>ссылке</a>*/
/*                     или скопируйте ссылку в адресную строку браузера:*/
/*                 <blockquote>{{ appHttpPath }}?route=User/approveToken/{{ user.token }}</blockquote>*/
/*                 </p>*/
/*             </td>*/
/*         </tr>*/
/* {% endblock %}*/
