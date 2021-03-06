<?php

class StabilizedPoint extends Point {

    private $type, $number, $h, $accuracyX, $accuracyY, $accuracyH;

    function getNumber() {
        return $this->number;
    }

    function __construct($strRow) {
        //P 12 3908 11994.506 13849.943 442.336 0 0.000 0.000 0 0.000 0 0 0 0 "" 08.08.2005 0
        $arr = preg_split("/[\s]+/", $strRow);
        $this->type =(int) $arr[1];
        $this->number = (int) $arr[2];
        $this->h = (float) $arr[5];
        $this->accuracyX =(float) $arr[7];
        $this->accuracyY =(float) $arr[8];
        $this->accuracyH =(float) $arr[10];
        parent::__construct1("$arr[2] $arr[3] $arr[4]");
    }

}
