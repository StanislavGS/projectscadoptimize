<?php
function autoloadA($className){
    //static $classMap=['point'=>'point.php','pLine'=>'pLine.php']
    if (is_readable($className.'.php')){
        require_once $className.'.php';
    }
}
spl_autoload_register("autoloadA");

//require_once 'point.php';
//require_once 'pLine.php';
//require_once 'contour.php';
//require_once 'town.php';