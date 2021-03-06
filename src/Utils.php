<?php

namespace IUcto;

use DateTime;

/**
 * Description of ArrayUtils
 *
 * @author odehnal@iprogress.cz
 */
class Utils {

    public static function getValueOrNull(array $array, $key) {
        if (array_key_exists($key, $array)) {
            return $array[$key];
        }
        return null;
    }
    
    public static function getDateTimeFrom($input) {
        if ($input instanceof DateTime) {
            return $input;
        }
        return DateTime::createFromFormat("U", $input);
    }
    
    public static function getTimestampFrom($input) {
        if ($input instanceof DateTime) {
            return $input->format("U");
        }
        return $input;
    }

}
