<?php

/* auth/auth.html */
class __TwigTemplate_bc020d2aa93d6696b1b543ec8bf81ab1d735d4a6fa6ea4112e3ab874d05d05ea extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layouts/main.html", "auth/auth.html", 1);
        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "layouts/main.html";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_content($context, array $blocks = array())
    {
        // line 3
        echo "<div class='container'>
    <div class=' col-md-offset-2 col-md-8 block-border-shadow'>
        <div class='col-md-offset-3 col-md-6'>

            <form class='form-horizontal' method='post' action=\"?route=AuthNew/loginProcess\">
                <div class='form-group'>
                    <h4>Войти в систему </h4>
                    <hr>
                </div>
                <div class='form-group'>
                    <label>Логин:</label>
                    <input class='form-control' name='login'/>
                    <span
                            class=\"help-block\">*Совпадает с адресом корпоративной почты (прим.: tomarov@ab.srb.local)</span>
                </div>
                <div class='form-group'>
                    <label>Пароль:</label><input class='form-control' type='password' name='password'>
                </div>
                <div class='form-group'>
                    <input type='checkbox' class=\"checkbox-inline\" name='remMeVks'>&nbsp<label>Запомнить меня</label>
                </div>
                <div class='form-group'>
                    <button class='btn btn-success'>Войти</button>
                </div>
            </form>
        </div>
    </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "auth/auth.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  31 => 3,  28 => 2,  11 => 1,);
    }
}
