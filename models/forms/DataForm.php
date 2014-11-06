<?php

namespace app\modules\directory\models\forms;

use yii\base\Model;
use app\modules\directory\directoryModule;
use app\modules\directory\models\db\Types;

class DataForm extends Model {
    public $typeId;
    public $value;
    public $text;
    public $description;
    public $visible;
    public $keywords;
    public $file;
    public $image;
    
    private $type_item;
    
    private function getTypeItem() {
        if(!isset($this->type_item)) {
            try {
                $this->type_item = Types::find()->where(['id' => $this->typeId])->one();
            } catch (Exception $ex) {
                
            }
        }
        
        return $this->type_item;
    }
    
    public function rules() {
        return [
            ['typeId', function($attribute, $params) {
                $type = $this->getTypeItem();
                if(empty($type)) {
                    $this->addError($attribute, 
                            directoryModule::t('edit', 'The data type is set incorrectly').'.');
                }
            }],
            [['value'], 'required'],
            ['value', 'string', 'min' => 3, 'max' => 255],
        ];
    }
    
    public function attributeLabels() {
        return [
            'typeId' => directoryModule::t('edit', 'Type'),
            'value' => directoryModule::t('edit', 'Value'),
            'text' => directoryModule::t('edit', 'Value'),
            'visible' => directoryModule::t('edit', 'show'),
            'description' => directoryModule::t('edit', 'Description'),
            'keywords' => directoryModule::t('edit', 'Keywords'),
            'file' => directoryModule::t('edit', 'File'),
            'image' => directoryModule::t('edit', 'Image'),
        ];
    }
}
