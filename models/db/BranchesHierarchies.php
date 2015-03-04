<?php

namespace app\modules\directory\models\db;

use yii\db\ActiveRecord;

class BranchesHierarchies extends ActiveRecord {
    public static function tableName() {
        return 'branches_hierarhies_t';
    }
}
