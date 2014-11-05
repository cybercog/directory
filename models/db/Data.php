<?php


namespace app\modules\directory\models\db;

use yii\db\ActiveRecord;

class Data extends ActiveRecord {
    public static function tableName() {
        return 'data_t';
    }
    
    public function getType() {
        return $this->hasMany(Types::className(), ['id' => 'type_id']);
    }
}
