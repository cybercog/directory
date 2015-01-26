<?php

namespace app\modules\directory\helpers;

class dbStringParser {
    public static function ParseStringL1($str) {
        if(is_string($str) && (strlen($str) > 0)) {
            $data = [];
            $spl = mb_split(chr(3), $str);
            $spl[] = chr(7);
            $spl_chr = false;
            foreach ($spl as $item) {
                if($item == chr(7)) {
                    if($spl_chr) {
                        $data[] = null;
                    } else {
                        $spl_chr = true;
                    }
                } else {
                    $spl_chr = false;
                    $data[] = $item;
                }
            }
            
            if(count($data) > 0) {
                return $data;
            }
        }
        
        return false;
    }

    public static function ParseStringL2($str) {
        if(is_string($str) && (strlen($str) > 0)) {
            $data = [];
            $spl = mb_split(chr(4), $str);
            foreach($spl as $item) {
                $data_t = dbStringParser::ParseStringL1($item);
                if($data_t) {
                    $data[] = $data_t;
                }
            }
            
            if(count($data) > 0) {
                return $data;
            }
        }
        
        return false;
    }
}
