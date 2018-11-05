<?php

class ReadStatus {

    /**
     * $blockType can be 'Outside','Header','Layer','Control','Table'
     */
    private $blockType, $blockName, $insideBlockStatus;
    private $listValidContours;

    function getBlockType() {
        return $this->blockType;
    }

    function getBlockName() {
        return $this->blockName;
    }

    function getListValidContours() {
        return $this->listValidContours;
    }

    function getInsideBlockStatus() {
        return $this->insideBlockStatus;
    }

    function setBlockType($blockType) {
        $this->blockType = $blockType;
    }

    function setBlockName($blockName) {
        $this->blockName = $blockName;
    }

    function setInsideBlockStatus($insideBlockStatus) {
        $this->insideBlockStatus = $insideBlockStatus;
    }

    function __construct($listValidContours) {
        $this->listValidContours = $listValidContours;
        $insideBlockStatus='N';
    }

}
