<?php

namespace BrCommons\Mask;

class Strings
{

    const ONLY_NUMBERS = '/[^0-9]/';

    public static function mask($val, $mask)
    {

        $val = self::cleanInput($val);

        $maskared = '';
        $k = 0;

        for ($i = 0; $i<=strlen($mask)-1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$k])) {
                    $maskared .= $val[$k++];
                }
            } else {
                if (isset($mask[$i])) {
                    $maskared .= $mask[$i];
                }
            }
        }

        return $maskared;
    }

    public static function cleanInput($string)
    {
        return preg_replace(self::ONLY_NUMBERS, '', (string) $string);
    }
}
