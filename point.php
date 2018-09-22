<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of point
 *
 * @author Stanislav Stanislavov
 */
class point {
    private $x,$y;
    public function getX() {    return $this->x;}
    public function getY() {    return $this->y;}
    function setXY($x,$y) {
        $this->x=$x;
        $this->y=$y;
    }
    function __construct() 
    { 
        $a = func_get_args(); 
        $i = func_num_args(); 
        if (method_exists($this,$f='__construct'.$i)) { 
            call_user_func_array(array($this,$f),$a); 
        }
    }
    
    public function __construct1($partOfLineFromFile) {
        $arr= preg_split("/[\s]+/", $partOfLineFromFile);
        $this->x=$arr[1];
        $this->y=$arr[2];
    }
    
    public function __construct2($partOfLineFromFile,$cooDiferences) {
        $arr= preg_split("/[\s]+/", $partOfLineFromFile);
        $this->x=$arr[1]+$cooDiferences->getX();
        $this->y=$arr[2]+$cooDiferences->getY();
    }
    
    public function updateWindowCorner($downLeft,&$cornerPoint) {
        if ($cornerPoint){
            if (($downLeft ^ $this->x>$cornerPoint->getX() )|| ($downLeft ^ $this->y>$cornerPoint->getY() )){
                $newX=($downLeft ^ $this->x>$cornerPoint->getX() )?$this->x:$cornerPoint->getX();
                $newY=($downLeft ^ $this->y>$cornerPoint->getY() )?$this->y:$cornerPoint->getY();
                $cornerPoint->setXY($newX,$newY);
            }
        } else {
            $cornerPoint=new point("0 $this->x $this->y");
        }
    }
}
