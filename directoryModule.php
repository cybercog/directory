<?php

namespace app\modules\directory;

class directoryModule extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\directory\controllers';
    
    public $showFooter = false;
    
    private static $cssPublishPath;
    private static $imgPublishPath;
    
    public static function getCSSPath() {
        if(!isset(directoryModule::$cssPublishPath)) {
            directoryModule::$cssPublishPath = \Yii::$app->assetManager->publish(__DIR__.'/css')[1];
        }
        
        return directoryModule::$cssPublishPath;
    }
    public static function getImagePath() {
        if(!isset(directoryModule::$imgPublishPath)) {
            directoryModule::$imgPublishPath = \Yii::$app->assetManager->publish(__DIR__.'/img')[1];
        }
        
        return directoryModule::$imgPublishPath;
    }

    public function init() {
        parent::init();
        
        \Yii::$app->getView()->registerCssFile(
                directoryModule::getCSSPath().'/directory-style.css');
        
        \Yii::$app->i18n->translations['modules/directory/*'] = [
            'class'          => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-EN',
            'basePath'       => '@app/modules/directory/i18n',
            'fileMap'        => [
                'modules/directory/search' => 'search.php',
                'modules/directory/edit' => 'edit.php',
            ],
        ];
    }
    
    public static function t($category, $message, $params = [], $language = null) {
        return \Yii::t('modules/directory/' . $category, $message, $params, $language);
    }
}
