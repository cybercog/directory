<?php

namespace app\modules\directory\models\db\views;

use yii\db\ActiveRecord;

class LowerData extends ActiveRecord {
    public static function tableName() {
        return 'data_tolower_v';
    }
}
