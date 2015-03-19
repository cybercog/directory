<?php

namespace app\modules\directory\models\db;

use yii\db\ActiveRecord;

class Hierahies extends ActiveRecord {
    public static function tableName() {
        return 'hierarhies_t';
    }
    
    public function getRootBranches() {
        return $this->hasMany(
                Branches::className(), ['id'=>'branch_root_id'])->joinWith([BranchesHierarchies::tableName() => function($query) { return $query->andFilterWhere(['hierarchy_id'=>$this->id])->addOrderBy(['position' => SORT_ASC]); }])
                /*->viaTable(
                        BranchesHierarchies::tableName(), ['hierarchy_id'=>'id'], function($query) { return $query->addOrderBy(['position' => SORT_ASC]); })*/;    }
}
