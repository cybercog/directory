<?php

namespace app\modules\directory\models\db\views;

use yii\db\ActiveRecord;

class LowerHierarchies extends ActiveRecord {
    public static function tableName() {
        return 'hierarchies_tolower_v';
    }
}
