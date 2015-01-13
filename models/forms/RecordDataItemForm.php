<?php

namespace app\modules\directory\models\forms;

use yii\base\Model;
use app\modules\directory\directoryModule;

class RecordDataItemForm extends Model {
    public $recordId;
    public $visible = true;
    public $dataId;
    public $position = 0;
    public $subPosition = 0;
    
    public function rules() {
        return [
            ['visible', 'boolean'],
            ['dataId', 'safe']
        ];
    }

    public function attributeLabels() {
        return [
            'visible' => directoryModule::ht('edit', 'show')
        ];
    }
}
