<?php

namespace app\modules\directory\models\db;

use yii\db\ActiveRecord;

class Hierahies extends ActiveRecord {
    public static function tableName() {
        return 'hierarhies_t';
    }
    
    public function getBranchesHierarchies() {
        return $this->hasMany(BranchesHierarchies::className(), ['hierarchy_id'=>'id']);
    }
    
    public function getRootBranches() {
        return Branches::getHierarchyRootBranches($this->id);
    }
}
