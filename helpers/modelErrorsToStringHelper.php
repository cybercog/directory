<?php

namespace app\modules\directory\helpers;

class modelErrorsToStringHelper {
    public static function to($errors, $fieldInfo = null) {
        $result = '';
        foreach ($errors as $field => $fieldErors) {
            if(isset($fieldInfo) || 
                    (isset($fieldInfo) && is_array($fieldInfo) && isset($fieldInfo[$field]))) {
                if(is_array($fieldInfo)) {
                    foreach ($fieldErors as $msg) {
                        $result .= '<div><strong><em>'.$fieldInfo[$field].'</em></strong> '.$msg.'</div>';
                    }
                } else {
                    foreach ($fieldErors as $msg) {
                        $result .= '<div><strong>'.$fieldInfo.'</strong> '.$msg.'</div>';
                    }
                }
            } else {
                foreach ($fieldErors as $msg) {
                    $result .= '<div>'.$msg.'</div>';
                }
            }
        }
        return $result;
    }
}
