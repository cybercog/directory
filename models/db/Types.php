<?php

namespace app\modules\directory\models\db;

use yii\db\ActiveRecord;

class Types extends ActiveRecord {
    public static function tableName() {
        return 'types_t';
    }
    
    public function getData() {
        return $this->hasMany(Data::className(), ['type_id' => 'id']);
    }
}
