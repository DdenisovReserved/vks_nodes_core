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

    public function error($templateName, $message = '')
    {
        $d = array('message'=>$message);
        $this->render('errors/' . $templateName, $d);
        die;
    }


    public function backWithData($message, $message_class = 'danger')
    {
        $this->putUserDataAtBackPack($this->request);
        App::$instance->MQ->setMessage($message, $message_class);
        ST::redirect("back");
    }

} // class end