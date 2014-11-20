<?php

namespace app\modules\directory\helpers;

use app\modules\directory\directoryModule;

class dataGridCellViewHelper {
    public static function getDataString($value, $display = false, $id = false) {
        $result = (!empty($id) ? '<div class="directory-hide-element row-id">'.$id.'</div>' : '');
        
        $_display = empty($display) ? $value : $display;
        
        if(strlen($_display) <= 20) {
            $result .= '<div class="row-display">'.$_display.'</div>';
        } else {
            $result .= '<div class="row-display">'.substr($_display, 0, 20).'</div>'
                    . '<div class="row-display-title">'.$_display.'</div>';
        }
        
        $result .= '<div class="directory-hide-element row-value">'.$value.'</div>';
        
        return $result;
        /*return ($display ? '<div class="row-display">'.$display.'</div>'
                . '<div class="directory-hide-element row-value">'.$value.'</div>' : 
                    '<div class="row-value row-display">'.$value.'</div>')
                . ($id ? '<div class="directory-hide-element row-id">'.$id.'</div>' : '');*/
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
    
    public static function getVisibleFlagString($visible) {
        return '<div align="center"><img src="'
                .(boolSaveHelper::string2boolean($visible) ? 
                directoryModule::getPublishPath('/img/16view.png') : 
            directoryModule::getPublishPath('/img/16lock.png')).'" /></div>';
    }
    
    public static function getValueDataString($type, $value, $text) {
        $result = (strlen($value) <= 20 ? $value : substr($value, 0, 20).'...');
        
        switch($type) {
            case 'string':
                if(strlen($value) > 20) {
                    $result = '<div class="row-display">'.$result.'&nbsp;'
                            . '<img class="directory-show-full-text" title="'
                            .directoryModule::ht('edit', 'Show completely').'" src="'
                            .directoryModule::getPublishPath('/img/info16.png').'" /></div>'
                            .'<div class="directory-hide-element row-value">'.$value.'</div>';
                } else {
                    $result = '<div class="row-display">'.$result.'</div>';
                }
                break;
            case 'text':
                $result = '<div class="row-display">'.$result.'&nbsp;'
                        . '<img class="directory-show-full-text" title="'
                        .directoryModule::ht('edit', 'Show completely').'" src="'
                        .directoryModule::getPublishPath('/img/info16.png').'" /></div>'
                        .'<div class="directory-hide-element row-value"><div>'
                    .$value
                    .'</div><div>'
                    .nl2br($text)
                    . '</div></div>';
                break;
            case 'image':
                $result = '<div class="row-display">'.$result.'&nbsp;'
                        . '<img class="directory-show-full-text" title="'
                        .directoryModule::ht('edit', 'Show completely').'" src="'
                        .directoryModule::getPublishPath('/img/info16.png').'" />'
                    . '&nbsp;<a href="'.$text.'">'.  directoryModule::ht('search', 'Download').'</a></div>'
                        .'<div class="directory-hide-element row-value"><div>'
                    .$value
                    .'</div><div>'
                    .'<img src="'.$text.'" />'
                    . '</div></div>';
                break;
            case 'file':
                $result = '<div class="row-display">'.$result.'&nbsp;'
                        . '<img class="directory-show-full-text" title="'
                        .directoryModule::ht('edit', 'Show completely').'" src="'
                        .directoryModule::getPublishPath('/img/info16.png').'" />'
                    . '&nbsp;<a href="'.$text.'">'.  directoryModule::ht('search', 'Download').'</a></div>'
                        .'<div class="directory-hide-element row-value"><div>'
                    .$value
                    .'</div><div>'
                    .'<a href="'.$text.'">'.  basename($text).'</a>'
                    . '</div></div>';
                break;
        }
        
        return $result;
    }
}
