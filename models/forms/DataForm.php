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
    public $visible;
    
    private $type_item;
    
    private function getTypeItem() {
        if(!isset($this->type_item)) {
            try {
                $this->type_item = Types::find()->where(['id' => $this->typeId])->one();
            } catch (Exception $ex) {
                
            }
        }
        
        return $this->type_item;
    }
    
    public function rules() {
        return [
            ['typeId', function($attribute, $params) {
                $type = $this->getTypeItem();
                if(empty($type)) {
                    $this->addError($attribute, 
                            directoryModule::t('edit', 'The data type is set incorrectly').'.');
                }
            }],
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
