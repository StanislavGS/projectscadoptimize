<?php

class Sign {

    private $type, $num, $x, $y, $rot, $scale, $begDate, $endDate;

//    10.	Условните знаци се представят във формат <S t n х у a m b d>, където:
//<S> е символ, който предхожда данни за условен знак;
//<t> е тип на условния знак по класификатора в приложение № 1;
//<n> е уникален номер на условния знак;
//<х, у> са координати на референтната точка на условния знак;
//< a > е ъгъл на завъртане на условния знак;
//<m> е мащабен коефициент на условния знак.
//<b>  е дата на легалната поява на обекта във формат “dd.mm.gggg”  ;
//<d>  е дата на преустановяване на легалното съществуване обекта във формат “dd.mm.gggg”  ;
    public function __construct($lineFromFile) {
        $arr = preg_split("/[\s]+/", trim(substr($lineFromFile, 1)));
        switch (count($arr)) {
            case 1:$this->type = $arr[0];
            case 2:$this->num = $arr[1];
            case 3:$this->x = $arr[2];
            case 4:$this->y = $arr[3];
            case 5:$this->rot = $arr[4];
            case 6:$this->scale = $arr[5];
            case 7:$this->begDate = $arr[6];
            case 8:$this->endDate = $arr[7];
        }
    }

    public function getScript($height) {
        return '-I' . PHP_EOL . 'UZ' . $this->type . PHP_EOL . $this->x . ',' . $this->y . PHP_EOL .
                $this->scale . PHP_EOL . $this->scale . PHP_EOL . $this->rot . PHP_EOL;
    }

    function getNum() {
        return $this->num;
    }

}
