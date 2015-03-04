<?php

namespace app\modules\directory\models\db;

use yii\db\ActiveRecord;

class Hierahies extends ActiveRecord {
    public static function tableName() {
        return 'hierarhies_t';
    }
    
    public function getRootBranches() {
        //return $this->hasMany(BranchesHierarchies::className(), ['hierarhy_id'=>'id'])->viaTable(Branches::tableName(), ['branch_root_id'=>'id']);
        return $this->hasMany(Branches::className(), ['id'=>'branch_root_id'])->viaTable(BranchesHierarchies::tableName(), ['hierarhy_id'=>'id']);
    }
}
