<?php

namespace app\modules\directory;

class directoryModule extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\directory\controllers';
    
    private static $publishPath;
    private static $publishImage;
    private static $publishCSS;


    public function __construct($id, $parent = null, $config = array()) {
        parent::__construct($id, $parent, $config);
        $this->defaultRoute = 'search';
    }

    public static function getPublishPath($resId) {
        if(!isset(directoryModule::$publishPath)) {
            directoryModule::$publishPath = \Yii::$app->assetManager->publish(__DIR__.'/assets')[1];
        }
        
        return directoryModule::$publishPath.$resId;
    }
    public static function getPublishImage($resId) {
        if(!isset(directoryModule::$publishImage)) {
            directoryModule::$publishImage = \Yii::$app->assetManager->publish(__DIR__.'/assets/img')[1];
        }
        
        return directoryModule::$publishImage.$resId;
    }
    public static function getPublishCSS() {
        if(!isset(directoryModule::$publishCSS)) {
            directoryModule::$publishCSS = \Yii::$app->assetManager->publish(__DIR__.'/assets/css/directory-style.css')[1];
        }
        
        return directoryModule::$publishCSS;
    }
    
    public static $SETTING = [    
        'showFooter' => false,
        'pjaxDefaultTimeout' => 30000,
        'uploadPathLocal' => '@webroot/uploads',
        'uploadPathWeb' => '@web/uploads',
        'gridRowCount' => 20,
        'compactGridRowCount' => 7,
        ];
    
    public function init() {
        parent::init();
        
        \Yii::$app->getView()->registerCssFile(
                directoryModule::getPublishCSS());
        
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

    public static function ht($category, $message, $params = [], $language = null) {
        return \yii\helpers\Html::encode(\Yii::t('modules/directory/' . $category, $message, $params, $language));
    }
}
