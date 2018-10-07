<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of StabilizedPoint
 *
 * @author Dell
 */
class StabilizedPoint extends Point {

    private $type, $number, $h, $accuracyX, $accuracyY, $accuracyH;

    function getNumber() {
        return $this->number;
    }

    function __construct($strRow) {
        //P 12 3908 11994.506 13849.943 442.336 0 0.000 0.000 0 0.000 0 0 0 0 "" 08.08.2005 0
        $arr = preg_split("/[\s]+/", $strRow);
        $this->type = $arr[1];
        $this->number = $arr[2];
        $this->h = $arr[5];
        $this->accuracyX = $arr[7];
        $this->accuracyY = $arr[8];
        $this->accuracyH = $arr[10];
        parent::__construct1("$arr[2] $arr[3] $arr[4]");
    }

}
