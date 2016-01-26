<?php
class TemplateBuilder
{
    public static function make ($obj,$tplFilename)
    {
//          dump($obj);
          $result = file_get_contents(CORE_REPOSITORY_REAL_PATH.$tplFilename);

           foreach ($obj as $key=>$val) {
               $result = str_replace("{".$key."}",$val, $result);
           }

          return  $result;

    }

    public static function justRender($viewName,$data=[])
    {
        extract($data, EXTR_PREFIX_SAME, "same");
//        dump(CORE_REPOSITORY_REAL_PATH."views/".$viewName.".php");
        if (is_file(CORE_REPOSITORY_REAL_PATH."views/".$viewName.".php"))
            include_once(CORE_REPOSITORY_REAL_PATH."views/".$viewName.".php");
        else {
            $sc = new Controller();
            $sc->error('template-not-found');
        }

    }


    public static function renderPartialForm ($tplFilename,$formAction, $model = false)
    {
        require(CORE_REPOSITORY_REAL_PATH.$tplFilename);

    }
}