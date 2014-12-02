<?php

namespace app\modules\directory\helpers;

class boolSaveHelper {
    public static function string2boolean($value) {
        return strtoupper($value) === 'Y';
    }

    public static function string2booleanForm($value) {
        return (strtoupper($value) === 'Y') ? '1' : '0';
    }
    
    public static function boolean2string($value) {
        return $value ? 'Y' : 'N';
    }
}
