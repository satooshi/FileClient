<?php
namespace Contrib\Component\String\Encoding;

/**
 * UTF-8 utility.
 */
class Utf8 extends EncodingConverter
{
    /**
     * Return UTF-8 encoded string from auto detected encoding.
     *
     * auto: ASCII,JIS,UTF-8,EUC-JP,SJIS
     *
     * @param string $str Converting string.
     * @return string UTF-8 encoded string.
     */
    public static function auto($str)
    {
        return mb_convert_encoding($str, 'UTF-8', static::AUTO);
    }
}
