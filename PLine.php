<?php
class PLine {

    private $points, $type, $idFromCAD, $borderType, $begDate, $endDate, $elevation;

    function crossLines($l1p1, $l1p2, $l2p1, $l2p2) {
        if (max($l1p1->getX(), $l1p2->getX()) < min($l2p1->getX(), $l2p2->getX()) ||
                max($l2p1->getX(), $l2p2->getX()) < min($l1p1->getX(), $l1p2->getX()) ||
                max($l1p1->getY(), $l1p2->getY()) < min($l2p1->getY(), $l2p2->getY()) ||
                max($l2p1->getY(), $l2p2->getY()) < min($l1p1->getY(), $l1p2->getY())) {
            return FALSE;
        }
        if (abs($l1p1->getX() - $l1p2->getX()) < ACCURANCY) {
            if (abs($l2p1->getX() - $l2p2->getX()) < ACCURANCY) {
                return (abs($l1p1->getY() - $l2p2->getY()) < ACCURANCY);
            }
            $a = ($l2p2->getY() - $l2p1->getY()) / ($l2p2->getX() - $l2p1->getX());
            $b = $l2p1->getY() - $a * $l2p1->getX();
            $x = 0.5 * ($l1p1->getX() + $l1p2->getX());
            $y = $a * $x + $b;
            return ($y - $l2p1->getY()) * ($y - $l2p2->getY()) <= 0;
        }
        $a = ($l1p2->getY() - $l1p1->getY()) / ($l1p2->getX() - $l1p1->getX());
        $b = $l1p1->getY() - $a * $l1p1->getX();
        $a1 = ($l2p2->getY() - $l2p1->getY()) / ($l2p2->getX() - $l2p1->getX());
        $b1 = $l2p1->getY() - $a1 * $l2p1->getX();
        if (abs($a-$a1)*$l1p1->getX()<ACCURANCY){
            return abs($b-$b1)<ACCURANCY;
        }
        $xIntersect=($b1-$b)/($a-$a1);
        
        
        if (($xIntersect-$l1p1->getX())*($xIntersect-$l1p2->getX())<0 &&
                ($xIntersect-$l2p1->getX())*($xIntersect-$l2p2->getX())<0){            
        }
        return ($xIntersect-$l1p1->getX())*($xIntersect-$l1p2->getX())<0 &&
                ($xIntersect-$l2p1->getX())*($xIntersect-$l2p2->getX())<0;
    }

    public function addPoint($point) {
        $this->points[] = $point;
    }

    public function crossAnotherPLine($anotherPLine) {
        $ptOld = NULL;
        foreach ($this->points as $pt1) {
            if ($ptOld) {
                $ptOld2 = NULL;
                foreach ($anotherPLine->getPoints() as $pt2) {
                    if ($ptOld2) {
                        if ($this->crossLines($ptOld, $pt1, $ptOld2, $pt2)) {
                            return TRUE;
                        }
                    }
                    $ptOld2 = $pt2;
                }
            }
            $ptOld = $pt1;
        }
        return FALSE;
    }

    function getMultipleDistancesToPoint($x, $y) {
        $ptOld = NULL;
        $p = 1.0;
        foreach ($this->points as $pt1) {
            if ($ptOld) {
                if ($x >= min($ptOld->getX(), $pt1->getX()) && $x < max($ptOld->getX(), $pt1->getX())) {
                    $a = ($pt1->getY() - $ptOld->getY()) / ($pt1->getX() - $ptOld->getX());
                    $b = $pt1->getY() - $a * $pt1->getX();
                    $p *= ($a * $x + $b - $y);
                }
            }
            $ptOld = $pt1;
        }
        return $p;
    }

    public function __construct($lineFromFile) {
        $arr = preg_split("/[\s]+/", trim(substr($lineFromFile, 1)));
        $this->type = $arr[0];
        $this->idFromCAD =(int) $arr[1];
        $this->borderType = $arr[2];
        $this->begDate = $arr[3];
        $this->endDate = $arr[4];
        if (isset($arr[5])) {
            $this->elevation = $arr[5];
        }
    }

    public function getFirstPoint() {
        return $this->points[0];       
    }

    public function getBegDate() {
        return $this->begDate;
    }

    public function getPoints() {
        return $this->points;
    }
    
    public function getScriptPline() {
        $st='pline ';
        foreach ($this->points as $value) {
            $st.=$value->getY().','.$value->getX().PHP_EOL;
        }
        return $st;
    }
    function getIdFromCAD() {
        return $this->idFromCAD;
    }

    function getBorderType() {
        return $this->borderType;
    }


}
