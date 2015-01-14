<?php

namespace app\modules\directory\models\db\views;

use yii\db\ActiveRecord;

class LowerDirectories extends ActiveRecord {
    public static function tableName() {
        return 'directories_tolower_v';
    }
}
