<?php

namespace app\modules\directory\models\db;

use yii\db\ActiveRecord;
use app\modules\directory\models\db\Data;

class Records extends ActiveRecord {
    public static function tableName() {
        return 'records_t';
    }
    
    public function getData() {
        return $this->hasMany(Data::className(), ['record_id' => 'id']);
    }
}
