<?php

namespace app\modules\directory\models\forms;
use app\modules\directory\directoryModule;

use yii\base\Model;

class BranchForm extends Model {
    public $name;
    public $description;
    public $visible = true;
    public $position = 0;

    public function rules() {
        return [
            ['description', 'safe'],
            ['name', 'required'],
            ['position', 'number'],
            ['name', 'string', 'min' => 3, 'max' => 255],
            ['visible', 'boolean']
        ];
    }
    
    public function attributeLabels() {
        return [
            'name' => directoryModule::ht('edit', 'Name'),
            'description' => directoryModule::ht('edit', 'Description'),
            'visible' => directoryModule::ht('edit', 'show'),
            'position' => directoryModule::ht('edit', 'Position'),
        ];
    }
}
