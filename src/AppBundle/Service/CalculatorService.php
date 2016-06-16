<?php
/**
 * Created by PhpStorm.
 * User: devteam
 * Date: 6/10/16
 * Time: 7:34 PM
 */

namespace AppBundle\Service;


class CalculatorService
{

    public function sum($op1, $op2)
    {
        return $op1 + $op2;
    }

    public function substract($op1, $op2)
    {
        return $op1 - $op2;
    }

}