<?php
//----------------------------class autoload---------------------------------
function spk_autoloader($class)
{
    @include CORE_REPOSITORY_REAL_PATH . 'models/' . $class . '.php';
    @include CORE_REPOSITORY_REAL_PATH . 'models/observers/' . $class . '.php';
    @include CORE_REPOSITORY_REAL_PATH . 'models/v2/' . $class . '.php';
    @include CORE_REPOSITORY_REAL_PATH . 'models/tech_support/' . $class . '.php';
    @include CORE_REPOSITORY_REAL_PATH . 'models/ca/' . $class . '.php';
    @include CORE_REPOSITORY_REAL_PATH . 'controllers/' . $class . '.php';
    @include CORE_REPOSITORY_REAL_PATH . 'components/' . $class . '.php';
    @include CORE_REPOSITORY_REAL_PATH . 'core/' . $class . '.php';
    @include CORE_REPOSITORY_REAL_PATH . 'class/' . $class . '.class.php';
    @include CORE_REPOSITORY_REAL_PATH . 'asserts/' . $class . '.php';
    @include CORE_REPOSITORY_REAL_PATH . 'exceptions/' . $class . '.php';
    @include CORE_REPOSITORY_REAL_PATH . 'class/pChart/class/' . $class . '.class.php';

}

spl_autoload_register('spk_autoloader');

//----------------------------!Автозагрузка классов----------------------------