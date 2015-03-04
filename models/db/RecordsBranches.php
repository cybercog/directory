<?php

namespace app\modules\directory\models\db;

use yii\db\ActiveRecord;

class RecordsBranches extends ActiveRecord {
    public static function tableName() {
        return 'records_branches_t';
    }
}
