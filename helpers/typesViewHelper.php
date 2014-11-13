<?php

namespace app\modules\directory\helpers;

use app\modules\directory\directoryModule;

class typesViewHelper {
    public static function getNameString($data) {
        return '<div class="directory-hide-element row-id">'.$data['id'].'</div>'
                . '<div class="row-value row-display">'.$data['name'].'</div>';
    }
    
    public static function getTypeString($data) {
        return '<div class="directory-hide-element row-value">'.$data['type'].'</div>'
                . '<div class="directory-'.$data['type'].'-type row-display">'.directoryModule::ht('edit', $data['type']).'</div>';
    }
    
    public static function getTextString($text) {
        return '<div class="directory-hide-element row-value">'.nl2br($text).'</div>'
                . '<div class="row-display">'
                .(strlen($text) <= 20 ? $text : 
                        substr($text, 0, 20)
                        .'...&nbsp;<img class="directory-show-full-text" title="'
                        .directoryModule::ht('edit', 'Show completely').'" '
                        . 'src="'.directoryModule::getPublishPath('/img/info16.png').'" />').'</div>';
    }
}
