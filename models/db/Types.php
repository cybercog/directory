<?php

namespace app\modules\directory\models\db;

use yii\db\ActiveRecord;
use app\modules\directory\models\db\Data;

class Types extends ActiveRecord {
    public static function tableName() {
        return 'types_t';
    }
    
    public function getRecordData() {
        return $this->hasMany(Data::className(), ['type_id' => 'id']);
    }
}
