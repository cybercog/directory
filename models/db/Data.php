<?php


namespace app\modules\directory\models\db;

use yii\db\ActiveRecord;
use app\modules\directory\models\db\Types;
use app\modules\directory\models\db\RecordsData;

class Data extends ActiveRecord {
    public static function tableName() {
        return 'data_t';
    }
    
    public function getType() {
        return $this->hasOne(Types::className(), ['id' => 'type_id']);
    }

    public function getRecordData() {
        return $this->hasMany(RecordsData::className(), ['data_id' => 'id']);
    }
}
