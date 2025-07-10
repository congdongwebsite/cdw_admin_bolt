<?php
defined('ABSPATH') || exit;
class FunctionNumber
{
    private $decimals = 0;
    private $decimal_separator = '.';
    private $thousands_separator = ',';
    private $precision = 0;
    private $symboyVND = 'đ';
    public function __construct()
    {
    }

    public function format($float, $decimals = '', $decimal_separator = '', $thousands_separator = '')
    {
        $decimals = empty($decimals) ? $this->decimals : $decimals;
        $decimal_separator = empty($decimal_separator) ? $this->decimal_separator : $decimal_separator;
        $thousands_separator = empty($thousands_separator) ? $this->thousands_separator : $thousands_separator;
        return number_format((float) $float, $decimals, $decimal_separator, $thousands_separator);
    }
    public function amount($float, $decimals = '0', $decimal_separator = ',', $thousands_separator = '.')
    {
        return $this->format((float) $float, $decimals, $decimal_separator, $thousands_separator);
    }
    public function amountDisplay($float, $decimals = '0', $decimal_separator = ',', $thousands_separator = '.')
    {
        return $this->format((float) $float, $decimals, $decimal_separator, $thousands_separator) . $this->symboyVND;
    }
    public function quantity($float, $decimals = '', $decimal_separator = ',', $thousands_separator = '.')
    {
        return $this->format((float) $float, $decimals, $decimal_separator, $thousands_separator);
    }
    public function round($num, $precision = '', $mode = PHP_ROUND_HALF_UP)
    {
        $precision = empty($precision) ? $this->precision : $precision;
        return round((float)$num, $precision, $mode);
    }
    public function percent($num, $precision = '2', $mode = PHP_ROUND_HALF_UP)
    {
        $precision = empty($precision) ? $this->precision : $precision;
        return round((float)$num, $precision, $mode);
    }
}
