<?php


namespace app\modules\directory\models\db;

use yii\db\ActiveRecord;

use app\modules\directory\models\db\Data;
use app\modules\directory\models\db\Records;

class RecordsData extends ActiveRecord {
    public static function tableName() {
        return 'records_data_t';
    }
    
    public function getData() {
        return $this->hasOne(Data::tableName(), ['id' => 'data_id']);
    }

    public function getRecord() {
        return $this->hasOne(Records::tableName(), ['id' => 'record_id']);
    }
}
