<?php

namespace app\modules\directory\models\db\views;

use yii\db\ActiveRecord;

class LowerTypes extends ActiveRecord {
    public static function tableName() {
        return 'types_tolower_v';
    }
}
