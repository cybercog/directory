<?php

namespace app\modules\directory\models\forms;

use yii\base\Model;
use app\modules\directory\directoryModule;

class RecordForm extends Model {
    public $visible = true;
    
    public function rules() {
        return [
            ['visible', 'boolean']
        ];
    }
    
    public function attributeLabels() {
        return [
            'visible' => directoryModule::ht('edit', 'show')
        ];
    }
}
