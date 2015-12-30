<?php

/* mails/base.twig */
class __TwigTemplate_e67a5b46af7558f6002bea122038e084b9313404396d70319c2c9f811f344d59 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'body' => array($this, 'block_body'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<style>
    body {
        /*background-color: #cff4cb;*/
        font: \"Georgia\", \"Times New Roman\", serif;
        font-size: 14px;
    }

    .mail-table {
        width: 100%;
        margin: 0 auto;
        /*border-left: 1px solid #1B2024;*/
        background-color: #FFFFFF;
        border-collapse: collapse;
    }
    .table-head-declined {
        background-color: #ff8173;
    }

    .mail-table td {
        padding: 10px;
        font-size: 16px;
        font-family: verdana, arial, helvetica, sans-serif;
        color: #1B2024;
        border: 1px solid #ccc;
    }

    .alt {
        background-color: #F0F0F0;
    }

    .code-connect-font {
        font-family: \"lucida console\", monospace;
        font-size: 18px;
        font-weight: bold;
        color: #FF5346;
    }

    .table-head td {
        font-family: \"lucida console\", monospace;
        background-color: #81AA21;
        color: #242222 !important;
    }
</style>
<body>
<table class=\"mail-table\">
    ";
        // line 46
        $this->displayBlock('body', $context, $blocks);
        // line 49
        echo "    <tr >
        <td colspan=\"2\" align=\"left\"><i>*Сообщение создано в автоматическом режиме</i>
        </td>
    </tr>
    <tr>
        <td align=\"left\" colspan=\"2\">
            <a href=\"";
        // line 55
        echo twig_escape_filter($this->env, (isset($context["appHttpPath"]) ? $context["appHttpPath"] : null), "html", null, true);
        echo "\">Переход к АС ВКС</a>
        </td>
    </tr>
</table>
</body>";
    }

    // line 46
    public function block_body($context, array $blocks = array())
    {
        // line 47
        echo "
    ";
    }

    public function getTemplateName()
    {
        return "mails/base.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  89 => 47,  86 => 46,  77 => 55,  69 => 49,  67 => 46,  20 => 1,);
    }
}
/* <style>*/
/*     body {*/
/*         /*background-color: #cff4cb;*//* */
/*         font: "Georgia", "Times New Roman", serif;*/
/*         font-size: 14px;*/
/*     }*/
/* */
/*     .mail-table {*/
/*         width: 100%;*/
/*         margin: 0 auto;*/
/*         /*border-left: 1px solid #1B2024;*//* */
/*         background-color: #FFFFFF;*/
/*         border-collapse: collapse;*/
/*     }*/
/*     .table-head-declined {*/
/*         background-color: #ff8173;*/
/*     }*/
/* */
/*     .mail-table td {*/
/*         padding: 10px;*/
/*         font-size: 16px;*/
/*         font-family: verdana, arial, helvetica, sans-serif;*/
/*         color: #1B2024;*/
/*         border: 1px solid #ccc;*/
/*     }*/
/* */
/*     .alt {*/
/*         background-color: #F0F0F0;*/
/*     }*/
/* */
/*     .code-connect-font {*/
/*         font-family: "lucida console", monospace;*/
/*         font-size: 18px;*/
/*         font-weight: bold;*/
/*         color: #FF5346;*/
/*     }*/
/* */
/*     .table-head td {*/
/*         font-family: "lucida console", monospace;*/
/*         background-color: #81AA21;*/
/*         color: #242222 !important;*/
/*     }*/
/* </style>*/
/* <body>*/
/* <table class="mail-table">*/
/*     {% block body %}*/
/* */
/*     {% endblock %}*/
/*     <tr >*/
/*         <td colspan="2" align="left"><i>*Сообщение создано в автоматическом режиме</i>*/
/*         </td>*/
/*     </tr>*/
/*     <tr>*/
/*         <td align="left" colspan="2">*/
/*             <a href="{{ appHttpPath }}">Переход к АС ВКС</a>*/
/*         </td>*/
/*     </tr>*/
/* </table>*/
/* </body>*/
