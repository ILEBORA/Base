<?php
namespace ILEBORA\Helpers;

class UtilHelper
{
    public static function formatCurrency(float $amount): string
    {
        return number_format($amount, 2);
    }
}
