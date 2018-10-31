<?php
class Point {

    private $x, $y;

    public function getX() {
        return $this->x;
    }

    public function getY() {
        return $this->y;
    }

    function setXY($x, $y) {
        $this->x = $x;
        $this->y = $y;
    }

    function __construct() {
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this, $f = '__construct' . $i)) {
            call_user_func_array(array($this, $f), $a);
        }
    }

    public function __construct1($partOfLineFromFile) {
        $arr = preg_split("/[\s]+/", $partOfLineFromFile);
        $this->setXY((double) $arr[1], (double) $arr[2]);        
    }

}
