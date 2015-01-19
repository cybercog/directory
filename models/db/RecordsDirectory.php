<?php

namespace app\modules\directory\models\db;

use yii\db\ActiveRecord;

class RecordsDirectory extends ActiveRecord {
    public static function tableName() {
        return 'records_directory_t';
    }
}
