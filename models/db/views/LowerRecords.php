<?php

namespace app\modules\directory\models\db\views;

use yii\db\ActiveRecord;

class LowerRecords extends ActiveRecord {
    public static function tableName() {
        return 'records_tolower_v';
    }
}
