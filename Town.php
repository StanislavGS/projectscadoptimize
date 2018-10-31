<?php

class Town {

    private $contours, $lines, $points, $texts, $symbols;
    private $cooDiferences, $referencePoint;

    public function readFile() {
        $i = func_num_args();
        if ($i < 1) {
            throw new Exception('Must specify file name');
        } elseif ($i > 1) {
            $status = new ReadStatus(func_get_arg(1));
        } else {
            $status = new ReadStatus(null);
        }
        $status->setBlockType('Outside');
        $fileName = func_get_arg(0);
        if (!($fh = @fopen($fileName, 'r'))) {
            throw new Exception('Cant open file ---->' . $fileName);
        }
        while (!feof($fh)) {
            $strRow = trim(fgets($fh));
            $funcName='funcRead' . $status->getBlockType();
            call_user_func(array($this,$funcName ), $strRow, $status);            
        }
    }

    private function funcReadOutside($strRow, ReadStatus $status) {
        if (strcasecmp($strRow, 'HEADER')==0) {
            $status->setBlockType('Header');
        } elseif (strncasecmp($strRow, 'LAYER', strlen('LAYER'))==0) {
            $status->setBlockType('Layer');
            $status->setBlockName(trim(substr($strRow, strlen('LAYER'))));
        } elseif (!strncasecmp($strRow, 'CONTROL', strlen('CONTROL'))) {
            $status->setBlockType('Control');
            $status->setBlockName(trim(substr($strRow, strlen('CONTROL'))));
        } elseif (!strncasecmp($strRow, 'TABLE', strlen('TABLE'))) {
            $status->setBlockType('Table');
            $status->setBlockName(trim(substr($strRow, strlen('TABLE'))));
        }
    }

    private function funcReadHeader($strRow, ReadStatus $status) {
        if ($strRow == 'END_HEADER') {
            $status->setBlockType('Outside');
            return;
        } else {
            if (!strncmp($strRow, "REFERENCE", strlen("REFERENCE"))) {
                $this->referencePoint = new Point($strRow);
            }
        }
        //here must by put some code
    }

    private function funcReadLayer($strRow, ReadStatus $status) {
        if (!strlen($strRow)) {
            return;
        }
        if ($strRow == 'END_LAYER') {
            $status->setBlockType('Outside');
            return;
        }
        $newObj = null;
        $fistSymb = $strRow[0];
        switch ($fistSymb) {
            case 'P':$newPoi = new StabilizedPoint($strRow);
                $this->points[$newPoi->getNumber()] = $newPoi;
                $status->setInsideBlockStatus(null);
                break;
            case 'L':$newObj = new PLine($strRow);
                $this->lines[$newObj->getIdFromCAD()] = $newObj;
                $status->setInsideBlockStatus($newObj);
                break;
            case 'C':$newObj = new Contour($strRow);
                $this->contours[$newObj->getIdentificator()] = $newObj;
                $status->setInsideBlockStatus($newObj);
                break;
            case 'T':$newObj = new Text($strRow);
                $this->texts[$newObj->getNum()] = $newObj;
                $status->setInsideBlockStatus($newObj);
                break;
            case 'S':$newObj = new Sign($strRow);
                $this->symbols[$newObj->getNumber()] = $newObj;
                $status->setInsideBlockStatus(null);
                break;
            default :
                if ($status->getInsideBlockStatus()) {
                    $status->setInsideBlockStatus(call_user_func(array($this, 
                        'readLayer' . get_class($status->getInsideBlockStatus())), 
                         $status->getInsideBlockStatus(), $strRow));
                }
        }
    }

    function readLayerPLine(PLine $obj, string $strRow) {
        $arr = explode(";", $strRow);
        foreach ($arr as $value) {
            $value = trim($value);
            if ($value) {
                $point = new point(trim($value));
                $this->points[(int) preg_split("/[\s]+/", trim($value))[0]] = $point;
                $obj->addPoint($point);
            }
        }
        return $obj;
    }

    function readLayerContour(Contour $obj, $strRow) {
        $arr = preg_split("/[\s]+/", $strRow);
        foreach ($arr as $value) {
            $obj->addPLine($this->lines[(int) $value]);
        }
        return $obj;
    }

    function readLayerText(Text $obj, $strRow) {
        $obj->setTextWithDescription($strRow);
        return null;
    }

    private function funcReadControl($strRow, ReadStatus $status) {
        if ($strRow == 'END_CONTROL') {
            $status->setBlockType('Outside');
            return;
        }
        //here must by put some code
    }

    private function funcReadTable($strRow, ReadStatus $status) {
        if ($strRow == 'END_TABLE') {
            $status->setBlockType('Outside');
            return;
        }
        //here must by put some code
    }

    public function makeListContours($townPipes) {
        $findedContours = null;
        $numLines = count($townPipes->getLines());
        $count = (int) $numLines / 100;
        $j = 0;
        $contoursFinded = 0;
        //$isFirst=TRUE;///debug
        //foreach ($this->contours as $value) {echo $value->getIdentificator()."\n";}///debug
        foreach ($townPipes->getLines() as $value) {
            //if ($isFirst) {var_dump ($value);$isFirst=FALSE;die();}///debug
            foreach ($this->contours as $contour) {
                if (!isset($findedContours[$contour->getIdentificator()]) && $contour->isInContour($value)) {
                    $findedContours[$contour->getIdentificator()] = TRUE;
                    $contoursFinded++;
                }
            }
            $j++;
            if ($j % $count == 0) {
                printf("Progress %.1f%%  %d contours finded\n", 100 * $j / $numLines, $contoursFinded);
            }
        }
        $st = '';
        foreach ($findedContours as $key => $vle) {
            $st .= $key . ';' . $this->contours[$key]->getXInsidePoint() . ';' . $this->contours[$key]->getYInsidePoint() . "\n";
        }
        return $st;
    }

    public function __construct($cooDiferencesInput) {
        $this->lines = null;
        $this->cooDiferences = $cooDiferencesInput ? new Point('0 ' . $cooDiferencesInput->getX() . ' ' . $cooDiferencesInput->getY()) : null;
        //echo 'coo diferences in comstruct--->>>';var_dump($this->cooDiferences);
        //for first time here record reference point for original project
        //if null are submitted reference point is first- 0,0
    }

    public function getLines() {
        return $this->lines;
    }

    //----in debug mode
    function getContours() {
        return $this->contours;
    }

        public function getList($objectName, $start, $lenth) {
        $j = 0;
        $st = '';
        foreach ($this->$objectName as $value) {
            if ($j >= $start && $j < $start + $lenth) {
                $st .= var_dump($value);
            } elseif ($j >= $start + $lenth) {
                break;
            }
            $j++;
        }
        return $st;
    }

    public function getScriptPlines() {
        $st = '';
        foreach ($this->lines as $value) {
            $st .= $value->getScriptPline() . "\n";
        }
        return $st;
    }

    public function getScriptContours($height) {
        $st = '';
        foreach ($this->contours as $value) {
            $st .= $value->getScript($height) . "\n";
        }
        return $st;
    }

}
