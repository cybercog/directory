<?php

namespace app\modules\directory\models\forms;

use yii\base\Model;
use app\modules\directory\directoryModule;
use app\modules\directory\models\db\Types;
use yii\helpers\Html;

class DataForm extends Model {
    public $typeId;
    public $value;
    public $text;
    public $description;
    public $visible = true;
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
                            directoryModule::ht('edit', 'The data type is set incorrectly').'.');
                }
            }],
            [['value'], function($attribute, $params) {
                $type = $this->getTypeItem();
                if($type->type == 'string') {
                    if(empty($this->value)) {
                        $this->addError($attribute, 
                                Html::encode(directoryModule::ht('edit', 'The field <{field}> must not be empty', ['field' => 'value'])).'.');
                    }
                }
            }],
            ['value', 'string', 'min' => 3, 'max' => 255],
            ['text', function($attribute, $params) {
                $type = $this->getTypeItem();
                if($type->type == 'text') {
                    if(empty($this->text)) {
                        $this->addError($attribute, 
                                Html::encode(directoryModule::ht('edit', 'The field <{field}> must not be empty', ['field' => 'text'])).'.');
                    }
                }
            }],
            ['text', 'string', 'min' => 3],
            [['description', 'keywords'], 'safe'],
            ['visible', 'boolean'],
            ['file', 'file', /*'sizeLimit' => 31457280,*/ 'maxSize' => 31457280, 'minSize' => 1],
            [['file'], function($attribute, $params) {
                $type = $this->getTypeItem();
                if($type->type == 'string') {
                    if(empty($this->file)) {
                        $this->addError($attribute, 
                                Html::encode(directoryModule::ht('edit', 'The field <{field}> must not be empty', ['field' => 'file'])).'.');
                    }
                }
            }],
            ['image', 'file', /*'sizeLimit' => 1048576,*/ 'maxSize' => 1048576, 'minSize' => 1, 'extensions' => 'jpg, png', 'mimeTypes' => 'image/jpeg, image/png'],
            [['image'], function($attribute, $params) {
                $type = $this->getTypeItem();
                if($type->type == 'string') {
                    if(empty($this->image)) {
                        $this->addError($attribute, 
                                Html::encode(directoryModule::ht('edit', 'The field <{field}> must not be empty', ['field' => 'image'])).'.');
                    }
                }
            }]
        ];
    }
    
    public function attributeLabels() {
        return [
            'typeId' => directoryModule::ht('edit', 'Type'),
            'value' => directoryModule::ht('edit', 'Value'),
            'text' => directoryModule::ht('edit', 'Value'),
            'visible' => directoryModule::ht('edit', 'show'),
            'description' => directoryModule::ht('edit', 'Description'),
            'keywords' => directoryModule::ht('edit', 'Keywords'),
            'file' => directoryModule::ht('edit', 'File'),
            'image' => directoryModule::ht('edit', 'Image'),
        ];
    }
}
