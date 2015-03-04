<?php

namespace app\modules\directory\models\db;

use yii\db\ActiveRecord;

class Branches extends ActiveRecord {
    public static function tableName() {
        return 'branches_t';
    }
}
