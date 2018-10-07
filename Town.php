<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of town
 *
 * @author Stanislav Stanislavov
 */
class Town {

    private $contours, $lines, $points, $texts, $symbols;
    private $cooDiferences, $referencePoint;

    public function readFile() {
        $i = func_num_args();
        if ($i < 1) {
            throw 'Must specify file name';
        } elseif ($i > 1) {
            $status = new ReadStatus(func_get_arg(1));
        } else {
            $status = new ReadStatus(NULL);
        }
        $fileName = func_get_args(0);
        if (!($fh = @fopen($fileName, 'r'))) {
            throw 'Cant open file ---->' . $fileName;
        }
        while (!feof($fh)) {
            $strRow = trim(fgets($fh));
            call_user_func('funcRead' . $status->getBlockType(), $strRow, $status);
        }
    }

    private function funcReadOutside($strRow, $status) {
        if (!strcasecmp($strRow, 'HEADER', strlen('HEADER'))) {
            $status->setBlockType('Header');
        } elseif (!strcasecmp($strRow, 'LAYER', strlen('LAYER'))) {
            $status->setBlockType('Layer');
            $status->setBlockName(trim(substr($strRow, strlen('LAYER'))));
        } elseif (!strcasecmp($strRow, 'CONTROL', strlen('CONTROL'))) {
            $status->setBlockType('Control');
            $status->setBlockName(trim(substr($strRow, strlen('CONTROL'))));
        } elseif (!strcasecmp($strRow, 'TABLE', strlen('TABLE'))) {
            $status->setBlockType('Table');
            $status->setBlockName(trim(substr($strRow, strlen('TABLE'))));
        }
    }

