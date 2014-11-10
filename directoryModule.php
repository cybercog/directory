<?php

namespace app\modules\directory;

class directoryModule extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\directory\controllers';
    
    public $showFooter = false;
    public $pjaxDefaultTimeout = 30000;
    public $uploadDir;
    
    private static $publishPath;
    
    public function __construct($id, $parent = null, $config = array()) {
        parent::__construct($id, $parent, $config);
        $this->uploadDir = \Yii::getAlias('@webroot').'/upload';
    }

    public static function getPublishPath($resId) {
        if(!isset(directoryModule::$publishPath)) {
            directoryModule::$publishPath = \Yii::$app->assetManager->publish(__DIR__.'/assets')[1];
        }
        
        return directoryModule::$publishPath.$resId;
    }
    
    public function init() {
        parent::init();
        
        \Yii::$app->getView()->registerCssFile(
                directoryModule::getPublishPath('/css/directory-style.css'));
        
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
