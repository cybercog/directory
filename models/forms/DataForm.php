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
    
    public function validate($attributeNames = null, $clearErrors = true){
        $type = null;
        try {
            $type = Types::find()->where(['id' => $this->typeId])->one();
        } catch (Exception $ex) {
            
        }
        
        if(!isset($type)) {
            return false;
        }
        
        $attributes = ['$typeId', 'description', 'visible', 'keywords'];
        
        switch($type->type) {
            case 'string':
                $attributes = array_merge($attributes, ['value']);
                break;
            case 'text':
                $attributes = array_merge($attributes, ['text']);
                break;
            case 'file':
                $attributes = array_merge($attributes, ['file']);
                break;
            case 'image':
                $attributes = array_merge($attributes, ['image']);
                break;
        }
        
        return parent::validate($attributes, $clearErrors);
    }
    
    public function rules() {
        return [
            ['value', 'string', 'min' => 3, 'max' => 255],
            ['text', 'string', 'min' => 3],
            [['description', 'keywords'], 'safe'],
            [['value', 'text', 'file', 'image', 'typeId'], 'required'],
            ['visible', 'boolean'],
            ['file', 'file', 'maxSize' => 31457280, 'minSize' => 1],
            ['image', 'file', 'maxSize' => 1048576, 'minSize' => 1, 'extensions' => 'jpg, png', 'mimeTypes' => 'image/jpeg, image/png']
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
