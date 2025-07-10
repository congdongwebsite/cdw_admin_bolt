<?php
defined('ABSPATH') || exit;
class DateTimeHandler
{
    private $formatFrom = "d/m/Y";
    private $formatTo = "Y-m-d H:i:s";


    public $format = "d/m/Y";
    public $formatDB = "Y-m-d H:i:s";
    public $formatTime = "H:i:s";

    public function __construct($formatFrom = null, $formatTo = null)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $formatFrom = $formatFrom ?? $this->formatFrom;
        $formatTo = $formatTo ?? $this->formatTo;
    }

    public function getCurrentDateTime($formatTo = null)
    {
        $formatTo = $formatTo ?? $this->formatTo;
        return date($formatTo);
    }
    public function create_datetime_now($format = null)
    {
        $format = $format ?? $this->formatTo;
        $datetime = new DateTime();
        $datetime_string = $datetime->format($format);
        $datetime = DateTime::createFromFormat($format, $datetime_string);
        return $datetime;
    }
    public function isValidDateFormat($dateString, $dateFormat = null)
    {
        $dateFormat = $dateFormat ?? $this->formatFrom;
        if ($dateString instanceof DateTime)
            $dateTime = $dateString;
        else
            $dateTime = DateTime::createFromFormat($dateFormat, $dateString);
        return $dateTime && $dateTime->format($dateFormat) == $dateString;
    }
    public function create_datetime_from_string($datetime_string, $format = null)
    {
        $format = $format ?? $this->formatTo;
        if ($datetime_string instanceof DateTime)
            $datetime = $datetime_string;
        else
            $datetime = DateTime::createFromFormat($format, $datetime_string);
        return $datetime;
    }
    public function convertDateTime($dateTimeString, $formatFrom = null, $formatTo = null)
    {
        $formatFrom = $formatFrom ?? $this->formatFrom;
        $formatTo = $formatTo ?? $this->formatTo;
        if (!$this->isValidDateFormat($dateTimeString, $formatFrom)) return $dateTimeString;
        if ($dateTimeString instanceof DateTime)
            $dateTime = $dateTimeString;
        else
            $dateTime = DateTime::createFromFormat($formatFrom, $dateTimeString);
        return $dateTime->setTime(0, 0, 0)->format($formatTo);
    }
    public function convertDateTimeDisplay($dateTimeString, $formatFrom = null, $formatTo = null)
    {
        $formatFrom = $formatFrom ?? $this->formatTo;
        $formatTo = $formatTo ?? $this->formatFrom;

        if (!$this->isValidDateFormat($dateTimeString, $formatFrom)) return $dateTimeString;

        if ($dateTimeString instanceof DateTime)
            $dateTime = $dateTimeString;
        else
            $dateTime = DateTime::createFromFormat($formatFrom, $dateTimeString);

        return $dateTime->setTime(0, 0, 0)->format($formatTo);
    }

    public function addDays($dateTimeString, $days, $formatFrom = null, $formatTo = null)
    {
        $formatFrom = $formatFrom ?? $this->formatFrom;
        $formatTo = $formatTo ?? $this->formatTo;
        if ($dateTimeString instanceof DateTime)
            $dateTime = $dateTimeString;
        else
            $dateTime = DateTime::createFromFormat($formatFrom, $dateTimeString);
        $dateTime->modify("+{$days} day");
        return $dateTime->format($formatTo);
    }

    public function addMonths($dateTimeString, $months, $formatFrom = null, $formatTo = null)
    {
        $formatFrom = $formatFrom ?? $this->formatFrom;
        $formatTo = $formatTo ?? $this->formatTo;
        if ($dateTimeString instanceof DateTime)
            $dateTime = $dateTimeString;
        else
            $dateTime = DateTime::createFromFormat($formatFrom, $dateTimeString);
        $dateTime->modify("+{$months} month");
        return $dateTime->format($formatTo);
    }

    public function addYears($dateTimeString, $years, $formatFrom = null, $formatTo = null)
    {
        $formatFrom = $formatFrom ?? $this->formatFrom;
        $formatTo = $formatTo ?? $this->formatTo;
        if ($dateTimeString instanceof DateTime)
            $dateTime = $dateTimeString;
        else
            $dateTime = DateTime::createFromFormat($formatFrom, $dateTimeString);
        $dateTime->modify("+{$years} year");
        return $dateTime->format($formatTo);
    }

    public function subtractDays($dateTimeString, $days, $formatFrom = null, $formatTo = null)
    {
        $formatFrom = $formatFrom ?? $this->formatFrom;
        $formatTo = $formatTo ?? $this->formatTo;
        if ($dateTimeString instanceof DateTime)
            $dateTime = $dateTimeString;
        else
            $dateTime = DateTime::createFromFormat($formatFrom, $dateTimeString);
        $dateTime->modify("-{$days} day");
        return $dateTime->format($formatTo);
    }

    public function subtractMonths($dateTimeString, $months, $formatFrom = null, $formatTo = null)
    {
        $formatFrom = $formatFrom ?? $this->formatFrom;
        $formatTo = $formatTo ?? $this->formatTo;
        if ($dateTimeString instanceof DateTime)
            $dateTime = $dateTimeString;
        else
            $dateTime = DateTime::createFromFormat($formatFrom, $dateTimeString);
        $dateTime->modify("-{$months} month");
        return $dateTime->format($formatTo);
    }

    public function subtractYears($dateTimeString, $years, $formatFrom = null, $formatTo = null)
    {
        $formatFrom = $formatFrom ?? $this->formatFrom;
        $formatTo = $formatTo ?? $this->formatTo;
        if ($dateTimeString instanceof DateTime)
            $dateTime = $dateTimeString;
        else
            $dateTime = DateTime::createFromFormat($formatFrom, $dateTimeString);
        $dateTime->modify("-{$years} year");
        return $dateTime->format($formatTo);
    }
    function human_display($dateTimeString, $formatTo = null)
    {
        $time_ago = human_time_diff(strtotime($dateTimeString), time());
        if (strpos($time_ago, 'second') !== false || strpos($time_ago, 'minute') !== false || strpos($time_ago, 'giây') !== false || strpos($time_ago, 'phút') !== false || strpos($time_ago, 'hour') !== false || strpos($time_ago, 'giờ') !== false) {
            return str_replace(
                array('hour', 'minutes', 'minute', 'seconds', 'second'),
                array('Giờ', 'phút', 'giây', 'giây'),
                $time_ago
            ) . ' trước';
        } elseif (date('Ymd', strtotime($dateTimeString)) == date('Ymd', time())) {
            return 'Hôm nay';
        } elseif (date('Ymd', strtotime('yesterday')) == date('Ymd', strtotime($dateTimeString))) {
            return 'Hôm qua';
        } else {
            return date('d/m/Y', strtotime($dateTimeString));
        }
    }
    function convert_timestamp_to_date($timestamp, $formatTo = null)
    {
        $formatTo = $formatTo ?? $this->format;
        $timestamp = intval($timestamp / 1000);
        $date = date($formatTo, $timestamp);
        return $date;
    }
    function get_timestamp($dateTimeString = "", $formatTo = null)
    {
        $formatTo = $formatTo ?? $this->formatTo;
        if (empty($dateTimeString)) $dateTimeString = date($formatTo);

        return strtotime($dateTimeString);
    }
}
