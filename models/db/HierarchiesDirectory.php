<?php

namespace app\modules\directory\models\db;

use yii\db\ActiveRecord;

class HierarchiesDirectory extends ActiveRecord {
    public static function tableName() {
        return 'hierarhies_directory_t';
    }
}
