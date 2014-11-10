<?php

namespace app\modules\directory\helpers;

class boolSaveHelper {
    public static function string2boolean($value) {
        return strtoupper($value) === 'Y';
    }
    
    public static function boolean2string($value) {
        return $value ? 'Y' : 'N';
    }
}
