<?php

namespace app\modules\directory\helpers;

class modelErrorsToStringHelper {
    public static function to($errors) {
        $result = '';
        foreach ($errors as $item) {
            foreach ($item as $msg) {
                $result .= '<div>'.$msg.'</div>';
            }
        }
        return $result;
    }
}
