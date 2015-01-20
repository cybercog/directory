<?php

namespace app\modules\directory\models\forms;

use yii\base\Model;
use app\modules\directory\directoryModule;
use app\modules\directory\models\db\Directories;

class DirectoryItemForm extends Model {
    public $directoryId;
    public $visible = true;
    
    public function rules() {
        return [
            ['directoryId', 'number', 'min' => 0],
            ['visible', 'boolean']
        ];
    }
    
    public function validate($attributeNames = null, $clearErrors = true) {
        $result = parent::validate($attributeNames, $clearErrors);
        if(!$result) {
            return $result;
        }
        
        try {
            $data = Directories::find()->where(['id' => $this->directoryId])->one();
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
        ];
    }
}
