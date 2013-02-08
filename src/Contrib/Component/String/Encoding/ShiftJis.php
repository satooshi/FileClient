<?php
namespace Contrib\Component\String\Encoding;

class ShiftJis extends EncodingConverter
{
    /**
     * Return SJIS encoded string from auto detected encoding.
     *
     * auto: ASCII,JIS,UTF-8,EUC-JP,SJIS
     *
     * @param string $str Converting string.
     * @return string SJIS encoded string.
     */
    public static function auto($str)
    {
        return mb_convert_encoding($str, 'SJIS', static::AUTO);
    }
}
