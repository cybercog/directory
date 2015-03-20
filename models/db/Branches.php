<?php

namespace app\modules\directory\models\db;

use yii\db\ActiveRecord;

class Branches extends ActiveRecord {
    public static function tableName() {
        return 'branches_t';
    }

    public function getBranchesHierarchies() {
        return $this->hasMany(BranchesHierarchies::className(), ['branch_root_id'=>'id']);
    }

    public function getBranchesBranches() {
        return $this->hasMany(BranchesBranches::className(), ['branch_child_id'=>'id']);
    }
    
    public static function getHierarchyRootBranches($hierarchy_id) {
        return Branches::find()->joinWith('branchesHierarchies', true, 'INNER JOIN')->addOrderBy(['position' => SORT_ASC])->andFilterWhere(['hierarchy_id'=>$hierarchy_id]);
    }
    
    public static function getHierarchyChildBranches($rootBranch, $hierarchy_id) {
        return Branches::find()->joinWith('branchesBranches', true, 'INNER JOIN')->addOrderBy(['position' => SORT_ASC])->andFilterWhere(['hierarchy_id'=>$hierarchy_id, 'branch_this_id'=>$rootBranch]);
    }
}