    private function funcReadHeader($strRow, ReadStatus $status) {
        if ($strRow == 'END_HEADER') {
            $status->setBlockType('Outside');
            return;
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
        $newObj = NULL;
        $fistSymb = $strRow[0];
        switch ($fistSymb) {
            case 'P':$newPoi = new StabilizedPoint($strRow);
                $this->points[$newPoi->getNumber()] = $newPoi;
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
                $this->texts[$newObj->getNumber()] = $newObj;
                $status->setInsideBlockStatus($newObj);
                break;
            case 'S':$newObj = new Sign($strRow);
                $this->symbols[$newObj->getNumber()] = $newObj;
                break;

            default:
                break;
        }
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

//    public function readFromFile($fileName, $levelLine, $localDebugMode,$listValidContours) {
//        $isInLayer = FALSE;
//        $isInHeader = FALSE;
//        $headerAllreadyRead = FALSE;
//        $currentLine = NULL;
//        $currentContour = NULL;
//        $referencePoint = NULL;
//        if (!($fh = @fopen($fileName, 'r'))) {
//            echo 'Cant open file ---->' . $fileName;
//            return NULL;
//        }
//
//        $status = 'outside';
//        if (DEBUGMODE) {
//            $j = 0;
//            $isFirst=TRUE;//            echo 'isfirst=true';
//            $downLeftPoint=NULL;
//            $upRightPoint=null;            
//        }
//        while (!feof($fh)) {
//            $strRow = trim(fgets($fh));
//            if (!$headerAllreadyRead) {
//                if ($isInHeader) {
//                    if ($strRow == 'END_HEADER') {
//                        $isInHeader = FALSE;
//                        $headerAllreadyRead = TRUE;
//                    } else {
//                        if (!strncmp(trim($strRow), 'REFERENCE', strlen('REFERENCE'))) {
//                            $arr = preg_split("/[\s]+/", $strRow);
//                            $referencePoint = new point($strRow);
//                            if (!$this->cooDiferences) {
//                                $this->cooDiferences = new point("0 0 0");
//                            } else {
//                                $this->cooDiferences->setXY($referencePoint->getX() - $this->cooDiferences->getX(), $referencePoint->getY() - $this->cooDiferences->getY());
//                                if (DEBUGMODE){
//                                    //echo 'reference point -->>';var_dump($referencePoint);
//                                    //echo 'coo diferences in setRefPoint--->>>';var_dump($this->cooDiferences);
//                                }
//                            }
//                        }
//                    }
//                } else {
//                    $isInHeader = (trim($strRow) == 'HEADER');
//                }
//            }
//            if ($isInLayer) {
//                if ($strRow == 'END_LAYER') {
//                    $isInLayer = FALSE;
//                    break;
//                } else {
//                    switch (substr($strRow, 0, 1)) {
//                        case "L":
//                            $status = 'lineToNotWrite';
//                            $arr = preg_split("/[\s]+/", $strRow);
//                            if ($arr[3] >= $levelLine) {
//                                $status = 'lineToWrite';
//                                if (DEBUGMODE) {
//                                    //var_dump($this->lines[(int) $currentLineNumber]);
//                                }
//                                $currentLine = new pLine($strRow);
//                                $currentLineNumber =$arr[2];
//                                $this->lines[$currentLineNumber] = $currentLine;
//                                if (DEBUGMODE && $isFirst){
//                                    //$isFirst=FALSE;
//                                    //var_dump($this->lines);
//                                }
//                                if (DEBUGMODE) {
//                                    //var_dump($this->lines[$currentLineNumber]);
//                                    //var_dump(count($this->lines));
//                                    //var_dump($arr);
//                                    //var_dump($this->lines);
//                                    //if ($j>5) {return NULL;}
//                                }
//                            }
//                            break;
//                        case "P":
//                            $status = 'point';
//                            break;
//                        case "C":
//                            if (DEBUGMODE && $isFirst){
//                                //$isFirst=FALSE;
//                                //var_dump($referencePoint);
//                                //var_dump($this->lines);
//                            }
//                            $arr = preg_split("/[\s]+/", $strRow);
//                            if ($arr[1] == $levelLine && isset($listValidContours[explode(".",$arr[2])[0]])) {
//                                $status = 'contour';
//                                $currentContour = new contour($strRow);
//                                $this->contours[$arr[2]] = $currentContour;
//                            } else {
//                                $status = 'noValidContour';
//                            }
//                            break;
//                        case "E":
//                            $status = 'outside';
//                            break;
//                        case "T":
//                            $status = 'text';
//                            break;
//                        case "S":
//                            $status = 'sign';
//                            break;
//                        default:
//                            if ($status != 'contour' && $status != 'text' && $status != 'lineToWrite') {
//                                $status = 'outside';
//                            } elseif ($status == 'lineToWrite') {
//                                $arr = explode(";", $strRow);
//                                foreach ($arr as $value) {
//                                    if (trim($value)) {                                        
//                                        $point = new point(trim($value), $this->cooDiferences);
//                                        if (DEBUGMODE){
//                                            $point->updateWindowCorner(TRUE,$downLeftPoint);
//                                            $point->updateWindowCorner(FALSE,$upRightPoint);
//                                        }
//                                        $this->points[preg_split("/[\s]+/", trim($value))[0]] = &$point;
//                                        $currentLine->addPoint($point);                                        
//                                    }
//                                }
//                            } elseif ($status == 'contour') {
//                                $arr = preg_split("/[\s]+/", $strRow);
//                                foreach ($arr as $value) {
//                                    $currentContour->addPLine($this->lines[$value]);
//                                    if (DEBUGMODE) {
//                                        //$count=count($this->lines);
//                                        //echo "$value($count)->>";var_dump($this->lines[$value]);                                        
//                                    }
//                                }
//                            }
//
//                            break;
//                    }
//
//                    if (DEBUGMODE) {
//                        $j++;
//                    }
//                }
//            } else {
//                $isInLayer = (trim($strRow) == 'LAYER CADASTER');
//            }
//        }
//        if (DEBUGMODE) {
//            //echo 'reference point -->>';var_dump($referencePoint);
//            //echo 'coo diferences--->>>';var_dump($this->cooDiferences);
//            echo $j .' lines readed ---'. (isset($this->contours)?count($this->contours):'0').' contours readed';
//            //var_dump($downLeftPoint);
//            //var_dump($upRightPoint);
//            echo "minX=".$downLeftPoint->getX()." minY=".$downLeftPoint->getY().")";
//            echo " maxX=".$upRightPoint->getX()." maxY=".$upRightPoint->getY()."\n";
////            foreach ($this->lines as $value) {
////                echo "first pline\n";
////                var_dump($value);
////                break;
////            }            
//        }
//        fclose($fh);
//        return $referencePoint;
//    }

    public function makeListContours($townPipes) {
        $findedContours = NULL;
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
        $this->lines = NULL;
        $this->cooDiferences = $cooDiferencesInput ? new Point('0 ' . $cooDiferencesInput->getX() . ' ' . $cooDiferencesInput->getY()) : NULL;
        //echo 'coo diferences in comstruct--->>>';var_dump($this->cooDiferences);
        //for first time here record reference point for original project
        //if NULL are submitted reference point is first- 0,0
    }

    public function getLines() {
        return $this->lines;
    }

    //----in debug mode

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
