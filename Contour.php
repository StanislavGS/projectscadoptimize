<?php
class Contour {

    private $pLines, $level, $identificator, $xInsidePoint, $yInsidePoint, $begDate, $endDate;

    function isPointInContour($point) {
        $p = 1.0;
        foreach ($this->pLines as $pLine) {
            $p *= $pLine->getMultipleDistancesToPoint($point->getX(), $point->getY());
        }
        return $p <= 0;
    }

    public function addPLine($pLine) {
        $this->pLines[] = $pLine;
    }

    function isInContour($npLine) {
        if ($this->isPointInContour($npLine->getFirstPoint())) {
            return TRUE;
        }
        foreach ($this->pLines as $value) {
            if ($npLine->crossAnotherPLine($value)) {
                //echo 'contour-'.$this->getIdentificator(). ';contour line-'.$value->getIdFromCAD().';another line-'.$npLine->getIdFromCAD()."\n";///debug
                return TRUE;
            }
        }
        return FALSE;
    }
    function getIdentificator() {
        return $this->identificator;
    }

        public function __construct($lineFromFile) {
        $arr = preg_split("/[\s]+/", trim(substr($lineFromFile, 1)));
        $this->level = $arr[0];
        $this->identificator = $arr[1];
        $this->xInsidePoint = $arr[2];
        $this->yInsidePoint = $arr[3];
        $this->begDate = $arr[4];
        $this->endDate = $arr[5];
    }
    
    public function getScript($height) {
        return "-text\n$this->yInsidePoint,$this->xInsidePoint\n$height\n0\n$this->identificator\n";        
    }
    function getXInsidePoint() {
        return $this->xInsidePoint;
    }

    function getYInsidePoint() {
        return $this->yInsidePoint;
    }


}
