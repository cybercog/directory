<?php

namespace app\modules\directory\models\forms;

use yii\base\Model;
use app\modules\directory\directoryModule;
use app\modules\directory\models\db\Data;

class RecordDataItemForm extends Model {
    public $visible = true;
    public $dataId;
    public $position = 0;
    public $subPosition = 0;
    
    public function rules() {
        return [
            [['dataId', 'position', 'subPosition'], 'number', 'min' => 0],
            ['visible', 'boolean']
        ];
    }
    
    public function validate($attributeNames = null, $clearErrors = true) {
        $result = parent::validate($attributeNames, $clearErrors);
        if(!$result) {
            return $result;
        }
        
        try {
            $data = Data::find()->where(['id' => $this->dataId])->one();
        } catch (Exception $ex) {
            
        }
        
        if(!isset($data)) {
            $this->addError('dataId', directoryModule::ht('edit', 'The specified data element is not there'));
            return false;
        }
        
        return $result;
    }

    public function attributeLabels() {
        return [
            'visible' => directoryModule::ht('edit', 'show'),
            'dataId' => directoryModule::ht('edit', 'Value'),
            'position' => directoryModule::ht('edit', 'Position'),
            'subPosition' => directoryModule::ht('edit', 'Sub.')
        ];
    }
}
