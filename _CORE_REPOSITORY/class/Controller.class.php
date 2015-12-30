<?php
use Symfony\Component\HttpFoundation\Request;

class Controller extends Core
{
    use validatorTrait;

    protected $request;

    function __construct($recordId = false)
    {
        $this->initValidator();
        $this->request = Request::createFromGlobals();
    }


    public function render($template, $d = [])
    {
        $data = $d;
        $askBp = ST::lookAtBackPack();

        $request = !count($askBp->request) ? $this->request : $askBp;

        $data['request'] = $request;

        TemplateBuilder::justRender($template, $data);
    }


    public function putUserDataAtBackPack(Request $request)
    {
        $_SESSION['backPack'] = $request;
    }

    public function error($templateName)
    {
        $this->render('errors/' . $templateName);
        die;
    }

} // class end