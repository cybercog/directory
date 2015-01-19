<?php

namespace app\modules\directory\models\forms;

use yii\base\Model;
use app\modules\directory\directoryModule;

class DirectoryItemForm extends Model {
    public $directoryId;
    public $visible = true;
    
    public function rules() {
        return [
            ['directoryId', 'number', 'min' => 0],
            ['visible', 'boolean']
        ];
    }
    
    public function attributeLabels() {
        return [
            'visible' => directoryModule::ht('edit', 'show'),
        ];
    }
}
