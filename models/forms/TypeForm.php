<?php

namespace app\modules\directory\models\forms;

use yii\base\Model;
use app\modules\directory\directoryModule;

class TypeForm extends Model {
    public $name;
    public $type;
    public $validate;
    public $description;

    public function rules() {
        return [
            [['validate', 'description'], 'safe'],
            [['name', 'type'], 'required'],
            ['name', 'string', 'min' => 3, 'max' => 255],
            ['type', 'yii\validators\RangeValidator', 'range' => ['string', 'text', 'image', 'file']]
        ];
    }
    
    public function attributeLabels() {
        return [
            'name' => directoryModule::t('edit', 'Name'),
            'type' => directoryModule::t('edit', 'Type'),
            'validate' => directoryModule::t('edit', 'Validate'),
            'description' => directoryModule::t('edit', 'Description'),
        ];
    }
}
