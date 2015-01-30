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
    public $visible = true;
    public $keywords;
    public $file;
    public $image;
    public $replase = 'new';


    public function validate($attributeNames = null, $clearErrors = true) {
        if(!isset($attributeNames)) {
            $type = null;
            try {
                $type = Types::find()->where(['id' => $this->typeId])->one();
            } catch (\Exception $ex) {

            }

            if(!isset($type)) {
                $this->addError('typeId', directoryModule::ht('edit', 'This type of data does not exist'));
                return false;
            }

            $attributes = ['typeId', 'description', 'visible', 'keywords'];

            switch($type->type) {
                case 'string':
                    $attributes = array_merge($attributes, ['value']);
                    break;
                case 'text':
                    $attributes = array_merge($attributes, ['text']);
                    break;
                case 'file':
                    if(($this->replase == 'change') ||
                            ($this->replase == 'new')) {
                        $attributes = array_merge($attributes, ['file']);
                    }
                    break;
                case 'image':
                    if(($this->replase == 'change') ||
                            ($this->replase == 'new')) {
                        $attributes = array_merge($attributes, ['image']);
                    }
                    break;
            }
        }
        
        return parent::validate($attributes, $clearErrors);
    }
    
    public function rules() {
        return [
            ['value', 'string', 'min' => 1, 'max' => 255],
            ['text', 'string', 'min' => 1],
            [['description', 'keywords'], 'safe'],
            [['value', 'text', 'typeId'], 'required'],
            ['visible', 'boolean'],
            ['replase', 'yii\validators\RangeValidator', 'range' => ['new', 'no', 'change']],
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
