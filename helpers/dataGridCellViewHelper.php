<?php

namespace app\modules\directory\helpers;

use app\modules\directory\directoryModule;

class dataGridCellViewHelper {
    public static function getDataTypeString($type) {
        return '<div class="directory-'.$type.'-type row-display">'.directoryModule::ht('edit', $type).'</div>';
    }

    public static function getTextString($text) {
        return '<div class="directory-hide-element row-value">'.nl2br($text).'</div>'
                . '<div class="row-display">'
                .(mb_strlen($text, 'utf8') <= 20 ? $text : 
                        mb_substr($text, 0, 20, 'utf8')
                        .'...&nbsp;<img class="directory-show-full-text" title="'
                        .directoryModule::ht('edit', 'Show completely').'" '
                        . 'src="'.directoryModule::getPublishImage('/info16.png').'" />').'</div>';
    }
    
    public static function getShortTextString($text) {
        return '<div class="directory-hide-element row-value">'.nl2br($text).'</div>'
                . '<div class="row-display"><img class="directory-show-full-text" title="'
                        .directoryModule::ht('edit', 'Show completely').'" '
                        . 'src="'.directoryModule::getPublishImage('/info16.png').'" /></div>';
    }
    
    public static function getVisibleFlagString($visible) {
        return '<div align="center"><img src="'
                .(boolSaveHelper::string2boolean($visible) ? 
                directoryModule::getPublishImage('/16view.png') : 
            directoryModule::getPublishImage('/16lock.png')).'" /></div>';
    }
    
    public static function getValueDataString($type, $value, $text) {
        $result = (mb_strlen($value, 'utf8') <= 20 ? $value : mb_substr($value, 0, 20, 'utf8').'...');
        
        switch($type) {
            case 'string':
                if(mb_strlen($value, 'utf8') > 20) {
                    $result = '<div class="row-display">'.$result.'&nbsp;'
                            . '<img class="directory-show-full-text" title="'
                            .directoryModule::ht('edit', 'Show completely').'" src="'
                            .directoryModule::getPublishImage('/info16.png').'" /></div>'
                            .'<div class="directory-hide-element row-value">'.$value.'</div>';
                } else {
                    $result = '<div class="row-display">'.$result.'</div>';
                }
                break;
            case 'text':
                $result = '<div class="row-display">'.$result.'&nbsp;'
                        . '<img class="directory-show-full-text" title="'
                        .directoryModule::ht('edit', 'Show completely').'" src="'
                        .directoryModule::getPublishImage('/info16.png').'" /></div>'
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
                        .directoryModule::getPublishImage('/info16.png').'" />'
                    . '&nbsp;<a href="'.$text.'" target="_blank" class="directory-data-file-download">'
                    .  directoryModule::ht('search', 'Download').'</a></div>'
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
                        .directoryModule::getPublishImage('/info16.png').'" />'
                    . '&nbsp;<a href="'.$text.'" target="_blank" class="directory-data-file-download">'
                    .  directoryModule::ht('search', 'Download').'</a></div>'
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
