<?php

namespace app\modules\directory\models\forms;

use yii\base\Model;
use app\modules\directory\directoryModule;

class DirectoryForm extends Model {
    public $name;
    public $description;
    public $visible = true;

    public function rules() {
        return [
            ['description', 'safe'],
            ['name', 'required'],
            ['name', 'string', 'min' => 3, 'max' => 255],
            ['visible', 'boolean']
        ];
    }
    
    public function attributeLabels() {
        return [
            'name' => directoryModule::ht('edit', 'Name'),
            'validate' => directoryModule::ht('edit', 'Validate'),
            'description' => directoryModule::ht('edit', 'Description'),
            'visible' => directoryModule::ht('edit', 'show'),
        ];
    }
}
