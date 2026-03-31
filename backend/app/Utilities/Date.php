<?php
namespace App\Utilities;
use Carbon\Carbon;
class Date extends Carbon
{
    protected static $formatFunction = 'translatedFormat';
    protected static $createFromFormatFunction = 'createFromFormatWithCurrentLocale';
    protected static $parseFunction = 'parseWithCurrentLocale';
    protected static $monthsOverflow = false;
    protected static $yearsOverflow = false;
    public static function parseWithCurrentLocale($time = null, $timezone = null)
    {
        if (is_string($time)) {
            $time = static::translateTimeString($time, static::getLocale(), 'en');
        }
        return parent::rawParse($time, $timezone);
    }
    public static function createFromFormatWithCurrentLocale($format, $time = null, $timezone = null)
    {
        if (is_string($time)) {
            $time = static::translateTimeString($time, static::getLocale(), 'en');
        }
        return parent::rawCreateFromFormat($format, $time, $timezone);
    }
    public static function getLanguageFromLocale($locale)
    {
        $parts = explode('_', str_replace('-', '_', $locale));
        return $parts[0];
    }
}
