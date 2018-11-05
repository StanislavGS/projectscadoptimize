<?php

class Text {

    private $type, $num, $x, $y, $h, $begDate, $endDate, $rot, $justify,
            $textWithDescription;

    function __construct($parameters) {
        $arr = preg_split("/[\s]+/", $parameters);
        switch (count($arr)) {
            case 2:$this->type = (int) $arr[1];
            case 3:$this->num = (int) $arr[2];
            case 4:$this->x = (double) $arr[3];
            case 5:$this->y = (double) $arr[4];
            case 6:$this->h = (float) $arr[5];
            case 7:$this->begDate = $arr[6];
            case 8:$this->endDate = $arr[7];
            case 9:$this->rot = (float) $arr[8];
            case 10:$this->justify = $arr[9];
        }
    }

    function setTextWithDescription($textWithDescription) {
        $this->textWithDescription = $textWithDescription;
    }

    function getNum() {
        return $this->num;
    }

    public function getScript() {
        return "-text\n$this->yInsidePoint,$this->xInsidePoint\n$this->h\n"
                . "$this->rot\n$this->textWithDescription\n";
    }

//<T t n х у h b d r j>, където: 
//<Т> е символ, който предхожда данни за текст; 
//<t> тип на текста по класификатора в приложение № 1;
//<n> е уникален номер на текста; 
//<х, у> са координати на текста;
//<h> височина на надписа в милиметри на хартията
//<b>  е дата на легалната поява на обекта във формат “dd.mm.gggg”  ;
//<d>  е дата на преустановяване на легалното съществуване обекта във формат “dd.mm.gggg”  ;
//<r> е ъгъл на завъртане на текста (100 гона за хоризонтален). 
//<j> двубуквен код за подравняване – първата буква 
}
