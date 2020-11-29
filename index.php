<?php

class Validate
{
    public static function validateCard($card)
    {
        if (is_numeric($card) && strlen($card) == 16) {
            return true;
        } else {
            return false;
        }
    }

    public static function validatePin($pin)
    {
        if (is_numeric($pin) && strlen($pin) == 4) {
            return true;
        } else {

            return false;
        }
    }
}

class ATM
{
    public $balance = 10000; //дефолтный баланс на карте
    public $banknote = [500, 200, 100, 50, 20, 10];

    public function __construct()
    {
        $this->inputCard();
    }


    public function inputCard()
    {
        echo PHP_EOL . 'Введите номер вашей карты' . PHP_EOL;

        $card = readline();
        $validCard = Validate::validateCard($card);
        if (!$validCard) {
            echo 'Номер карты введен некорректно' . PHP_EOL;
            $this->inputCard();
        } elseif ($validCard) {
            $this->inputPin();
        }
    }

    public function inputPin()
    {
        echo PHP_EOL . 'Введите Pin-код' . PHP_EOL;

        $pin = readline();
        $validPin = Validate::validatePin($pin);
        if (!$validPin) {
            echo 'Pin-код введен некорректно' . PHP_EOL;
            $this->inputPin()
        } elseif ($validPin) {
            $this->mainMenu();
        }
    }
    public function mainMenu()
    {
        echo  PHP_EOL . "Для просмотра баланса введите 1." . PHP_EOL . "Для выдачи наличных введите 2." . PHP_EOL;
        $menu = readline();
        if (intval($menu) == 1)
        {
            $this->balance();
        }
        elseif ($menu == 2)
        {
            $this->cash();
        }
        else{
            echo 'Ошибка';
            $this->mainMenu();
        }
    }
    public function balance()
    {
        echo "-------------------" . PHP_EOL . "На вашем счету $this->balance гривен" . PHP_EOL . PHP_EOL . "Для выдачи наличных введите 1" . PHP_EOL . "Для завершения работы введите любой другой символ " . PHP_EOL;
        $balance = readline();
        if (intval($balance) == 1){
            $this->cash();
        }

    }
    public function cash()
    {
        echo PHP_EOL . 'Введите сумму для снятия' . PHP_EOL . 'Доступные банкноты: 10, 20, 50, 100, 200, 500 грн' . PHP_EOL;
        $cash = readline();
        if ($cash > $this->balance)
        {
            echo PHP_EOL . 'Недостаточно денег на балансе' . PHP_EOL;
            $this->mainMenu();
        }
        if ($cash <= $this->balance)
        {
            $this->getMoney($cash);
        }
    }
    public function getMoney($cash)
    {
        $necessaryBanknotes = [];

        foreach ($this->banknote as $quantity => $note){
            if (floor($cash / $note) >= 1){
                $necessaryBanknotes[] = ['количество купюр' => floor($cash/ $note ), 'купюра' => $note];
            }
        }
        if (count($necessaryBanknotes)){
            foreach ($necessaryBanknotes as $key =>&$necessaryBanknote){
                foreach ($this->banknote as $note){
                    $return = $cash % ($necessaryBanknote['количество купюр'] * $necessaryBanknote['купюра']);
                    if (floor($return / $note) >= 1){
                        $necessaryBanknote['additional'] = ['количество купюр' => floor($return / $note), 'купюра' => $note];
                    }
                }
                unset($necessaryBanknote);
            }

        }
        $banknoteArray = [];
        $finalBanknoteArray = [];
        foreach ($necessaryBanknotes as $necessaryBanknote){
            $qty = 0;
            if(isset($necessaryBanknote['additional']) && is_array($necessaryBanknote['additional']) && count($necessaryBanknote['additional'])){
                $qty += $necessaryBanknote['additional']['количество купюр'];
            }
            $qty += $necessaryBanknote['количество купюр'];
            $banknoteArray[] = $qty;
        }
        echo PHP_EOL . 'Получите вашу сумму следующими купюрами: ' . PHP_EOL;
        print_r($necessaryBanknotes[array_keys($banknoteArray, min($banknoteArray))[0]]);
        $this->balance = $this->balance - $cash;

        echo 'Для возвращения на главное меню введите 1' . PHP_EOL . 'Для завершения работы введите любую другую кнопку' . PHP_EOL;
        $result = readline();
        if (intval($result == 1)){
            $this->mainMenu();
        }


}
}

$a = new ATM();
