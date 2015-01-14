<?php

namespace app\modules\directory\models\db;

use yii\db\ActiveRecord;
//use app\modules\directory\models\db\Data;

class Directories extends ActiveRecord {
    public static function tableName() {
        return 'directories_t';
    }
}
