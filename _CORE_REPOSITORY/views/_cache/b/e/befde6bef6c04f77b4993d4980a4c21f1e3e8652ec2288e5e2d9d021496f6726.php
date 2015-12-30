<?php

/* layouts/main.html */
class __TwigTemplate_befde6bef6c04f77b4993d4980a4c21f1e3e8652ec2288e5e2d9d021496f6726 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html>
<head>
    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=EmulateIE8,chrome=1\"/>
    <title>АС ВКС</title>
    <link rel=\"stylesheet\" type=\"text/css\" href=\"css/bootstrap.min.css\" />
    <link rel=\"stylesheet\" type=\"text/css\" href=\"css/core.css\" />
    <script type=\"text/javascript\" src=\"js/jquery/jquery-1.11.0.min.js\"></script>
    <script type=\"text/javascript\" src=\"js/jquery/jquery-ui-1.10.3.custom.js\"></script>
    <script type=\"text/javascript\" src=\"js/jquery-timepicker/jquery-ui-timepicker-addon.js\"></script>
    <script type=\"text/javascript\" src=\"js/jquery/jquery.timepicker.min.js\"></script>
    <script type=\"text/javascript\" src=\"js/support/support.js\"></script>
    <script type=\"text/javascript\" src=\"js/bootstrap.js\"></script>
    <script type=\"text/javascript\" src=\"js/core.js\"></script>
    <link rel=\"stylesheet\" type=\"text/css\" href=\"css/jquery-ui.css\" />
    <link rel=\"stylesheet\" type=\"text/css\" href=\"js/jquery-timepicker/jquery-ui-timepicker-addon.css\" />
    <link rel=\"stylesheet\" type=\"text/css\" href=\"css/jquery.timepicker.css\" />
    <!--[if lt IE 9]>
    <script src=\"js/html5shiv.js\"></script>
    <script src=\"js/respond.min.js\"></script>
    <![endif]-->
    <!--    add fancybox    -->
    <script type=\"text/javascript\" src=\"js/jquery.fancybox.js\"></script>
    <script type=\"text/javascript\" src=\"js/jquery/bootstrap.file-input.js\"></script>

    <link rel=\"stylesheet\" type=\"text/css\" href=\"css/jquery.fancybox.css\" />
    <!--    !add fancybox    -->
    <!-- add bootstrap checkbox change -->
    <link rel=\"stylesheet\" type=\"text/css\" href=\"css/bootstrap-switch.min.css\" />
    <script src=\"js/bootstrap-switch.min.js\"></script>

</head>
<body>

<div id=\"content\">";
        // line 35
        $this->displayBlock('content', $context, $blocks);
        echo "</div>
</body>
</html>";
    }

    public function block_content($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "layouts/main.html";
    }

    public function getDebugInfo()
    {
        return array (  56 => 35,  20 => 1,);
    }
}
