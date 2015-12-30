<?php

class ModalWindow_controller extends Controller
{
    public function generate()
    {
        $request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();

        $title = $request->request->has('title') ? $request->request->get('title') : '';
        $content = $request->request->has('content') ? $request->request->get('content') : '';
        $more_buttons = $request->request->has('more_buttons') ? $request->request->get('more_buttons') : '';
        $this->render("vks/modals/_template", compact("title", 'content', 'more_buttons'));
    }

    public function instantShow($message)
    {
        ST::setVarPhptoJS($message, 'message');
        echo "
             <script>
            $(document).ready(function() {
                $.fancybox({
                'width': 720,
                'autoSize': false,
                'height': 'auto',
                'content': message
            });
            })
            </script>";
    }

    public function pull()
    {
        $request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
        $this->render("vks/modals/" . $request->request->get('name'));
    }
}