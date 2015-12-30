<?php
use Symfony\Component\Translation\Exception\NotFoundResourceException;
class Assert
{

    public $css = [];
    public $js = [];

//    function __construct(array $css, array $js) {
//        $this->css = $css;
//        $this->js = $js;
//    }

    function init()
    {

        foreach ($this->css as $cssfile) {

                if (!is_file(CORE_REPOSITORY_REAL_PATH."css/{$cssfile}")) throw new NotFoundResourceException("assert file: ".CORE_REPOSITORY_REAL_PATH."css/{$cssfile} not found");

            print "<link rel='stylesheet' type='text/css' href='".CORE_REPOSITORY_HTTP_PATH."css/{$cssfile}'>";
        }
        foreach ($this->js as $js) {
            if (!is_file(CORE_REPOSITORY_REAL_PATH."js/{$js}")) throw new NotFoundResourceException("assert file: ".CORE_REPOSITORY_REAL_PATH."js/{$js} not found");
            print "<script src='".CORE_REPOSITORY_HTTP_PATH."js/{$js}'></script>";
        }
    }

}