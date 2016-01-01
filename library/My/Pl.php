<?php

class My_Pl
{

    const ASCII_ALL = 'acelnoszzACELNOSZZ';

    //ąćęłńóśźżĄĆĘŁŃÓŚŹŻ
    const ISO_ALL = "\xb1\xe6\xea\xb3\xf1\xf3\xbc\xbf\xa1\xc6\xca\xa3\xd1\xd3\xa6\xac\xaf";

    //ąśźĄŚŹ
    const ISO_SPECIFIC = "\xb1\xb6\xbc\xa1\xa6\xac";

    //ąśźĄŚŹ
    const WIN_SPECIFIC = "\xb9\x9c\x9f\xa5\x8c\x8f";

    public static $_ARRAY_TRANSLITERATE = array(
            'é' => 'e', 'ö' => 'o', 'ş' => 's', 'ü' => 'u',
            'á' => 'a', 'ñ' => 'n', 'ç' => 'c', 'è' => 'e',
            'ß' => 'ss'
        );


    /*
     *   Source: ISO-8859-2
     *
     */
    public static function iso2win($string)
    {
        return strtr($string, self::ISO_SPECIFIC, self::WIN_SPECIFIC);
    }
    public static function iso2utf8($string)
    {
        return iconv('ISO-8859-2', 'UTF-8', $string);
    }
    public static function iso2ascii($string)
    {
        return strtr($string, self::ISO_ALL, self::ASCII_ALL);
    }



    /*
     *   Source: UTF-8
     *
     */
    public static function utf82iso($string)
    {
        return iconv('UTF-8', 'ISO-8859-2', $string);
    }
    public static function utf82win($string)
    {
        return iconv('UTF-8', 'WINDOWS-1250', $string);
    }
    public static function utf82ascii($string)
    {
        $string = self::transliterate($string);

        /*
         * Urywamy wszystkie ogonki różne od polskich
         * Polskie ogonki kodujemy w iso
         */
        $string = iconv('utf-8', 'ISO-8859-2//TRANSLIT//IGNORE', $string);

        /*
         * urywamy polskie ogonki
         */
        $string = self::iso2ascii($string);

        return $string;
    }

    public static function transliterate($string)
    {
        return str_replace(
            array_keys(self::$_ARRAY_TRANSLITERATE),
            array_values(self::$_ARRAY_TRANSLITERATE),
            $string
        );
    }

}  