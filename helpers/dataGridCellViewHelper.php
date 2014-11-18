<?php

namespace app\modules\directory\helpers;

use app\modules\directory\directoryModule;

class dataGridCellViewHelper {
    public static function getDataString($value, $display = false, $id = false) {
        return ($display ? '<div class="row-display">'.$display.'</div>'
                . '<div class="directory-hide-element row-value">'.$value.'</div>' : 
                    '<div class="row-value row-display">'.$value.'</div>')
                . ($id ? '<div class="directory-hide-element row-id">'.$id.'</div>' : '');
    }

    public static function getDataTypeString($type) {
        return '<div class="directory-hide-element row-value">'.$type.'</div>'
                . '<div class="directory-'.$type.'-type row-display">'.directoryModule::ht('edit', $type).'</div>';
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
