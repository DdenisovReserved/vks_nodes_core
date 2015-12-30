<?php

class Help_controller extends Controller
{
    public function ask($file, $key)
    {
        $result = null;
        if(file_exists(NODE_REAL_PATH . "config/".$file.".xml")) {
            $helpfile= (object)simplexml_load_file(NODE_REAL_PATH . "config/".$file.".xml");
            foreach($helpfile as $element) {
                if (strval($element->name) == $key) {
                    $result = "<div style='font-size: 16px;'>".strval($element->content)."</div>";
                    if (isset($element->image))
                        $result = "<div class='text-center'><image style='width:95%;' src='".$element->image."'/></div>".$result;
                }


            }
        }

        if (ST::isAjaxRequest()) {
            print json_encode([$result]);
        } else {
            return $result;
        }
    }

}