<?php

namespace App;

class Utils
{
    public static function isAssocArray(array $array): bool
    {
        $keys = array_keys($array);
        return ($keys !== array_keys($keys));
    }
}
