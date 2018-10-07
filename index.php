<?php
if (!php_sapi_name()=="cli"){
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
}
            require 'autoload.php';
            include_once 'listCadasterDistricts.php';
            $j=0;
            define("ACCURANCY", 0.0001);
            define("DEBUGMODE",TRUE);
            
            //$prrob1=new town(NULL);
            //$refPointProb=$prrob1->readFromFile("..\\..\\other projects\\Zip Vraca magistralen\\Proekt\\cad4-with servitut.cad",0,FALSE);
            //echo $prrob1->getScriptPlines();
            
            $city=new Town(NULL);
            $refPointCity=$city->readFromFile("..\\VR_27_06_2016.cad",2,FALSE,$listOfCadDistricts);
            $water=new Town($refPointCity);
            $refPointWater=$water->readFromFile("..\\VOD54_tr.cad",0,TRUE,NULL);
            $sewage=new Town($refPointCity);
            $refPointSewage=$sewage->readFromFile("..\\KAN2005_tr.cad",0,TRUE,NULL);
            //echo $city->getScriptContours(10);
            //echo $city->getScriptPlines();
            echo $city->makeListContours($water);
            echo $city->makeListContours($sewage);
        
if (!php_sapi_name()=="cli"){
        ?>    
    </body>
</html>
<?php
}
